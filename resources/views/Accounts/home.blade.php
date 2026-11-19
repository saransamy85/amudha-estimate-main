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

@section('title', 'Accounts & Finance Dashboard')

@section('content')
<div class="accounts-home-container py-3">

    {{-- Top Header & Quick Action Row --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-3 mb-1">
                <div class="header-icon-box">
                    <i class="bi bi-wallet2 text-success"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Finance, Accounts & Staff Presence</h4>
                    <span class="text-muted small">
                        Overview of daily staff attendance, customer 360° records, procurement accounts, and financial operations.
                    </span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-xs d-inline-flex align-items-center gap-2 me-1">
                <span class="pulse-indicator"></span>
                <span class="fw-medium text-secondary">{{ now()->format('l, d M Y') }}</span>
            </span>

            <a href="{{ route('accounts.attendance') }}" class="btn btn-success d-inline-flex align-items-center gap-2 rounded-3 px-3 py-2 shadow-sm text-white">
                <i class="bi bi-calendar2-check-fill"></i>
                <span class="fw-medium">Staff Attendance</span>
            </a>

            <a href="{{ route('customersearch') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-search-heart text-primary"></i>
                <span>Customer Search</span>
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

    {{-- Section 1: Executive KPI Stat Cards --}}
    <div class="mb-4">
        <div class="row g-3">
            {{-- 1. Staff Present Today --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <a href="{{ route('accounts.attendance') }}" class="text-decoration-none">
                    <div class="modern-card card-converted" style="animation-delay: 0.05s;">
                        <div class="icon-box">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="text-muted mb-0 fw-semibold">Present Today</h6>
                                <span class="modern-badge bg-success-subtle text-success border border-success-subtle">
                                    {{ $totalStaff > 0 ? round(($presentToday / $totalStaff) * 100) : 0 }}% Turnout
                                </span>
                            </div>
                            <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $presentToday }}">
                                {{ $presentToday }} <small class="text-muted fs-6 fw-normal">/ {{ $totalStaff }}</small>
                            </h2>
                            <small class="text-muted">Staff logged into CRM today</small>
                        </div>
                        <div class="card-accent-bar"></div>
                    </div>
                </a>
            </div>

            {{-- 2. Staff Currently Online --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <a href="{{ route('accounts.attendance') }}" class="text-decoration-none">
                    <div class="modern-card card-today" style="animation-delay: 0.1s;">
                        <div class="icon-box">
                            <i class="bi bi-broadcast"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="text-muted mb-0 fw-semibold">Online Now</h6>
                                <span class="modern-badge bg-purple-subtle text-purple border border-purple-subtle d-inline-flex align-items-center gap-1">
                                    <span class="pulse-indicator-purple"></span> Live
                                </span>
                            </div>
                            <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $onlineToday }}">
                                {{ $onlineToday }}
                            </h2>
                            <small class="text-muted">Active user sessions in CRM</small>
                        </div>
                        <div class="card-accent-bar"></div>
                    </div>
                </a>
            </div>

            {{-- 3. Total Estimates --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <a href="{{ route('customersearch') }}" class="text-decoration-none">
                    <div class="modern-card card-estimate" style="animation-delay: 0.15s;">
                        <div class="icon-box">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="text-muted mb-0 fw-semibold">Estimates Pipeline</h6>
                                <span class="modern-badge bg-primary-subtle text-primary border border-primary-subtle">Quoted</span>
                            </div>
                            <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $estimatesCount }}">
                                {{ $estimatesCount }}
                            </h2>
                            <small class="text-muted">Customer quotations on record</small>
                        </div>
                        <div class="card-accent-bar"></div>
                    </div>
                </a>
            </div>

            {{-- 4. Purchase Orders --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <a href="{{ route('admin.po_orders') }}" class="text-decoration-none">
                    <div class="modern-card card-customer" style="animation-delay: 0.2s;">
                        <div class="icon-box">
                            <i class="bi bi-cart-check"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="text-muted mb-0 fw-semibold">Purchase Orders</h6>
                                <span class="modern-badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Procurement</span>
                            </div>
                            <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $purchaseOrdersCount }}">
                                {{ $purchaseOrdersCount }}
                            </h2>
                            <small class="text-muted">Vendor & contractor orders</small>
                        </div>
                        <div class="card-accent-bar"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    {{-- Section 2: Quick Action Service Launcher --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
        <div class="card-body p-3">
            <div class="row g-2 text-center">
                <div class="col-6 col-md-3">
                    <a href="{{ route('accounts.attendance') }}" class="quick-launch-tile text-decoration-none d-block p-3 rounded-3 h-100">
                        <div class="quick-icon-box bg-success-subtle text-success mx-auto mb-2">
                            <i class="bi bi-calendar2-check-fill fs-4"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0 fs-6">Staff Attendance</h6>
                        <span class="text-muted small">Daily logins & presence</span>
                    </a>
                </div>

                <div class="col-6 col-md-3">
                    <a href="{{ route('customersearch') }}" class="quick-launch-tile text-decoration-none d-block p-3 rounded-3 h-100">
                        <div class="quick-icon-box bg-primary-subtle text-primary mx-auto mb-2">
                            <i class="bi bi-search-heart fs-4"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0 fs-6">Customer Search</h6>
                        <span class="text-muted small">Estimates, POs & Dispatches</span>
                    </a>
                </div>

                <div class="col-6 col-md-3">
                    <a href="{{ route('admin.po_orders') }}" class="quick-launch-tile text-decoration-none d-block p-3 rounded-3 h-100">
                        <div class="quick-icon-box bg-warning-subtle text-warning-emphasis mx-auto mb-2">
                            <i class="bi bi-receipt fs-4"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0 fs-6">Purchase Orders</h6>
                        <span class="text-muted small">Vendor bill audit</span>
                    </a>
                </div>

                <div class="col-6 col-md-3">
                    <a href="{{ route('reception.reports') }}" class="quick-launch-tile text-decoration-none d-block p-3 rounded-3 h-100">
                        <div class="quick-icon-box bg-info-subtle text-info mx-auto mb-2">
                            <i class="bi bi-truck fs-4"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0 fs-6">Logistics & Freight</h6>
                        <span class="text-muted small">Transport expense audit</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 3: Today's Live Attendance Feed --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="p-2 rounded-3 bg-success-subtle text-success">
                    <i class="bi bi-person-check fs-5"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">Today's Staff Logins & Presence</h5>
                    <span class="text-muted small">Staff members who have logged into CRM on {{ now()->format('d M Y') }}</span>
                </div>
            </div>

            <a href="{{ route('accounts.attendance') }}" class="btn btn-sm btn-outline-success rounded-3 px-3 py-1">
                <span>View Full Attendance Dashboard</span>
                <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th class="ps-4">Staff Member</th>
                            <th>Role</th>
                            <th>Login Time</th>
                            <th>Duration</th>
                            <th>Login Count</th>
                            <th>System Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todayAttendances as $att)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar-mini" style="background: {{ '#' . substr(md5($att->user->name ?? 'User'), 0, 6) }};">
                                        {{ strtoupper(substr($att->user->name ?? 'U', 0, 2)) }}
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark">{{ $att->user->name ?? 'User #' . $att->user_id }}</span>
                                        <small class="text-muted d-block">{{ $att->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $att->user->Role ?? 'Staff' }}
                                </span>
                            </td>

                            <td>
                                <div class="fw-semibold text-dark">
                                    <i class="bi bi-box-arrow-in-right text-success me-1"></i>
                                    {{ $att->formatted_login_time }}
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border font-monospace">
                                    {{ $att->duration }}
                                </span>
                            </td>

                            <td>
                                <span class="small text-secondary">{{ $att->login_count }} {{ Str::plural('session', $att->login_count) }}</span>
                            </td>

                            <td>
                                @if(isset($att->user) && $att->user->Status == 'Online')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 small">
                                        <span class="pulse-indicator-inline"></span> Online
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1 small">
                                        Offline
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-calendar-x display-6 text-muted opacity-50 mb-2 d-block"></i>
                                No staff logins recorded for today yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .accounts-home-container {
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

    .quick-launch-tile {
        background: #f8fafc;
        border: 1px solid #edf2f7;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .quick-launch-tile:hover {
        background: #ffffff;
        transform: translateY(-4px);
        box-shadow: 0 12px 25px -4px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }

    .quick-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.25s ease;
    }

    .quick-launch-tile:hover .quick-icon-box {
        transform: scale(1.1) rotate(4deg);
    }

    .user-avatar-mini {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        color: #ffffff;
        font-weight: 700;
        font-size: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
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
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #10b981;
        margin-right: 3px;
    }

    .pulse-indicator-purple {
        display: inline-block;
        width: 6px;
        height: 6px;
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
</style>
@endsection
