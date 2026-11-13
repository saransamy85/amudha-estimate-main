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

@section('title', 'Project Sites Directory')

@section('content')
<div class="sites-directory-container py-3">

    {{-- Top Header & Quick Action Row --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb" class="mb-1">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item">
                        <a href="{{ route('receptiondashboard') }}" class="text-decoration-none text-muted">
                            <i class="bi bi-speedometer2 me-1"></i>Reception
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">
                        Sites Directory
                    </li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon-box">
                    <i class="bi bi-building text-primary"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Project Sites Directory & Logistics Hub</h4>
                    <span class="text-muted small">
                        Monitor active project locations, dispatch frequencies, transport freight costs, and project milestone progress.
                    </span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('receptionmaterialitems') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 rounded-3 px-3 py-2 shadow-sm">
                <i class="bi bi-box-arrow-right"></i>
                <span class="fw-medium">New Dispatch</span>
            </a>

            <a href="{{ route('materialreturn') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-arrow-return-left text-warning"></i>
                <span>Record Return</span>
            </a>

            <a href="{{ route('reception.reports') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-bar-chart-line text-info"></i>
                <span>Reports</span>
            </a>

            <a href="{{ route('receptiondashboard') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-speedometer2 text-secondary"></i>
                <span>Dashboard</span>
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

    {{-- Section 1: Executive KPI Stat Cards --}}
    <div class="mb-4">
        <div class="row g-3">
            {{-- 1. Active Sites --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-estimate" style="animation-delay: 0.05s;">
                    <div class="icon-box">
                        <i class="bi bi-buildings-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Active Projects</h6>
                            <span class="modern-badge bg-primary-subtle text-primary border border-primary-subtle">Active</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $activeSitesCount ?? $sites->count() }}">
                            {{ $activeSitesCount ?? $sites->count() }}
                        </h2>
                        <small class="text-muted">Ongoing site operations</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 2. In Progress --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-customer" style="animation-delay: 0.1s;">
                    <div class="icon-box">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">In Progress</h6>
                            <span class="modern-badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Executing</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $progressCount ?? 0 }}">
                            {{ $progressCount ?? 0 }}
                        </h2>
                        <small class="text-muted">Sites actively receiving materials</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 3. Yet to Start --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-lead" style="animation-delay: 0.15s;">
                    <div class="icon-box">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Yet to Start</h6>
                            <span class="modern-badge bg-secondary-subtle text-secondary border border-secondary-subtle">Queued</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $yetToStartCount ?? 0 }}">
                            {{ $yetToStartCount ?? 0 }}
                        </h2>
                        <small class="text-muted">Approved sites awaiting kickoff</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 4. Total Completed --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-converted" style="animation-delay: 0.2s;">
                    <div class="icon-box">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Completed Projects</h6>
                            <span class="modern-badge bg-success-subtle text-success border border-success-subtle">Delivered</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $completedCount ?? 0 }}">
                            {{ $completedCount ?? 0 }}
                        </h2>
                        <small class="text-muted">Handed over client projects</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 2: Mode Switcher & Filter Pills --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
        <div class="card-body p-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <ul class="nav nav-pills custom-site-pills gap-2" id="siteViewsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-3 py-2 fw-semibold" id="tab-cards-btn" data-bs-toggle="pill" data-bs-target="#tab-cards" type="button" role="tab">
                        <i class="bi bi-grid-fill me-1"></i>
                        Active Sites Grid
                        <span class="badge bg-white text-primary ms-1 border">{{ $sites->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-3 py-2 fw-semibold" id="tab-table-btn" data-bs-toggle="pill" data-bs-target="#tab-table" type="button" role="tab">
                        <i class="bi bi-table me-1"></i>
                        All Sites Master Directory
                        <span class="badge bg-secondary-subtle text-secondary ms-1">{{ $cli->count() }}</span>
                    </button>
                </li>
            </ul>

            <div class="input-group input-group-sm" style="max-width: 280px;">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" id="siteCardsSearchInput" class="form-control bg-light border-start-0 ps-0" placeholder="Filter active sites...">
            </div>
        </div>
    </div>

    {{-- Section 3: Tab Content --}}
    <div class="tab-content" id="siteViewsTabsContent">

        {{-- TAB 1: ACTIVE SITES CARDS GRID --}}
        <div class="tab-pane fade show active" id="tab-cards" role="tabpanel">
            <div class="row g-4" id="activeSitesCardsContainer">
                @forelse($sites as $site)
                    @php
                        $dispatchCount = $site->materialDispatches->count();
                        $transportCharge = (float) $site->materialDispatches->sum('transport_charge');
                        $lastDispatch = $site->materialDispatches->sortByDesc('dispatch_date')->first();

                        if ($site->status == 'Yet to Start') {
                            $progress = 15;
                            $barClass = 'bg-secondary';
                            $statusPill = 'bg-secondary-subtle text-secondary border border-secondary-subtle';
                        } else {
                            $progress = 65;
                            $barClass = 'bg-warning';
                            $statusPill = 'bg-warning-subtle text-warning-emphasis border border-warning-subtle';
                        }
                    @endphp

                    <div class="col-lg-4 col-md-6 site-card-col" data-search-content="{{ strtolower($site->client_name . ' ' . $site->Location . ' ' . $site->Mobile . ' ' . $site->Product . ' ' . $site->Area . ' ' . $site->status) }}">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white site-executive-card">
                            <div class="card-body p-4 d-flex flex-column">
                                {{-- Card Header: Title & Status --}}
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="site-icon-box">
                                            <i class="bi bi-building text-primary fs-5"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('sitetimeline', $site->id) }}" class="fw-bold text-dark text-decoration-none site-title-link fs-6 d-block">
                                                {{ $site->client_name }}
                                            </a>
                                            <small class="text-muted d-flex align-items-center gap-1">
                                                <i class="bi bi-geo-alt-fill text-danger" style="font-size: 11px;"></i>
                                                <span>{{ Str::limit($site->Location ?: 'Location Unset', 24) }}</span>
                                            </small>
                                        </div>
                                    </div>

                                    <span class="badge {{ $statusPill }} rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                        {{ $site->status }}
                                    </span>
                                </div>

                                {{-- Contact & Specifications --}}
                                <div class="p-2 bg-light rounded-3 my-3 small text-secondary">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span>
                                            <i class="bi bi-telephone-fill text-success me-1"></i>
                                            @if($site->Mobile)
                                                <a href="tel:{{ $site->Mobile }}" class="text-decoration-none text-dark fw-medium">{{ $site->Mobile }}</a>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </span>
                                        <span>
                                            <i class="bi bi-aspect-ratio text-info me-1"></i>
                                            {{ $site->Area ? $site->Area . ' Sq.ft' : 'Standard' }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-truncate" style="max-width: 170px;">
                                            <i class="bi bi-tag-fill text-warning me-1"></i>
                                            {{ $site->Product ?: 'General Works' }}
                                        </span>
                                        <span class="text-dark fw-bold">
                                            ₹ {{ $site->formatted_total_value ?? $site->Total_values ?? '0' }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Metrics 3-Box Strip --}}
                                <div class="row g-2 text-center mb-3">
                                    <div class="col-4">
                                        <div class="border rounded-3 p-2 bg-white">
                                            <small class="text-muted d-block" style="font-size: 0.68rem;">Dispatches</small>
                                            <h6 class="text-primary mb-0 fw-bold">{{ $dispatchCount }}</h6>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="border rounded-3 p-2 bg-white">
                                            <small class="text-muted d-block" style="font-size: 0.68rem;">Freight</small>
                                            <h6 class="text-dark mb-0 fw-bold">₹ {{ number_format($transportCharge, 0) }}</h6>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="border rounded-3 p-2 bg-white">
                                            <small class="text-muted d-block" style="font-size: 0.68rem;">Last Sent</small>
                                            <h6 class="text-secondary mb-0 fw-medium" style="font-size: 0.8rem;">
                                                @if($lastDispatch)
                                                    {{ date('d M', strtotime($lastDispatch->dispatch_date)) }}
                                                @else
                                                    —
                                                @endif
                                            </h6>
                                        </div>
                                    </div>
                                </div>

                                {{-- Progress Indicator --}}
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-1 small text-muted">
                                        <span>Project Execution</span>
                                        <span class="fw-semibold text-dark">{{ $progress }}%</span>
                                    </div>
                                    <div class="progress rounded-pill mb-3" style="height: 8px;">
                                        <div class="progress-bar {{ $barClass }}" role="progressbar" style="width: {{ $progress }}%"></div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('sitetimeline', $site->id) }}" class="btn btn-primary btn-sm rounded-3 flex-grow-1 d-inline-flex align-items-center justify-content-center gap-1 shadow-xs">
                                            <i class="bi bi-clock-history"></i>
                                            <span>Timeline</span>
                                        </a>

                                        <a href="{{ route('receptionmaterialitems') }}" class="btn btn-outline-primary btn-sm rounded-3 px-2" title="Issue Material Dispatch">
                                            <i class="bi bi-truck"></i>
                                        </a>

                                        <button type="button" class="btn btn-light border btn-sm rounded-3 px-2 text-secondary" data-bs-toggle="modal" data-bs-target="#statusModal{{ $site->id }}" title="Update Status">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                            <i class="bi bi-building display-3 text-muted opacity-50 mb-3 d-block"></i>
                            <h5 class="fw-bold text-dark">No Active Sites Found</h5>
                            <p class="text-muted small mb-0">There are currently no projects marked with 'Progress' or 'Yet to Start' status.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- TAB 2: COMPLETE SITES MASTER DIRECTORY TABLE --}}
        <div class="tab-pane fade" id="tab-table" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary px-3 py-2 rounded-pill font-monospace shadow-2xs">
                            <i class="bi bi-list-check me-1"></i> MASTER DIRECTORY
                        </span>
                        <h5 class="mb-0 fw-bold text-dark">All Client Project Sites</h5>
                    </div>
                    <span class="text-muted small">Total registered client sites: <strong>{{ $cli->count() }}</strong></span>
                </div>

                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table id="clientTable" class="table table-hover align-middle nowrap" style="width:100%">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-3">Customer / Site Name</th>
                                    <th>Registered</th>
                                    <th>Mobile</th>
                                    <th>Site Location</th>
                                    <th>Area</th>
                                    <th>Product</th>
                                    <th>Project Value</th>
                                    <th>Status</th>
                                    <th class="text-center pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cli as $cl)
                                <tr>
                                    <td class="ps-3">
                                        <a href="{{ route('sitetimeline', $cl->id) }}" class="fw-bold text-dark text-decoration-none site-title-link">
                                            {{ $cl->client_name }}
                                        </a>
                                        <div class="text-muted small" style="font-size: 0.72rem;">
                                            Dispatches: <strong>{{ $cl->materialDispatches->count() }}</strong>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="text-muted small">
                                            {{ $cl->created_at ? $cl->created_at->format('d-m-Y') : '—' }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($cl->Mobile)
                                            <a href="tel:{{ $cl->Mobile }}" class="text-decoration-none text-dark fw-medium small">
                                                <i class="bi bi-telephone text-success me-1"></i>{{ $cl->Mobile }}
                                            </a>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="small text-secondary">
                                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                            {{ $cl->Location ?: '—' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="small text-dark">
                                            {{ $cl->Area ? $cl->Area . ' Sq.ft' : '—' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $cl->Product ?: 'General' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="fw-bold text-dark">
                                            ₹ {{ $cl->formatted_total_value ?? $cl->Total_values ?? '0' }}
                                        </span>
                                    </td>

                                    <td>
                                        @switch($cl->status)
                                            @case('Yet to Start')
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1">Yet to Start</span>
                                            @break

                                            @case('Progress')
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2 py-1">Progress</span>
                                            @break

                                            @case('Completed')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">Completed</span>
                                            @break

                                            @default
                                                <span class="badge bg-light text-dark border rounded-pill px-2 py-1">{{ $cl->status ?? 'Active' }}</span>
                                        @endswitch
                                    </td>

                                    <td class="text-center pe-3">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('sitetimeline', $cl->id) }}" class="btn btn-sm btn-light border rounded-3 px-2 py-1 text-primary shadow-2xs" title="View Site Logistics Timeline">
                                                <i class="bi bi-clock-history"></i>
                                            </a>

                                            <button type="button" class="btn btn-sm btn-light border rounded-3 px-2 py-1 text-secondary shadow-2xs" data-bs-toggle="modal" data-bs-target="#statusModal{{ $cl->id }}" title="Update Project Status">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Status Update Modals for Customers --}}
@foreach ($cli as $cl)
<div class="modal fade" id="statusModal{{ $cl->id }}" tabindex="-1" aria-labelledby="statusModalLabel{{ $cl->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('customerstatusupdate', $cl->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header bg-white border-bottom p-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 rounded-3 bg-primary-subtle text-primary">
                            <i class="bi bi-arrow-repeat fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0" id="statusModalLabel{{ $cl->id }}">
                                Update Project Status
                            </h5>
                            <small class="text-muted">Modify the current execution stage for this site.</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small text-uppercase">
                            Project Site / Customer
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-building"></i></span>
                            <input type="text" class="form-control bg-light fw-bold text-dark" value="{{ $cl->client_name }}" readonly>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold text-secondary small text-uppercase">
                            Execution Status Stage <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg" name="status" required>
                            <option value="Yet to Start" {{ $cl->status == 'Yet to Start' ? 'selected' : '' }}>
                                ⏳ Yet to Start (Awaiting Kickoff)
                            </option>
                            <option value="Progress" {{ $cl->status == 'Progress' ? 'selected' : '' }}>
                                🔨 Progress (Under Active Execution)
                            </option>
                            <option value="Completed" {{ $cl->status == 'Completed' ? 'selected' : '' }}>
                                ✅ Completed (Handed Over to Client)
                            </option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top p-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-light border rounded-3 px-3 text-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success rounded-3 px-4 text-white fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Save Status</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<style>
    .sites-directory-container {
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

    .site-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .site-title-link:hover {
        color: #4f46e5 !important;
        text-decoration: underline !important;
    }

    .site-executive-card {
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease;
    }

    .site-executive-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 30px -8px rgba(15, 23, 42, 0.1) !important;
    }

    /* Custom Site Pills */
    .custom-site-pills .nav-link {
        color: #64748b;
        background: #f8fafc;
        border: 1px solid #edf2f7;
        transition: all 0.2s ease;
    }

    .custom-site-pills .nav-link:hover {
        background: #eef2ff;
        color: #4f46e5;
    }

    .custom-site-pills .nav-link.active {
        background: #4f46e5;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
    }

    .custom-site-pills .nav-link.active .badge {
        background: #ffffff !important;
        color: #4f46e5 !important;
    }

    .shadow-xs {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .shadow-2xs {
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Real-time client-side filter for Active Sites Cards
        const searchInput = document.getElementById('siteCardsSearchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const query = this.value.toLowerCase().trim();
                const cardCols = document.querySelectorAll('#activeSitesCardsContainer .site-card-col');

                cardCols.forEach(function(col) {
                    const content = col.getAttribute('data-search-content') || col.innerText.toLowerCase();
                    if (content.includes(query)) {
                        col.style.display = '';
                    } else {
                        col.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endpush
@endsection
