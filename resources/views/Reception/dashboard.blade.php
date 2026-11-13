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

@section('title', 'Front Desk & Logistics Dashboard')

@section('content')
<div class="reception-dashboard-container py-3">

    {{-- Top Header & Quick Action Row --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-3 mb-1">
                <div class="header-icon-box">
                    <i class="bi bi-building-gear text-primary"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Front Desk & Logistics Dashboard</h4>
                    <span class="text-muted small">
                        Real-time site material dispatches, inward returns, vehicle freight logistics, and active project timelines.
                    </span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-xs d-inline-flex align-items-center gap-2 me-1">
                <span class="pulse-indicator"></span>
                <span class="fw-medium text-secondary">{{ now()->format('l, d M Y') }}</span>
            </span>

            <a href="{{ route('receptionmaterialitems') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 rounded-3 px-3 py-2 shadow-sm">
                <i class="bi bi-box-arrow-right"></i>
                <span class="fw-medium">New Dispatch</span>
            </a>

            <a href="{{ route('materialreturn') }}" class="btn btn-success d-inline-flex align-items-center gap-2 rounded-3 px-3 py-2 shadow-sm text-white">
                <i class="bi bi-arrow-return-left"></i>
                <span class="fw-medium">Record Return</span>
            </a>

            <a href="{{ route('receptionsites') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-geo-alt-fill text-danger"></i>
                <span>Sites</span>
            </a>

            <a href="{{ route('reception.reports') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-bar-chart-line text-info"></i>
                <span>Reports</span>
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

    {{-- Section 1: Executive KPI Cards --}}
    <div class="mb-4">
        <div class="row g-3">
            {{-- 1. Active Sites --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <a href="{{ route('receptionsites') }}" class="text-decoration-none">
                    <div class="modern-card card-estimate" style="animation-delay: 0.05s;">
                        <div class="icon-box">
                            <i class="bi bi-buildings-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="text-muted mb-0 fw-semibold">Active Sites</h6>
                                <span class="modern-badge bg-primary-subtle text-primary border border-primary-subtle">Live Sites</span>
                            </div>
                            <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $activeSitesCount ?? ($yetToStart + $progress) }}">
                                {{ $activeSitesCount ?? ($yetToStart + $progress) }}
                            </h2>
                            <small class="text-muted">
                                <span class="text-warning fw-semibold">{{ $progress }}</span> In Progress &bull;
                                <span class="text-secondary fw-semibold">{{ $yetToStart }}</span> Yet to Start
                            </small>
                        </div>
                        <div class="card-accent-bar"></div>
                    </div>
                </a>
            </div>

            {{-- 2. Material Dispatches (Outward) --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <a href="{{ route('receptionmaterialitems') }}" class="text-decoration-none">
                    <div class="modern-card card-lead" style="animation-delay: 0.1s;">
                        <div class="icon-box">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="text-muted mb-0 fw-semibold">Material Dispatches</h6>
                                @if(($todayDispatches ?? 0) > 0)
                                    <span class="modern-badge bg-info-subtle text-info border border-info-subtle">+{{ $todayDispatches }} Today</span>
                                @else
                                    <span class="modern-badge bg-secondary-subtle text-secondary border border-secondary-subtle">Outward</span>
                                @endif
                            </div>
                            <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $totalDispatches ?? 0 }}">
                                {{ $totalDispatches ?? 0 }}
                            </h2>
                            <small class="text-muted">Site delivery consignments</small>
                        </div>
                        <div class="card-accent-bar"></div>
                    </div>
                </a>
            </div>

            {{-- 3. Inward Returns (Materials-In) --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <a href="{{ route('materialreturn.history') }}" class="text-decoration-none">
                    <div class="modern-card card-converted" style="animation-delay: 0.15s;">
                        <div class="icon-box">
                            <i class="bi bi-box-arrow-in-left"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="text-muted mb-0 fw-semibold">Materials-In Returns</h6>
                                @if(($todayReturns ?? 0) > 0)
                                    <span class="modern-badge bg-success-subtle text-success border border-success-subtle">+{{ $todayReturns }} Today</span>
                                @else
                                    <span class="modern-badge bg-success-subtle text-success border border-success-subtle">Recovered</span>
                                @endif
                            </div>
                            <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $totalReturns ?? 0 }}">
                                {{ $totalReturns ?? 0 }}
                            </h2>
                            <small class="text-muted">Surplus & returned items</small>
                        </div>
                        <div class="card-accent-bar"></div>
                    </div>
                </a>
            </div>

            {{-- 4. Total Transport Freight --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <a href="{{ route('reception.reports') }}" class="text-decoration-none">
                    <div class="modern-card card-customer" style="animation-delay: 0.2s;">
                        <div class="icon-box">
                            <i class="bi bi-currency-rupee"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="text-muted mb-0 fw-semibold">Transport Freight</h6>
                                <span class="modern-badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Logistics</span>
                            </div>
                            <h2 class="fw-bold mb-1 display-6 text-dark">
                                ₹ {{ number_format($totalTransport ?? 0, 0) }}
                            </h2>
                            <small class="text-muted">Total vehicle transport charges</small>
                        </div>
                        <div class="card-accent-bar"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    {{-- Section 2: Quick Operational Launch Strip --}}
    <div class="mb-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-body p-3">
                <div class="row g-2 text-center">
                    {{-- 1. Material Out --}}
                    <div class="col-6 col-md-4 col-lg-2-4">
                        <a href="{{ route('receptionmaterialitems') }}" class="quick-launch-tile text-decoration-none d-block p-3 rounded-3 h-100">
                            <div class="quick-icon-box bg-primary-subtle text-primary mx-auto mb-2">
                                <i class="bi bi-box-arrow-right fs-4"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-0 fs-6">Material Out</h6>
                            <span class="text-muted small">Dispatch challan</span>
                        </a>
                    </div>

                    {{-- 2. Record Return --}}
                    <div class="col-6 col-md-4 col-lg-2-4">
                        <a href="{{ route('materialreturn') }}" class="quick-launch-tile text-decoration-none d-block p-3 rounded-3 h-100">
                            <div class="quick-icon-box bg-warning-subtle text-warning-emphasis mx-auto mb-2">
                                <i class="bi bi-arrow-return-left fs-4"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-0 fs-6">Record Return</h6>
                            <span class="text-muted small">Inward voucher</span>
                        </a>
                    </div>

                    {{-- 3. Return History --}}
                    <div class="col-6 col-md-4 col-lg-2-4">
                        <a href="{{ route('materialreturn.history') }}" class="quick-launch-tile text-decoration-none d-block p-3 rounded-3 h-100">
                            <div class="quick-icon-box bg-success-subtle text-success mx-auto mb-2">
                                <i class="bi bi-box-arrow-in-left fs-4"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-0 fs-6">Materials-In Log</h6>
                            <span class="text-muted small">Audit return receipts</span>
                        </a>
                    </div>

                    {{-- 4. Active Sites --}}
                    <div class="col-6 col-md-4 col-lg-2-4">
                        <a href="{{ route('receptionsites') }}" class="quick-launch-tile text-decoration-none d-block p-3 rounded-3 h-100">
                            <div class="quick-icon-box bg-info-subtle text-info mx-auto mb-2">
                                <i class="bi bi-building fs-4"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-0 fs-6">Project Sites</h6>
                            <span class="text-muted small">Locations & timelines</span>
                        </a>
                    </div>

                    {{-- 5. Reports --}}
                    <div class="col-12 col-md-4 col-lg-2-4">
                        <a href="{{ route('reception.reports') }}" class="quick-launch-tile text-decoration-none d-block p-3 rounded-3 h-100">
                            <div class="quick-icon-box bg-secondary-subtle text-secondary mx-auto mb-2">
                                <i class="bi bi-bar-chart-line-fill fs-4"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-0 fs-6">Logistics Reports</h6>
                            <span class="text-muted small">Freight analytics</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 3: Main Operational Grid (8 cols Dispatches / 4 cols Inward & Sites) --}}
    <div class="row g-4">
        {{-- Left Column (8 cols): Recent Outward Material Dispatches --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 rounded-3 bg-primary-subtle text-primary">
                            <i class="bi bi-truck fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">Recent Material Dispatches</h5>
                            <span class="text-muted small">Outward movement consignments sent to active project sites</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div class="input-group input-group-sm" style="max-width: 220px;">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" id="dispatchSearchInput" class="form-control bg-light border-start-0 ps-0" placeholder="Search dispatches...">
                        </div>
                        <a href="{{ route('receptionmaterialitems') }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 rounded-3">
                            <i class="bi bi-plus-lg"></i>
                            <span class="d-none d-sm-inline">Dispatch</span>
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="dispatchesTable">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4">Site / Client</th>
                                    <th>Date & Transport</th>
                                    <th>Route</th>
                                    <th>Dispatched Items</th>
                                    <th class="text-end">Freight</th>
                                    <th class="text-center pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dispatches as $dispatch)
                                <tr class="dispatch-row">
                                    {{-- Site / Client Info --}}
                                    <td class="ps-4 py-3" style="min-width: 180px;">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <a href="{{ route('sitetimeline', $dispatch->customer_id) }}" class="fw-bold text-dark text-decoration-none site-link">
                                                {{ $dispatch->customer->client_name ?? 'Site #' . $dispatch->customer_id }}
                                            </a>
                                            @if(isset($dispatch->customer->status))
                                                @if($dispatch->customer->status == 'Progress')
                                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill" style="font-size: 0.68rem;">In Progress</span>
                                                @elseif($dispatch->customer->status == 'Yet to Start')
                                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill" style="font-size: 0.68rem;">Yet to Start</span>
                                                @elseif($dispatch->customer->status == 'Completed')
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 0.68rem;">Completed</span>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="text-muted small d-flex align-items-center gap-2">
                                            <span>
                                                <i class="bi bi-person-fill text-secondary"></i>
                                                {{ $dispatch->user_name ?? 'Receptionist' }}
                                            </span>
                                            @if($dispatch->person_name)
                                                <span>&bull; Recv: {{ Str::limit($dispatch->person_name, 15) }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Date & Transport --}}
                                    <td style="min-width: 140px;">
                                        <div class="fw-semibold text-dark mb-1">
                                            <i class="bi bi-calendar-event text-primary me-1"></i>
                                            {{ date('d M, Y', strtotime($dispatch->dispatch_date)) }}
                                        </div>
                                        <div class="text-muted small">
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-truck me-1"></i>
                                                {{ $dispatch->transport_type }}
                                            </span>
                                            @if($dispatch->vehicle_no)
                                                <span class="ms-1 font-monospace small text-secondary">{{ $dispatch->vehicle_no }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Route --}}
                                    <td style="min-width: 150px;">
                                        <div class="small">
                                            <div class="text-secondary d-flex align-items-center gap-1 mb-1">
                                                <i class="bi bi-circle-fill text-success" style="font-size: 7px;"></i>
                                                <span class="text-truncate" style="max-width: 120px;" title="{{ $dispatch->from_location }}">{{ $dispatch->from_location }}</span>
                                            </div>
                                            <div class="text-dark fw-medium d-flex align-items-center gap-1">
                                                <i class="bi bi-geo-alt-fill text-danger" style="font-size: 11px;"></i>
                                                <span class="text-truncate" style="max-width: 120px;" title="{{ $dispatch->to_location }}">{{ $dispatch->to_location }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Dispatched Items Summary --}}
                                    <td style="min-width: 200px;">
                                        <div class="d-flex align-items-center gap-1 mb-1">
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                                                <i class="bi bi-box-seam me-1"></i>
                                                {{ $dispatch->items->count() }} {{ Str::plural('item', $dispatch->items->count()) }}
                                            </span>
                                        </div>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($dispatch->items->take(2) as $item)
                                                <span class="badge bg-light text-dark border fw-normal" style="font-size: 0.72rem;" title="{{ $item->description }}">
                                                    {{ Str::limit($item->item, 14) }}:
                                                    <strong>{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }} {{ $item->unit }}</strong>
                                                </span>
                                            @endforeach
                                            @if($dispatch->items->count() > 2)
                                                <span class="badge bg-light text-muted border" style="font-size: 0.7rem;" title="{{ $dispatch->items->skip(2)->pluck('item')->implode(', ') }}">
                                                    +{{ $dispatch->items->count() - 2 }} more
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Freight Charge --}}
                                    <td class="text-end" style="min-width: 100px;">
                                        <div class="fw-bold text-dark">
                                            ₹ {{ number_format($dispatch->transport_charge, 2) }}
                                        </div>
                                        <small class="text-muted" style="font-size: 0.7rem;">Freight</small>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-center pe-4" style="min-width: 90px;">
                                        <a href="{{ route('sitetimeline', $dispatch->customer_id) }}" class="btn btn-sm btn-light border rounded-3 px-2 py-1 text-primary shadow-2xs" title="View Site Dispatch Chronology">
                                            <i class="bi bi-clock-history me-1"></i>
                                            <span>Timeline</span>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="p-4">
                                            <div class="mb-3">
                                                <i class="bi bi-truck display-4 text-muted opacity-50"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark">No Material Dispatches Recorded Yet</h6>
                                            <p class="text-muted small mb-3">Begin by issuing outward materials to active project sites.</p>
                                            <a href="{{ route('receptionmaterialitems') }}" class="btn btn-sm btn-primary rounded-3 px-3">
                                                <i class="bi bi-plus-circle me-1"></i>
                                                Create Outward Dispatch
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($dispatches->count() > 0)
                <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        Showing recent outward dispatches &bull; Total recorded: <strong>{{ $totalDispatches ?? $dispatches->count() }}</strong>
                    </span>
                    <a href="{{ route('reception.reports') }}" class="text-decoration-none small fw-semibold text-primary d-inline-flex align-items-center gap-1">
                        <span>View Detailed Logistics Reports</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Right Column (4 cols): Inward Returns & Active Sites Snapshot --}}
        <div class="col-lg-4">
            {{-- 1. Recent Inward Material Returns --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 rounded-3 bg-success-subtle text-success">
                            <i class="bi bi-box-arrow-in-left fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">Recent Inward Returns</h6>
                            <span class="text-muted small">Surplus materials received</span>
                        </div>
                    </div>
                    <a href="{{ route('materialreturn.history') }}" class="btn btn-sm btn-light border rounded-3 px-2 py-1 text-secondary" title="View Full Return History">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentReturns ?? [] as $return)
                        <div class="list-group-item p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-monospace" style="font-size: 0.72rem;">
                                        {{ $return->return_no }}
                                    </span>
                                    <a href="{{ route('sitetimeline', $return->customer_id) }}" class="fw-bold text-dark text-decoration-none d-block mt-1">
                                        {{ $return->customer->client_name ?? 'Site #' . $return->customer_id }}
                                    </a>
                                </div>
                                <a href="{{ route('materialreturn.view', $return->id) }}" class="btn btn-xs btn-outline-success rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                    <i class="bi bi-receipt"></i> Voucher
                                </a>
                            </div>

                            <div class="d-flex justify-content-between align-items-center text-muted small mt-2">
                                <span>
                                    <i class="bi bi-calendar2 me-1"></i>
                                    {{ date('d M, Y', strtotime($return->return_date)) }}
                                </span>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-arrow-return-left text-success me-1"></i>
                                    {{ $return->items->count() }} {{ Str::plural('item', $return->items->count()) }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="p-4 text-center">
                            <i class="bi bi-box-arrow-in-left display-6 text-muted opacity-50 mb-2 d-block"></i>
                            <span class="text-muted small d-block mb-2">No material returns recorded yet.</span>
                            <a href="{{ route('materialreturn') }}" class="btn btn-sm btn-outline-success rounded-3 px-3">
                                <i class="bi bi-plus-lg me-1"></i> Record Return
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>

                @if(isset($recentReturns) && $recentReturns->count() > 0)
                <div class="card-footer bg-white border-top py-2 px-3 text-center">
                    <a href="{{ route('materialreturn.history') }}" class="small fw-semibold text-success text-decoration-none">
                        Browse Complete Return History ({{ $totalReturns ?? $recentReturns->count() }}) &rarr;
                    </a>
                </div>
                @endif
            </div>

            {{-- 2. Active Project Sites Quick Pulse --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 rounded-3 bg-info-subtle text-info">
                            <i class="bi bi-building fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">Active Sites Pulse</h6>
                            <span class="text-muted small">Live project locations</span>
                        </div>
                    </div>
                    <a href="{{ route('receptionsites') }}" class="btn btn-sm btn-light border rounded-3 px-2 py-1 text-secondary" title="View All Project Sites">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($activeSites ?? [] as $site)
                        <div class="list-group-item p-3 border-bottom d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1 pe-2">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="fw-bold text-dark site-title">{{ $site->client_name }}</span>
                                    @if($site->status == 'Progress')
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill" style="font-size: 0.65rem;">Progress</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill" style="font-size: 0.65rem;">Yet to Start</span>
                                    @endif
                                </div>
                                <div class="text-muted small d-flex align-items-center gap-2">
                                    <span>
                                        <i class="bi bi-geo-alt text-danger"></i>
                                        {{ Str::limit($site->Location ?? 'Location not specified', 20) }}
                                    </span>
                                    <span>&bull;</span>
                                    <span>{{ $site->materialDispatches->count() }} dispatches</span>
                                </div>
                            </div>

                            <a href="{{ route('sitetimeline', $site->id) }}" class="btn btn-sm btn-light border rounded-circle p-2 text-primary" title="View Timeline">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                        @empty
                        <div class="p-4 text-center">
                            <i class="bi bi-building display-6 text-muted opacity-50 mb-2 d-block"></i>
                            <span class="text-muted small">No active sites found.</span>
                        </div>
                        @endforelse
                    </div>
                </div>

                @if(isset($activeSites) && $activeSites->count() > 0)
                <div class="card-footer bg-white border-top py-2 px-3 text-center">
                    <a href="{{ route('receptionsites') }}" class="small fw-semibold text-primary text-decoration-none">
                        Explore Full Site Directory ({{ $activeSitesCount ?? $activeSites->count() }}) &rarr;
                    </a>
                </div>
                @endif
            </div>

            {{-- 3. Project Execution Progress Snapshot --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-pie-chart-fill text-primary me-2"></i>
                        Project Execution Status
                    </h6>
                </div>
                <div class="card-body p-4">
                    @php
                        $tot = max($totalCustomers ?? 1, 1);
                        $progPct = round((($progress ?? 0) / $tot) * 100);
                        $yetPct = round((($yetToStart ?? 0) / $tot) * 100);
                        $compPct = round((($completed ?? 0) / $tot) * 100);
                    @endphp

                    {{-- Multi-colored progress bar --}}
                    <div class="progress rounded-pill mb-3" style="height: 12px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $progPct }}%" title="In Progress: {{ $progress }} ({{ $progPct }}%)"></div>
                        <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $yetPct }}%" title="Yet to Start: {{ $yetToStart }} ({{ $yetPct }}%)"></div>
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $compPct }}%" title="Completed: {{ $completed }} ({{ $compPct }}%)"></div>
                    </div>

                    {{-- Metrics Legend --}}
                    <div class="row g-2 text-center pt-2">
                        <div class="col-4">
                            <div class="p-2 rounded-3 bg-light border">
                                <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                                    <span class="status-dot bg-warning"></span>
                                    <span class="small fw-semibold text-secondary">Progress</span>
                                </div>
                                <h5 class="fw-bold text-dark mb-0">{{ $progress }}</h5>
                                <small class="text-muted" style="font-size: 0.7rem;">{{ $progPct }}%</small>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="p-2 rounded-3 bg-light border">
                                <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                                    <span class="status-dot bg-secondary"></span>
                                    <span class="small fw-semibold text-secondary">Yet to Start</span>
                                </div>
                                <h5 class="fw-bold text-dark mb-0">{{ $yetToStart }}</h5>
                                <small class="text-muted" style="font-size: 0.7rem;">{{ $yetPct }}%</small>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="p-2 rounded-3 bg-light border">
                                <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                                    <span class="status-dot bg-success"></span>
                                    <span class="small fw-semibold text-secondary">Completed</span>
                                </div>
                                <h5 class="fw-bold text-dark mb-0">{{ $completed }}</h5>
                                <small class="text-muted" style="font-size: 0.7rem;">{{ $compPct }}%</small>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3 pt-2 border-top">
                        <small class="text-muted">
                            Total registered customer projects: <strong class="text-dark">{{ $totalCustomers }}</strong>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .reception-dashboard-container {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    .header-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #eef2ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.12);
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

    @keyframes pulseGreen {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }

    /* 5-Column Responsive Flex for Quick Launch Tiles */
    @media (min-width: 992px) {
        .col-lg-2-4 {
            flex: 0 0 auto;
            width: 20%;
        }
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

    .site-link:hover {
        color: #4f46e5 !important;
        text-decoration: underline !important;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .btn-xs {
        padding: 0.2rem 0.5rem;
        font-size: 0.72rem;
        line-height: 1.2;
    }

    .shadow-xs {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .shadow-2xs {
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Real-time client-side search for Dispatches table
        const searchInput = document.getElementById('dispatchSearchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const query = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('#dispatchesTable tbody tr.dispatch-row');

                rows.forEach(function(row) {
                    const text = row.innerText.toLowerCase();
                    if (text.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endpush
@endsection
