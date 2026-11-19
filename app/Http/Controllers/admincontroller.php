<?php

namespace App\Http\Controllers;

use App\Models\customers;
use App\Models\estimate;
use App\Models\estimateitems;
use App\Models\leadfeedback;
use App\Models\LeadActivity;
use App\Models\leads;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Attendance;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use DB;

class admincontroller extends Controller
{
    //

    public function getEstimateCount()
    {
        return estimate::whereDoesntHave('lead', function ($query) {
            $query->whereRaw('LOWER(Status) = ?', ['cancelled']);
        })->whereDoesntHave('customer')->count();
    }

    public function getConvertedEstimateCount()
    {
        return estimate::whereHas('customer')->count();
    }

    public function getCancelledEstimateCount()
    {
        return estimate::whereHas('lead', function ($query) {
            $query->whereRaw('LOWER(Status) = ?', ['cancelled']);
        })->whereDoesntHave('customer')->count();
    }

    public function getCustomerCount()
    {
        return customers::count();
    }

    public function getLeadCount()
    {
        return leads::where(function ($query) {
            $query->where('Status', '!=', 'Confirmed')
                ->orWhereNull('Status');
        })->count();
    }

    // public function dashboard()
    // {
    //     if (session()->has('username')) {
    //         $estimates = estimate::latest()->get();
    //         $escount = $this->getEstimateCount();
    //         $cuscount = $this->getCustomerCount();
    //         $onl = User::where('Status', 'Online')->get();
    //         $lc = $this->getLeadCount();
    //         $todayCount = estimate::whereDate('created_at', today())->count();
    //         $monthlyCount = leads::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();

    //         // Calculate converted estimates (estimates that became customers)
    //         $convertedEstimates = estimate::whereNotNull('lead_id')
    //             ->pluck('lead_id')
    //             ->toArray();
    //         return view('admin/dashboard', compact('estimates', 'escount', 'cuscount', 'onl', 'lc', 'todayCount', 'monthlyCount', 'convertedEstimates'));
    //     }
    //     return redirect()->route('login')->with('error', 'You must Login');
    // }


    public function dashboard(Request $request)
{
    if (session()->has('username')) {

        $query = Estimate::with('lead');

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('estimate_no', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('customer_address', 'like', "%{$search}%");

            });
        }

        $estimates = $query->latest()->get();

        $escount = $this->getEstimateCount();
        $convertedCount = $this->getConvertedEstimateCount();
        $cancelledCount = $this->getCancelledEstimateCount();
        $cuscount = customers::count();
        $lc = leads::count();
        // $lc = $this->getLeadCount();
        $onl = User::where('Status', 'Online')->get();

        $todayDate = Carbon::today()->toDateString();
        $todayIstDate = Carbon::today('Asia/Kolkata')->toDateString();
        $todayCount = estimate::where(function($q) use ($todayDate, $todayIstDate) {
            $q->whereDate('created_at', $todayDate)
              ->orWhereDate('created_at', $todayIstDate)
              ->orWhereDate('estimate_date', $todayDate)
              ->orWhereDate('estimate_date', $todayIstDate);
        })->count();

        $monthlyCount = leads::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $convertedEstimates = customers::whereNotNull('estimate_id')
            ->pluck('estimate_id')
            ->toArray();

        // Financial & Chart Analytics for Modern Dashboard
        $totalPipelineValue = estimate::whereYear('created_at', now()->year)->sum('net_total') ?? 0;
        $totalConvertedValue = customers::whereYear('created_at', now()->year)->sum('Total_values') ?? 0;
        $conversionRate = ($escount + $convertedCount) > 0 ? round(($convertedCount / ($escount + $convertedCount)) * 100, 1) : 0;

        $monthlyEstimatesData = estimate::select(
            DB::raw('MONTH(COALESCE(estimate_date, created_at)) as month'),
            DB::raw('COUNT(*) as total_count'),
            DB::raw('SUM(net_total) as total_value')
        )
        ->whereYear(DB::raw('COALESCE(estimate_date, created_at)'), now()->year)
        ->groupBy(DB::raw('MONTH(COALESCE(estimate_date, created_at))'))
        ->orderBy(DB::raw('MONTH(COALESCE(estimate_date, created_at))'))
        ->get()
        ->keyBy('month');

        $chartMonths = [];
        $chartEstimateCounts = [];
        $chartEstimateValues = [];

        for ($m = 1; $m <= 12; $m++) {
            $chartMonths[] = Carbon::create(now()->year, $m, 1)->format('M');
            $chartEstimateCounts[] = $monthlyEstimatesData->has($m) ? (int)$monthlyEstimatesData[$m]->total_count : 0;
            $chartEstimateValues[] = $monthlyEstimatesData->has($m) ? (float)$monthlyEstimatesData[$m]->total_value : 0;
        }

        return view('admin.dashboard', compact(
            'estimates',
            'escount',
            'convertedCount',
            'cancelledCount',
            'cuscount',
            'onl',
            'lc',
            'todayCount',
            'monthlyCount',
            'convertedEstimates',
            'totalPipelineValue',
            'totalConvertedValue',
            'conversionRate',
            'chartMonths',
            'chartEstimateCounts',
            'chartEstimateValues'
        ));
    }

    return redirect()->route('login');
}

    public function registration()
    {
        return view('Auth/registration');
    }

    public function regsubmission(Request $request)
    {
        $request->validate([
            'employee_code' => 'required|unique:users,employee_code',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|digits:10',
            'password' => 'required|min:6|confirmed',
            'Role' => 'required|in:Admin,Sales,Receptionist,Purchase,Accounts',
            'department' => 'required',
            'joining_date' => 'required|date',
        ]);

        try {
            User::create([
                'employee_code' => $request->employee_code,
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'Role' => $request->Role,
                'department' => $request->department,
                'joining_date' => $request->joining_date,
                'Status' => 'Offline',
                'employment_status' => 'Active',
            ]);

            return redirect()
                ->route('login')
                ->with('success', 'User registered successfully.');
        } catch (Exception $e) {
            Log::error('User Registration Error', [
                'message' => $e->getMessage(),
                'email' => $request->email,
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Unable to register user. Please try again.');
        }
    }

    public function login()
    {
        return view('Auth/login');
    }

    public function logins(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
            }
            // Prevent inactive users from logging in
            if (Auth::user()->employment_status == 'Inactive') {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Your account has been deactivated. Please contact the administrator.'
                ])->withInput();
            }

            $request->session()->regenerate();

            session([
                'username' => Auth::user()->name
            ]);

            Auth::user()->update([
                'Status' => 'Online',
                'last_login' => Carbon::now(),
            ]);

            switch (Auth::user()->Role) {
                case 'Sales':
                    return redirect()->route('salesdashboard');

                case 'Receptionist':
                    return redirect()->route('receptiondashboard');

                case 'Purchase':
                    return redirect()->route('purchasedashboard');

                case 'Accounts':
                    return redirect()->route('accountshome');

                case 'Admin':
                default:
                    return redirect()->route('dashboard1');
            }
        } catch (Exception $e) {
            Log::error('Login Error: ' . $e->getMessage(), [
                'email' => $request->email,
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->with('error', 'Something went wrong. Please try again.')
                ->withInput();
        }
    }

    public function logout()
    {
        if (Auth::check()) {
            auth()->user()->update(['Status' => 'Offline']);
        }

        Auth::logout();
        // Clear all session data
        session()->invalidate();
        // Regenerate CSRF token
        session()->regenerateToken();

        return redirect()->route('login');
    }

    public function adminlead(Request $request)
    {
        if (Auth::user()->Role == 'Admin') {
            $lc = leads::count();
            $escount = $this->getEstimateCount();
            $cuscount = $this->getCustomerCount();
            $onl = User::where('Status', 'Online')->get();
            $referenceLeads = leads::with('feedbacks')
                ->where('source', 'Reference')
                ->latest()
                ->get();

            $lds = Leads::with('feedbacks');

            if ($request->search != '') {
                $search = $request->search;

                $lds->where(function ($query) use ($search) {
                    $query
                        ->where('Name', 'LIKE', "%{$search}%")
                        ->orWhere('Mobile', 'LIKE', "%{$search}%");
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

            $leadSC = leads::select('Status', \DB::raw('count(*) as total'))
                ->groupBy('Status')
                ->pluck('total', 'Status');

            $lsc = leads::select('source', \DB::raw('count(*) as total'))
                ->groupBy('source')
                ->pluck('total', 'source');

            // Current Month Lead, Status, and Source Metrics
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

            return view('admin/admin-lead', compact(
                'onl',
                'lds',
                'lc',
                'leadSC',
                'lsc',
                'escount',
                'cuscount',
                'referenceLeads',
                'currentMonthName',
                'monthLeadCount',
                'monthLeadSC',
                'monthLsc'
            ));
        }

        return redirect()->route('leaddash');
    }

    public function edit($id)
    {
        $lead = leads::findOrFail($id);
        return view('admin/leadedit', compact('lead'));
    }

    public function update(Request $request, $id)
{
    $lead = leads::findOrFail($id);


        $lead->update([
            'Name' => $request->Name ?? '',
            'Mobile' => $request->Mobile ?? '',
            'email' => $request->email,
            'Product' => $request->Product ?? '',
            'Total_Area' => $request->Total_Area ?? '',
            'Site_location' => $request->Site_location ?? '',
            'Description' => $request->Description ?? '',
            'source' => $request->source ?? '',
            'Status' => $request->Status ?? '',
        ]);

    if (Auth::user()->Role == 'Admin') {
        return redirect()->route('adminlead')->with('success', 'Lead updated successfully');
    }
    return redirect()->route('leaddash')->with('success', 'Lead updated successfully');
}

    public function destroy($id)
    {
        if (Auth::user()->Role !== 'Admin') {
            return redirect()->route('adminlead')->with('error', 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {
            $lead = leads::findOrFail($id);
            $leadName = $lead->Name ?: 'ID #' . $id;

            // Delete feedbacks safely
            try {
                if (Schema::hasTable('leadfeedbacks')) {
                    leadfeedback::where('lead_id', $id)->delete();
                }
            } catch (\Throwable $e) {
                // Ignore if table not present
            }

            // Delete activities (only if table exists)
            try {
                if (Schema::hasTable('lead_activities')) {
                    LeadActivity::where('lead_id', $id)->delete();
                }
            } catch (\Throwable $e) {
                // Table does not exist in this environment
            }

            // Dissociate estimates safely
            try {
                if (Schema::hasTable('estimates')) {
                    estimate::where('lead_id', $id)->update(['lead_id' => null]);
                }
            } catch (\Throwable $e) {
                // Ignore if estimates table not present or column missing
            }

            // Delete lead record
            $lead->delete();

            DB::commit();

            return redirect()->route('adminlead')->with('success', "Enquiry #{$id} ({$leadName}) has been deleted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('adminlead')->with('error', 'Failed to delete enquiry: ' . $e->getMessage());
        }
    }

   

    public function admincus()
    {
        $cli = customers::all();
        $escount = $this->getEstimateCount();
        $cuscount = $this->getCustomerCount();
        $lc = $this->getLeadCount();
        return view('admin/customer', compact('cli', 'escount', 'cuscount', 'lc'));
    }

    public function dailyReportPdf()
    {
        $today = Carbon::today();

        $todayLeads = leads::whereDate('created_at', $today)->count();

        $todayEstimates = estimate::whereDate('created_at', $today)->count();

        $todayFeedbacks = leadfeedback::whereDate('created_at', $today)->count();

        $todayCustomers = customers::whereDate('created_at', $today)->count();

        $leadSC = leads::select(
            'Status',
            DB::raw('count(*) as total')
        )
            ->groupBy('Status')
            ->pluck('total', 'Status');

        $lsc = leads::select(
            'source',
            DB::raw('count(*) as total')
        )
            ->groupBy('source')
            ->pluck('total', 'source');

        $todayLeadList = leads::whereDate('created_at', $today)->get();
        $todayFeedbackList = leadfeedback::with('lead')
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        $monthlyLeads = leads::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        $escount = estimate::count();

        $lc = leads::count();

        $pdf = PDF::loadView(
            'admin/daily-report-pdf',
            compact(
                'todayLeads',
                'todayEstimates',
                'todayFeedbacks',
                'todayCustomers',
                'todayLeadList',
                'todayFeedbackList'
            )
        );

        return $pdf->download(
            'Daily_Report_' . $today->format('d-m-Y') . '.pdf'
        );
    }

    public function weeklyReportPdf()
    {
        $from = Carbon::today()->subDays(6);
        $to = Carbon::today();

        $weeklyLeads = leads::whereBetween('created_at', [$from, $to])->count();

        $weeklyEstimates = estimate::whereBetween('created_at', [$from, $to])->count();

        $weeklyFeedbacks = leadfeedback::whereBetween('created_at', [$from, $to])->count();

        $weeklyCustomers = customers::whereBetween('created_at', [$from, $to])->count();

        $weeklyEstimateValue = estimate::whereBetween('created_at', [$from, $to])
            ->sum('net_total');

        $weeklyLeadList = leads::whereBetween('created_at', [$from, $to])
            ->latest()
            ->get();

        $weeklyEstimateList = estimate::whereBetween('created_at', [$from, $to])
            ->latest()
            ->get();

        $weeklyCustomerList = customers::whereBetween('created_at', [$from, $to])
            ->latest()
            ->get();

        $weeklyFeedbackList = leadfeedback::with('lead')
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->get();

        $leadStatus = leads::whereBetween('created_at', [$from, $to])
            ->select('Status', DB::raw('COUNT(*) as total'))
            ->groupBy('Status')
            ->pluck('total', 'Status');

        $leadSource = leads::whereBetween('created_at', [$from, $to])
            ->select('source', DB::raw('COUNT(*) as total'))
            ->groupBy('source')
            ->pluck('total', 'source');

        $pdf = PDF::loadView(
            'admin.weekly-report-pdf',
            compact(
                'from',
                'to',
                'weeklyLeads',
                'weeklyEstimates',
                'weeklyCustomers',
                'weeklyFeedbacks',
                'weeklyEstimateValue',
                'weeklyLeadList',
                'weeklyEstimateList',
                'weeklyCustomerList',
                'weeklyFeedbackList',
                'leadStatus',
                'leadSource'
            )
        );

        return $pdf->download(
            'Weekly_Report_' . $from->format('d-m-Y')
            . '_to_' . $to->format('d-m-Y') . '.pdf'
        );
    }

    public function sourceReportPdf(Request $request)
    {
        $from = $request->filled('from_date')
            ? Carbon::parse($request->from_date)->startOfDay()
            : Carbon::now('Asia/Kolkata')->startOfMonth()->startOfDay();

        $to = $request->filled('to_date')
            ? Carbon::parse($request->to_date)->endOfDay()
            : Carbon::now('Asia/Kolkata')->endOfDay();

        // Multiple Choice Sources parsing
        $selectedSources = [];
        if ($request->has('sources')) {
            $rawSources = $request->input('sources');
            if (is_array($rawSources)) {
                $selectedSources = array_values(array_filter($rawSources));
            } elseif (is_string($rawSources)) {
                $selectedSources = array_values(array_filter(explode(',', $rawSources)));
            }
        } elseif ($request->filled('source') && $request->source !== 'all') {
            $selectedSources = [$request->source];
        }

        $allAvailableChannels = ['Website', 'WhatsApp', 'Phone Call', 'Showroom-walk-in', 'Reference', 'Old Database'];
        $isAllSelected = empty($selectedSources) || (count($selectedSources) >= count($allAvailableChannels));

        $baseQuery = leads::whereBetween('created_at', [$from, $to]);

        if (!empty($selectedSources) && !$isAllSelected) {
            $baseQuery->where(function ($q) use ($selectedSources) {
                foreach ($selectedSources as $idx => $s) {
                    $clean = trim($s);
                    $variants = [$clean, strtolower($clean)];
                    if (strcasecmp($clean, 'Phone Call') === 0) {
                        $variants[] = 'Phonecall';
                        $variants[] = 'phonecall';
                    }
                    if (strcasecmp($clean, 'Showroom-walk-in') === 0) {
                        $variants[] = 'Showroom-Walk-in';
                        $variants[] = 'showroom-walk-in';
                    }
                    if (strcasecmp($clean, 'WhatsApp') === 0) {
                        $variants[] = 'Whatsapp';
                        $variants[] = 'whatsapp';
                        $variants[] = 'Email or WhatsApp';
                    }

                    if ($idx === 0) {
                        $q->whereIn('source', $variants)
                          ->orWhere('source', 'LIKE', "%{$clean}%");
                    } else {
                        $q->orWhereIn('source', $variants)
                          ->orWhere('source', 'LIKE', "%{$clean}%");
                    }
                }
            });
        }

        $totalLeads = (clone $baseQuery)->count();
        $confirmedCount = (clone $baseQuery)->where('Status', 'Confirmed')->count();
        $quotedCount = (clone $baseQuery)->whereIn('Status', ['Quote Shared', 'Site Visit', 'Details shared'])->count();
        $activeFollowupCount = (clone $baseQuery)->whereIn('Status', ['Follow up', 'Details shared'])->count();

        // Lead Source Distribution (only from filtered baseQuery)
        $leadSources = (clone $baseQuery)
            ->select('source', DB::raw('COUNT(*) as total'))
            ->groupBy('source')
            ->orderByDesc('total')
            ->get();

        // Pre-calculate confirmed per source for conversion metrics
        $sourceConfirmed = (clone $baseQuery)
            ->where('Status', 'Confirmed')
            ->select('source', DB::raw('COUNT(*) as total'))
            ->groupBy('source')
            ->pluck('total', 'source');

        // Product Breakdown (only from filtered baseQuery)
        $productReport = (clone $baseQuery)
            ->select('Product', DB::raw('COUNT(*) as total'))
            ->groupBy('Product')
            ->orderByDesc('total')
            ->get();

        // Status Breakdown (only from filtered baseQuery)
        $statusReport = (clone $baseQuery)
            ->select('Status', DB::raw('COUNT(*) as total'))
            ->groupBy('Status')
            ->orderByDesc('total')
            ->get();

        // Backward compatibility counts
        $productCount = (clone $baseQuery)
            ->select('Product', DB::raw('count(*) as total'))
            ->groupBy('Product')
            ->pluck('total', 'Product');

        $statusCount = (clone $baseQuery)
            ->select('Status', DB::raw('count(*) as total'))
            ->groupBy('Status')
            ->pluck('total', 'Status');

        $leadList = (clone $baseQuery)->orderBy('created_at', 'desc')->get();

        $sources = $leadSources->pluck('source')->filter()->values()->toArray();
        if (empty($sources)) {
            $sources = !empty($selectedSources) ? $selectedSources : $allAvailableChannels;
        }

        $scopeText = $isAllSelected 
            ? 'All Marketing Channels' 
            : implode(', ', $selectedSources);

        $selectedSource = $isAllSelected ? 'all' : implode(', ', $selectedSources);

        $pdf = PDF::loadView(
            'admin.source-report-pdf',
            compact(
                'from',
                'to',
                'totalLeads',
                'confirmedCount',
                'quotedCount',
                'activeFollowupCount',
                'leadSources',
                'sourceConfirmed',
                'productReport',
                'statusReport',
                'productCount',
                'statusCount',
                'leadList',
                'selectedSource',
                'selectedSources',
                'scopeText',
                'isAllSelected',
                'sources'
            )
        );

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download(
            'Lead_Source_Report_' . $from->format('d-m-Y')
            . '_To_' . $to->format('d-m-Y') . '.pdf'
        );
    }

    public function po_orders()
    {
        $orders = PurchaseOrder::with([
            'vendor',
            'customer'
        ])
            ->latest()
            ->get();
        return view('admin/po_orders', compact('orders'));
    }

    public function usermanagement()
    {
        $users = User::all();
        $myLeads = Auth::user()->leads;
        $myestimates = Auth::user()->estimates;
        return view('admin/user-details', compact('users', 'myLeads', 'myestimates'));
    }
    public function msgs()
    {
        $escount=estimate::count();
        $cuscount=customers::count();
        $lc=leads::count();
        $customerMobiles = customers::whereNotNull('Mobile')->where('Mobile', '!=', '')->pluck('Mobile')->unique()->values();
        $leadMobiles = leads::whereNotNull('Mobile')->where('Mobile', '!=', '')->pluck('Mobile')->unique()->values();
        return view('admin/rcs',compact('escount','cuscount','lc','customerMobiles','leadMobiles'));
    }
}
