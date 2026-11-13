@extends('layouts.salesbase')

@section('title', 'Sales Lead Pipeline')

@section('content')
<div class="sales-lead-container py-3">

    @php
    $statusData = [
    'Cancelled' => ['icon' => 'bi-x-circle-fill', 'color' => 'danger', 'bg' => 'danger-subtle'],
    'Confirmed' => ['icon' => 'bi-check-circle-fill', 'color' => 'success', 'bg' => 'success-subtle'],
    'Details shared' => ['icon' => 'bi-file-earmark-text-fill', 'color' => 'primary', 'bg' => 'primary-subtle'],
    'Follow up' => ['icon' => 'bi-clock-history', 'color' => 'secondary', 'bg' => 'secondary-subtle'],
    'Quote Shared' => ['icon' => 'bi-chat-quote-fill', 'color' => 'warning', 'bg' => 'warning-subtle'],
    'RNR' => ['icon' => 'bi-arrow-repeat', 'color' => 'info', 'bg' => 'info-subtle'],
    'Site Visit' => ['icon' => 'bi-geo-alt-fill', 'color' => 'success', 'bg' => 'success-subtle'],
    ];

    $sourceData = [
    'Old Database' => ['icon' => 'bi-database-fill', 'color' => '#3b82f6', 'bg' => '#eff6ff'],
    'Phone Call' => ['icon' => 'bi-telephone-fill', 'color' => '#84cc16', 'bg' => '#f7fee7'],
    'Reference' => ['icon' => 'bi-people-fill', 'color' => '#9333ea', 'bg' => '#faf5ff'],
    'Showroom-walk-in' => ['icon' => 'bi-shop', 'color' => '#f97316', 'bg' => '#fff7ed'],
    'Website' => ['icon' => 'bi-globe', 'color' => '#2563eb', 'bg' => '#eff6ff'],
    'WhatsApp' => ['icon' => 'bi-whatsapp', 'color' => '#22c55e', 'bg' => '#f0fdf4'],
    ];

    $allSources = array_unique(array_merge(array_keys($sourceData), $lsc->keys()->toArray()));
    @endphp

    {{-- Top Header & Action Row --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="header-icon-box">
                    <i class="bi bi-bullseye text-primary"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Sales Lead Pipeline</h4>
                    <span class="text-muted small">Track incoming enquiries, channel sources, follow-up notes, and estimate conversions.</span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2 rounded-3 px-3 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#AddLeadModal">
                <i class="bi bi-plus-circle"></i>
                <span class="fw-medium">Add New Lead</span>
            </button>
            <a href="{{ route('salescustomer') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-people text-success"></i>
                <span>Customers ({{ $cuscount ?? 0 }})</span>
            </a>
            @if(request()->filled('search') || request()->filled('status') || request()->filled('source') || request()->filled('period'))
            <a href="{{ route('leaddash') }}" class="btn btn-light border rounded-3 px-3 py-2 text-danger d-inline-flex align-items-center gap-1 shadow-sm" title="Clear Filters">
                <i class="bi bi-x-circle"></i>
                <span>Clear Filter</span>
            </a>
            @endif
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm d-flex align-items-center gap-2 mb-4 p-3" role="alert">
        <i class="bi bi-check-circle-fill text-success fs-5"></i>
        <div class="fw-medium text-success-emphasis">{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 shadow-sm d-flex align-items-center gap-2 mb-4 p-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
        <div class="fw-medium text-danger-emphasis">{{ session('error') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Section 1: Pipeline Status Breakdown --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0">Pipeline Status Breakdown</h5>
                <small class="text-muted">Click any card to filter leads by current stage</small>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('leaddash') }}" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 text-secondary {{ !request()->filled('status') && !request()->filled('source') && request('period') !== 'this_month' ? 'border-primary text-primary fw-bold' : '' }}">
                    All Leads ({{ $lc }})
                </a>
                <a href="{{ route('leaddash', array_merge(request()->query(), ['period' => 'this_month'])) }}" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 text-secondary {{ request('period') === 'this_month' ? 'border-primary text-primary fw-bold' : '' }}">
                    <i class="bi bi-calendar-check me-1 text-primary"></i> {{ $currentMonthName }} ({{ $monthLeadCount }})
                </a>
            </div>
        </div>

        <div class="row g-3">
            {{-- Total Leads Card --}}
            <div class="col-xl-3 col-lg-4 col-md-6">
                <a href="{{ route('leaddash') }}" class="text-decoration-none">
                    <div class="lead-kpi-card {{ !request()->filled('status') && !request()->filled('source') && !request()->filled('period') ? 'active-filter' : '' }}">
                        <div class="lead-kpi-icon bg-primary-subtle text-primary">
                            <i class="bi bi-collection-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="text-muted mb-0 fw-semibold">Total Registered</h6>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">Total</span>
                            </div>
                            <h3 class="fw-bold text-dark mb-1 counter-val" data-target="{{ $lc }}">{{ $lc }}</h3>
                            <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light-subtle">
                                <span class="text-muted extra-small">This Month:</span>
                                <span class="badge bg-primary text-white rounded-pill px-2 py-0.5 fw-semibold" style="font-size: 11px;">{{ $monthLeadCount }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            @foreach ($leadSC as $status => $count)
            @php
            $isStatusActive = request('status') === $status;
            $cfg = $statusData[$status] ?? ['icon' => 'bi-circle', 'color' => 'primary', 'bg' => 'primary-subtle'];
            $mStatusCount = $monthLeadSC[$status] ?? 0;
            @endphp
            <div class="col-xl-3 col-lg-4 col-md-6">
                <a href="{{ route('leaddash', array_merge(request()->query(), ['status' => $status])) }}" class="text-decoration-none">
                    <div class="lead-kpi-card {{ $isStatusActive ? 'active-filter' : '' }}">
                        <div class="lead-kpi-icon bg-{{ $cfg['bg'] }} text-{{ $cfg['color'] }}">
                            <i class="bi {{ $cfg['icon'] }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="text-muted mb-0 fw-semibold text-truncate" style="max-width: 130px;">{{ $status }}</h6>
                                <span class="badge bg-{{ $cfg['bg'] }} text-{{ $cfg['color'] }} border border-{{ $cfg['color'] }}-subtle rounded-pill">{{ $status }}</span>
                            </div>
                            <h3 class="fw-bold text-dark mb-1 counter-val" data-target="{{ $count }}">{{ $count }}</h3>
                            <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-light-subtle">
                                <span class="text-muted extra-small">This Month:</span>
                                <span class="badge bg-light text-dark border rounded-pill px-2 py-0.5 fw-semibold" style="font-size: 11px;">{{ $mStatusCount }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Section 2: Marketing Acquisition Sources --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0">Marketing Acquisition Sources</h5>
                <small class="text-muted">Enquiry volume distribution by channel and current month intake</small>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill d-inline-flex align-items-center gap-2">
                    <i class="bi bi-calendar2-check-fill text-primary"></i>
                    <span><strong>{{ $currentMonthName }}</strong>: <span class="fw-bold fs-6">{{ $monthLeadCount }}</span> Leads</span>
                </span>
                <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill">
                    All-Time: <strong>{{ $lc }}</strong>
                </span>
            </div>
        </div>

        {{-- Current Month Pulse Strip --}}
        <div class="month-pulse-banner p-3 p-md-4 rounded-4 mb-4">
            <div class="row align-items-center g-3">
                <div class="col-xl-3 col-lg-4 border-end-lg pe-lg-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="month-pulse-icon">
                            <i class="bi bi-bar-chart-fill"></i>
                        </div>
                        <div>
                            <div class="text-uppercase fw-bold text-muted extra-small tracking-wider">{{ $currentMonthName }} Intake</div>
                            <div class="d-flex align-items-baseline gap-2">
                                <h2 class="fw-bold text-dark mb-0 counter-val" data-target="{{ $monthLeadCount }}">{{ $monthLeadCount }}</h2>
                                <span class="text-muted small">New Leads</span>
                            </div>
                            <small class="text-muted d-block mt-0.5" style="font-size: 11.5px;">
                                {{ $lc > 0 ? round(($monthLeadCount / $lc) * 100) : 0 }}% of all-time registered
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-8 ps-lg-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-semibold text-dark small d-flex align-items-center gap-1.5">
                            <i class="bi bi-funnel-fill text-primary"></i> Current Month Status Breakdown
                        </span>
                        <small class="text-muted">Stage distribution for {{ $currentMonthName }}</small>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($statusData as $stName => $stCfg)
                        @php $stMCount = $monthLeadSC[$stName] ?? 0; @endphp
                        <div class="month-status-chip d-flex align-items-center gap-2 px-2.5 py-1.5 rounded-3 bg-white border shadow-xs">
                            <i class="bi {{ $stCfg['icon'] }} text-{{ $stCfg['color'] }}"></i>
                            <span class="text-secondary small fw-medium">{{ $stName }}:</span>
                            <span class="badge bg-{{ $stCfg['bg'] }} text-{{ $stCfg['color'] }} rounded-pill fw-bold">{{ $stMCount }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Source Tiles Grid --}}
        <div class="row g-3">
            @foreach ($allSources as $source)
            @php
            $count = $lsc[$source] ?? 0;
            $mSourceCount = $monthLsc[$source] ?? 0;
            $isSourceActive = request('source') === $source;
            $scfg = $sourceData[$source] ?? ['icon' => 'bi-folder', 'color' => '#2563eb', 'bg' => '#eff6ff'];
            $monthShare = $monthLeadCount > 0 ? round(($mSourceCount / $monthLeadCount) * 100) : 0;
            @endphp
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('leaddash', array_merge(request()->query(), ['source' => $source])) }}" class="text-decoration-none">
                    <div class="lead-source-tile {{ $isSourceActive ? 'active-filter' : '' }}">
                        <div class="source-tile-icon" style="background-color: {{ $scfg['bg'] }}; color: {{ $scfg['color'] }};">
                            <i class="bi {{ $scfg['icon'] }}"></i>
                        </div>
                        <h6 class="fw-semibold text-dark mb-2 text-truncate" title="{{ $source }}">{{ $source }}</h6>

                        <div class="source-stat-box p-2 rounded-3 mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="extra-small text-muted fw-medium">This Month:</span>
                                <span class="fw-bold fs-6 text-primary">{{ $mSourceCount }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="extra-small text-muted">All-Time:</span>
                                <span class="fw-semibold text-dark small">{{ $count }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between text-muted extra-small">
                            <span>Month Share</span>
                            <span class="fw-semibold {{ $monthShare > 0 ? 'text-primary' : 'text-muted' }}">{{ $monthShare }}%</span>
                        </div>

                        <div class="source-bar-accent" style="background-color: {{ $scfg['color'] }};"></div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Optional Section: Reference Channel Spotlight --}}

    {{-- Section 3: Master Lead Directory Table --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0">Sales Lead Directory</h5>
                <small class="text-muted">Complete list of incoming enquiries, follow-up history, and quick estimate creation</small>
            </div>

            <form method="GET" action="{{ route('leaddash') }}" class="d-flex align-items-center gap-2 flex-wrap">
                @if(request()->filled('period'))
                <input type="hidden" name="period" value="{{ request('period') }}">
                @endif

                <div class="position-relative" style="min-width: 240px;">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" name="search" id="leadLiveSearch" class="form-control rounded-pill ps-5 py-2" placeholder="Search name, phone, product..." value="{{ request('search') }}" style="font-size: 13.5px; border-color: #e2e8f0;">
                </div>

                <select name="status" class="form-select rounded-pill py-2 px-3" style="font-size: 13.5px; border-color: #e2e8f0; min-width: 150px;" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    @foreach($leadSC as $st => $c)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }} ({{ $c }})</option>
                    @endforeach
                </select>

                <select name="source" class="form-select rounded-pill py-2 px-3" style="font-size: 13.5px; border-color: #e2e8f0; min-width: 150px;" onchange="this.form.submit()">
                    <option value="">All Sources</option>
                    @foreach($allSources as $sc)
                    @php $scTotal = $lsc[$sc] ?? 0; @endphp
                    <option value="{{ $sc }}" {{ request('source') == $sc ? 'selected' : '' }}>{{ $sc }} ({{ $scTotal }})</option>
                    @endforeach
                </select>

                @if(request('period') === 'this_month')
                <span class="badge bg-primary text-white rounded-pill px-3 py-2 d-inline-flex align-items-center gap-1 shadow-xs">
                    <i class="bi bi-calendar-check"></i> {{ $currentMonthName }}
                    <a href="{{ route('leaddash', request()->except('period')) }}" class="text-white text-decoration-none ms-1 fw-bold" title="Remove period filter">&times;</a>
                </span>
                @endif

                @if(request()->filled('search') || request()->filled('status') || request()->filled('source') || request()->filled('period'))
                <a href="{{ route('leaddash') }}" class="btn btn-sm btn-light border rounded-pill px-3 py-2 text-secondary" title="Reset Filters">
                    <i class="bi bi-x-circle me-1"></i> Reset
                </a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 custom-lead-table" id="masterLeadTable">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th class="text-start">Date &amp; ID</th>
                        <th class="text-start">Prospect Details</th>
                        <th class="text-start">Site &amp; Location</th>
                        <th class="text-start">Product &amp; Area</th>
                        <th class="text-center">Source</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="min-width: 210px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lds as $key => $leds)
                    @php
                    $initial = strtoupper(substr($leds->Name ?? 'L', 0, 1));
                    $isCancelled = strtolower($leds->Status) == 'cancelled';
                    $cleanPhone = preg_replace('/[^0-9]/', '', $leds->Mobile ?? '');
                    $cfg = $statusData[$leds->Status] ?? ['icon' => 'bi-circle', 'color' => 'primary', 'bg' => 'primary-subtle'];
                    @endphp
                    <tr class="lead-row {{ $isCancelled ? 'lead-row-cancelled' : '' }}">
                        <td class="text-center text-muted small fw-medium">{{ $key + 1 }}</td>
                        <td class="text-start">
                            <div class="d-flex align-items-center gap-2">
                                <div class="lead-id-box">
                                    <i class="bi bi-hash"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark lead-id-text">#{{ $leds->id }}</div>
                                    <small class="text-muted d-flex align-items-center gap-1">
                                        <i class="bi bi-calendar-event"></i>
                                        {{ $leds->created_at ? $leds->created_at->format('d M Y') : '-' }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td class="text-start">
                            <div class="d-flex align-items-center gap-2">
                                <div class="client-avatar">{{ $initial }}</div>
                                <div>
                                    <div class="fw-semibold text-dark lead-name">{{ $leds->Name }}</div>
                                    @if($leds->Mobile)
                                    <div class="d-flex align-items-center gap-1">
                                        <a href="tel:{{ $leds->Mobile }}" class="text-muted text-decoration-none small lead-phone">
                                            <i class="bi bi-telephone me-1"></i>{{ $leds->Mobile }}
                                        </a>
                                        @if($cleanPhone)
                                        <a href="https://wa.me/91{{ $cleanPhone }}" target="_blank" class="badge bg-success text-white text-decoration-none px-1.5 py-0.5 rounded-pill" style="font-size: 10px;" title="WhatsApp">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-start lead-location" style="max-width: 180px;">
                            <div class="d-flex align-items-center gap-1 text-truncate" title="{{ $leds->Site_location }}">
                                <i class="bi bi-geo-alt-fill text-danger small"></i>
                                <span class="text-dark small fw-medium">{{ $leds->Site_location ?: '—' }}</span>
                            </div>
                        </td>
                        <td class="text-start lead-product">
                            <span class="badge bg-light text-dark border px-2 py-1 mb-1 d-inline-block">{{ $leds->Product ?: 'General' }}</span>
                            @if($leds->Total_Area)
                            <div class="text-muted small"><i class="bi bi-aspect-ratio me-1"></i>{{ $leds->Total_Area }} Sq.ft</div>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border px-2 py-1 small fw-medium">
                                {{ $leds->source ?: 'Direct' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $cfg['bg'] }} text-{{ $cfg['color'] }} border border-{{ $cfg['color'] }}-subtle rounded-pill px-3 py-1 fw-semibold">
                                <i class="bi {{ $cfg['icon'] }} me-1"></i>{{ $leds->Status }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex align-items-center gap-1">
                                @if(!$isCancelled)
                                <a href="{{ route('estimates.create1', ['lead_id' => $leds->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1 d-inline-flex align-items-center gap-1 shadow-sm" style="font-size: 12px;" title="Create Estimate">
                                    <i class="bi bi-file-earmark-plus"></i> Estimate
                                </a>
                                @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1" style="font-size: 11px;">
                                    Cancelled
                                </span>
                                @endif

                                <button type="button" class="btn btn-sm btn-light border text-primary action-icon-btn" data-bs-toggle="modal" data-bs-target="#feedbackModal" data-lead-id="{{ $leds->id }}" data-lead-name="{{ $leds->Name }}" data-status="{{ $leds->Status }}" title="Add Feedback">
                                    <i class="bi bi-chat-left-text"></i>
                                </button>

                                <button type="button" class="btn btn-sm btn-light border text-info action-icon-btn" data-bs-toggle="modal" data-bs-target="#timelineModal{{ $leds->id }}" title="View Timeline">
                                    <i class="bi bi-clock-history"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <div class="d-flex flex-column align-items-center gap-2">
                                <i class="bi bi-person-x fs-1 text-muted"></i>
                                <span class="fw-semibold">No leads found</span>
                                <small class="text-muted">Adjust your search keyword or selected filters to find records.</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Timeline Modals (outside table for valid HTML) --}}
@foreach ($lds as $leds)
<div class="modal fade text-start" id="timelineModal{{ $leds->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-info-subtle text-info" style="width: 36px; height: 36px; border-radius: 10px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark">Lead Interaction Timeline</h5>
                        <p class="text-muted small mb-0">{{ $leds->Name }} (#{{ $leds->id }}) &bull; Source: {{ $leds->source }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body py-3">
                <div class="modern-timeline">
                    <div class="timeline-step">
                        <div class="timeline-step-marker bg-primary"></div>
                        <div class="timeline-step-content p-3 rounded-3 bg-light border mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-primary small">Lead Created</strong>
                                <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i>{{ $leds->created_at ? $leds->created_at->format('d M Y h:i A') : '-' }}</span>
                            </div>
                            <div class="small text-muted">Enquiry registered via {{ $leds->source ?: 'Direct Channel' }}</div>
                        </div>
                    </div>

                    @if(!empty($leds->Description))
                    <div class="timeline-step">
                        <div class="timeline-step-marker bg-info"></div>
                        <div class="timeline-step-content p-3 rounded-3 bg-light border mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-info small">Initial Enquiry Notes</strong>
                                <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i>{{ $leds->created_at ? $leds->created_at->format('d M Y h:i A') : '-' }}</span>
                            </div>
                            <p class="small text-dark mb-0">{{ $leds->Description }}</p>
                        </div>
                    </div>
                    @endif

                    @forelse($leds->feedbacks as $fb)
                    <div class="timeline-step">
                        <div class="timeline-step-marker bg-success"></div>
                        <div class="timeline-step-content p-3 rounded-3 bg-light border mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-success small">Follow-up Note</strong>
                                <span class="text-muted small"><i class="bi bi-clock me-1"></i>{{ $fb->created_at ? $fb->created_at->format('d M Y h:i A') : '-' }}</span>
                            </div>
                            <p class="small text-dark mb-0">{{ $fb->feedback }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="p-3 text-center text-muted small bg-light rounded-3 border">
                        No follow-up notes logged yet.
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-3 px-3 py-2 text-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

{{-- Shared Feedback Modal --}}
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form method="POST" action="{{ route('addfeedback') }}">
                @csrf
                <input type="hidden" name="lead_id" id="lead_id">

                <div class="modal-header border-0 pb-0">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary-subtle text-primary" style="width: 36px; height: 36px; border-radius: 10px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-chat-quote-fill"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark">Lead Feedback</h5>
                            <p class="text-muted small mb-0" id="feedbackModalSubtitle">Record conversation notes</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold small mb-1">Feedback Notes <span class="text-danger">*</span></label>
                        <textarea name="feedback" class="form-control rounded-3 p-2.5" rows="3" placeholder="Enter notes from call, site visit, or client discussion..." required></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-secondary fw-semibold small mb-1">Update Pipeline Status <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3 py-2 px-3" name="status" id="feedback_status" required>
                            <option value="" disabled>-- Select New Status --</option>
                            <option value="Details shared">Details shared</option>
                            <option value="Follow up">Follow up</option>
                            <option value="Confirmed">Confirmed</option>
                            <option value="Quote Shared">Quote Shared</option>
                            <option value="Site Visit">Schedule For Site Visit</option>
                            <option value="RNR">RNR</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3 py-2 text-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 d-inline-flex align-items-center gap-1 shadow-sm">
                        <i class="bi bi-check-circle"></i> Save Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL: Add New Lead --}}
<div class="modal fade" id="AddLeadModal" tabindex="-1" aria-labelledby="AddLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form method="POST" action="{{ route('addlead') }}">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary-subtle text-primary" style="width: 38px; height: 38px; border-radius: 10px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-person-plus-fill fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark" id="AddLeadModalLabel">Create New Lead</h5>
                            <p class="text-muted small mb-0">Register a new client enquiry into the sales pipeline</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body py-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-secondary fw-semibold small mb-1">Acquisition Source <span class="text-danger">*</span></label>
                            <select name="source" class="form-select rounded-3 py-2 px-3" required style="font-size: 14px;">
                                <option value="" selected disabled>Choose Source</option>
                                <option value="Website">Website</option>
                                <option value="Showroom-walk-in">Showroom-Walk-in</option>
                                <option value="Phone Call">Phone Call</option>
                                <option value="WhatsApp">Email or WhatsApp</option>
                                <option value="Reference">Reference</option>
                                <option value="Old Database">Old Database</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary fw-semibold small mb-1">Initial Status <span class="text-danger">*</span></label>
                            <select name="Status" class="form-select rounded-3 py-2 px-3" required style="font-size: 14px;">
                                <option value="Details shared" selected>Details shared</option>
                            </select>
                            <small class="text-muted" style="font-size: 11px;">Use the Feedback button after saving to update pipeline stage.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary fw-semibold small mb-1">Client Name <span class="text-danger">*</span></label>
                            <input type="text" name="Name" class="form-control rounded-3 py-2 px-3" placeholder="e.g. Karthik Sundaram" required style="font-size: 14px;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary fw-semibold small mb-1">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" name="Mobile" class="form-control rounded-3 py-2 px-3" placeholder="e.g. 9876543210" required style="font-size: 14px;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary fw-semibold small mb-1">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-3 py-2 px-3" placeholder="client@example.com" style="font-size: 14px;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary fw-semibold small mb-1">Product / Service <span class="text-danger">*</span></label>
                            <input type="text" name="Product" class="form-control rounded-3 py-2 px-3" placeholder="e.g. Tensile Roofing, Gazebo" required style="font-size: 14px;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary fw-semibold small mb-1">Total Area (Sq.ft) <span class="text-danger">*</span></label>
                            <input type="text" name="Total_Area" class="form-control rounded-3 py-2 px-3" placeholder="e.g. 1500" required style="font-size: 14px;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary fw-semibold small mb-1">Site Location / City <span class="text-danger">*</span></label>
                            <input type="text" name="Site_location" class="form-control rounded-3 py-2 px-3" placeholder="e.g. Chennai / Coimbatore" required style="font-size: 14px;">
                        </div>

                        <div class="col-12">
                            <label class="form-label text-secondary fw-semibold small mb-1">Initial Project Description / Scope</label>
                            <textarea name="Description" class="form-control rounded-3 p-3" rows="3" placeholder="Provide any details from the enquiry, customer preferences, or special requests..." style="font-size: 14px;"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3 py-2 text-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-check-lg"></i>
                        <span>Save Lead</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .sales-lead-container {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    .header-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #eef2ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .lead-kpi-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 18px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.25s ease;
        height: 100%;
    }

    .lead-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 22px -5px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }

    .lead-kpi-card.active-filter {
        border-color: #4f46e5 !important;
        background: #f8fafc;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
    }

    .lead-kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .lead-source-tile {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 16px 14px;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: all 0.25s ease;
        height: 100%;
    }

    .lead-source-tile:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 18px -4px rgba(15, 23, 42, 0.06);
        border-color: #cbd5e1;
    }

    .lead-source-tile.active-filter {
        border-color: #4f46e5 !important;
        background: #f8fafc;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
    }

    .source-tile-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        margin: 0 auto 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .source-bar-accent {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        opacity: 0.8;
    }

    .source-stat-box {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
    }

    .month-pulse-banner {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
    }

    .month-pulse-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: #e0e7ff;
        color: #4338ca;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    @media (min-width: 992px) {
        .border-end-lg {
            border-right: 1px solid #e2e8f0;
        }
    }

    .month-status-chip {
        font-size: 12px;
        transition: all 0.2s ease;
    }

    .month-status-chip:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        border-color: #cbd5e1;
    }

    .extra-small {
        font-size: 11px;
    }

    .shadow-xs {
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .custom-lead-table thead th {
        background-color: #f8fafc;
        color: #475569;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 14px;
    }

    .custom-lead-table tbody td {
        padding: 12px 14px;
        font-size: 13.5px;
        border-bottom: 1px solid #f1f5f9;
    }

    .lead-row-cancelled {
        background-color: #fff1f2 !important;
    }

    .lead-id-box {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        background: #f1f5f9;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .client-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #eef2ff;
        color: #4f46e5;
        font-weight: 700;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .action-icon-btn {
        width: 30px;
        height: 30px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .action-icon-btn:hover {
        background-color: #f1f5f9;
    }

    .modern-timeline {
        position: relative;
        padding-left: 20px;
    }

    .modern-timeline::before {
        content: '';
        position: absolute;
        left: 6px;
        top: 6px;
        bottom: 6px;
        width: 2px;
        background: #e2e8f0;
    }

    .timeline-step {
        position: relative;
        margin-bottom: 12px;
    }

    .timeline-step-marker {
        position: absolute;
        left: -20px;
        top: 14px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 3px solid #ffffff;
        box-shadow: 0 0 0 1px #cbd5e1;
    }

</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const feedbackModal = document.getElementById('feedbackModal');
        if (feedbackModal) {
            feedbackModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const leadId = button.getAttribute('data-lead-id');
                const leadName = button.getAttribute('data-lead-name');
                const currentStatus = button.getAttribute('data-status');

                document.getElementById('lead_id').value = leadId;
                const subtitle = document.getElementById('feedbackModalSubtitle');
                if (subtitle && leadName) {
                    subtitle.textContent = 'Record conversation notes for ' + leadName + ' (#' + leadId + ')';
                }
                const statusSelect = document.getElementById('feedback_status');
                if (statusSelect && currentStatus) {
                    statusSelect.value = currentStatus;
                }
            });
        }

        const liveSearchInput = document.getElementById('leadLiveSearch');
        const tableRows = document.querySelectorAll('#masterLeadTable .lead-row');

        if (liveSearchInput && tableRows.length) {
            liveSearchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();

                tableRows.forEach(row => {
                    const idEl = row.querySelector('.lead-id-text');
                    const nameEl = row.querySelector('.lead-name');
                    const phoneEl = row.querySelector('.lead-phone');
                    const locEl = row.querySelector('.lead-location');
                    const prodEl = row.querySelector('.lead-product');

                    const text = (
                        (idEl ? idEl.textContent : '') + ' ' +
                        (nameEl ? nameEl.textContent : '') + ' ' +
                        (phoneEl ? phoneEl.textContent : '') + ' ' +
                        (locEl ? locEl.textContent : '') + ' ' +
                        (prodEl ? prodEl.textContent : '')
                    ).toLowerCase();

                    row.style.display = (!query || text.includes(query)) ? '' : 'none';
                });
            });
        }
    });

</script>
@endpush
