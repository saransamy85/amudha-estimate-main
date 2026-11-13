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

@section('title', 'Customer 360° Search')

@section('content')
<div class="customer-search-container py-3">

    {{-- Top Header & Quick Action Row --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="header-icon-box">
                    <i class="bi bi-person-vcard text-primary"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Customer 360° Dossier & Search</h4>
                    <span class="text-muted small">Search client accounts by Lead ID, Phone number, or Name to review proposals, procurement spend, and dispatch logistics.</span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            @if(isset($lead) && $lead)
                <a href="{{ route('customer.report.pdf', $lead->id) }}" target="_blank" class="btn btn-danger d-inline-flex align-items-center gap-2 rounded-3 px-3 py-2 shadow-sm">
                    <i class="bi bi-file-earmark-pdf"></i>
                    <span class="fw-medium">Export Statement (PDF)</span>
                </a>
                <button type="button" onclick="window.print()" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-printer"></i>
                    <span>Print</span>
                </button>
            @endif
            <a href="{{ route('customersearch') }}" class="btn btn-light border rounded-3 px-3 py-2 text-secondary d-inline-flex align-items-center gap-2 shadow-sm" title="Reset Search">
                <i class="bi bi-arrow-counterclockwise"></i>
                <span>Reset</span>
            </a>
        </div>
    </div>

    {{-- Search Filter Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
        <form method="GET" action="{{ route('customersearch') }}" id="customerSearchForm">
            <div class="row g-3 align-items-center">
                <div class="col-lg-8 col-md-7">
                    <label class="form-label text-secondary fw-semibold small mb-2">Search Customer Dossier</label>
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-5"></i>
                        <input type="text" name="lead_id" class="form-control rounded-pill ps-5 py-2 pe-5 search-input-styled" 
                               value="{{ request('lead_id') }}" 
                               placeholder="Enter Lead ID (e.g. 102), Customer Name, or 10-digit Mobile Number..." 
                               required autofocus>
                        @if(request()->filled('lead_id'))
                            <a href="{{ route('customersearch') }}" class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted text-decoration-none" title="Clear">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4 col-md-5 d-flex align-items-end pt-md-4">
                    <div class="d-flex align-items-center gap-2 w-100">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 d-inline-flex align-items-center justify-content-center gap-2 shadow-sm flex-grow-1">
                            <i class="bi bi-arrow-right-circle-fill"></i>
                            <span class="fw-semibold">Search Dossier</span>
                        </button>
                        @if(request()->filled('lead_id'))
                            <a href="{{ route('customersearch') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2" title="Clear Search">
                                Clear
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Quick Suggestion Pills (Recent Leads) --}}
            @if(isset($recentLeads) && $recentLeads->count() > 0)
                <div class="mt-3 pt-3 border-top d-flex align-items-center gap-2 flex-wrap">
                    <span class="text-muted small fw-medium me-1"><i class="bi bi-clock-history me-1"></i>Recent Accounts:</span>
                    @foreach($recentLeads as $rLead)
                        <a href="{{ route('customersearch', ['lead_id' => $rLead->id]) }}" 
                           class="badge bg-light text-dark border text-decoration-none rounded-pill px-3 py-2 quick-lead-badge {{ request('lead_id') == $rLead->id ? 'border-primary text-primary fw-bold' : '' }}">
                            #{{ $rLead->id }} {{ $rLead->Name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </form>
    </div>

    {{-- Alert: Not Found --}}
    @if(request()->filled('lead_id') && !$lead)
        <div class="alert alert-warning border-0 rounded-4 shadow-sm p-4 mb-4 text-center">
            <div class="d-inline-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-circle mb-3" style="width: 54px; height: 54px; font-size: 26px;">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">No Account Found for "{{ request('lead_id') }}"</h5>
            <p class="text-muted mb-3 small">We couldn't find any lead or customer record matching this query. Please check the Lead ID, Mobile Number, or Customer Name.</p>
            <a href="{{ route('customersearch') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-2">
                <i class="bi bi-arrow-left me-1"></i> Try Another Search
            </a>
        </div>
    @endif

    {{-- Main Content: Lead Found --}}
    @if(isset($lead) && $lead)
        @php
            $initial = strtoupper(substr($lead->Name ?? 'C', 0, 1));
            $totalEstimateVal = $estimates->sum('net_total');
            $totalPoVal = $purchaseOrders->sum('grand_total');
            $totalDispatchCharge = $materialDispatches->sum('transport_charge');
            $grossMargin = $totalEstimateVal - $totalPoVal;
            $marginPercent = $totalEstimateVal > 0 ? round(($grossMargin / $totalEstimateVal) * 100, 1) : 0;
            $cleanMobile = preg_replace('/[^0-9]/', '', $lead->Mobile);
        @endphp

        {{-- 1. Hero Customer Profile Card --}}
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4 customer-profile-hero position-relative overflow-hidden">
            <div class="hero-accent-shape"></div>
            <div class="row g-4 align-items-center position-relative">
                <div class="col-xl-5 col-lg-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="client-hero-avatar">
                            {{ $initial }}
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h4 class="fw-bold text-dark mb-0">{{ $lead->Name }}</h4>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-semibold">
                                    #LEAD-{{ $lead->id }}
                                </span>
                                @if(strtolower($lead->Status) == 'converted')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-semibold">
                                        <i class="bi bi-check-circle me-1"></i> Converted Client
                                    </span>
                                @elseif(strtolower($lead->Status) == 'cancelled' || strtolower($lead->Status) == 'dropped')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1 fw-semibold">
                                        {{ $lead->Status }}
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1 fw-semibold">
                                        {{ $lead->Status ?? 'Active' }}
                                    </span>
                                @endif
                            </div>
                            <small class="text-muted d-flex align-items-center gap-2">
                                <span><i class="bi bi-calendar3 me-1"></i> Registered: {{ $lead->created_at ? $lead->created_at->format('d M Y') : '—' }}</span>
                                <span>•</span>
                                <span><i class="bi bi-person-badge me-1"></i> By: {{ $lead->created_by ?? 'Admin' }}</span>
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Client Quick Attributes --}}
                <div class="col-xl-7 col-lg-6">
                    <div class="row g-2">
                        {{-- Mobile --}}
                        <div class="col-sm-6">
                            <div class="p-2 rounded-3 bg-light border">
                                <div class="text-muted small fw-semibold">Phone / WhatsApp</div>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <a href="tel:{{ $lead->Mobile }}" class="text-dark fw-bold text-decoration-none">
                                        <i class="bi bi-telephone-fill text-primary me-1"></i>{{ $lead->Mobile }}
                                    </a>
                                    @if($cleanMobile)
                                        <a href="https://wa.me/91{{ $cleanMobile }}" target="_blank" class="badge bg-success text-white text-decoration-none px-2 py-1 rounded-pill" title="Chat on WhatsApp">
                                            <i class="bi bi-whatsapp"></i> Chat
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-sm-6">
                            <div class="p-2 rounded-3 bg-light border">
                                <div class="text-muted small fw-semibold">Email Address</div>
                                <div class="mt-1 text-truncate text-dark fw-medium">
                                    @if($lead->email)
                                        <a href="mailto:{{ $lead->email }}" class="text-dark text-decoration-none">
                                            <i class="bi bi-envelope-fill text-secondary me-1"></i>{{ $lead->email }}
                                        </a>
                                    @else
                                        <span class="text-muted fst-italic">Not specified</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Location --}}
                        <div class="col-sm-6">
                            <div class="p-2 rounded-3 bg-light border">
                                <div class="text-muted small fw-semibold">Site Location</div>
                                <div class="mt-1 text-truncate text-dark fw-medium" title="{{ $lead->Site_location }}">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $lead->Site_location ?: '—' }}
                                </div>
                            </div>
                        </div>

                        {{-- Product & Size --}}
                        <div class="col-sm-6">
                            <div class="p-2 rounded-3 bg-light border">
                                <div class="text-muted small fw-semibold">Product & Scope</div>
                                <div class="mt-1 text-truncate text-dark fw-medium">
                                    <i class="bi bi-box-seam-fill text-warning me-1"></i>{{ $lead->Product ?: '—' }} 
                                    @if($lead->Total_Area)
                                        <span class="text-muted small">({{ $lead->Total_Area }})</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Financial & Operational 360° KPI Cards --}}
        <div class="row g-3 mb-4">
            {{-- Quotes Total --}}
            <div class="col-xl-3 col-md-6">
                <div class="summary-stat-card card-stat-estimates bg-white p-3 rounded-4 shadow-sm border">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Total Quoted Value</span>
                        <div class="stat-icon-pill bg-primary-subtle text-primary">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">₹{{ number_format($totalEstimateVal, 2) }}</h3>
                    <div class="d-flex align-items-center justify-content-between small text-muted">
                        <span>{{ $estimates->count() }} Quotations generated</span>
                        <span class="badge bg-primary-subtle text-primary">Proposals</span>
                    </div>
                </div>
            </div>

            {{-- Procurement Spend --}}
            <div class="col-xl-3 col-md-6">
                <div class="summary-stat-card card-stat-po bg-white p-3 rounded-4 shadow-sm border">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Procurement Spend (POs)</span>
                        <div class="stat-icon-pill bg-warning-subtle text-warning">
                            <i class="bi bi-cart-check-fill"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">₹{{ number_format($totalPoVal, 2) }}</h3>
                    <div class="d-flex align-items-center justify-content-between small text-muted">
                        <span>{{ $purchaseOrders->count() }} Purchase Orders</span>
                        <span class="badge bg-warning-subtle text-warning">Orders</span>
                    </div>
                </div>
            </div>

            {{-- Logistics & Dispatch --}}
            <div class="col-xl-3 col-md-6">
                <div class="summary-stat-card card-stat-dispatch bg-white p-3 rounded-4 shadow-sm border">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Logistics & Freight</span>
                        <div class="stat-icon-pill bg-info-subtle text-info">
                            <i class="bi bi-truck"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">₹{{ number_format($totalDispatchCharge, 2) }}</h3>
                    <div class="d-flex align-items-center justify-content-between small text-muted">
                        <span>{{ $materialDispatches->count() }} Dispatch shipments</span>
                        <span class="badge bg-info-subtle text-info">Logistics</span>
                    </div>
                </div>
            </div>

            {{-- Net Project Margin --}}
            <div class="col-xl-3 col-md-6">
                <div class="summary-stat-card card-stat-margin bg-white p-3 rounded-4 shadow-sm border">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Estimated Gross Margin</span>
                        <div class="stat-icon-pill {{ $grossMargin >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold {{ $grossMargin >= 0 ? 'text-success' : 'text-danger' }} mb-1">₹{{ number_format($grossMargin, 2) }}</h3>
                    <div class="d-flex align-items-center justify-content-between small text-muted">
                        <span>Margin Rate: <strong class="{{ $grossMargin >= 0 ? 'text-success' : 'text-danger' }}">{{ $marginPercent }}%</strong></span>
                        <span class="badge {{ $grossMargin >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                            {{ $grossMargin >= 0 ? 'Net Positive' : 'Deficit' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Detailed Data Sections with Nav Pills --}}
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <ul class="nav nav-pills custom-dossier-pills gap-2" id="dossierTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" 
                                id="estimates-tab" data-bs-toggle="pill" data-bs-target="#tab-estimates" type="button" role="tab">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>Estimates & Quotations</span>
                            <span class="badge bg-primary rounded-pill">{{ $estimates->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" 
                                id="pos-tab" data-bs-toggle="pill" data-bs-target="#tab-pos" type="button" role="tab">
                            <i class="bi bi-receipt"></i>
                            <span>Purchase Orders</span>
                            <span class="badge bg-warning text-dark rounded-pill">{{ $purchaseOrders->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" 
                                id="dispatch-tab" data-bs-toggle="pill" data-bs-target="#tab-dispatch" type="button" role="tab">
                            <i class="bi bi-truck"></i>
                            <span>Material Dispatches</span>
                            <span class="badge bg-info text-dark rounded-pill">{{ $materialDispatches->count() }}</span>
                        </button>
                    </li>
                </ul>

                <div class="text-muted small">
                    <i class="bi bi-info-circle me-1"></i> Client Profile Dossier
                </div>
            </div>

            <div class="tab-content pt-2" id="dossierTabContent">

                {{-- TAB 1: Estimates --}}
                <div class="tab-pane fade show active" id="tab-estimates" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 custom-modern-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th class="text-start">Estimate Details</th>
                                    <th class="text-start">Quotation Date</th>
                                    <th class="text-start">Customer / Site Name</th>
                                    <th class="text-end">Total Amount (₹)</th>
                                    <th class="text-center" style="min-width: 140px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($estimates as $key => $estimate)
                                    <tr>
                                        <td class="text-center text-muted small fw-medium">{{ $key + 1 }}</td>
                                        <td class="text-start">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="table-row-icon bg-primary-subtle text-primary">
                                                    <i class="bi bi-file-earmark-text"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-primary">#{{ $estimate->estimate_no }}</span>
                                                    <div class="text-muted small">ID: {{ $estimate->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-start text-muted">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            {{ $estimate->estimate_date ? \Carbon\Carbon::parse($estimate->estimate_date)->format('d M Y') : '—' }}
                                        </td>
                                        <td class="text-start">
                                            <span class="fw-semibold text-dark">{{ $estimate->customer_name }}</span>
                                        </td>
                                        <td class="text-end fw-bold text-dark fs-6">
                                            ₹{{ number_format($estimate->net_total, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex align-items-center gap-1">
                                                <a href="{{ route('estimates.show', $estimate->id) }}" class="btn btn-sm btn-light border text-secondary action-btn" title="View Proposal">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('estimates.pdf', $estimate->id) }}" class="btn btn-sm btn-danger rounded-pill px-3 py-1 shadow-sm d-inline-flex align-items-center gap-1" target="_blank" title="Download PDF">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                    <span style="font-size: 12px;">PDF</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="bi bi-file-earmark-x fs-1 text-muted d-block mb-2"></i>
                                            <span class="fw-semibold">No estimates found for this lead</span>
                                            <div class="small text-muted">No quotations have been prepared yet.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 2: Purchase Orders --}}
                <div class="tab-pane fade" id="tab-pos" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 custom-modern-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th class="text-start">PO Number</th>
                                    <th class="text-start">Vendor / Supplier</th>
                                    <th class="text-start">Type / Template</th>
                                    <th class="text-end">Grand Total (₹)</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="min-width: 140px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchaseOrders as $key => $po)
                                    <tr>
                                        <td class="text-center text-muted small fw-medium">{{ $key + 1 }}</td>
                                        <td class="text-start">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="table-row-icon bg-warning-subtle text-warning">
                                                    <i class="bi bi-receipt"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark">#{{ $po->po_no }}</span>
                                                    <div class="text-muted small">
                                                        {{ $po->po_date ? \Carbon\Carbon::parse($po->po_date)->format('d M Y') : '—' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-start">
                                            <span class="fw-semibold text-dark">{{ $po->vendor->company_name ?? ($po->contractor_name ?? '—') }}</span>
                                        </td>
                                        <td class="text-start">
                                            <span class="badge bg-light text-dark border px-2 py-1">{{ $po->po_template ?: 'Standard' }}</span>
                                        </td>
                                        <td class="text-end fw-bold text-dark fs-6">
                                            ₹{{ number_format($po->grand_total, 2) }}
                                        </td>
                                        <td class="text-center">
                                            @if(strtolower($po->status) == 'approved')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-semibold">Approved</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1 fw-semibold">{{ $po->status ?? 'Pending' }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex align-items-center gap-1">
                                                <a href="{{ route('purchase.view', $po->id) }}" class="btn btn-sm btn-light border text-secondary action-btn" title="View PO Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('purchase.pdf', $po->id) }}" class="btn btn-sm btn-danger rounded-pill px-3 py-1 shadow-sm d-inline-flex align-items-center gap-1" target="_blank" title="Download PO PDF">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                    <span style="font-size: 12px;">PDF</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="bi bi-cart-x fs-1 text-muted d-block mb-2"></i>
                                            <span class="fw-semibold">No purchase orders found for this customer</span>
                                            <div class="small text-muted">No procurement orders have been issued for this site yet.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 3: Material Dispatches --}}
                <div class="tab-pane fade" id="tab-dispatch" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 custom-modern-table">
                            <thead>
                                <tr>
                                    <th class="text-start">Dispatch Date</th>
                                    <th class="text-start">Supervisor / Driver</th>
                                    <th class="text-start">Route (From → To)</th>
                                    <th class="text-start">Transport Mode & Vehicle</th>
                                    <th class="text-end">Transport Charge (₹)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($materialDispatches as $dispatch)
                                    <tr>
                                        <td class="text-start">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="table-row-icon bg-info-subtle text-info">
                                                    <i class="bi bi-truck"></i>
                                                </div>
                                                <span class="fw-semibold text-dark">
                                                    {{ $dispatch->dispatch_date ? \Carbon\Carbon::parse($dispatch->dispatch_date)->format('d M Y') : '—' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-start">
                                            <span class="fw-medium text-dark">{{ $dispatch->person_name ?: '—' }}</span>
                                        </td>
                                        <td class="text-start">
                                            <span class="badge bg-light text-dark border">
                                                {{ $dispatch->from_location ?: 'Origin' }} <i class="bi bi-arrow-right mx-1 text-primary"></i> {{ $dispatch->to_location ?: 'Site' }}
                                            </span>
                                        </td>
                                        <td class="text-start">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-secondary small">{{ $dispatch->transport_type ?: 'Road' }}</span>
                                                @if($dispatch->vehicle_no)
                                                    <span class="badge bg-secondary-subtle text-dark border">{{ $dispatch->vehicle_no }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold text-dark fs-6">
                                            ₹{{ number_format($dispatch->transport_charge, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            <i class="bi bi-box-seam fs-1 text-muted d-block mb-2"></i>
                                            <span class="fw-semibold">No material dispatch records found</span>
                                            <div class="small text-muted">No logistics or site deliveries recorded for this account.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    {{-- Initial Blank State (Before Search) --}}
    @elseif(!request()->filled('lead_id'))
        <div class="card border-0 shadow-sm rounded-4 bg-white p-5 text-center my-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle mb-3 mx-auto" style="width: 72px; height: 72px; font-size: 32px;">
                <i class="bi bi-search-heart"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">Customer 360° Profile Lookup</h4>
            <p class="text-muted mx-auto mb-4" style="max-width: 540px;">
                Enter a Lead ID, Customer Name, or Mobile Number in the search box above to instantly pull up complete quotation history, purchase order commitments, and material dispatch records.
            </p>

            <div class="row g-3 justify-content-center mt-2 text-start" style="max-width: 900px; margin: 0 auto;">
                <div class="col-md-4">
                    <div class="p-3 rounded-4 border bg-light h-100">
                        <div class="d-flex align-items-center gap-2 mb-2 text-primary fw-bold">
                            <i class="bi bi-file-earmark-check fs-5"></i>
                            <span>Estimates & Quotes</span>
                        </div>
                        <p class="small text-muted mb-0">Consolidated history of generated estimates, net proposal values, and PDF downloads.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-4 border bg-light h-100">
                        <div class="d-flex align-items-center gap-2 mb-2 text-warning fw-bold">
                            <i class="bi bi-cart-check fs-5"></i>
                            <span>Procurement POs</span>
                        </div>
                        <p class="small text-muted mb-0">Track vendor purchase orders, contractor work orders, and approval statuses.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-4 border bg-light h-100">
                        <div class="d-flex align-items-center gap-2 mb-2 text-info fw-bold">
                            <i class="bi bi-truck fs-5"></i>
                            <span>Dispatch Logistics</span>
                        </div>
                        <p class="small text-muted mb-0">Monitor material shipments, vehicle details, driver logs, and transport costs.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- Scoped Styles --}}
<style>
.customer-search-container {
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

.search-input-styled {
    font-size: 14.5px;
    border-color: #e2e8f0;
    transition: all 0.25s ease;
}

.search-input-styled:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
}

.quick-lead-badge {
    transition: all 0.2s ease;
    font-size: 12.5px;
}

.quick-lead-badge:hover {
    background-color: #eef2ff !important;
    color: #4f46e5 !important;
    border-color: #c7d2fe !important;
    transform: translateY(-1px);
}

/* Hero Customer Profile */
.customer-profile-hero {
    background: linear-gradient(to right, #ffffff, #fafafa);
}

.client-hero-avatar {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    color: #ffffff;
    font-size: 24px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 8px 16px -4px rgba(79, 70, 229, 0.35);
}

/* Stat Cards */
.summary-stat-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-color: #edf2f7 !important;
}

.summary-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 22px -4px rgba(15, 23, 42, 0.08) !important;
}

.stat-icon-pill {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
}

/* Tabs */
.custom-dossier-pills .nav-link {
    color: #475569;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}

.custom-dossier-pills .nav-link:hover {
    background-color: #f1f5f9;
}

.custom-dossier-pills .nav-link.active {
    background-color: #0d6efd !important;
    color: #ffffff !important;
    border-color: #0d6efd !important;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
}

.custom-dossier-pills .nav-link.active .badge {
    background-color: rgba(255, 255, 255, 0.25) !important;
    color: #ffffff !important;
}

/* Modern Tables */
.custom-modern-table thead th {
    background-color: #f8fafc;
    color: #475569;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    border-bottom: 1px solid #e2e8f0;
    padding: 12px 14px;
}

.custom-modern-table tbody td {
    padding: 13px 14px;
    font-size: 13.5px;
    border-bottom: 1px solid #f1f5f9;
}

.table-row-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
}

.action-btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.2s;
}

.action-btn:hover {
    background-color: #f1f5f9;
}
</style>
@endsection
