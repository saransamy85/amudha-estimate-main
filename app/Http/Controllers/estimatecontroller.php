<?php

namespace App\Http\Controllers;

use App\Models\customers;
use App\Models\estimate;
use App\Models\estimateitems;
use App\Models\leads;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class estimatecontroller extends Controller
{
    public function index()
    {
        if (session()->has('username')) {
            $estimates = estimate::latest()->get();

            return view('estimates/index', compact('estimates'));
        }
        return redirect()->route('login')->with('error', 'You must Login');
    }

    public function create()
    {
        return view('estimates.create');
    }

    public function create1($lead = null)
    {
        $lead = $lead ? Leads::findOrFail($lead) : null;

        return view('estimates.create2', compact('lead'));
    }

    public function store(Request $request)
    {
        // DB::beginTransaction();

        try {
            $subtotal = 0;

            foreach ($request->area as $i => $area) {
                $rate = $request->rate[$i];
                $value = $area * $rate;
                $subtotal += $value;
            }
            $discount = $request->discount ?? 0;

            $transportCharges = $request->transport_charges ?? 0;
            // Don't allow discount greater than subtotal
            if ($discount > $subtotal) {
                $discount = $subtotal;
            }


            // Amount after discount
            $discountedAmount = $subtotal - $discount;


            $taxableAmount = $discountedAmount + $transportCharges;

            $gstAmount = ($taxableAmount * $request->gst_percent) / 100;

            $netTotal = $taxableAmount + $gstAmount;

            $baseName = $request->customer_name;
            $finalName = $baseName;

            if ($request->boolean('re_estimate')) {
                $latest = Estimate::where('customer_name', 'LIKE', $baseName . '®%')
                    ->orderByRaw('LENGTH(customer_name) DESC')
                    ->orderBy('customer_name', 'DESC')
                    ->first();

                if ($latest) {
                    preg_match('/®(\d+)$/', $latest->customer_name, $matches);

                    $next = isset($matches[1]) ? $matches[1] + 1 : 1;

                    $finalName = $baseName . '®' . $next;
                } else {
                    $finalName = $baseName . '®';
                }
            }

            $estimate = Estimate::create([
                'lead_id' => $request->lead_id,
                'estimate_no' => generateEstimateNumber(),
                'estimate_date' => now(),
                'customer_name' => $finalName,
                'customer_address' => $request->address_line1 . ', ' . $request->address_line2,
                'mobile' => $request->mobile,
                'description' => $request->description,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'gst_percent' => $request->gst_percent,
                'gst_amount' => $gstAmount,
                'transport_charges' => $transportCharges,
                'net_total' => $netTotal,
                'estimatedby' => Auth::user()->name,
                'created_by' => Auth::id(),
            ]);

            foreach ($request->area as $i => $area) {
                $rate = $request->rate[$i];
                $value = $area * $rate;

                $estimate->items()->create([
                    'location' => $request->location[$i],
                    'area' => $area,
                    'unit' => $request->unit[$i],
                    'rate' => $rate,
                    'value' => $value,
                ]);
            }

            // =====================================================
            // CREATE ADDITIONAL NOTES
            // =====================================================

            foreach ($request->additional_notes ?? [] as $key => $note) {

                $note = trim($note);

                // Skip empty notes
                if ($note === '') {
                    continue;
                }

                $estimate->notes()->create([

                    'note' => $note,
                    'sort_order' => $key + 1,

                ]);
            }

            // DB::commit();

            return redirect()
                ->route('estimates.show', $estimate->id)
                ->with('success', 'Estimate created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        if (session()->has('username')) {
            $estimate = estimate::with('items')->findOrFail($id);
            $amountWords = rupees_in_words($estimate->net_total);
            return view('estimates.show', compact('estimate', 'amountWords'));
        }
        return redirect()->route('login')->with('error', 'You must Login');
    }

    public function edit($id)
    {
        if (session()->has('username')) {
            $estimate = estimate::with(['items', 'notes'])->findOrFail($id);
            return view('estimates.edit', compact('estimate'));
        }
        return redirect()->route('login')->with('error', 'You must Login');
    }

    public function update(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                $estimate = Estimate::findOrFail($id);

                // =====================================================
                // DELETE OLD ITEMS
                // =====================================================
                $estimate->items()->delete();

                // =====================================================
                // CALCULATE SUBTOTAL & CREATE SUBMITTED ITEMS
                // =====================================================
                $subtotal = 0;

                foreach ($request->area ?? [] as $i => $area) {
                    $location = $request->location[$i] ?? null;
                    $unit = $request->unit[$i] ?? null;
                    $area = (float) $area;
                    $rate = (float) ($request->rate[$i] ?? 0);

                    // Skip empty rows where neither location, area, nor rate is provided
                    if (empty($location) && $area == 0 && $rate == 0) {
                        continue;
                    }

                    $value = $area * $rate;
                    $subtotal += $value;

                    $estimate->items()->create([
                        'location' => $location,
                        'area' => $area,
                        'unit' => $unit,
                        'rate' => $rate,
                        'value' => $value,
                    ]);
                }

                // =====================================================
                // DISCOUNT
                // =====================================================
                $discount = (float) ($request->discount ?? 0);
                if ($discount > $subtotal) {
                    $discount = $subtotal;
                }

                // =====================================================
                // TRANSPORTATION
                // =====================================================
                $transportCharges = (float) (
                    $request->transportation ?? $request->transport_charges ?? 0
                );

                // =====================================================
                // GST
                // =====================================================
                $gstPercent = (float) ($request->gst_percent ?? 0);

                // =====================================================
                // TAXABLE AMOUNT & GST & NET TOTAL
                // =====================================================
                $taxableAmount = max(0, $subtotal - $discount + $transportCharges);
                $gstAmount = ($taxableAmount * $gstPercent) / 100;
                $netTotal = $taxableAmount + $gstAmount;

                // Format customer address
                $customerAddress = trim($request->address_line1 . ($request->filled('address_line2') ? ', ' . $request->address_line2 : ''));

                // =====================================================
                // UPDATE ESTIMATE
                // =====================================================
                $estimate->update([
                    'customer_name' => $request->customer_name,
                    'customer_address' => $customerAddress,
                    'mobile' => $request->mobile,
                    'description' => $request->description,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'gst_percent' => $gstPercent,
                    'gst_amount' => $gstAmount,
                    'transport_charges' => $transportCharges,
                    'net_total' => $netTotal,
                ]);

                // =====================================================
                // UPDATE ADDITIONAL NOTES (IF PROVIDED)
                // =====================================================
                if ($request->has('notes_submitted') || $request->has('additional_notes')) {
                    $estimate->notes()->delete();

                    foreach ($request->additional_notes ?? [] as $key => $note) {
                        $note = trim($note);
                        if ($note === '') {
                            continue;
                        }

                        $estimate->notes()->create([
                            'note' => $note,
                            'sort_order' => $key + 1,
                        ]);
                    }
                }
            });

            return redirect()
                ->route('estimates.show', $id)
                ->with('success', 'Estimate updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update estimate: ' . $e->getMessage());
        }
    }

    public function destroyItem($id)
    {
        try {
            $item = estimateitems::findOrFail($id);
            $estimateId = $item->estimate_id;
            $item->delete();

            // Recalculate estimate totals
            $estimate = estimate::with('items')->findOrFail($estimateId);
            $subtotal = $estimate->items->sum('value');
            $discount = min($estimate->discount ?? 0, $subtotal);
            $transportCharges = $estimate->transport_charges ?? 0;
            $taxableAmount = max(0, $subtotal - $discount + $transportCharges);
            $gstAmount = ($taxableAmount * ($estimate->gst_percent ?? 0)) / 100;
            $netTotal = $taxableAmount + $gstAmount;

            $estimate->update([
                'subtotal' => $subtotal,
                'discount' => $discount,
                'gst_amount' => $gstAmount,
                'net_total' => $netTotal,
            ]);

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Item deleted successfully.',
                    'subtotal' => $subtotal,
                    'net_total' => $netTotal,
                ]);
            }

            return back()->with('success', 'Item deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to delete item: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to delete item: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        estimate::destroy($id);
        return back()->with('success', 'Estimate Deleted');
    }

    public function pdf($id)
    {
        if (session()->has('username')) {
            $estimate = estimate::with('items')->findOrFail($id);
            $amountWords = rupees_in_words($estimate->net_total);
            $pdf = PDF::loadView('estimates.pdf2', compact('estimate', 'amountWords'));
            return $pdf->stream('Estimate-' . $estimate->estimate_no . '.pdf');
        }
        return redirect()->route('login')->with('error', 'You must Login');
    }
}
