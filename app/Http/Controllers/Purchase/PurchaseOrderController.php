<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\customers;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Vendor;
use App\Models\PurchaseOrderContractorItem;
use App\Models\PurchaseOrderTerm;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    //
    public function create()
    {
        $vendors = Vendor::where('status', 'Active')
            ->orderBy('company_name')
            ->get();

        $sites = customers::where('status', '!=', 'Completed')
            ->orderBy('client_name')
            ->get();

        return view(
            'Purchase.purchaseorders.create2',
            compact(
                'vendors',
                'sites'
            )
        );
    }

    public function index()
    {
        $orders = PurchaseOrder::with([
            'vendor',
            'customer'
        ])
            ->latest()
            ->get();

        return view(
            'Purchase.purchaseorders.index',
            compact('orders')
        );
    }

    private function generatePoNo($company)
    {
        $prefix = $company == 'Amudha Decors' ? 'AD' : 'AR';

        $year = date('Y');
        $nextYear = substr($year + 1, -2);

        $financialYear = $year . '-' . $nextYear;

        $last = PurchaseOrder::where('company', $company)
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            preg_match('/(\d+)$/', $last->po_no, $matches);

            $number = isset($matches[1]) ? (int) $matches[1] + 1 : 150;
        } else {
            $number = 150;
        }

        return $prefix . '/PO/' . $financialYear . '/' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
{
    
    // =====================================================
    // VALIDATION
    // =====================================================

    $poTemplate = $request->po_type === 'contractor'
    ? $request->contractor_template
    : $request->po_template;
    $request->validate([

        'company' => 'required',

        'site_id' => 'required',

        'po_type' => 'required|in:material,contractor',

        'po_date' => 'required|date',

        'po_template' => 'required',

        'contractor_template' => $request->po_type === 'contractor' ? 'required|in:common,lumpsum' : 'nullable',

        'vendor_id' => $request->po_type === 'material' ? 'required' : 'nullable',

        'contractor_name' => $request->po_type === 'contractor' ? 'required|string|max:255' : 'nullable',

        'contractor_address' => $request->po_type === 'contractor'? 'required|string': 'nullable',

        'po_no' => $request->company == 'Arasuvel Roofings' ? 'required|unique:purchase_orders,po_no' : 'nullable',

    ]);


    DB::beginTransaction();


    try {

        // =====================================================
        // CREATE PURCHASE ORDER
        // =====================================================

   
        $po = PurchaseOrder::create([

            'po_no' => $request->po_no,

            'company' => $request->company,

            'vendor_id' => $request->po_type === 'material' ? $request->vendor_id : null,

            'contractor_name' => $request->po_type === 'contractor' ? $request->contractor_name : null,
            'contractor_address' => $request->po_type === 'contractor' ? $request->contractor_address : null,

            'site_id' => $request->site_id,

            'po_type' => $request->po_type,

            'po_template' => $poTemplate,

            'po_date' => $request->po_date,

            'subtotal' => $request->subtotal ?? 0,

            'gst_percent' => $request->gst_percent ?? 0,

            'gst_amount' => $request->gst_amount ?? 0,

            'grand_total' => $request->grand_total ?? 0,

            'remarks' => $request->remarks,

            'created_by' => Auth::user()->name,

            'status' => 'Pending',

        ]);

        
        // =====================================================
        // MATERIAL PO
        // =====================================================

        if ($request->po_type === 'material') {

            if (!$request->has('material')) {

                DB::rollBack();

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Please add at least one material.'
                    );
            }


            foreach ($request->material as $key => $material) {

                // Skip empty rows
                if (trim($material) === '') {
                    continue;
                }


                PurchaseOrderItem::create([

                    'purchase_order_id' => $po->id,

                    'material' => $material,

                    'size' =>
                        $request->size[$key] ?? null,

                    'width' =>
                        $request->width[$key] ?? null,

                    'color' =>
                        $request->color[$key] ?? null,

                    'dia' =>
                        $request->dia[$key] ?? null,

                    'length' =>
                        $request->length[$key] ?? null,

                    'thickness' =>
                        $request->thickness[$key] ?? null,

                    'nos' =>
                        $request->nos[$key] ?? null,

                    'qty' =>
                        $request->qty[$key] ?? $request->area[$key] ?? $request->sqm[$key] ?? null,

                    'unit' =>
                        $request->unit[$key] ?? null,

                    'approx_weight' =>
                        $request->approx_weight[$key] ?? null,

                    'rate' =>
                        $request->rate[$key] ?? 0,

                    'amount' =>
                        $request->amount[$key] ?? 0,

                    'description' =>
                        $request->description[$key] ?? null,

                ]);
            }
        }


        // =====================================================
        // CONTRACTOR PO
        // =====================================================

        elseif ($request->po_type === 'contractor') {

            $contractorType =
                $request->contractor_template ?? 'common';


            // =================================================
            // COMMON CONTRACTOR
            // =================================================

            if ($contractorType === 'common') {

                if (!$request->has('scope_of_work')) {

                    DB::rollBack();
                   

                    return back()
                        ->withInput()
                        ->with(
                            'error',
                            'Please add at least one contractor item.'
                        );
                }


                foreach (
                    $request->scope_of_work
                    as $key => $scope
                ) {

                    // Skip blank rows
                    if (trim($scope) === '') {
                        continue;
                    }


                    $qty =
                        (float) (
                            $request->contractor_qty[$key]
                            ?? 0
                        );


                    $rate =
                        (float) (
                            $request->contractor_rate[$key]
                            ?? 0
                        );


                    // Calculate amount on server
                    $amount =
                        $qty * $rate;


                    PurchaseOrderContractorItem::create([

                        'purchase_order_id' =>
                            $po->id,

                        'scope_of_work' =>
                            trim($scope),

                        'qty' =>
                            $qty,

                        'unit' =>
                            $request->contractor_unit[$key]
                            ?? null,

                        'rate' =>
                            $rate,

                        'amount' =>
                            $amount,

                    ]);

                }

            }


            // =================================================
            // LUMPSUM CONTRACTOR
            // =================================================

            elseif ($contractorType === 'lumpsum') {

                if (!$request->has('lumpsum_scope')) {

                    DB::rollBack();

                    return back()
                        ->withInput()
                        ->with(
                            'error',
                            'Please add at least one lumpsum item.'
                        );
                }


                foreach (
                    $request->lumpsum_scope
                    as $key => $scope
                ) {

                    // Skip blank rows
                    if (trim($scope) === '') {
                        continue;
                    }


                    $amount =
                        (float) (
                            $request->lumpsum_amount[$key]
                            ?? 0
                        );


                   


                    PurchaseOrderContractorItem::create([

                        'purchase_order_id' =>
                            $po->id,

                        'scope_of_work' =>
                            trim($scope),

                        'qty' => 1,

                        'unit' => 'L.S',

                        'rate' =>
                            $amount,

                        'amount' =>
                            $amount,

                    ]);

                }

            }


            // =================================================
            // TERMS & CONDITIONS
            // =================================================

            if ($request->has('terms')) {

                foreach (
                    $request->terms
                    as $key => $term
                ) {

                    // Skip empty terms
                    if (trim($term) === '') {
                        continue;
                    }


                    PurchaseOrderTerm::create([

                        'purchase_order_id' =>
                            $po->id,

                        'term' =>
                            trim($term),

                        'sort_order' =>
                            $key + 1,

                    ]);

                }

            }

        }


        DB::commit();

        return redirect()
            ->route('purchase.index')
            ->with(
                'success',
                'Purchase Order Created Successfully.'
            );


    } catch (\Exception $e) {

        DB::rollBack();


        return back()
            ->withInput()
            ->with(
                'error',
                'Failed to create Purchase Order: '
                . $e->getMessage()
            );
    }
}

    public function loadTemplate($template)
    {
        $templates = [
            'anchor',
            'MSpipe',
            'fabrication',
            'sandwichpanel',
            'gutter',
            'polycarbonate',
            'metalsheet',
            'paint',
            'retractableawnings',
        ];

        if (!in_array($template, $templates)) {
            abort(404);
        }

        return view("Purchase.purchaseorders.templates.$template");
    }

    public function edit($id)
    {
        $po = PurchaseOrder::with(['items', 'contractorItems', 'terms'])->findOrFail($id);

        if (empty($po->po_type)) {
            $isContractor = in_array(strtolower(trim($po->po_template ?? '')), ['common', 'lumpsum']);
            $po->po_type = $isContractor ? 'contractor' : 'material';
        }

        $vendors = Vendor::where('status', 'Active')->get();

        $sites = customers::all();

        return view(
            'Purchase.purchaseorders.edit',
            compact(
                'po',
                'vendors',
                'sites'
            )
        );
    }

    public function view($id)
    {
        $po = PurchaseOrder::with([
            'vendor',
            'customer',
            'items'
        ])->findOrFail($id);

        return view(
            'Purchase.purchaseorders.view',
            compact('po')
        );
    }

    public function pdf($id)
{
    $po = PurchaseOrder::with([
        'vendor',
        'customer',
        'items',
        'contractorItems',
        'terms'
    ])->findOrFail($id);

   

    $pdf = Pdf::loadView(
        'Purchase.purchaseorders.pdf.purchaseupdate',
        compact('po')
    );

    $pdf->setPaper('A4', 'portrait');

    return $pdf->stream($po->po_no . '.pdf');
}

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            PurchaseOrderItem::where('purchase_order_id', $id)->delete();

            PurchaseOrder::findOrFail($id)->delete();

            DB::commit();

            return redirect()
                ->route('purchase.index')
                ->with('success', 'Purchase Order Deleted Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $po = PurchaseOrder::findOrFail($id);

        $poTemplate = $request->po_type === 'contractor'
            ? ($request->contractor_template ?: $request->po_template ?: $po->po_template ?: 'common')
            : ($request->po_template ?: $po->po_template ?: 'anchor');

        $effectivePoType = $request->po_type ?: ($po->po_type ?: (in_array(strtolower(trim($poTemplate)), ['common', 'lumpsum']) ? 'contractor' : 'material'));
        $request->merge(['po_type' => $effectivePoType]);

        $request->validate([
            'company' => 'required',
            'site_id' => 'required',
            'po_type' => 'required|in:material,contractor',
            'po_date' => 'required|date',
            'po_template' => 'nullable',
            'contractor_template' => 'nullable|in:common,lumpsum',
            'vendor_id' => $request->po_type === 'material' ? 'required' : 'nullable',
            'contractor_name' => $request->po_type === 'contractor' ? 'required|string|max:255' : 'nullable',
            'contractor_address' => $request->po_type === 'contractor'? 'required|string': 'nullable',
        ]);

        DB::beginTransaction();

        try {
            // Update Purchase Order Header
            $po->update([
                'company' => $request->company,
                'vendor_id' => $request->po_type === 'material' ? $request->vendor_id : null,
                'contractor_name' => $request->po_type === 'contractor' ? $request->contractor_name : null,
                'contractor_address' => $request->po_type === 'contractor' ? $request->contractor_address : null,
                'site_id' => $request->site_id,
                'po_type' => $request->po_type,
                'po_template' => $poTemplate,
                'po_date' => $request->po_date,
                'subtotal' => $request->subtotal ?? 0,
                'gst_percent' => $request->gst_percent ?? 0,
                'gst_amount' => $request->gst_amount ?? 0,
                'grand_total' => $request->grand_total ?? 0,
                'remarks' => $request->remarks,
            ]);

            if ($request->po_type === 'material') {
                // Existing item ids
                $existingIds = $po->items()->pluck('id')->toArray();
                $submittedIds = [];

                if ($request->has('material')) {
                    foreach ($request->material as $key => $material) {
                        if (trim($material) == '') continue;

                        $itemData = [
                            'material' => $material,
                            'size' => $request->size[$key] ?? null,
                            'width' => $request->width[$key] ?? null,
                            'color' => $request->color[$key] ?? null,
                            'dia' => $request->dia[$key] ?? null,
                            'length' => $request->length[$key] ?? null,
                            'thickness' => $request->thickness[$key] ?? null,
                            'nos' => $request->nos[$key] ?? null,
                            'qty' => $request->qty[$key] ?? $request->area[$key] ?? $request->sqm[$key] ?? null,
                            'unit' => $request->unit[$key] ?? null,
                            'approx_weight' => $request->approx_weight[$key] ?? null,
                            'cutting_charge' => $request->cutting_charge[$key] ?? 0,
                            'rate' => $request->rate[$key] ?? 0,
                            'amount' => $request->amount[$key] ?? 0,
                            'description' => $request->description[$key] ?? null,
                        ];

                        if (!empty($request->item_id[$key])) {
                            $item = PurchaseOrderItem::find($request->item_id[$key]);
                            if ($item) {
                                $item->update($itemData);
                                $submittedIds[] = $item->id;
                            }
                        } else {
                            $newItem = $po->items()->create($itemData);
                            $submittedIds[] = $newItem->id;
                        }
                    }
                }
                $deleteIds = array_diff($existingIds, $submittedIds);
                PurchaseOrderItem::whereIn('id', $deleteIds)->delete();
                
                $po->contractorItems()->delete();
                $po->terms()->delete();

            } elseif ($request->po_type === 'contractor') {
                $contractorType = $poTemplate ?? $request->contractor_template ?? 'common';
                
                $existingContractorIds = $po->contractorItems()->pluck('id')->toArray();
                $submittedContractorIds = [];

                if ($contractorType === 'common' && $request->has('scope_of_work')) {
                    foreach ($request->scope_of_work as $key => $scope) {
                        if (trim($scope) === '') continue;
                        
                        $qty = (float) ($request->contractor_qty[$key] ?? 0);
                        $rate = (float) ($request->contractor_rate[$key] ?? 0);
                        $amount = $qty * $rate;
                        
                        $itemData = [
                            'scope_of_work' => trim($scope),
                            'qty' => $qty,
                            'unit' => $request->contractor_unit[$key] ?? null,
                            'rate' => $rate,
                            'amount' => $amount,
                        ];

                        if (!empty($request->contractor_item_id[$key])) {
                            $item = PurchaseOrderContractorItem::find($request->contractor_item_id[$key]);
                            if ($item) {
                                $item->update($itemData);
                                $submittedContractorIds[] = $item->id;
                            }
                        } else {
                            $newItem = $po->contractorItems()->create($itemData);
                            $submittedContractorIds[] = $newItem->id;
                        }
                    }
                } elseif ($contractorType === 'lumpsum' && $request->has('lumpsum_scope')) {
                    foreach ($request->lumpsum_scope as $key => $scope) {
                        if (trim($scope) === '') continue;
                        
                        $amount = (float) ($request->lumpsum_amount[$key] ?? 0);
                        
                        $itemData = [
                            'scope_of_work' => trim($scope),
                            'qty' => 1,
                            'unit' => 'L.S',
                            'rate' => $amount,
                            'amount' => $amount,
                        ];

                        if (!empty($request->lumpsum_item_id[$key])) {
                            $item = PurchaseOrderContractorItem::find($request->lumpsum_item_id[$key]);
                            if ($item) {
                                $item->update($itemData);
                                $submittedContractorIds[] = $item->id;
                            }
                        } else {
                            $newItem = $po->contractorItems()->create($itemData);
                            $submittedContractorIds[] = $newItem->id;
                        }
                    }
                }
                
                $deleteContractorIds = array_diff($existingContractorIds, $submittedContractorIds);
                PurchaseOrderContractorItem::whereIn('id', $deleteContractorIds)->delete();

                $existingTermIds = $po->terms()->pluck('id')->toArray();
                $submittedTermIds = [];

                if ($request->has('terms')) {
                    foreach ($request->terms as $key => $term) {
                        if (trim($term) === '') continue;

                        $termData = [
                            'term' => trim($term),
                            'sort_order' => $key + 1,
                        ];

                        if (!empty($request->term_id[$key])) {
                            $termModel = PurchaseOrderTerm::find($request->term_id[$key]);
                            if ($termModel) {
                                $termModel->update($termData);
                                $submittedTermIds[] = $termModel->id;
                            }
                        } else {
                            $newTerm = $po->terms()->create($termData);
                            $submittedTermIds[] = $newTerm->id;
                        }
                    }
                }
                $deleteTermIds = array_diff($existingTermIds, $submittedTermIds);
                PurchaseOrderTerm::whereIn('id', $deleteTermIds)->delete();
                
                $po->items()->delete();
            }

            DB::commit();

            return redirect()
                ->route('purchase.index')
                ->with('success', 'Purchase Order Updated Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
