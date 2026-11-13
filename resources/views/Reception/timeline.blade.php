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

@section('title', 'Site Logistics Timeline - ' . $customer->client_name)

@section('content')
<div class="site-timeline-container py-3">

    {{-- Breadcrumb & Quick Action Bar --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb" class="mb-1">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item">
                        <a href="{{ route('receptiondashboard') }}" class="text-decoration-none text-muted">
                            <i class="bi bi-speedometer2 me-1"></i>Reception
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('receptionsites') }}" class="text-decoration-none text-muted">
                            <i class="bi bi-building me-1"></i>Sites
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">
                        {{ $customer->client_name }}
                    </li>
                </ol>
            </nav>
            <h4 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                <span>Site Material Logistics Chronology</span>
            </h4>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap no-print">
            <a href="{{ route('receptionsites') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Sites</span>
            </a>

            <a href="{{ route('receptionmaterialitems') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 rounded-3 px-3 py-2 shadow-sm">
                <i class="bi bi-box-arrow-right"></i>
                <span class="fw-medium">New Dispatch</span>
            </a>

            <a href="{{ route('materialreturn') }}" class="btn btn-success d-inline-flex align-items-center gap-2 rounded-3 px-3 py-2 shadow-sm text-white">
                <i class="bi bi-arrow-return-left"></i>
                <span class="fw-medium">Record Return</span>
            </a>

            <button type="button" onclick="window.print()" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm" title="Print Site Logistics Report">
                <i class="bi bi-printer"></i>
                <span class="d-none d-sm-inline">Print</span>
            </button>
        </div>
    </div>

    {{-- Master Site Information Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-3 mb-2 flex-wrap">
                        <div class="site-avatar-box">
                            <i class="bi bi-building text-primary"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h3 class="fw-bold text-dark mb-0">{{ $customer->client_name }}</h3>
                                @if($customer->status == 'Progress')
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-1">
                                        <i class="bi bi-tools me-1"></i> In Progress
                                    </span>
                                @elseif($customer->status == 'Yet to Start')
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">
                                        <i class="bi bi-hourglass-split me-1"></i> Yet to Start
                                    </span>
                                @elseif($customer->status == 'Completed')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                        <i class="bi bi-check-circle-fill me-1"></i> Completed
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1">{{ $customer->status ?? 'Active' }}</span>
                                @endif
                            </div>
                            <span class="text-muted small">Registered Site ID: #{{ $customer->id }} &bull; Project Registered: {{ date('d M, Y', strtotime($customer->created_at)) }}</span>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3 mt-3 pt-2 border-top">
                        <div class="d-flex align-items-center gap-2 text-secondary small">
                            <i class="bi bi-geo-alt-fill text-danger fs-6"></i>
                            <span><strong>Location:</strong> {{ $customer->Location ?: 'Location not specified' }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-secondary small">
                            <i class="bi bi-telephone-fill text-success fs-6"></i>
                            <span>
                                <strong>Mobile:</strong>
                                @if($customer->Mobile)
                                    <a href="tel:{{ $customer->Mobile }}" class="text-decoration-none text-dark fw-medium">{{ $customer->Mobile }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </span>
                        </div>
                        @if($customer->Area)
                        <div class="d-flex align-items-center gap-2 text-secondary small">
                            <i class="bi bi-bounding-box text-info fs-6"></i>
                            <span><strong>Area:</strong> {{ $customer->Area }}</span>
                        </div>
                        @endif
                        @if($customer->Product)
                        <div class="d-flex align-items-center gap-2 text-secondary small">
                            <i class="bi bi-tag-fill text-warning fs-6"></i>
                            <span><strong>Product:</strong> {{ $customer->Product }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4 text-lg-end border-start-lg ps-lg-4">
                    <div class="p-3 bg-light rounded-3 text-lg-end text-start">
                        <div class="text-muted small mb-1">Contract / Estimate Value</div>
                        <h3 class="fw-bold text-dark mb-0">
                            ₹ {{ $customer->formatted_total_value ?? $customer->Total_values ?? '0.00' }}
                        </h3>
                        <small class="text-muted">Total Project Valuation</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 2: Executive Logistics KPI Cards --}}
    <div class="mb-4">
        <div class="row g-3">
            {{-- 1. Total Dispatches --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-lead" style="animation-delay: 0.05s;">
                    <div class="icon-box">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Total Dispatches</h6>
                            <span class="modern-badge bg-info-subtle text-info border border-info-subtle">Outward</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $dispatchCount }}">{{ $dispatchCount }}</h2>
                        <small class="text-muted">Outward delivery consignments</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 2. Total Materials Dispatched --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-estimate" style="animation-delay: 0.1s;">
                    <div class="icon-box">
                        <i class="bi bi-boxes"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Dispatched Items</h6>
                            <span class="modern-badge bg-primary-subtle text-primary border border-primary-subtle">Line Items</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $materialCount }}">{{ $materialCount }}</h2>
                        <small class="text-muted">Total material particulars delivered</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 3. Total Materials Returned (Inward) --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-converted" style="animation-delay: 0.15s;">
                    <div class="icon-box">
                        <i class="bi bi-box-arrow-in-left"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Inward Returns</h6>
                            <span class="modern-badge bg-success-subtle text-success border border-success-subtle">Recovered</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $returnsCount ?? 0 }}">{{ $returnsCount ?? 0 }}</h2>
                        <small class="text-muted">
                            <span class="text-success fw-semibold">{{ $returnedItemsCount ?? 0 }}</span> items returned back
                        </small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 4. Total Transport Freight --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-customer" style="animation-delay: 0.2s;">
                    <div class="icon-box">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Site Freight Total</h6>
                            <span class="modern-badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Freight</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 text-dark">₹ {{ number_format($transportTotal, 2) }}</h2>
                        <small class="text-muted">Accumulated vehicle transport freight</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 3: Timeline Navigation Tabs & Filter Bar --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white no-print">
        <div class="card-body p-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <ul class="nav nav-pills custom-timeline-pills gap-2" id="timelineTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-3 py-2 fw-semibold" id="tab-all-btn" data-bs-toggle="pill" data-bs-target="#tab-all" type="button" role="tab">
                        <i class="bi bi-clock-history me-1"></i>
                        All Activity
                        <span class="badge bg-white text-primary ms-1 border">{{ $dispatches->count() + ($returns->count() ?? 0) }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-3 py-2 fw-semibold" id="tab-dispatches-btn" data-bs-toggle="pill" data-bs-target="#tab-dispatches" type="button" role="tab">
                        <i class="bi bi-truck me-1"></i>
                        Outward Dispatches
                        <span class="badge bg-info-subtle text-info ms-1">{{ $dispatchCount }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-3 py-2 fw-semibold" id="tab-returns-btn" data-bs-toggle="pill" data-bs-target="#tab-returns" type="button" role="tab">
                        <i class="bi bi-box-arrow-in-left me-1"></i>
                        Inward Returns
                        <span class="badge bg-success-subtle text-success ms-1">{{ $returnsCount ?? 0 }}</span>
                    </button>
                </li>
            </ul>

            <div class="input-group input-group-sm" style="max-width: 280px;">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" id="timelineSearchInput" class="form-control bg-light border-start-0 ps-0" placeholder="Filter timeline entries...">
            </div>
        </div>
    </div>

    {{-- Section 4: Tab Content & Chronological Activity Stream --}}
    <div class="tab-content" id="timelineTabsContent">

        {{-- TAB 1: ALL ACTIVITY COMBINED --}}
        <div class="tab-pane fade show active" id="tab-all" role="tabpanel">
            @php
                // Merge dispatches and returns into a single chronological stream sorted descending by date
                $combinedStream = collect();

                foreach($dispatches as $d) {
                    $combinedStream->push([
                        'type' => 'dispatch',
                        'date' => $d->dispatch_date,
                        'created_at' => $d->created_at ?? $d->dispatch_date,
                        'data' => $d,
                    ]);
                }

                foreach($returns ?? [] as $r) {
                    $combinedStream->push([
                        'type' => 'return',
                        'date' => $r->return_date,
                        'created_at' => $r->created_at ?? $r->return_date,
                        'data' => $r,
                    ]);
                }

                $combinedStream = $combinedStream->sortByDesc(function($item) {
                    return $item['date'] . ' ' . ($item['data']->id ?? 0);
                });
            @endphp

            @if($combinedStream->count() > 0)
                <div class="timeline-wrapper">
                    @foreach($combinedStream as $event)
                        @if($event['type'] == 'dispatch')
                            @php $dispatch = $event['data']; @endphp
                            <div class="timeline-card-entry timeline-entry-dispatch mb-4" data-search-content="{{ strtolower($dispatch->dispatch_date . ' ' . $dispatch->vehicle_no . ' ' . $dispatch->transport_type . ' ' . $dispatch->person_name . ' ' . $dispatch->from_location . ' ' . $dispatch->to_location . ' ' . $dispatch->items->pluck('item')->implode(' ')) }}">
                                <div class="timeline-marker marker-dispatch">
                                    <i class="bi bi-truck fs-5"></i>
                                </div>

                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                                    {{-- Consignment Header --}}
                                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-primary px-3 py-2 rounded-pill font-monospace shadow-2xs">
                                                <i class="bi bi-box-arrow-right me-1"></i> OUTWARD DISPATCH
                                            </span>
                                            <span class="fw-bold text-dark fs-6">
                                                {{ date('l, d F Y', strtotime($dispatch->dispatch_date)) }}
                                            </span>
                                        </div>

                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                                                <i class="bi bi-currency-rupee text-warning"></i>
                                                Freight: <strong>₹ {{ number_format($dispatch->transport_charge, 2) }}</strong>
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Transit & Logistics Info Strip --}}
                                    <div class="card-body p-4 pb-3">
                                        <div class="row g-3 p-3 bg-light rounded-3 mb-3">
                                            <div class="col-6 col-md-3">
                                                <small class="text-muted d-block mb-1">
                                                    <i class="bi bi-truck me-1 text-primary"></i> Vehicle & Mode
                                                </small>
                                                <span class="fw-semibold text-dark">{{ $dispatch->transport_type ?: 'Standard' }}</span>
                                                @if($dispatch->vehicle_no)
                                                    <span class="badge bg-white text-secondary border font-monospace ms-1">{{ $dispatch->vehicle_no }}</span>
                                                @endif
                                            </div>

                                            <div class="col-6 col-md-3">
                                                <small class="text-muted d-block mb-1">
                                                    <i class="bi bi-person me-1 text-info"></i> In-Charge / Receiver
                                                </small>
                                                <span class="fw-semibold text-dark">{{ $dispatch->person_name ?: 'Site Receiver' }}</span>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <small class="text-muted d-block mb-1">
                                                    <i class="bi bi-signpost-2 me-1 text-danger"></i> Transit Route
                                                </small>
                                                <div class="d-flex align-items-center gap-2 text-dark">
                                                    <span class="fw-medium text-secondary">{{ $dispatch->from_location }}</span>
                                                    <i class="bi bi-arrow-right text-muted"></i>
                                                    <span class="fw-bold text-dark">{{ $dispatch->to_location }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Items Particulars Table --}}
                                        <div class="table-responsive rounded-3 border">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead class="table-light small text-uppercase text-secondary">
                                                    <tr>
                                                        <th class="ps-3" style="width: 5%;">#</th>
                                                        <th style="width: 40%;">Material Item Description</th>
                                                        <th style="width: 20%;">Quantity Dispatched</th>
                                                        <th style="width: 35%;">Remarks / Specifications</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($dispatch->items as $idx => $item)
                                                    <tr>
                                                        <td class="ps-3 text-muted small">{{ $idx + 1 }}</td>
                                                        <td class="fw-semibold text-dark">
                                                            <i class="bi bi-box-seam me-2 text-primary opacity-75"></i>
                                                            {{ $item->item }}
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill fw-bold">
                                                                {{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }} {{ $item->unit }}
                                                            </span>
                                                        </td>
                                                        <td class="text-muted small">
                                                            {{ $item->description ?: '—' }}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="card-footer bg-white border-top py-2 px-4 d-flex justify-content-between align-items-center text-muted small">
                                        <span>
                                            <i class="bi bi-person-circle me-1 text-secondary"></i>
                                            Logged by: <strong>{{ $dispatch->user_name ?? 'Reception Staff' }}</strong>
                                        </span>
                                        <span>
                                            Total line items: <strong>{{ $dispatch->items->count() }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- RETURN ENTRY --}}
                            @php $return = $event['data']; @endphp
                            <div class="timeline-card-entry timeline-entry-return mb-4" data-search-content="{{ strtolower($return->return_date . ' ' . $return->return_no . ' ' . $return->vehicle_no . ' ' . $return->transport_type . ' ' . $return->person_name . ' ' . $return->remarks . ' ' . $return->items->pluck('dispatchItem.item')->implode(' ')) }}">
                                <div class="timeline-marker marker-return">
                                    <i class="bi bi-box-arrow-in-left fs-5"></i>
                                </div>

                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                                    {{-- Return Header --}}
                                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-success px-3 py-2 rounded-pill font-monospace shadow-2xs">
                                                <i class="bi bi-arrow-return-left me-1"></i> INWARD RETURN
                                            </span>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-monospace">
                                                {{ $return->return_no }}
                                            </span>
                                            <span class="fw-bold text-dark fs-6">
                                                {{ date('l, d F Y', strtotime($return->return_date)) }}
                                            </span>
                                        </div>

                                        <div class="d-flex align-items-center gap-2">
                                            <a href="{{ route('materialreturn.view', $return->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1">
                                                <i class="bi bi-receipt me-1"></i> View Receipt
                                            </a>
                                        </div>
                                    </div>

                                    {{-- Logistics Details --}}
                                    <div class="card-body p-4 pb-3">
                                        <div class="row g-3 p-3 bg-light rounded-3 mb-3">
                                            <div class="col-6 col-md-3">
                                                <small class="text-muted d-block mb-1">
                                                    <i class="bi bi-truck me-1 text-success"></i> Transport
                                                </small>
                                                <span class="fw-semibold text-dark">{{ $return->transport_type ?: 'Standard' }}</span>
                                                @if($return->vehicle_no)
                                                    <span class="badge bg-white text-secondary border font-monospace ms-1">{{ $return->vehicle_no }}</span>
                                                @endif
                                            </div>

                                            <div class="col-6 col-md-3">
                                                <small class="text-muted d-block mb-1">
                                                    <i class="bi bi-person me-1 text-info"></i> Returned By
                                                </small>
                                                <span class="fw-semibold text-dark">{{ $return->person_name ?: 'Site In-Charge' }}</span>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <small class="text-muted d-block mb-1">
                                                    <i class="bi bi-chat-quote me-1 text-secondary"></i> Return Remarks
                                                </small>
                                                <span class="text-dark">{{ $return->remarks ?: 'Surplus materials recovered from project site.' }}</span>
                                            </div>
                                        </div>

                                        {{-- Returned Items Table --}}
                                        <div class="table-responsive rounded-3 border">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead class="table-light small text-uppercase text-secondary">
                                                    <tr>
                                                        <th class="ps-3" style="width: 5%;">#</th>
                                                        <th style="width: 45%;">Returned Material Particulars</th>
                                                        <th style="width: 25%;">Quantity Returned</th>
                                                        <th style="width: 25%;">Condition / Notes</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($return->items as $idx => $retItem)
                                                    <tr>
                                                        <td class="ps-3 text-muted small">{{ $idx + 1 }}</td>
                                                        <td class="fw-semibold text-dark">
                                                            <i class="bi bi-arrow-return-left me-2 text-success opacity-75"></i>
                                                            {{ $retItem->dispatchItem->item ?? 'Item #' . $retItem->dispatch_item_id }}
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1 rounded-pill fw-bold">
                                                                {{ rtrim(rtrim(number_format($retItem->return_quantity, 2), '0'), '.') }}
                                                                {{ $retItem->dispatchItem->unit ?? 'Units' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-muted small">
                                                            {{ $retItem->description ?: '—' }}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="card-footer bg-white border-top py-2 px-4 d-flex justify-content-between align-items-center text-muted small">
                                        <span>
                                            <i class="bi bi-person-circle me-1 text-secondary"></i>
                                            Received by: <strong>{{ $return->user_name ?? 'Reception Staff' }}</strong>
                                        </span>
                                        <span>
                                            Returned line items: <strong>{{ $return->items->count() }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 bg-white p-5 text-center">
                    <div class="mb-3">
                        <i class="bi bi-clock-history display-3 text-muted opacity-50"></i>
                    </div>
                    <h5 class="fw-bold text-dark">No Logistics Activity Logged for this Site Yet</h5>
                    <p class="text-muted small mb-4">No outward material dispatches or inward returns have been created for {{ $customer->client_name }}.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('receptionmaterialitems') }}" class="btn btn-primary rounded-3 px-3">
                            <i class="bi bi-box-arrow-right me-1"></i> Create First Dispatch
                        </a>
                        <a href="{{ route('materialreturn') }}" class="btn btn-success rounded-3 px-3 text-white">
                            <i class="bi bi-arrow-return-left me-1"></i> Record Return
                        </a>
                    </div>
                </div>
            @endif
        </div>

        {{-- TAB 2: OUTWARD DISPATCHES ONLY --}}
        <div class="tab-pane fade" id="tab-dispatches" role="tabpanel">
            @if($dispatches->count() > 0)
                <div class="timeline-wrapper">
                    @foreach($dispatches as $dispatch)
                        <div class="timeline-card-entry timeline-entry-dispatch mb-4" data-search-content="{{ strtolower($dispatch->dispatch_date . ' ' . $dispatch->vehicle_no . ' ' . $dispatch->transport_type . ' ' . $dispatch->person_name . ' ' . $dispatch->from_location . ' ' . $dispatch->to_location . ' ' . $dispatch->items->pluck('item')->implode(' ')) }}">
                            <div class="timeline-marker marker-dispatch">
                                <i class="bi bi-truck fs-5"></i>
                            </div>

                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                                <div class="card-header bg-white border-bottom py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-primary px-3 py-2 rounded-pill font-monospace shadow-2xs">
                                            <i class="bi bi-box-arrow-right me-1"></i> DISPATCH #{{ $dispatch->id }}
                                        </span>
                                        <span class="fw-bold text-dark fs-6">
                                            {{ date('l, d F Y', strtotime($dispatch->dispatch_date)) }}
                                        </span>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                                            <i class="bi bi-currency-rupee text-warning"></i>
                                            Freight: <strong>₹ {{ number_format($dispatch->transport_charge, 2) }}</strong>
                                        </span>
                                    </div>
                                </div>

                                <div class="card-body p-4 pb-3">
                                    <div class="row g-3 p-3 bg-light rounded-3 mb-3">
                                        <div class="col-6 col-md-3">
                                            <small class="text-muted d-block mb-1"><i class="bi bi-truck me-1 text-primary"></i> Vehicle & Mode</small>
                                            <span class="fw-semibold text-dark">{{ $dispatch->transport_type ?: 'Standard' }}</span>
                                            @if($dispatch->vehicle_no)
                                                <span class="badge bg-white text-secondary border font-monospace ms-1">{{ $dispatch->vehicle_no }}</span>
                                            @endif
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <small class="text-muted d-block mb-1"><i class="bi bi-person me-1 text-info"></i> In-Charge / Receiver</small>
                                            <span class="fw-semibold text-dark">{{ $dispatch->person_name ?: 'Site Receiver' }}</span>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <small class="text-muted d-block mb-1"><i class="bi bi-signpost-2 me-1 text-danger"></i> Transit Route</small>
                                            <div class="d-flex align-items-center gap-2 text-dark">
                                                <span class="fw-medium text-secondary">{{ $dispatch->from_location }}</span>
                                                <i class="bi bi-arrow-right text-muted"></i>
                                                <span class="fw-bold text-dark">{{ $dispatch->to_location }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive rounded-3 border">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light small text-uppercase text-secondary">
                                                <tr>
                                                    <th class="ps-3" style="width: 5%;">#</th>
                                                    <th style="width: 40%;">Material Item Description</th>
                                                    <th style="width: 20%;">Quantity Dispatched</th>
                                                    <th style="width: 35%;">Remarks / Specifications</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($dispatch->items as $idx => $item)
                                                <tr>
                                                    <td class="ps-3 text-muted small">{{ $idx + 1 }}</td>
                                                    <td class="fw-semibold text-dark">
                                                        <i class="bi bi-box-seam me-2 text-primary opacity-75"></i>
                                                        {{ $item->item }}
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill fw-bold">
                                                            {{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }} {{ $item->unit }}
                                                        </span>
                                                    </td>
                                                    <td class="text-muted small">{{ $item->description ?: '—' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card-footer bg-white border-top py-2 px-4 d-flex justify-content-between align-items-center text-muted small">
                                    <span><i class="bi bi-person-circle me-1 text-secondary"></i> Logged by: <strong>{{ $dispatch->user_name ?? 'Reception Staff' }}</strong></span>
                                    <span>Total items: <strong>{{ $dispatch->items->count() }}</strong></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 bg-white p-5 text-center">
                    <i class="bi bi-truck display-3 text-muted opacity-50 mb-3"></i>
                    <h5 class="fw-bold text-dark">No Dispatches Created Yet</h5>
                    <p class="text-muted small mb-3">Deliver outward materials to this site by creating a new dispatch entry.</p>
                    <a href="{{ route('receptionmaterialitems') }}" class="btn btn-primary rounded-3 px-3 mx-auto">
                        <i class="bi bi-plus-circle me-1"></i> Issue Outward Dispatch
                    </a>
                </div>
            @endif
        </div>

        {{-- TAB 3: INWARD RETURNS ONLY --}}
        <div class="tab-pane fade" id="tab-returns" role="tabpanel">
            @if(isset($returns) && $returns->count() > 0)
                <div class="timeline-wrapper">
                    @foreach($returns as $return)
                        <div class="timeline-card-entry timeline-entry-return mb-4" data-search-content="{{ strtolower($return->return_date . ' ' . $return->return_no . ' ' . $return->vehicle_no . ' ' . $return->transport_type . ' ' . $return->person_name . ' ' . $return->remarks . ' ' . $return->items->pluck('dispatchItem.item')->implode(' ')) }}">
                            <div class="timeline-marker marker-return">
                                <i class="bi bi-box-arrow-in-left fs-5"></i>
                            </div>

                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                                <div class="card-header bg-white border-bottom py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-success px-3 py-2 rounded-pill font-monospace shadow-2xs">
                                            <i class="bi bi-arrow-return-left me-1"></i> INWARD RETURN
                                        </span>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-monospace">
                                            {{ $return->return_no }}
                                        </span>
                                        <span class="fw-bold text-dark fs-6">
                                            {{ date('l, d F Y', strtotime($return->return_date)) }}
                                        </span>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('materialreturn.view', $return->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1">
                                            <i class="bi bi-receipt me-1"></i> View Receipt
                                        </a>
                                    </div>
                                </div>

                                <div class="card-body p-4 pb-3">
                                    <div class="row g-3 p-3 bg-light rounded-3 mb-3">
                                        <div class="col-6 col-md-3">
                                            <small class="text-muted d-block mb-1"><i class="bi bi-truck me-1 text-success"></i> Transport</small>
                                            <span class="fw-semibold text-dark">{{ $return->transport_type ?: 'Standard' }}</span>
                                            @if($return->vehicle_no)
                                                <span class="badge bg-white text-secondary border font-monospace ms-1">{{ $return->vehicle_no }}</span>
                                            @endif
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <small class="text-muted d-block mb-1"><i class="bi bi-person me-1 text-info"></i> Returned By</small>
                                            <span class="fw-semibold text-dark">{{ $return->person_name ?: 'Site In-Charge' }}</span>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <small class="text-muted d-block mb-1"><i class="bi bi-chat-quote me-1 text-secondary"></i> Return Remarks</small>
                                            <span class="text-dark">{{ $return->remarks ?: 'Surplus materials recovered from project site.' }}</span>
                                        </div>
                                    </div>

                                    <div class="table-responsive rounded-3 border">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light small text-uppercase text-secondary">
                                                <tr>
                                                    <th class="ps-3" style="width: 5%;">#</th>
                                                    <th style="width: 45%;">Returned Material Particulars</th>
                                                    <th style="width: 25%;">Quantity Returned</th>
                                                    <th style="width: 25%;">Condition / Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($return->items as $idx => $retItem)
                                                <tr>
                                                    <td class="ps-3 text-muted small">{{ $idx + 1 }}</td>
                                                    <td class="fw-semibold text-dark">
                                                        <i class="bi bi-arrow-return-left me-2 text-success opacity-75"></i>
                                                        {{ $retItem->dispatchItem->item ?? 'Item #' . $retItem->dispatch_item_id }}
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1 rounded-pill fw-bold">
                                                            {{ rtrim(rtrim(number_format($retItem->return_quantity, 2), '0'), '.') }}
                                                            {{ $retItem->dispatchItem->unit ?? 'Units' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-muted small">{{ $retItem->description ?: '—' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card-footer bg-white border-top py-2 px-4 d-flex justify-content-between align-items-center text-muted small">
                                    <span><i class="bi bi-person-circle me-1 text-secondary"></i> Received by: <strong>{{ $return->user_name ?? 'Reception Staff' }}</strong></span>
                                    <span>Returned items: <strong>{{ $return->items->count() }}</strong></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 bg-white p-5 text-center">
                    <i class="bi bi-box-arrow-in-left display-3 text-muted opacity-50 mb-3"></i>
                    <h5 class="fw-bold text-dark">No Material Returns Recorded for this Site</h5>
                    <p class="text-muted small mb-3">Recover excess materials or tools returned from this project site.</p>
                    <a href="{{ route('materialreturn') }}" class="btn btn-success rounded-3 px-3 text-white mx-auto">
                        <i class="bi bi-plus-circle me-1"></i> Record Material Return
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .site-timeline-container {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    .site-avatar-box {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: #eef2ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.12);
    }

    @media (min-width: 992px) {
        .border-start-lg {
            border-left: 1px solid #edf2f7;
        }
    }

    /* Custom Timeline Navigation Pills */
    .custom-timeline-pills .nav-link {
        color: #64748b;
        background: #f8fafc;
        border: 1px solid #edf2f7;
        transition: all 0.2s ease;
    }

    .custom-timeline-pills .nav-link:hover {
        background: #eef2ff;
        color: #4f46e5;
    }

    .custom-timeline-pills .nav-link.active {
        background: #4f46e5;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
    }

    .custom-timeline-pills .nav-link.active .badge {
        background: #ffffff !important;
        color: #4f46e5 !important;
    }

    /* Themed Timeline Layout */
    .timeline-wrapper {
        position: relative;
        padding-left: 36px;
    }

    .timeline-wrapper::before {
        content: '';
        position: absolute;
        top: 24px;
        bottom: 24px;
        left: 17px;
        width: 3px;
        background: linear-gradient(180deg, #3b82f6 0%, #10b981 100%);
        border-radius: 4px;
    }

    .timeline-card-entry {
        position: relative;
        transition: transform 0.2s ease;
    }

    .timeline-card-entry:hover {
        transform: translateY(-2px);
    }

    .timeline-marker {
        position: absolute;
        left: -36px;
        top: 14px;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.12);
        z-index: 2;
        transition: transform 0.25s ease;
    }

    .timeline-card-entry:hover .timeline-marker {
        transform: scale(1.15);
    }

    .timeline-marker.marker-dispatch {
        border: 3px solid #0ea5e9;
        color: #0ea5e9;
        background: #f0f9ff;
    }

    .timeline-marker.marker-return {
        border: 3px solid #10b981;
        color: #10b981;
        background: #ecfdf5;
    }

    .shadow-2xs {
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    /* Print Optimization */
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
        .site-timeline-container {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        .timeline-wrapper {
            padding-left: 0 !important;
        }

        .timeline-wrapper::before,
        .timeline-marker {
            display: none !important;
        }

        .card {
            border: 1px solid #ccc !important;
            box-shadow: none !important;
            break-inside: avoid;
            margin-bottom: 20px !important;
        }
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Real-time search filter for timeline entries
        const searchInput = document.getElementById('timelineSearchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const query = this.value.toLowerCase().trim();
                const activeTab = document.querySelector('.tab-pane.active');
                if (!activeTab) return;

                const entries = activeTab.querySelectorAll('.timeline-card-entry');
                entries.forEach(function(entry) {
                    const content = entry.getAttribute('data-search-content') || entry.innerText.toLowerCase();
                    if (content.includes(query)) {
                        entry.style.display = '';
                    } else {
                        entry.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endpush
@endsection
