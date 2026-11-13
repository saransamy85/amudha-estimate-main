@extends('layouts.adminbase')

@section('title', 'Executive Dashboard')

@section('content')
<div class="admin-dashboard-container py-3">

    {{-- Top Header & Quick Action Row --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="header-icon-box">
                    <i class="bi bi-speedometer2 text-primary"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Executive Admin Dashboard</h4>
                    <span class="text-muted small">Comprehensive overview of active estimates, customer conversions, pipeline revenues, and business inquiries for {{ now()->year }}.</span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            {{-- <a href="{{ route('estimates.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 rounded-3 px-3 py-2 shadow-sm">
            <i class="bi bi-plus-circle"></i>
            <span class="fw-medium">New Estimate</span>
            </a> --}}
            <a href="{{ route('adminreport') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-bar-chart-line"></i>
                <span>Reports</span>
            </a>
            <a href="{{ route('rcsmsg') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-chat-left-text-fill text-success"></i>
                <span>RCS</span>
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
            {{-- 1. Active Estimates --}}
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="modern-card card-estimate" style="animation-delay: 0.05s;">
                    <div class="icon-box">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Active Estimates</h6>
                            <span class="modern-badge bg-primary-subtle text-primary border border-primary-subtle">Active</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val" data-target="{{ $escount }}">{{ $escount }}</h2>
                        <small class="text-muted">Quoted & in pipeline</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 2. Converted Clients --}}
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="modern-card card-converted" style="animation-delay: 0.1s;">
                    <div class="icon-box">
                        <i class="bi bi-file-earmark-check-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Converted</h6>
                            <span class="modern-badge bg-success-subtle text-success border border-success-subtle">Success</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val" data-target="{{ $convertedCount ?? 0 }}">{{ $convertedCount ?? 0 }}</h2>
                        <small class="text-muted">Converted to customer</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 3. Cancelled Estimates --}}
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="modern-card card-cancelled" style="animation-delay: 0.15s;">
                    <div class="icon-box">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Cancelled</h6>
                            <span class="modern-badge bg-danger-subtle text-danger border border-danger-subtle">Dropped</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val" data-target="{{ $cancelledCount ?? 0 }}">{{ $cancelledCount ?? 0 }}</h2>
                        <small class="text-muted">Dropped estimates</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 4. Total Leads --}}
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="modern-card card-lead" style="animation-delay: 0.2s;">
                    <div class="icon-box">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Total Enquiries</h6>
                            <span class="modern-badge bg-info-subtle text-info border border-info-subtle">Enquiries</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val" data-target="{{ $lc }}">{{ $lc }}</h2>
                        <small class="text-muted">Inquiries registered</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 5. Customer Base --}}
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="modern-card card-customer" style="animation-delay: 0.25s;">
                    <div class="icon-box">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Customers</h6>
                            <span class="modern-badge bg-warning-subtle text-warning border border-warning-subtle">Clients</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val" data-target="{{ $cuscount }}">{{ $cuscount }}</h2>
                        <small class="text-muted">Confirmed client accounts</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 6. Today's Estimates --}}
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="modern-card card-today" style="animation-delay: 0.3s;">
                    <div class="icon-box">
                        <i class="bi bi-calendar-event-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Today's Estimates</h6>
                            <span class="modern-badge bg-primary-subtle text-primary border border-primary-subtle">Today</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val" data-target="{{ $todayCount }}">{{ $todayCount }}</h2>
                        <small class="text-muted">Estimates created today</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 7. Monthly Inquiries --}}
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="modern-card card-month" style="animation-delay: 0.35s;">
                    <div class="icon-box">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">This Month's Enquiries</h6>
                            <span class="modern-badge bg-secondary-subtle text-secondary border border-secondary-subtle">{{ now()->format('M') }}</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val" data-target="{{ $monthlyCount }}">{{ $monthlyCount }}</h2>
                        <small class="text-muted">Inquiries in {{ now()->format('F') }}</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 2: Financial Pipeline Highlights Banner --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="highlight-stat-card bg-white p-3 rounded-4 shadow-sm border border-light">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Annual Quoted Pipeline ({{ now()->year }})</span>
                    <div class="highlight-icon bg-primary-subtle text-primary">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-1">₹{{ number_format($totalPipelineValue ?? 0, 2) }}</h4>
                <div class="d-flex align-items-center gap-1 small text-muted">
                    <span class="badge bg-primary-subtle text-primary">{{ $escount + ($convertedCount ?? 0) }} Quotes</span>
                    <span>total estimated value</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="highlight-stat-card bg-white p-3 rounded-4 shadow-sm border border-light">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Realized Closed Revenue</span>
                    <div class="highlight-icon bg-success-subtle text-success">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-success mb-1">₹{{ number_format($totalConvertedValue ?? 0, 2) }}</h4>
                <div class="d-flex align-items-center gap-1 small text-muted">
                    <span class="badge bg-success-subtle text-success">{{ $convertedCount ?? 0 }} Clients</span>
                    <span>confirmed conversion value</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="highlight-stat-card bg-white p-3 rounded-4 shadow-sm border border-light">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Estimate Conversion Rate</span>
                    <div class="highlight-icon bg-warning-subtle text-warning">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-1">{{ $conversionRate ?? 0 }}%</h4>
                <div class="d-flex align-items-center gap-1 small text-muted">
                    <div class="progress flex-grow-1" style="height: 6px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ min($conversionRate ?? 0, 100) }}%;"></div>
                    </div>
                    <span class="fw-semibold text-dark">{{ $convertedCount ?? 0 }}/{{ max($escount + ($convertedCount ?? 0), 1) }}</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="highlight-stat-card bg-white p-3 rounded-4 shadow-sm border border-light">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">System Active Users</span>
                    <div class="highlight-icon bg-info-subtle text-info">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-1">{{ count($onl ?? []) }} Online</h4>
                <div class="d-flex align-items-center gap-1 small text-muted">
                    <span class="status-pulse-dot"></span>
                    <span>Active staff on CRM right now</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 3: Estimate Management Directory Table --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0">Estimate Directory</h5>
                <small class="text-muted">Review quotations, download PDFs, convert estimates, or update proposal details</small>
            </div>

            {{-- Search & Actions Toolbar --}}
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <form method="GET" action="{{ route('dashboard1') }}" class="d-flex align-items-center gap-2">
                    <div class="position-relative" style="min-width: 260px;">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" name="search" id="estimateLiveSearch" class="form-control rounded-pill ps-5 py-2" placeholder="Search estimate, client, phone..." value="{{ request('search') }}" style="font-size: 13.5px; border-color: #e2e8f0;">
                    </div>
                    @if(request()->filled('search'))
                    <a href="{{ route('dashboard1') }}" class="btn btn-sm btn-light border rounded-pill px-3 py-2 text-secondary" title="Clear Search">
                        <i class="bi bi-x-circle me-1"></i> Clear
                    </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Interactive Filter Tabs --}}
        <div class="d-flex flex-wrap gap-2 mb-3 pt-1 border-top">
            <button type="button" class="btn btn-sm rounded-pill px-3 py-1 fw-semibold estimate-filter-btn active" data-filter="all">
                All Quotes <span class="badge bg-dark-subtle text-dark ms-1">{{ count($estimates) }}</span>
            </button>
            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 fw-semibold text-primary estimate-filter-btn" data-filter="active">
                Active / Pending <span class="badge bg-primary-subtle text-primary ms-1">{{ $escount }}</span>
            </button>
            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 fw-semibold text-success estimate-filter-btn" data-filter="converted">
                Converted <span class="badge bg-success-subtle text-success ms-1">{{ $convertedCount ?? 0 }}</span>
            </button>
            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 fw-semibold text-danger estimate-filter-btn" data-filter="cancelled">
                Cancelled <span class="badge bg-danger-subtle text-danger ms-1">{{ $cancelledCount ?? 0 }}</span>
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 custom-estimate-table" id="estimateMasterTable">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th class="text-start">Estimate Details</th>
                        <th class="text-start">Customer Details</th>
                        <th class="text-start">Location / Address</th>
                        <th class="text-end">Total Amount (₹)</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="min-width: 240px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($estimates as $key => $estimate)
                    @php
                    $isConverted = in_array($estimate->id, $convertedEstimates);
                    $isCancelled = $estimate->lead && strtolower($estimate->lead->Status) == 'cancelled';
                    $rowStatus = $isConverted ? 'converted' : ($isCancelled ? 'cancelled' : 'active');
                    $initial = strtoupper(substr($estimate->customer_name ?? 'C', 0, 1));
                    @endphp
                    <tr class="estimate-row" data-status="{{ $rowStatus }}">
                        <td class="text-center text-muted small fw-medium">{{ $key + 1 }}</td>
                        <td class="text-start">
                            <div class="d-flex align-items-center gap-2">
                                <div class="estimate-doc-icon">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-primary estimate-no">#{{ $estimate->estimate_no }}</div>
                                    <small class="text-muted d-flex align-items-center gap-1">
                                        <i class="bi bi-calendar-event"></i>
                                        {{ \Carbon\Carbon::parse($estimate->estimate_date)->format('d M Y') }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td class="text-start">
                            <div class="d-flex align-items-center gap-2">
                                <div class="client-avatar">
                                    {{ $initial }}
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark customer-name">{{ $estimate->customer_name }}</div>
                                    @if($estimate->mobile)
                                    <small class="text-muted">
                                        <a href="tel:{{ $estimate->mobile }}" class="text-muted text-decoration-none customer-phone">
                                            <i class="bi bi-telephone me-1"></i>{{ $estimate->mobile }}
                                        </a>
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-start text-secondary small customer-addr" style="max-width: 220px;">
                            <span class="d-inline-block text-truncate" style="max-width: 200px;" title="{{ $estimate->customer_address }}">
                                {{ $estimate->customer_address ?: '—' }}
                            </span>
                        </td>
                        <td class="text-end fw-bold text-dark fs-6">
                            ₹ {{ number_format($estimate->net_total, 2) }}
                        </td>
                        <td class="text-center">
                            @if($isConverted)
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-semibold">
                                <i class="bi bi-check-circle me-1"></i> Converted
                            </span>
                            @elseif($isCancelled)
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1 fw-semibold">
                                <i class="bi bi-x-circle me-1"></i> Cancelled
                            </span>
                            @else
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-semibold">
                                <i class="bi bi-hourglass-split me-1"></i> Active
                            </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex align-items-center gap-1">
                                {{-- View --}}
                                <a href="{{ route('estimates.show', $estimate->id) }}" class="btn btn-sm btn-light border text-secondary action-icon-btn" title="View Proposal">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('estimates.edit', $estimate->id) }}" class="btn btn-sm btn-light border text-warning action-icon-btn" title="Edit Proposal">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                {{-- PDF --}}
                                <a href="{{ route('estimates.pdf', $estimate->id) }}" class="btn btn-sm btn-light border text-danger action-icon-btn" target="_blank" title="Download PDF">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>

                                {{-- Convert to Customer Button --}}
                                @if(!$isConverted)
                                <form action="{{ route('customers.convert', $estimate->id) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-2 py-1 d-inline-flex align-items-center gap-1 shadow-sm" style="font-size: 11.5px;">
                                        <i class="bi bi-person-check-fill"></i> Convert
                                    </button>
                                </form>
                                @else
                                <span class="badge bg-light text-success border px-2 py-1 small fw-semibold">
                                    <i class="bi bi-check-all"></i> Client
                                </span>
                                @endif

                                {{-- Delete --}}
                                <form action="{{ route('estimates.destroy', $estimate->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure you want to permanently delete this estimate?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger action-icon-btn" title="Delete Estimate">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <div class="d-flex flex-column align-items-center gap-2">
                                <i class="bi bi-file-earmark-x fs-1 text-muted"></i>
                                <span class="fw-semibold">No estimates found</span>
                                <small class="text-muted">Create a new estimate or try adjusting your search filters.</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Scoped Styles --}}
<style>
    .admin-dashboard-container {
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

    .highlight-stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .highlight-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px -4px rgba(0, 0, 0, 0.06) !important;
    }

    .highlight-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    .status-pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #10b981;
        display: inline-block;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
    }

    .estimate-doc-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #eef2ff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .client-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #0d6efd;
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

    .estimate-filter-btn.active {
        background-color: #0d6efd !important;
        color: #ffffff !important;
        border-color: #0d6efd !important;
    }

    .estimate-filter-btn.active .badge {
        background-color: rgba(255, 255, 255, 0.25) !important;
        color: #ffffff !important;
    }

    /* Custom Table */
    .custom-estimate-table thead th {
        background-color: #f8fafc;
        color: #475569;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 14px;
    }

    .custom-estimate-table tbody td {
        padding: 12px 14px;
        font-size: 13.5px;
        border-bottom: 1px solid #f1f5f9;
    }

</style>

{{-- Table Filtering & Search Scripts --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Client-Side Instant Table Filter & Search
        const liveSearchInput = document.getElementById('estimateLiveSearch');
        const filterButtons = document.querySelectorAll('.estimate-filter-btn');
        const tableRows = document.querySelectorAll('#estimateMasterTable .estimate-row');

        let currentFilter = 'all';

        function applyTableFilters() {
            const query = liveSearchInput ? liveSearchInput.value.toLowerCase().trim() : '';

            tableRows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                const noEl = row.querySelector('.estimate-no');
                const nameEl = row.querySelector('.customer-name');
                const phoneEl = row.querySelector('.customer-phone');
                const addrEl = row.querySelector('.customer-addr');

                const text = (
                    (noEl ? noEl.textContent : '') + ' ' +
                    (nameEl ? nameEl.textContent : '') + ' ' +
                    (phoneEl ? phoneEl.textContent : '') + ' ' +
                    (addrEl ? addrEl.textContent : '')
                ).toLowerCase();

                const matchesStatus = (currentFilter === 'all' || rowStatus === currentFilter);
                const matchesSearch = (!query || text.includes(query));

                if (matchesStatus && matchesSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        if (liveSearchInput) {
            liveSearchInput.addEventListener('input', applyTableFilters);
        }

        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                filterButtons.forEach(b => {
                    b.classList.remove('active');
                    b.classList.add('btn-light', 'border');
                });
                this.classList.add('active');
                this.classList.remove('btn-light', 'border');

                currentFilter = this.getAttribute('data-filter');
                applyTableFilters();
            });
        });

    });

</script>
@endsection
