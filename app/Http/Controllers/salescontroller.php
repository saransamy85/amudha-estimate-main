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
use Illuminate\Support\Facades\Auth;
use DB;

class salescontroller extends Controller
{
    public function salesdash(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must Login');
        }

        if (Auth::user()->Role == 'Admin') {
            return redirect('dashboard');
        }

        $query = estimate::with(['lead', 'customer']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('estimate_no', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('mobile', 'LIKE', "%{$search}%")
                  ->orWhere('customer_address', 'LIKE', "%{$search}%");
            });
        }

        $statusFilter = $request->get('status', 'all');
        if ($statusFilter === 'converted') {
            $query->whereHas('customer');
        } elseif ($statusFilter === 'cancelled') {
            $query->whereHas('lead', function ($q) {
                $q->whereRaw('LOWER(Status) = ?', ['cancelled']);
            })->whereDoesntHave('customer');
        } elseif ($statusFilter === 'active') {
            $query->whereDoesntHave('lead', function ($q) {
                $q->whereRaw('LOWER(Status) = ?', ['cancelled']);
            })->whereDoesntHave('customer');
        }

        $estimates = $query->latest()->paginate(10)->withQueryString();

        $lc = leads::count();
        $cuscount = customers::count();
        $escount = estimate::whereDoesntHave('lead', function ($q) {
            $q->whereRaw('LOWER(Status) = ?', ['cancelled']);
        })->whereDoesntHave('customer')->count();
        $convertedCount = estimate::whereHas('customer')->count();
        $cancelledCount = estimate::whereHas('lead', function ($q) {
            $q->whereRaw('LOWER(Status) = ?', ['cancelled']);
        })->whereDoesntHave('customer')->count();
        $totalEstimates = estimate::count();

        $convertedEstimates = customers::whereNotNull('estimate_id')
            ->pluck('estimate_id')
            ->toArray();

        $todayDate = Carbon::today()->toDateString();
        $todayIstDate = Carbon::today('Asia/Kolkata')->toDateString();
        $todayCount = estimate::where(function ($q) use ($todayDate, $todayIstDate) {
            $q->whereDate('created_at', $todayDate)
              ->orWhereDate('created_at', $todayIstDate)
              ->orWhereDate('estimate_date', $todayDate)
              ->orWhereDate('estimate_date', $todayIstDate);
        })->count();

        $monthlyCount = leads::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalPipelineValue = estimate::whereYear('created_at', now()->year)->sum('net_total') ?? 0;

        return view('sales.salesdashboard', compact(
            'estimates',
            'todayCount',
            'monthlyCount',
            'lc',
            'cuscount',
            'escount',
            'convertedCount',
            'cancelledCount',
            'totalEstimates',
            'convertedEstimates',
            'totalPipelineValue',
            'statusFilter'
        ));
    }

    public function salescus(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must Login');
        }

        $query = customers::latest();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('client_name', 'LIKE', "%{$search}%")
                  ->orWhere('Mobile', 'LIKE', "%{$search}%")
                  ->orWhere('Location', 'LIKE', "%{$search}%")
                  ->orWhere('Product', 'LIKE', "%{$search}%");
            });
        }

        $cli = $query->paginate(15)->withQueryString();
        return view('sales.salescustomer', compact('cli'));
    }

    public function leaddash(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must Login');
        }

        if (Auth::user()->Role == 'Admin') {
            return redirect()->route('adminlead');
        }

        $cuscount = customers::count();
        $lc = leads::count();

        $lds = leads::with('feedbacks');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $lds->where(function ($query) use ($search) {
                $query
                    ->where('Name', 'LIKE', "%{$search}%")
                    ->orWhere('Mobile', 'LIKE', "%{$search}%")
                    ->orWhere('Product', 'LIKE', "%{$search}%")
                    ->orWhere('Site_location', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $lds->where('Status', $request->status);
        }

        if ($request->filled('source')) {
            $lds->where('source', $request->source);
        }

        $nowInIndia = Carbon::now('Asia/Kolkata');
        $currentMonth = $nowInIndia->month;
        $currentYear = $nowInIndia->year;
        $currentMonthName = $nowInIndia->format('F Y');

        if ($request->get('period') === 'this_month') {
            $lds->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear);
        }

        $lds = $lds->orderBy('id', 'DESC')->get();

        $leadfeed = leadfeedback::all();

        $leadSC = leads::select('Status', \DB::raw('count(*) as total'))
            ->groupBy('Status')
            ->pluck('total', 'Status');

        $lsc = leads::select('source', \DB::raw('count(*) as total'))
            ->groupBy('source')
            ->pluck('total', 'source');

        $monthLeadCount = leads::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $monthLeadSC = leads::select('Status', \DB::raw('count(*) as total'))
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->groupBy('Status')
            ->pluck('total', 'Status');

        $monthLsc = leads::select('source', \DB::raw('count(*) as total'))
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->groupBy('source')
            ->pluck('total', 'source');

        $referenceLeads = leads::with('feedbacks')
            ->where('source', 'Reference')
            ->latest()
            ->paginate(10);

        return view('sales.leaddash', compact(
            'lds',
            'leadfeed',
            'leadSC',
            'lsc',
            'lc',
            'cuscount',
            'referenceLeads',
            'currentMonthName',
            'monthLeadCount',
            'monthLeadSC',
            'monthLsc'
        ));
    }

    public function addlead(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must Login');
        }

        leads::create([
            'source' => $request->source,
            'Name' => $request->Name,
            'Mobile' => $request->Mobile,
            'email' => $request->email,
            'Product' => $request->Product,
            'Total_Area' => $request->Total_Area,
            'Description' => $request->Description,
            'Site_location' => $request->Site_location,
            'Status' => $request->Status ?? $request->status ?? 'Details shared',
            'created_by' => Auth::id(),
        ]);

        if (Auth::user()->Role == 'Admin') {
            return redirect()->route('adminlead')->with('success', 'Lead added successfully.');
        }

        return redirect()->route('leaddash')->with('success', 'Lead added successfully.');
    }

    public function addfeedback(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must Login');
        }

        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'feedback' => 'required|string',
        ]);

        $lead = leads::findOrFail($request->lead_id);
        $lead->update([
            'Status' => $request->status ?? $lead->Status,
        ]);
        $lead->feedbacks()->create([
            'feedback' => $request->feedback,
        ]);

        if (Auth::user()->Role == 'Admin') {
            return redirect()->route('adminlead')->with('success', 'Feedback updated successfully.');
        }

        return redirect()->route('leaddash')->with('success', 'Feedback updated successfully.');
    }

    public function addestimate()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must Login');
        }

        return view('sales.addestimate');
    }

    public function salesgreetings()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must Login');
        }

        $emailLeads = leads::whereNotNull('email')
            ->where('email', '!=', '')
            ->latest()
            ->take(50)
            ->get(['id', 'Name', 'email', 'Mobile', 'Product', 'Status']);

        return view('sales.salesgreetings', compact('emailLeads'));
    }
}
