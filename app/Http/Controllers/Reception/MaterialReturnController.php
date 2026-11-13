<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\customers;
use App\Models\MaterialDispatchItem;
use App\Models\MaterialReturn;
use App\Models\MaterialReturnItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialReturnController extends Controller
{
    public function index()
    {
        $customers = customers::whereIn('status', [
            'Yet to Start',
            'Progress'
        ])
            ->orderBy('client_name')
            ->get();

        $recentReturns = MaterialReturn::with([
            'customer',
            'items.dispatchItem'
        ])
            ->orderBy('return_date', 'DESC')
            ->orderBy('id', 'DESC')
            ->limit(6)
            ->get();

        $todayReturnsCount = MaterialReturn::whereDate('return_date', Carbon::today())->count();
        $totalReturnsCount = MaterialReturn::count();
        $totalReturnedItemsCount = MaterialReturnItem::count();
        $totalReturnTransport = (float) (MaterialReturn::sum('transport_charge') ?? 0);

        return view(
            'Reception.materialreturn',
            compact(
                'customers',
                'recentReturns',
                'todayReturnsCount',
                'totalReturnsCount',
                'totalReturnedItemsCount',
                'totalReturnTransport'
            )
        );
    }

    public function getDispatchItems($customer)
    {
        $items = MaterialDispatchItem::with([
            'dispatch',
            'returnItems'
        ])
            ->whereHas('dispatch', function ($q) use ($customer) {
                $q->where('customer_id', $customer);
            })
            ->get();

        $data = [];

        foreach ($items as $item) {
            $returned = (float) $item->returnItems->sum('return_quantity');
            $quantity = (float) $item->quantity;
            $balance = $quantity - $returned;

            if ($balance > 0) {
                $data[] = [
                    'dispatch_item_id' => $item->id,
                    'dispatch_id' => $item->dispatch_id,
                    'dispatch_date' => $item->dispatch ? date('d M, Y', strtotime($item->dispatch->dispatch_date)) : '',
                    'item' => $item->item,
                    'dispatch_qty' => $quantity,
                    'returned_qty' => $returned,
                    'balance' => $balance,
                    'unit' => $item->unit,
                    'description' => $item->description
                ];
            }
        }

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'return_date' => 'required',
            'transport_type' => 'required',
            'person_name' => 'required'
        ]);

        try {
            $returnId = null;

            DB::transaction(function () use ($request, &$returnId) {
                $return = MaterialReturn::create([
                    'return_no' => 'MR' . date('YmdHis'),
                    'customer_id' => $request->customer_id,
                    'person_name' => $request->person_name,
                    'vehicle_no' => $request->vehicle_no,
                    'transport_type' => $request->transport_type,
                    'transport_charge' => $request->transport_charge ?? 0,
                    'return_date' => $request->return_date,
                    'remarks' => $request->remarks,
                    'user_name' => Auth::user()->name
                ]);

                $returnId = $return->id;
                $hasItems = false;

                if ($request->has('dispatch_item_id') && is_array($request->dispatch_item_id)) {
                    foreach ($request->dispatch_item_id as $index => $dispatchItemId) {
                        $qty = isset($request->return_quantity[$index]) ? (float) $request->return_quantity[$index] : 0;

                        if ($qty <= 0) {
                            continue;
                        }

                        $dispatchItem = MaterialDispatchItem::with('returnItems')->findOrFail($dispatchItemId);
                        $alreadyReturned = (float) $dispatchItem->returnItems->sum('return_quantity');
                        $balance = (float) ($dispatchItem->quantity - $alreadyReturned);

                        if ($qty > $balance) {
                            throw new \Exception("Return quantity ({$qty}) for '{$dispatchItem->item}' exceeds available site balance ({$balance}).");
                        }

                        MaterialReturnItem::create([
                            'return_id' => $return->id,
                            'dispatch_item_id' => $dispatchItem->id,
                            'return_quantity' => $qty,
                            'description' => $dispatchItem->description
                        ]);

                        $hasItems = true;
                    }
                }

                if (!$hasItems) {
                    throw new \Exception('Please enter a return quantity greater than 0 for at least one item.');
                }
            });

            return redirect()
                ->route('materialreturn')
                ->with('success', 'Material Return Voucher created and saved successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
