<?php

namespace App\Http\Controllers;

use App\Models\customers;
use App\Models\estimate;
use App\Models\estimateitems;
use App\Models\leadfeedback;
use App\Models\leads;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class reportcontroller extends Controller
{
    //
    public function adminreport()
    {
        if (session()->has('username')) {
            $escount = estimate::count();

            $lc = leads::count();

            $leadSC = leads::select(
                'Status',
                DB::raw('COUNT(*) as total')
            )
                ->groupBy('Status')
                ->pluck('total', 'Status');

            $lsc = leads::select(
                'source',
                DB::raw('COUNT(*) as total')
            )
                ->groupBy('source')
                ->pluck('total', 'source');

            $todayDate = Carbon::today()->toDateString();
            $todayIstDate = Carbon::today('Asia/Kolkata')->toDateString();
            $istStartUtc = Carbon::today('Asia/Kolkata')->startOfDay()->setTimezone('UTC');
            $istEndUtc = Carbon::today('Asia/Kolkata')->endOfDay()->setTimezone('UTC');

            $todayLeads = leads::where(function($q) use ($todayDate, $todayIstDate, $istStartUtc, $istEndUtc) {
                $q->whereBetween('created_at', [$istStartUtc, $istEndUtc])
                  ->orWhereDate('created_at', $todayDate)
                  ->orWhereDate('created_at', $todayIstDate);
            })->count();

            $todayEstimates = estimate::where(function($q) use ($todayDate, $todayIstDate, $istStartUtc, $istEndUtc) {
                $q->whereBetween('created_at', [$istStartUtc, $istEndUtc])
                  ->orWhereDate('created_at', $todayDate)
                  ->orWhereDate('created_at', $todayIstDate)
                  ->orWhereDate('estimate_date', $todayDate)
                  ->orWhereDate('estimate_date', $todayIstDate);
            })->count();

            $todayFeedbacks = leadfeedback::where(function($q) use ($todayDate, $todayIstDate, $istStartUtc, $istEndUtc) {
                $q->whereBetween('created_at', [$istStartUtc, $istEndUtc])
                  ->orWhereDate('created_at', $todayDate)
                  ->orWhereDate('created_at', $todayIstDate);
            })->count();

            $todayCustomers = customers::where(function($q) use ($todayDate, $todayIstDate, $istStartUtc, $istEndUtc) {
                $q->whereBetween('created_at', [$istStartUtc, $istEndUtc])
                  ->orWhereDate('created_at', $todayDate)
                  ->orWhereDate('created_at', $todayIstDate);
            })->count();

            // Current Month metrics
            $currentMonth = now()->month;
            $currentYear = now()->year;

            $monthLeads = leads::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count();

            $monthEstimates = estimate::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count();

            $monthFeedbacks = leadfeedback::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count();

            $monthCustomers = customers::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count();

            $monthEstimateValue = estimate::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->sum('net_total');

            $monthCustomerValue = customers::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->sum('Total_values');

            $monthlyLeads = leads::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
                ->whereYear('created_at', now()->year)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy(DB::raw('MONTH(created_at)'))
                ->get();

            $monthlyEstimates = estimate::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(net_total) as value')
            )
                ->whereYear('created_at', now()->year)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy(DB::raw('MONTH(created_at)'))
                ->get();

            $monthlyCustomers = customers::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(Total_values) as value')
            )
                ->whereYear('created_at', now()->year)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy(DB::raw('MONTH(created_at)'))
                ->get();

            $monthLabels = $monthlyLeads->map(function ($row) {
                return Carbon::create()->month($row->month)->format('M');
            });

            $monthValues = $monthlyLeads->pluck('total');

            // Map data for 12 months for comprehensive modern charts and table
            $leadsByMonth = $monthlyLeads->keyBy('month');
            $estimatesByMonth = $monthlyEstimates->keyBy('month');
            $customersByMonth = $monthlyCustomers->keyBy('month');

            $allMonthLabels = [];
            $leadSeries = [];
            $estimateSeries = [];
            $customerSeries = [];
            $estimateValueSeries = [];
            $customerValueSeries = [];

            for ($m = 1; $m <= 12; $m++) {
                $allMonthLabels[] = Carbon::create()->month($m)->format('M');
                $leadSeries[] = $leadsByMonth->has($m) ? (int)$leadsByMonth[$m]->total : 0;
                $estimateSeries[] = $estimatesByMonth->has($m) ? (int)$estimatesByMonth[$m]->total : 0;
                $customerSeries[] = $customersByMonth->has($m) ? (int)$customersByMonth[$m]->total : 0;
                $estimateValueSeries[] = $estimatesByMonth->has($m) ? (float)$estimatesByMonth[$m]->value : 0;
                $customerValueSeries[] = $customersByMonth->has($m) ? (float)$customersByMonth[$m]->value : 0;
            }

            $ytdLeads = $monthlyLeads->sum('total');
            $ytdEstimates = $monthlyEstimates->sum('total');
            $ytdCustomers = $monthlyCustomers->sum('total');
            $ytdEstimateValue = $monthlyEstimates->sum('value');
            $ytdCustomerValue = $monthlyCustomers->sum('value');
            $ytdConversionRate = $ytdLeads > 0 ? round(($ytdCustomers / $ytdLeads) * 100, 1) : 0;

            return view('admin.reports', compact(
                'escount',
                'lc',
                'leadSC',
                'lsc',
                'monthlyLeads',
                'monthlyEstimates',
                'monthlyCustomers',
                'todayLeads',
                'todayEstimates',
                'todayFeedbacks',
                'todayCustomers',
                'monthLabels',
                'monthValues',
                'allMonthLabels',
                'leadSeries',
                'estimateSeries',
                'customerSeries',
                'estimateValueSeries',
                'customerValueSeries',
                'ytdLeads',
                'ytdEstimates',
                'ytdCustomers',
                'ytdEstimateValue',
                'ytdCustomerValue',
                'ytdConversionRate',
                'monthLeads',
                'monthEstimates',
                'monthFeedbacks',
                'monthCustomers',
                'monthEstimateValue',
                'monthCustomerValue'
            ));
        }

        return redirect()
            ->route('login')
            ->with('error', 'You must Login');
    }

    public function customReportForm()
    {
        return view('admin.custom-report');
    }

    public function customReportPdf(Request $request)
    {
        $from = $request->filled('from_date')
            ? Carbon::parse($request->from_date)->startOfDay()
            : Carbon::now('Asia/Kolkata')->startOfMonth()->startOfDay();

        $to = $request->filled('to_date')
            ? Carbon::parse($request->to_date)->endOfDay()
            : Carbon::now('Asia/Kolkata')->endOfDay();

        $type = $request->report_type;

        if ($type == 'source') {
            return app(\App\Http\Controllers\admincontroller::class)->sourceReportPdf($request);
        } elseif ($type == 'estimate') {
            $estimates = estimate::whereBetween('created_at', [$from, $to])
                ->latest()
                ->get();
            
            $totalCount = $estimates->count();
            $totalValue = $estimates->sum('net_total');

            $dateString = $from->format('d-m-Y') . ' to ' . $to->format('d-m-Y');

            $pdf = PDF::loadView(
                'admin.estimate-monthly-report-pdf',
                compact('estimates', 'totalCount', 'totalValue', 'dateString')
            );

            return $pdf->download('Estimate_Custom_Report_' . $from->format('d-m-Y') . '_To_' . $to->format('d-m-Y') . '.pdf');
        } elseif ($type == 'customer') {
            $customers = customers::whereBetween('created_at', [$from, $to])
                ->latest()
                ->get();
            
            $totalCount = $customers->count();
            $totalValue = $customers->sum('Total_values');

            $dateString = $from->format('d-m-Y') . ' to ' . $to->format('d-m-Y');

            $pdf = PDF::loadView(
                'admin.customer-monthly-report-pdf',
                compact('customers', 'totalCount', 'totalValue', 'dateString')
            );

            return $pdf->download('Customer_Custom_Report_' . $from->format('d-m-Y') . '_To_' . $to->format('d-m-Y') . '.pdf');
        }

        // Summary for Business Report

        $totalLeads = leads::whereBetween('created_at', [$from, $to])->count();

        $totalEstimates = estimate::whereBetween('created_at', [$from, $to])->count();

        $totalCustomers = customers::whereBetween('created_at', [$from, $to])->count();

        // Lead Source

        $leadSources = leads::select(
            'source',
            DB::raw('COUNT(*) as total')
        )
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('source')
            ->orderByDesc('total')
            ->get();

        // Product

        $productReport = leads::select(
            'Product',
            DB::raw('COUNT(*) as total')
        )
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('Product')
            ->orderByDesc('total')
            ->get();

        // Status

        $statusReport = leads::select(
            'Status',
            DB::raw('COUNT(*) as total')
        )
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('Status')
            ->orderByDesc('total')
            ->get();

        // Confirmed Orders

        $confirmedOrders = leads::whereBetween('created_at', [$from, $to])
            ->where('Status', 'Confirmed')
            ->latest()
            ->get();

        $confirmedCount = $confirmedOrders->count();

        $confirmedAmount = $confirmedOrders->sum('net_total');

        // Lead Details

        $leadDetails = leads::with(['feedbacks' => function ($query) {
            $query->latest();
        }])
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = PDF::loadView(
            'admin.monthly-report-pdf',
            compact(
                'from',
                'to',
                'totalLeads',
                'totalEstimates',
                'totalCustomers',
                'leadSources',
                'productReport',
                'statusReport',
                'confirmedOrders',
                'confirmedCount',
                'confirmedAmount',
                'leadDetails'
            )
        );

        return $pdf->download(
            'Business_Report_' . $from->format('d-m-Y') . '_To_' . $to->format('d-m-Y') . '.pdf'
        );
    }

    public function estimateMonthlyReportPdf()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $estimates = estimate::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->latest()
            ->get();
        
        $totalCount = $estimates->count();
        $totalValue = $estimates->sum('net_total');

        $dateString = now()->format('F Y');

        $pdf = PDF::loadView(
            'admin.estimate-monthly-report-pdf',
            compact('estimates', 'totalCount', 'totalValue', 'dateString')
        );

        return $pdf->download('Estimate_Monthly_Report_' . now()->format('M_Y') . '.pdf');
    }

    public function customerMonthlyReportPdf()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $customers = customers::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->latest()
            ->get();
        
        $totalCount = $customers->count();
        $totalValue = $customers->sum('Total_values');

        $dateString = now()->format('F Y');

        $pdf = PDF::loadView(
            'admin.customer-monthly-report-pdf',
            compact('customers', 'totalCount', 'totalValue', 'dateString')
        );

        return $pdf->download('Customer_Monthly_Report_' . now()->format('M_Y') . '.pdf');
    }
}
