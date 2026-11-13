<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseReportController extends Controller
{
    /**
     * Display purchase reports page (if accessed directly).
     */
    public function index()
    {
        return redirect()->route('purchasedashboard');
    }

    /**
     * Download Monthly PO Report PDF for a specific month (defaults to current month).
     */
    public function monthlyPoReportPdf(Request $request)
    {
        $month = (int)($request->input('month') ?? now()->month);
        $year = (int)($request->input('year') ?? now()->year);

        $dateString = Carbon::create($year, $month, 1)->format('F Y');

        $orders = PurchaseOrder::with(['vendor', 'customer'])
            ->where(function ($query) use ($month, $year) {
                $query->where(function ($q) use ($month, $year) {
                    $q->whereNotNull('po_date')
                      ->whereMonth('po_date', $month)
                      ->whereYear('po_date', $year);
                })->orWhere(function ($q) use ($month, $year) {
                    $q->whereNull('po_date')
                      ->whereMonth('created_at', $month)
                      ->whereYear('created_at', $year);
                });
            })
            ->latest('id')
            ->get();

        $totalCount = $orders->count();
        $approvedCount = $orders->where('status', 'Approved')->count();
        $pendingCount = $orders->where('status', 'Pending')->count();
        $totalValue = $orders->sum('grand_total');
        $approvedValue = $orders->where('status', 'Approved')->sum('grand_total');
        $pendingValue = $orders->where('status', 'Pending')->sum('grand_total');

        $pdf = Pdf::loadView('Purchase.reports.monthly-po-report-pdf', compact(
            'orders',
            'dateString',
            'month',
            'year',
            'totalCount',
            'approvedCount',
            'pendingCount',
            'totalValue',
            'approvedValue',
            'pendingValue'
        ));

        $pdf->setPaper('A4', 'landscape');

        $filename = 'PO_Monthly_Report_' . Carbon::create($year, $month, 1)->format('M_Y') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Download Custom Date Range Purchase Order PDF or CSV.
     */
    public function customPoReportPdf(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from = Carbon::parse($request->from_date)->startOfDay();
        $to = Carbon::parse($request->to_date)->endOfDay();
        $vendorId = $request->input('vendor_id');
        $status = $request->input('status');
        $format = $request->input('export_format', 'pdf');

        $query = PurchaseOrder::with(['vendor', 'customer'])
            ->where(function ($q) use ($from, $to) {
                $q->where(function ($sub) use ($from, $to) {
                    $sub->whereNotNull('po_date')
                        ->whereBetween('po_date', [$from->toDateString(), $to->toDateString()]);
                })->orWhere(function ($sub) use ($from, $to) {
                    $sub->whereNull('po_date')
                        ->whereBetween('created_at', [$from, $to]);
                });
            });

        if (!empty($vendorId) && $vendorId !== 'all') {
            $query->where('vendor_id', $vendorId);
        }

        if (!empty($status) && $status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->latest('id')->get();

        // Export as CSV if requested
        if ($format === 'csv') {
            return $this->streamCsvExport($orders, $from, $to);
        }

        $dateString = $from->format('d-m-Y') . ' to ' . $to->format('d-m-Y');
        $vendorName = (!empty($vendorId) && $vendorId !== 'all') ? Vendor::find($vendorId)?->company_name : 'All Vendors';
        $filterStatus = (!empty($status) && $status !== 'all') ? $status : 'All Statuses';

        $totalCount = $orders->count();
        $approvedCount = $orders->where('status', 'Approved')->count();
        $pendingCount = $orders->where('status', 'Pending')->count();
        $totalValue = $orders->sum('grand_total');
        $approvedValue = $orders->where('status', 'Approved')->sum('grand_total');
        $pendingValue = $orders->where('status', 'Pending')->sum('grand_total');

        $pdf = Pdf::loadView('Purchase.reports.monthly-po-report-pdf', compact(
            'orders',
            'dateString',
            'totalCount',
            'approvedCount',
            'pendingCount',
            'totalValue',
            'approvedValue',
            'pendingValue',
            'vendorName',
            'filterStatus'
        ));

        $pdf->setPaper('A4', 'landscape');

        $filename = 'Purchase_Order_Report_' . $from->format('d-m-Y') . '_To_' . $to->format('d-m-Y') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Export POs to CSV file.
     */
    public function exportCsv(Request $request)
    {
        $orders = PurchaseOrder::with(['vendor', 'customer'])
            ->latest('id')
            ->get();

        return $this->streamCsvExport($orders, now()->startOfYear(), now());
    }

    /**
     * Helper to stream CSV download.
     */
    protected function streamCsvExport($orders, $from, $to)
    {
        $filename = 'Purchase_Orders_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                '#',
                'PO Number',
                'PO Date',
                'Vendor / Supplier',
                'Customer / Site',
                'PO Type',
                'Subtotal (INR)',
                'GST Amount (INR)',
                'Grand Total (INR)',
                'Status'
            ]);

            foreach ($orders as $index => $po) {
                $vendorName = $po->vendor->company_name ?? ($po->contractor_name ?? '—');
                $customerName = $po->customer->client_name ?? '—';
                $poDate = $po->po_date ? Carbon::parse($po->po_date)->format('d-m-Y') : $po->created_at->format('d-m-Y');

                fputcsv($handle, [
                    $index + 1,
                    $po->po_no,
                    $poDate,
                    $vendorName,
                    $customerName,
                    $po->po_type ?? '—',
                    number_format($po->subtotal ?? 0, 2, '.', ''),
                    number_format($po->gst_amount ?? 0, 2, '.', ''),
                    number_format($po->grand_total ?? 0, 2, '.', ''),
                    $po->status ?? 'Pending'
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}

