@php
if(Auth::user()->Role == 'Admin'){
    $layout = 'layouts.adminbase';
}
elseif(Auth::user()->Role == 'Sales'){
    $layout = 'layouts.salesbase';
}
elseif(Auth::user()->Role == 'Purchase'){
    $layout = 'layouts.purchasebase';
}
elseif(Auth::user()->Role == 'Accounts'){
    $layout = 'layouts.accountsbase';
}
elseif(Auth::user()->Role == 'Receptionist'){
    $layout = 'layouts.receptionbase';
}
else{
    $layout = 'layouts.adminbase';
}
@endphp

@extends($layout)

@section('title', 'Staff Attendance Dashboard')

@section('content')
<div class="attendance-dashboard-container py-3">

    {{-- Top Header & Quick Action Row --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb" class="mb-1">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item">
                        <a href="{{ route('accountshome') }}" class="text-decoration-none text-muted">
                            <i class="bi bi-wallet2 me-1"></i>Accounts
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">
                        Staff Attendance
                    </li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon-box">
                    <i class="bi bi-calendar2-check-fill text-success"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Staff Attendance & Presence Dashboard</h4>
                    <span class="text-muted small">
                        Automated CRM login attendance tracking, working hours, active staff presence, and audit records.
                    </span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap no-print">
            <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-xs d-inline-flex align-items-center gap-2 me-1">
                <span class="pulse-indicator"></span>
                <span class="fw-medium text-secondary">{{ date('l, d M Y', strtotime($selectedDate)) }}</span>
            </span>

            <a href="{{ route('accounts.attendance.csv', ['date' => $selectedDate]) }}" class="btn btn-outline-success rounded-3 px-3 py-2 d-inline-flex align-items-center gap-2 shadow-sm" title="Export this date attendance to CSV">
                <i class="bi bi-file-earmark-spreadsheet"></i>
                <span class="fw-medium">Export CSV</span>
            </a>

            <button type="button" onclick="window.print()" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm" title="Print Attendance Report">
                <i class="bi bi-printer"></i>
                <span class="d-none d-sm-inline">Print</span>
            </button>

            <a href="{{ route('accountshome') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-speedometer2 text-secondary"></i>
                <span>Accounts</span>
            </a>
        </div>
    </div>

    {{-- System Notifications / Flash Alerts --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div class="fw-medium">{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <div class="fw-medium">{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Section 1: Executive Attendance KPI Stat Cards --}}
    <div class="mb-4">
        <div class="row g-3">
            {{-- 1. Total Active Staff --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-lead" style="animation-delay: 0.05s;">
                    <div class="icon-box">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Total Staff</h6>
                            <span class="modern-badge bg-primary-subtle text-primary border border-primary-subtle">Active Team</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $totalActiveStaff }}">
                            {{ $totalActiveStaff }}
                        </h2>
                        <small class="text-muted">Registered active employees</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 2. Present Today --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-converted" style="animation-delay: 0.1s;">
                    <div class="icon-box">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Present</h6>
                            @php
                                $rate = $totalActiveStaff > 0 ? round(($totalPresentCount / $totalActiveStaff) * 100) : 0;
                            @endphp
                            <span class="modern-badge bg-success-subtle text-success border border-success-subtle">{{ $rate }}% Turnout</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $totalPresentCount }}">
                            {{ $totalPresentCount }}
                        </h2>
                        <small class="text-muted">Staff logged in on {{ date('d M', strtotime($selectedDate)) }}</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 3. Absent / Not Logged In --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-cancelled" style="animation-delay: 0.15s;">
                    <div class="icon-box">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Not Logged In</h6>
                            <span class="modern-badge bg-danger-subtle text-danger border border-danger-subtle">Absent</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $totalAbsentCount }}">
                            {{ $totalAbsentCount }}
                        </h2>
                        <small class="text-muted">Awaiting login for this date</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 4. Currently Online --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-today" style="animation-delay: 0.2s;">
                    <div class="icon-box">
                        <i class="bi bi-broadcast"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Currently Online</h6>
                            <span class="modern-badge bg-purple-subtle text-purple border border-purple-subtle d-inline-flex align-items-center gap-1">
                                <span class="pulse-indicator-purple"></span> Live
                            </span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $totalOnlineCount }}">
                            {{ $totalOnlineCount }}
                        </h2>
                        <small class="text-muted">Active in CRM right now</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 2: Date & Filter Toolbar --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white no-print">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('accounts.attendance') }}" id="attendanceFilterForm" class="row g-2 align-items-center">
                {{-- Date Selector --}}
                <div class="col-12 col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted border-end-0">
                            <i class="bi bi-calendar3"></i>
                        </span>
                        <input type="date" name="date" class="form-control bg-light border-start-0" value="{{ $selectedDate }}" onchange="document.getElementById('attendanceFilterForm').submit()">
                    </div>
                </div>

                {{-- Quick Date Buttons --}}
                <div class="col-12 col-md-2">
                    <div class="btn-group btn-group-sm w-100" role="group">
                        <a href="{{ route('accounts.attendance', ['date' => now()->toDateString()]) }}" class="btn {{ $selectedDate == now()->toDateString() ? 'btn-primary' : 'btn-light border' }}">
                            Today
                        </a>
                        <a href="{{ route('accounts.attendance', ['date' => now()->subDay()->toDateString()]) }}" class="btn {{ $selectedDate == now()->subDay()->toDateString() ? 'btn-primary' : 'btn-light border' }}">
                            Yesterday
                        </a>
                    </div>
                </div>

                {{-- Role Filter --}}
                <div class="col-6 col-md-2">
                    <select name="role" class="form-select form-select-sm" onchange="document.getElementById('attendanceFilterForm').submit()">
                        <option value="">All Roles</option>
                        @foreach($roles as $r)
                            <option value="{{ $r }}" {{ $roleFilter == $r ? 'selected' : '' }}>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Filter --}}
                <div class="col-6 col-md-2">
                    <select name="status" class="form-select form-select-sm" onchange="document.getElementById('attendanceFilterForm').submit()">
                        <option value="">All Statuses</option>
                        <option value="Present" {{ $statusFilter == 'Present' ? 'selected' : '' }}>Present (Logged In)</option>
                        <option value="Absent" {{ $statusFilter == 'Absent' ? 'selected' : '' }}>Absent (Not Logged In)</option>
                        <option value="Late" {{ $statusFilter == 'Late' ? 'selected' : '' }}>Late Arrival</option>
                        <option value="Half Day" {{ $statusFilter == 'Half Day' ? 'selected' : '' }}>Half Day</option>
                        <option value="On Duty" {{ $statusFilter == 'On Duty' ? 'selected' : '' }}>On Duty / Site</option>
                    </select>
                </div>

                {{-- Search Filter --}}
                <div class="col-12 col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Search staff name..." value="{{ $search }}">
                        @if($search || $roleFilter || $statusFilter || $selectedDate != now()->toDateString())
                            <a href="{{ route('accounts.attendance') }}" class="btn btn-outline-secondary" title="Reset Filters">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Section 3: Staff Attendance Master Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success px-3 py-2 rounded-pill font-monospace shadow-2xs">
                    <i class="bi bi-calendar-check me-1"></i> ATTENDANCE ROSTER
                </span>
                <span class="text-muted small">Showing attendance for <strong>{{ date('d F Y', strtotime($selectedDate)) }}</strong> ({{ $staffList->count() }} staff listed)</span>
            </div>

            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark border">
                    <span class="pulse-indicator me-1"></span> {{ $totalOnlineCount }} Online Now
                </span>
                <span class="badge bg-success-subtle text-success border border-success-subtle">
                    {{ $totalPresentCount }} / {{ $totalActiveStaff }} Present
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="attendanceTable">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th class="ps-4" style="width: 5%;">#</th>
                            <th style="width: 25%;">Staff Member</th>
                            <th style="width: 14%;">Role / Dept</th>
                            <th style="width: 12%;">Attendance</th>
                            <th style="width: 11%;">First Login</th>
                            <th style="width: 11%;">Last Activity</th>
                            <th style="width: 9%;" class="text-center">Duration</th>
                            <th style="width: 8%;" class="text-center">Live Status</th>
                            <th style="width: 5%;" class="text-center pe-4 no-print">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staffList as $idx => $record)
                        @php
                            $user = $record->user;
                            $att = $record->attendance;
                            $initials = strtoupper(substr($user->name, 0, 2));

                            // Themed status badge
                            if ($record->is_present) {
                                if ($record->status == 'Late') {
                                    $badgeClass = 'bg-warning-subtle text-warning-emphasis border border-warning-subtle';
                                    $statusIcon = 'bi-clock-history';
                                } elseif ($record->status == 'Half Day') {
                                    $badgeClass = 'bg-info-subtle text-info border border-info-subtle';
                                    $statusIcon = 'bi-hourglass-split';
                                } elseif ($record->status == 'On Duty') {
                                    $badgeClass = 'bg-primary-subtle text-primary border border-primary-subtle';
                                    $statusIcon = 'bi-briefcase-fill';
                                } else {
                                    $badgeClass = 'bg-success-subtle text-success border border-success-subtle';
                                    $statusIcon = 'bi-check-circle-fill';
                                }
                            } else {
                                $badgeClass = 'bg-danger-subtle text-danger border border-danger-subtle';
                                $statusIcon = 'bi-x-circle-fill';
                            }
                        @endphp
                        <tr>
                            {{-- Index --}}
                            <td class="ps-4 text-muted small fw-bold">{{ $loop->iteration }}</td>

                            {{-- Staff Info --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar-circle" style="background: {{ '#' . substr(md5($user->name), 0, 6) }};">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $user->name }}</div>
                                        <div class="text-muted small d-flex align-items-center gap-2">
                                            <span>{{ $user->email }}</span>
                                            @if($user->employee_code)
                                                <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.65rem;">
                                                    {{ $user->employee_code }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Role --}}
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-shield-lock me-1 text-primary"></i>{{ $user->Role ?? 'Staff' }}
                                </span>
                                @if($user->department)
                                    <div class="text-muted small mt-1">{{ $user->department }}</div>
                                @endif
                            </td>

                            {{-- Attendance Status --}}
                            <td>
                                <span class="badge {{ $badgeClass }} rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.75rem;">
                                    <i class="bi {{ $statusIcon }} me-1"></i>
                                    {{ $record->is_present ? $record->status : 'Absent' }}
                                </span>
                                @if($record->remarks)
                                    <small class="text-muted d-block mt-1 fst-italic text-truncate" style="max-width: 120px;" title="{{ $record->remarks }}">
                                        {{ $record->remarks }}
                                    </small>
                                @endif
                            </td>

                            {{-- First Login --}}
                            <td>
                                @if($record->is_present)
                                    <div class="fw-semibold text-dark d-flex align-items-center gap-1">
                                        <i class="bi bi-box-arrow-in-right text-success"></i>
                                        <span>{{ $record->login_time }}</span>
                                        @if($att && $att->checkin_latitude)
                                            <a href="{{ $att->google_maps_checkin_url }}" target="_blank" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none" title="GPS: {{ $att->checkin_address ?: $att->checkin_latitude . ', ' . $att->checkin_longitude }}">
                                                <i class="bi bi-geo-alt-fill"></i> GPS
                                            </a>
                                        @endif
                                    </div>
                                    @if($att && $att->checkin_address)
                                        <small class="text-muted d-block text-truncate" style="max-width: 150px; font-size: 0.68rem;" title="{{ $att->checkin_address }}">
                                            <i class="bi bi-geo"></i> {{ $att->checkin_address }}
                                        </small>
                                    @elseif($att && $att->login_count > 1)
                                        <small class="text-muted" style="font-size: 0.68rem;">{{ $att->login_count }} logins</small>
                                    @endif
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            {{-- Last Activity / Logout --}}
                            <td>
                                @if($record->is_present)
                                    <div class="small text-secondary d-flex align-items-center gap-1">
                                        @if($att && $att->logout_time)
                                            <i class="bi bi-box-arrow-right text-danger"></i>
                                            <span>{{ $record->logout_time }}</span>
                                            @if($att->checkout_latitude)
                                                <a href="{{ $att->google_maps_checkout_url }}" target="_blank" class="badge bg-danger-subtle text-danger border border-danger-subtle text-decoration-none" title="GPS: {{ $att->checkout_address ?: $att->checkout_latitude . ', ' . $att->checkout_longitude }}">
                                                    <i class="bi bi-geo-alt-fill"></i> GPS
                                                </a>
                                            @endif
                                        @elseif($att && $att->last_activity_at)
                                            <i class="bi bi-activity text-info me-1"></i>
                                            {{ $att->last_activity_at->format('h:i A') }}
                                        @else
                                            <span class="text-success fw-medium">Active</span>
                                        @endif
                                    </div>
                                    @if($att && $att->checkout_address)
                                        <small class="text-muted d-block text-truncate" style="max-width: 150px; font-size: 0.68rem;" title="{{ $att->checkout_address }}">
                                            <i class="bi bi-geo"></i> {{ $att->checkout_address }}
                                        </small>
                                    @elseif($att && $att->ip_address)
                                        <small class="text-muted font-monospace" style="font-size: 0.65rem;">{{ $att->ip_address }}</small>
                                    @endif
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            {{-- Working Duration --}}
                            <td class="text-center">
                                @if($record->is_present)
                                    <span class="badge bg-light text-dark border font-monospace">
                                        <i class="bi bi-stopwatch text-secondary me-1"></i>
                                        {{ $record->duration }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            {{-- Live System Status --}}
                            <td class="text-center">
                                @if($user->Status == 'Online')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 small">
                                        <span class="pulse-indicator-inline"></span> Online
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1 small">
                                        Offline
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="text-center pe-4 no-print">
                                <button type="button" class="btn btn-sm btn-light border rounded-3 px-2 py-1 text-secondary shadow-2xs" data-bs-toggle="modal" data-bs-target="#editAttendanceModal{{ $user->id }}" title="Update Attendance Status">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="p-4">
                                    <i class="bi bi-calendar-x display-4 text-muted opacity-50 mb-3 d-block"></i>
                                    <h6 class="fw-bold text-dark">No Staff Records Found</h6>
                                    <p class="text-muted small mb-0">No employees matched your current filter criteria.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">
                Automatic tracking records first login of the day when staff accesses CRM.
            </span>
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small">Turnout:</span>
                <div class="progress rounded-pill" style="width: 140px; height: 10px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $rate }}%" aria-valuenow="{{ $rate }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="small fw-bold text-dark">{{ $rate }}%</span>
            </div>
        </div>
    </div>

    {{-- Section 4: Monthly Presence Summary Widget --}}
    @if(isset($monthlyPresentCounts) && $monthlyPresentCounts->count() > 0)
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4 no-print">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded-3 bg-primary-subtle text-primary">
                        <i class="bi bi-calendar-month fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark">Monthly Presence Snapshot ({{ date('F Y', strtotime($selectedDate)) }})</h6>
                        <span class="text-muted small">Total days present in the current calendar month</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <div class="row g-3">
                @foreach($staffList as $record)
                @php
                    $u = $record->user;
                    $daysPresent = $monthlyPresentCounts->get($u->id) ?? 0;
                @endphp
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold text-dark d-block text-truncate" style="max-width: 140px;">{{ $u->name }}</span>
                            <small class="text-muted">{{ $u->Role }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-monospace fw-bold fs-6">
                                {{ $daysPresent }}d
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Modals for Updating Staff Attendance Status --}}
@foreach($staffList as $record)
@php
    $u = $record->user;
    $att = $record->attendance;
@endphp
<div class="modal fade" id="editAttendanceModal{{ $u->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $u->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('accounts.attendance.update') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $u->id }}">
                <input type="hidden" name="attendance_date" value="{{ $selectedDate }}">

                <div class="modal-header bg-white border-bottom p-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 rounded-3 bg-success-subtle text-success">
                            <i class="bi bi-calendar2-check fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0" id="modalLabel{{ $u->id }}">
                                Update Staff Attendance
                            </h5>
                            <small class="text-muted">{{ $u->name }} &bull; {{ date('d M Y', strtotime($selectedDate)) }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small text-uppercase">Staff Member</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control bg-light fw-bold" value="{{ $u->name }} ({{ $u->Role }})" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small text-uppercase">Attendance Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select form-select-lg" required>
                            <option value="Present" {{ ($att && $att->status == 'Present') || (!$att && $record->status == 'Present') ? 'selected' : '' }}>
                                ✅ Present (On Duty)
                            </option>
                            <option value="Late" {{ $att && $att->status == 'Late' ? 'selected' : '' }}>
                                ⏰ Late (Delayed Arrival)
                            </option>
                            <option value="Half Day" {{ $att && $att->status == 'Half Day' ? 'selected' : '' }}>
                                ⏳ Half Day
                            </option>
                            <option value="On Duty" {{ $att && $att->status == 'On Duty' ? 'selected' : '' }}>
                                🚗 On Duty / Client Site Visit
                            </option>
                            <option value="Absent" {{ (!$att && $record->status == 'Absent') || ($att && $att->status == 'Absent') ? 'selected' : '' }}>
                                ❌ Absent / On Leave
                            </option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Login Time</label>
                            <input type="time" name="login_time" class="form-control" value="{{ $att && $att->login_time ? substr($att->login_time, 0, 5) : '09:00' }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Logout Time</label>
                            <input type="time" name="logout_time" class="form-control" value="{{ $att && $att->logout_time ? substr($att->logout_time, 0, 5) : '' }}">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold text-secondary small text-uppercase">Remarks / Reason</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="e.g. Approved leave, Work from site, Half day permission...">{{ $att ? $att->remarks : '' }}</textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top p-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-light border rounded-3 px-3 text-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success rounded-3 px-4 text-white fw-semibold shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<style>
    .attendance-dashboard-container {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    .header-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #ecfdf5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
    }

    .user-avatar-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        color: #ffffff;
        font-weight: 700;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .pulse-indicator {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #10b981;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulseGreen 2s infinite;
    }

    .pulse-indicator-inline {
        display: inline-block;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background-color: #10b981;
        margin-right: 4px;
    }

    .pulse-indicator-purple {
        display: inline-block;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background-color: #8b5cf6;
        animation: pulsePurple 2s infinite;
    }

    @keyframes pulseGreen {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    @keyframes pulsePurple {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(139, 92, 246, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(139, 92, 246, 0); }
    }

    .shadow-xs { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
    .shadow-2xs { box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); }

    @media print {
        .no-print,
        .sidebar,
        .top-navbar,
        #sidebarToggle {
            display: none !important;
        }

        .main-content,
        .page-content,
        .container-fluid,
        .attendance-dashboard-container {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        .card {
            border: 1px solid #ccc !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection
