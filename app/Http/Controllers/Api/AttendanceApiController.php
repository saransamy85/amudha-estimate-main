<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceApiController extends Controller
{
    /**
     * Today's Attendance Status for Mobile App
     * GET /api/attendance/today
     */
    public function today(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated user.',
            ], 401);
        }

        $todayDate = Carbon::today('Asia/Kolkata')->toDateString();
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $todayDate)
            ->first();

        $currentTime = Carbon::now('Asia/Kolkata')->format('h:i:s A');

        return response()->json([
            'status' => true,
            'server_date' => $todayDate,
            'server_time' => $currentTime,
            'is_checked_in' => !empty($attendance && $attendance->login_time),
            'is_checked_out' => !empty($attendance && $attendance->logout_time),
            'data' => $attendance,
        ], 200);
    }

    /**
     * Staff Check-In with GPS Coordinates
     * POST /api/attendance/check-in
     *
     * Params:
     * - latitude (required|numeric)
     * - longitude (required|numeric)
     * - address (optional|string)
     * - device_info (optional|string)
     * - remarks (optional|string)
     */
    public function checkIn(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated user.',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:500',
            'device_info' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'GPS coordinates validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $todayDate = Carbon::today('Asia/Kolkata')->toDateString();
        $currentTime = Carbon::now('Asia/Kolkata')->format('H:i:s');

        try {
            // Check if already checked in today
            $existing = Attendance::where('user_id', $user->id)
                ->whereDate('attendance_date', $todayDate)
                ->first();

            if ($existing && !empty($existing->login_time)) {
                return response()->json([
                    'status' => false,
                    'already_checked_in' => true,
                    'message' => 'You have already checked in today at ' . $existing->formatted_login_time . '.',
                    'data' => $existing,
                ], 400);
            }

            // Record attendance check-in
            $attendance = Attendance::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'attendance_date' => $todayDate,
                ],
                [
                    'login_time' => $currentTime,
                    'checkin_latitude' => $request->latitude,
                    'checkin_longitude' => $request->longitude,
                    'checkin_address' => $request->address,
                    'device_info' => $request->device_info ?: substr((string) $request->userAgent(), 0, 255),
                    'source' => 'mobile_app',
                    'status' => 'Present',
                    'login_count' => 1,
                    'ip_address' => $request->ip(),
                    'user_agent' => substr((string) $request->userAgent(), 0, 255),
                    'remarks' => $request->remarks ?: ($existing ? $existing->remarks : null),
                    'last_activity_at' => Carbon::now('Asia/Kolkata'),
                ]
            );

            // Update user status
            $user->update([
                'Status' => 'Online',
                'last_login' => Carbon::now('Asia/Kolkata'),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Check-in successful at ' . $attendance->formatted_login_time . '.',
                'data' => $attendance,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to record check-in: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Staff Check-Out with GPS Coordinates
     * POST /api/attendance/check-out
     *
     * Params:
     * - latitude (required|numeric)
     * - longitude (required|numeric)
     * - address (optional|string)
     * - device_info (optional|string)
     * - remarks (optional|string)
     */
    public function checkOut(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated user.',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:500',
            'device_info' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'GPS coordinates validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $todayDate = Carbon::today('Asia/Kolkata')->toDateString();
        $currentTime = Carbon::now('Asia/Kolkata')->format('H:i:s');

        try {
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('attendance_date', $todayDate)
                ->first();

            if (!$attendance || empty($attendance->login_time)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot check out without checking in first for today.',
                ], 400);
            }

            if (!empty($attendance->logout_time)) {
                return response()->json([
                    'status' => false,
                    'already_checked_out' => true,
                    'message' => 'You have already checked out today at ' . $attendance->formatted_logout_time . '.',
                    'data' => $attendance,
                ], 400);
            }

            $updateData = [
                'logout_time' => $currentTime,
                'checkout_latitude' => $request->latitude,
                'checkout_longitude' => $request->longitude,
                'checkout_address' => $request->address,
                'last_activity_at' => Carbon::now('Asia/Kolkata'),
            ];

            if ($request->filled('device_info')) {
                $updateData['device_info'] = $request->device_info;
            }

            if ($request->filled('remarks')) {
                $updateData['remarks'] = $request->remarks;
            }

            $attendance->update($updateData);

            return response()->json([
                'status' => true,
                'message' => 'Check-out successful at ' . $attendance->formatted_logout_time . '. Total duration: ' . $attendance->duration . '.',
                'data' => $attendance,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to record check-out: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * User's Attendance History
     * GET /api/attendance/history
     *
     * Params:
     * - month (optional: 1-12)
     * - year (optional: e.g. 2026)
     * - limit (optional: default 30)
     */
    public function history(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated user.',
            ], 401);
        }

        $now = Carbon::now('Asia/Kolkata');
        $month = (int) $request->get('month', $now->month);
        $year = (int) $request->get('year', $now->year);
        $limit = min(60, (int) $request->get('limit', 31));

        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->orderBy('attendance_date', 'DESC')
            ->limit($limit)
            ->get();

        $presentCount = Attendance::where('user_id', $user->id)
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->whereIn('status', ['Present', 'Late', 'Half Day', 'On Duty'])
            ->count();

        return response()->json([
            'status' => true,
            'month' => $month,
            'year' => $year,
            'summary' => [
                'days_present' => $presentCount,
                'records_count' => $attendances->count(),
            ],
            'data' => $attendances,
        ], 200);
    }

    /**
     * Staff Live Attendance Roster (Admin & Accounts only)
     * GET /api/attendance/all
     *
     * Params:
     * - date (optional: YYYY-MM-DD, defaults to today IST)
     * - role (optional: e.g. Sales, Receptionist, Purchase)
     */
    public function allStaff(Request $request)
    {
        $user = $request->user();
        if (!$user || !in_array($user->Role, ['Admin', 'Accounts'])) {
            return response()->json([
                'status' => false,
                'message' => 'Access Denied: Restricted to Admin and Accounts only.',
            ], 403);
        }

        $date = $request->get('date', Carbon::today('Asia/Kolkata')->toDateString());
        $role = $request->get('role');

        $usersQuery = User::where('employment_status', '!=', 'Inactive');
        if (!empty($role)) {
            $usersQuery->where('Role', $role);
        }
        $users = $usersQuery->orderBy('name')->get();

        $attendances = Attendance::whereDate('attendance_date', $date)
            ->get()
            ->keyBy('user_id');

        $roster = $users->map(function ($u) use ($attendances) {
            $att = $attendances->get($u->id);
            return [
                'user_id' => $u->id,
                'employee_code' => $u->employee_code ?: 'EMP-' . str_pad($u->id, 3, '0', STR_PAD_LEFT),
                'name' => $u->name,
                'email' => $u->email,
                'role' => $u->Role,
                'system_status' => $u->Status,
                'is_present' => !empty($att && $att->login_time),
                'status' => $att ? $att->status : 'Absent',
                'login_time' => $att ? $att->formatted_login_time : null,
                'logout_time' => $att ? $att->formatted_logout_time : null,
                'duration' => $att ? $att->duration : null,
                'checkin_latitude' => $att ? $att->checkin_latitude : null,
                'checkin_longitude' => $att ? $att->checkin_longitude : null,
                'checkin_address' => $att ? $att->checkin_address : null,
                'google_maps_checkin' => $att ? $att->google_maps_checkin_url : null,
                'checkout_latitude' => $att ? $att->checkout_latitude : null,
                'checkout_longitude' => $att ? $att->checkout_longitude : null,
                'checkout_address' => $att ? $att->checkout_address : null,
                'google_maps_checkout' => $att ? $att->google_maps_checkout_url : null,
                'source' => $att ? $att->source : null,
                'device_info' => $att ? $att->device_info : null,
            ];
        });

        return response()->json([
            'status' => true,
            'date' => $date,
            'total_staff' => $users->count(),
            'present_count' => $attendances->count(),
            'data' => $roster,
        ], 200);
    }
}
