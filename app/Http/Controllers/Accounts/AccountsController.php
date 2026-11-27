<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\customers;
use App\Models\estimate;
use App\Models\leads;
use App\Models\PurchaseOrder;
use App\Models\MaterialDispatch;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class AccountsController extends Controller
{
    public function home()
    {
        $today = Carbon::today('Asia/Kolkata')->toDateString();
        $totalStaff = User::where('employment_status', '!=', 'Inactive')->count();
        $presentToday = Attendance::whereDate('attendance_date', $today)->count();
        $onlineToday = User::where('Status', 'Online')->count();
        $absentToday = max(0, $totalStaff - $presentToday);

        $todayAttendances = Attendance::with('user')
            ->whereDate('attendance_date', $today)
            ->latest('login_time')
            ->take(8)
            ->get();

        $recentLeadsCount = leads::count();
        $estimatesCount = estimate::count();
        $purchaseOrdersCount = PurchaseOrder::count();

        return view('Accounts.home', compact(
            'totalStaff',
            'presentToday',
            'onlineToday',
            'absentToday',
            'todayAttendances',
            'recentLeadsCount',
            'estimatesCount',
            'purchaseOrdersCount',
            'today'
        ));
    }

    public function customerSearch(Request $request)
    {
        $lead = null;
        $estimates = collect();
        $purchaseOrders = collect();
        $materialDispatches = collect();
        $recentLeads = leads::latest()->take(6)->get();

        if ($request->filled('lead_id')) {
            $searchKey = trim($request->lead_id);

            // 1. Try finding by ID or exact match
            if (is_numeric($searchKey)) {
                $lead = leads::find($searchKey) ?? leads::where('Mobile', $searchKey)->first();
            } else {
                $lead = leads::where('Name', 'like', "%{$searchKey}%")
                    ->orWhere('Mobile', 'like', "%{$searchKey}%")
                    ->first();
            }

            // 2. Fallback: check if customer record matches
            if (!$lead) {
                $cust = customers::where('Mobile', $searchKey)
                    ->orWhere('client_name', 'like', "%{$searchKey}%")
                    ->first();
                if ($cust && $cust->lead_id) {
                    $lead = leads::find($cust->lead_id);
                }
            }

            if ($lead) {
                // All Estimates for this Lead
                $estimates = estimate::with('customer')
                    ->where('lead_id', $lead->id)
                    ->latest()
                    ->get();

                // Customer IDs from estimates & direct customer associations
                $customerIds = $estimates
                    ->pluck('customer.id')
                    ->filter()
                    ->toArray();

                $directCustomerIds = customers::where('lead_id', $lead->id)->pluck('id')->toArray();
                $customerIds = array_values(array_unique(array_merge($customerIds, $directCustomerIds)));

                // Purchase Orders (eager load vendor & customer)
                $purchaseOrders = PurchaseOrder::with(['pocustomer', 'vendor'])
                    ->whereIn('site_id', $customerIds)
                    ->latest()
                    ->get();

                // Material Dispatches
                $materialDispatches = MaterialDispatch::with('customer')
                    ->whereIn('customer_id', $customerIds)
                    ->latest()
                    ->get();
            }
        }

        return view(
            'Accounts.customersearch',
            compact('lead', 'estimates', 'purchaseOrders', 'materialDispatches', 'recentLeads')    
        );
    }

    public function customerReportPdf($lead_id)
    {
        // Lead Details
        $lead = Leads::findOrFail($lead_id);

        // Estimate Details
        $estimates = Estimate::with('customer')
            ->where('lead_id', $lead_id)
                ->get();

        // Customer IDs
        $customerIds = $estimates
            ->pluck('customer.id')
            ->filter()
        ->toArray();

        // Purchase Orders
        $purchaseOrders = PurchaseOrder::with(['vendor', 'customer'])
            ->whereIn('site_id', $customerIds)
            ->get();

        // Material Dispatches
        $materialDispatches = MaterialDispatch::with('customer')
            ->whereIn('customer_id', $customerIds)
            ->get();

        $pdf = Pdf::loadView(
            'Accounts.customer-report-pdf',
            compact('lead', 'estimates', 'purchaseOrders', 'materialDispatches')
        );

        return $pdf->stream('Customer_Report_'.$lead->id.'.pdf');
    }

    /**
     * Staff Attendance Dashboard (Restricted to Admin & Accounts)
     */
    public function attendance(Request $request)
    {
        if (!Auth::check() || !in_array(Auth::user()->Role, ['Admin', 'Accounts'])) {
            abort(403, 'Access Denied: The Staff Attendance Dashboard is only accessible to Admin and Accounts.');
        }

        $selectedDate = $request->get('date', Carbon::today('Asia/Kolkata')->toDateString());
        $roleFilter = $request->get('role');
        $search = $request->get('search');
        $statusFilter = $request->get('status');

        $query = User::where('employment_status', '!=', 'Inactive');

        if (!empty($roleFilter)) {
            $query->where('Role', $roleFilter);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        $allUsers = $query->orderBy('name', 'ASC')->get();

        // Get attendances for selected date
        $attendances = Attendance::whereDate('attendance_date', $selectedDate)
            ->get()
            ->keyBy('user_id');

        // Build composite staff attendance list
        $staffList = $allUsers->map(function ($u) use ($attendances) {
            $att = $attendances->get($u->id);
            return (object) [
                'user' => $u,
                'attendance' => $att,
                'is_present' => !empty($att),
                'status' => $att ? $att->status : 'Absent',
                'login_time' => $att ? $att->formatted_login_time : '—',
                'logout_time' => $att ? $att->formatted_logout_time : '—',
                'duration' => $att ? $att->duration : '—',
                'login_count' => $att ? $att->login_count : 0,
                'ip_address' => $att ? $att->ip_address : '—',
                'remarks' => $att ? $att->remarks : '',
            ];
        });

        // Filter by status if specified
        if (!empty($statusFilter)) {
            if ($statusFilter == 'Present') {
                $staffList = $staffList->filter(function ($item) {
                    return $item->is_present && in_array($item->status, ['Present', 'Late', 'Half Day', 'On Duty']);
                });
            } elseif ($statusFilter == 'Absent') {
                $staffList = $staffList->filter(function ($item) {
                    return !$item->is_present || $item->status == 'Absent';
                });
            } else {
                $staffList = $staffList->filter(function ($item) use ($statusFilter) {
                    return $item->status == $statusFilter;
                });
            }
        }

        // Summary Statistics for the selected date
        $totalActiveStaff = User::where('employment_status', '!=', 'Inactive')->count();
        $totalPresentCount = Attendance::whereDate('attendance_date', $selectedDate)
            ->whereIn('status', ['Present', 'Late', 'Half Day', 'On Duty'])
            ->count();
        $totalAbsentCount = max(0, $totalActiveStaff - $totalPresentCount);
        $totalOnlineCount = User::where('Status', 'Online')->count();

        // Late Count (logged in after 09:30 AM)
        $totalLateCount = Attendance::whereDate('attendance_date', $selectedDate)
            ->where('login_time', '>', '09:30:00')
            ->count();

        // Available distinct roles for filtering
        $roles = User::whereNotNull('Role')->distinct()->pluck('Role');

        // Recent monthly presence summary (days present in current month)
        $monthStart = Carbon::parse($selectedDate)->startOfMonth()->toDateString();
        $monthEnd = Carbon::parse($selectedDate)->endOfMonth()->toDateString();
        $monthlyPresentCounts = Attendance::whereBetween('attendance_date', [$monthStart, $monthEnd])
            ->whereIn('status', ['Present', 'Late', 'Half Day', 'On Duty'])
            ->selectRaw('user_id, count(*) as days_present')
            ->groupBy('user_id')
            ->pluck('days_present', 'user_id');

        return view('Accounts.attendance', compact(
            'staffList',
            'selectedDate',
            'roleFilter',
            'statusFilter',
            'search',
            'totalActiveStaff',
            'totalPresentCount',
            'totalAbsentCount',
            'totalOnlineCount',
            'totalLateCount',
            'roles',
            'monthlyPresentCounts'
        ));
    }

    /**
     * Update or manually record attendance (Restricted to Admin & Accounts)
     */
    public function updateAttendanceStatus(Request $request)
    {
        if (!Auth::check() || !in_array(Auth::user()->Role, ['Admin', 'Accounts'])) {
            abort(403, 'Access Denied: You do not have permission to modify staff attendance.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'attendance_date' => 'required|date',
            'status' => 'required|string',
        ]);

        try {
            $attendance = Attendance::firstOrNew([
                'user_id' => $request->user_id,
                'attendance_date' => $request->attendance_date,
            ]);

            $attendance->status = $request->status;

            if ($request->filled('login_time')) {
                $attendance->login_time = $request->login_time;
            } elseif (!$attendance->exists && empty($attendance->login_time)) {
                $attendance->login_time = Carbon::now('Asia/Kolkata')->format('H:i:s');
            }

            if ($request->filled('logout_time')) {
                $attendance->logout_time = $request->logout_time;
            }

            if ($request->filled('remarks')) {
                $attendance->remarks = $request->remarks;
            }

            if (!$attendance->exists) {
                $attendance->login_count = 1;
                $attendance->ip_address = $request->ip();
            }

            $attendance->save();

            return back()->with('success', 'Staff attendance updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating attendance: ' . $e->getMessage());
        }
    }

    /**
     * Export Attendance CSV (Restricted to Admin & Accounts)
     */
    public function exportAttendanceCsv(Request $request)
    {
        if (!Auth::check() || !in_array(Auth::user()->Role, ['Admin', 'Accounts'])) {
            abort(403, 'Access Denied: You do not have permission to export staff attendance.');
        }

        $date = $request->get('date', Carbon::today('Asia/Kolkata')->toDateString());
        $users = User::where('employment_status', '!=', 'Inactive')->orderBy('name')->get();
        $attendances = Attendance::whereDate('attendance_date', $date)->get()->keyBy('user_id');

        $filename = "attendance_report_{$date}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($users, $attendances, $date) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Employee Code', 'Staff Name', 'Email', 'Role', 'Date', 'Status', 'First Login', 'Check-In GPS', 'Check-In Address', 'Last Logout', 'Check-Out GPS', 'Check-Out Address', 'Working Duration', 'Source', 'Device Info', 'IP Address', 'Remarks']);

            foreach ($users as $u) {
                $att = $attendances->get($u->id);
                fputcsv($file, [
                    $u->id,
                    $u->employee_code ?: 'EMP-' . str_pad($u->id, 3, '0', STR_PAD_LEFT),
                    $u->name,
                    $u->email,
                    $u->Role,
                    $date,
                    $att ? $att->status : 'Absent',
                    $att ? $att->formatted_login_time : '—',
                    $att && $att->checkin_latitude ? "{$att->checkin_latitude}, {$att->checkin_longitude}" : '—',
                    $att ? ($att->checkin_address ?: '—') : '—',
                    $att ? $att->formatted_logout_time : '—',
                    $att && $att->checkout_latitude ? "{$att->checkout_latitude}, {$att->checkout_longitude}" : '—',
                    $att ? ($att->checkout_address ?: '—') : '—',
                    $att ? $att->duration : '—',
                    $att ? ($att->source ?: 'web') : '—',
                    $att ? ($att->device_info ?: '—') : '—',
                    $att ? $att->login_count : 0,
                    $att ? $att->ip_address : '—',
                    $att ? $att->remarks : '',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Individual User Self Attendance Check-In / Punch In
     * (Available to all logged-in staff - requires GPS location)
     */
    public function myCheckIn(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Location permission check
        if (!$request->filled('latitude') || !$request->filled('longitude')) {
            return back()->with('error', 'Location permission is required to mark your attendance. Please allow GPS location access in your browser/device settings and try again.');
        }

        $userId = Auth::id();
        $todayDate = Carbon::today('Asia/Kolkata')->toDateString();
        $currentTime = Carbon::now('Asia/Kolkata')->format('H:i:s');

        try {
            $attendance = Attendance::firstOrCreate(
                [
                    'user_id' => $userId,
                    'attendance_date' => $todayDate,
                ],
                [
                    'login_time' => $currentTime,
                    'status' => 'Present',
                    'checkin_latitude' => $request->latitude,
                    'checkin_longitude' => $request->longitude,
                    'checkin_address' => $request->address,
                    'device_info' => $request->get('device_info', substr((string) $request->userAgent(), 0, 255)),
                    'source' => $request->get('source', 'web_gps'),
                    'ip_address' => $request->ip(),
                    'user_agent' => substr((string) $request->userAgent(), 0, 255),
                    'login_count' => 1,
                    'last_activity_at' => Carbon::now('Asia/Kolkata'),
                ]
            );

            if (!$attendance->wasRecentlyCreated) {
                if (empty($attendance->login_time)) {
                    $attendance->login_time = $currentTime;
                    $attendance->status = 'Present';
                }
                if (empty($attendance->checkin_latitude)) {
                    $attendance->checkin_latitude = $request->latitude;
                    $attendance->checkin_longitude = $request->longitude;
                    $attendance->checkin_address = $request->address;
                }
                $attendance->increment('login_count');
                $attendance->update([
                    'last_activity_at' => Carbon::now('Asia/Kolkata'),
                    'ip_address' => $request->ip(),
                ]);
                $msg = 'Your attendance is already marked for today at ' . Carbon::parse($attendance->login_time)->setTimezone('Asia/Kolkata')->format('h:i A') . ' (GPS Verified).';
            } else {
                $msg = 'Attendance marked successfully! Check-in recorded at ' . Carbon::parse($currentTime)->setTimezone('Asia/Kolkata')->format('h:i A') . ' with GPS location verified.';
            }

            // Keep user Status as Online
            Auth::user()->update([
                'Status' => 'Online',
                'last_login' => Carbon::now('Asia/Kolkata'),
            ]);

            return back()->with('success', $msg);
        } catch (\Exception $e) {
            return back()->with('error', 'Could not record attendance: ' . $e->getMessage());
        }
    }

    /**
     * Individual User Self Attendance Check-Out / Punch Out
     * (Available to all logged-in staff - requires GPS location)
     */
    public function myCheckOut(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Location permission check
        if (!$request->filled('latitude') || !$request->filled('longitude')) {
            return back()->with('error', 'Location permission is required to record check-out. Please allow GPS location access in your browser/device settings and try again.');
        }

        $userId = Auth::id();
        $todayDate = Carbon::today('Asia/Kolkata')->toDateString();
        $currentTime = Carbon::now('Asia/Kolkata')->format('H:i:s');

        try {
            $attendance = Attendance::where('user_id', $userId)
                ->whereDate('attendance_date', $todayDate)
                ->first();

            if ($attendance) {
                $attendance->update([
                    'logout_time' => $currentTime,
                    'checkout_latitude' => $request->latitude,
                    'checkout_longitude' => $request->longitude,
                    'checkout_address' => $request->address,
                    'last_activity_at' => Carbon::now('Asia/Kolkata'),
                ]);
                $msg = 'Check-out recorded successfully at ' . Carbon::parse($currentTime)->setTimezone('Asia/Kolkata')->format('h:i A') . ' (GPS Verified). Have a great day!';
            } else {
                Attendance::create([
                    'user_id' => $userId,
                    'attendance_date' => $todayDate,
                    'login_time' => $currentTime,
                    'logout_time' => $currentTime,
                    'status' => 'Present',
                    'checkin_latitude' => $request->latitude,
                    'checkin_longitude' => $request->longitude,
                    'checkin_address' => $request->address,
                    'checkout_latitude' => $request->latitude,
                    'checkout_longitude' => $request->longitude,
                    'checkout_address' => $request->address,
                    'source' => $request->get('source', 'web_gps'),
                    'ip_address' => $request->ip(),
                    'user_agent' => substr((string) $request->userAgent(), 0, 255),
                    'login_count' => 1,
                    'last_activity_at' => Carbon::now('Asia/Kolkata'),
                ]);
                $msg = 'Attendance & Check-out recorded at ' . Carbon::parse($currentTime)->setTimezone('Asia/Kolkata')->format('h:i A') . ' (GPS Verified).';
            }

            return back()->with('success', $msg);
        } catch (\Exception $e) {
            return back()->with('error', 'Could not record punch-out: ' . $e->getMessage());
        }
    }
}
