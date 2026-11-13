<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseDashboardController extends Controller
{
    //

    public function getvendorcount()
    {
        return Vendor::count();
    }

    public function getpocount()
    {
        return PurchaseOrder::count();
    }

    public function index()
    {
        $totalVendor = $this->getvendorcount();

        $totalPO = $this->getpocount();

        $pendingPO = PurchaseOrder::where('status', 'Pending')->count();

        $approvedPO = PurchaseOrder::where('status', 'Approved')->count();

        $totalPoValue = PurchaseOrder::sum('grand_total') ?? 0;
        $approvedPoValue = PurchaseOrder::where('status', 'Approved')->sum('grand_total') ?? 0;
        $pendingPoValue = PurchaseOrder::where('status', 'Pending')->sum('grand_total') ?? 0;
        $poApprovalRate = $totalPO > 0 ? round(($approvedPO / $totalPO) * 100, 1) : 0;

        $vendorPoCounts = Vendor::withCount('purchaseOrders')
            ->withSum('purchaseOrders', 'grand_total')
            ->orderByDesc('purchase_orders_sum_grand_total')
            ->get();

        $recentPOs = PurchaseOrder::with(['vendor', 'customer'])
            ->latest()
            ->take(6)
            ->get();

        // Monthly PO aggregation for the current year
        $monthlyPOs = PurchaseOrder::select(
            DB::raw('MONTH(COALESCE(po_date, created_at)) as month'),
            DB::raw('COUNT(*) as total_count'),
            DB::raw('SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) as approved_count'),
            DB::raw('SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending_count'),
            DB::raw('SUM(grand_total) as total_value')
        )
        ->whereYear(DB::raw('COALESCE(po_date, created_at)'), now()->year)
        ->groupBy(DB::raw('MONTH(COALESCE(po_date, created_at))'))
        ->orderBy(DB::raw('MONTH(COALESCE(po_date, created_at))'))
        ->get();

        $allVendors = Vendor::orderBy('company_name')->get();

        return view('Purchase.dashboard', compact(
            'totalVendor',
            'totalPO',
            'pendingPO',
            'approvedPO',
            'totalPoValue',
            'approvedPoValue',
            'pendingPoValue',
            'poApprovalRate',
            'vendorPoCounts',
            'recentPOs',
            'monthlyPOs',
            'allVendors'
        ));
    }
}
