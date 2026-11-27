@extends('layouts.salesbase')

@section('title', 'Sales Dashboard')

@section('content')
<div class="sales-dashboard-container py-3">

    {{-- Top Header & Quick Actions --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="header-icon-box">
                    <i class="bi bi-graph-up-arrow text-primary"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Sales Dashboard</h4>
                    <span class="text-muted small">Track quotations, follow up with leads, and monitor customer conversions for {{ now()->year }}.</span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('leaddash') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-bullseye text-info"></i>
                <span>Leads ({{ $lc ?? 0 }})</span>
            </a>
            {{-- <a href="{{ route('addestimate') }}" class="btn btn-primary rounded-3 px-3 py-2 d-inline-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-plus-circle"></i>
            <span class="fw-medium">Create Estimate</span>
            </a> --}}
        </div>
    </div>

    {{-- Flash Notifications --}}
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

    {{-- KPI Summary Cards --}}
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
                        <h2 class="fw-bold mb-1 display-6 counter-val" data-target="{{ $escount ?? 0 }}">{{ $escount ?? 0 }}</h2>
                        <small class="text-muted">Quoted &amp; in pipeline</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 2. Total Leads --}}
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="modern-card card-lead" style="animation-delay: 0.1s;">
                    <div class="icon-box">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Leads</h6>
                            <span class="modern-badge bg-info-subtle text-info border border-info-subtle">Inquiries</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val" data-target="{{ $lc ?? 0 }}">{{ $lc ?? 0 }}</h2>
                        <small class="text-muted">Total leads received</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 3. Today's Estimates --}}
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="modern-card card-today" style="animation-delay: 0.15s;">
                    <div class="icon-box">
                        <i class="bi bi-calendar-event-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Today</h6>
                            <span class="modern-badge bg-primary-subtle text-primary border border-primary-subtle">Today</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val" data-target="{{ $todayCount ?? 0 }}">{{ $todayCount ?? 0 }}</h2>
                        <small class="text-muted">Estimates created today</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 4. Monthly Leads --}}
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="modern-card card-month" style="animation-delay: 0.2s;">
                    <div class="icon-box">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">This Month</h6>
                            <span class="modern-badge bg-secondary-subtle text-secondary border border-secondary-subtle">{{ now()->format('M') }}</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val" data-target="{{ $monthlyCount ?? 0 }}">{{ $monthlyCount ?? 0 }}</h2>
                        <small class="text-muted">Leads in {{ now()->format('F') }}</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 5. Converted Customers --}}
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="modern-card card-converted" style="animation-delay: 0.25s;">
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

            {{-- 6. Total Customers --}}
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="modern-card card-customer" style="animation-delay: 0.3s;">
                    <div class="icon-box">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Customers</h6>
                            <span class="modern-badge bg-warning-subtle text-warning border border-warning-subtle">Clients</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val" data-target="{{ $cuscount ?? 0 }}">{{ $cuscount ?? 0 }}</h2>
                        <small class="text-muted">Confirmed client accounts</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 7. Annual Pipeline Value --}}
            <div class="col-xl-4 col-lg-4 col-md-12">
                <div class="modern-card card-estimate" style="animation-delay: 0.35s;">
                    <div class="icon-box">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Pipeline ({{ now()->year }})</h6>
                            <span class="modern-badge bg-primary-subtle text-primary border border-primary-subtle">Quoted</span>
                        </div>
                        <h3 class="fw-bold mb-1 text-dark">₹{{ number_format($totalPipelineValue ?? 0, 2) }}</h3>
                        <small class="text-muted">Total estimated quote value</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Estimate Directory Table --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0">Estimate Directory</h5>
                <small class="text-muted">Review quotations, download PDFs, convert estimates, or update proposal details</small>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <form method="GET" action="{{ route('salesdashboard') }}" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="status" value="{{ $statusFilter ?? 'all' }}">
                    <div class="position-relative" style="min-width: 260px;">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" name="search" class="form-control rounded-pill ps-5 py-2" placeholder="Search estimate, client, phone..." value="{{ request('search') }}" style="font-size: 13.5px; border-color: #e2e8f0;">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 py-2">Search</button>
                    @if(request()->filled('search') || ($statusFilter ?? 'all') !== 'all')
                    <a href="{{ route('salesdashboard') }}" class="btn btn-sm btn-light border rounded-pill px-3 py-2 text-secondary" title="Clear Filters">
                        <i class="bi bi-x-circle me-1"></i> Clear
                    </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Status Filter Tabs --}}
        <div class="d-flex flex-wrap gap-2 mb-3 pt-2 border-top">
            <a href="{{ route('salesdashboard', array_filter(['search' => request('search'), 'status' => 'all'])) }}" class="btn btn-sm rounded-pill px-3 py-1 fw-semibold {{ ($statusFilter ?? 'all') === 'all' ? 'btn-dark' : 'btn-light border text-dark' }}">
                All Quotes <span class="badge {{ ($statusFilter ?? 'all') === 'all' ? 'bg-secondary' : 'bg-dark-subtle text-dark' }} ms-1">{{ $totalEstimates ?? 0 }}</span>
            </a>
            <a href="{{ route('salesdashboard', array_filter(['search' => request('search'), 'status' => 'active'])) }}" class="btn btn-sm rounded-pill px-3 py-1 fw-semibold {{ ($statusFilter ?? 'all') === 'active' ? 'btn-primary' : 'btn-light border text-primary' }}">
                Active / Pending <span class="badge {{ ($statusFilter ?? 'all') === 'active' ? 'bg-white text-primary' : 'bg-primary-subtle text-primary' }} ms-1">{{ $escount ?? 0 }}</span>
            </a>
            <a href="{{ route('salesdashboard', array_filter(['search' => request('search'), 'status' => 'converted'])) }}" class="btn btn-sm rounded-pill px-3 py-1 fw-semibold {{ ($statusFilter ?? 'all') === 'converted' ? 'btn-success' : 'btn-light border text-success' }}">
                Converted <span class="badge {{ ($statusFilter ?? 'all') === 'converted' ? 'bg-white text-success' : 'bg-success-subtle text-success' }} ms-1">{{ $convertedCount ?? 0 }}</span>
            </a>
            <a href="{{ route('salesdashboard', array_filter(['search' => request('search'), 'status' => 'cancelled'])) }}" class="btn btn-sm rounded-pill px-3 py-1 fw-semibold {{ ($statusFilter ?? 'all') === 'cancelled' ? 'btn-danger' : 'btn-light border text-danger' }}">
                Cancelled <span class="badge {{ ($statusFilter ?? 'all') === 'cancelled' ? 'bg-white text-danger' : 'bg-danger-subtle text-danger' }} ms-1">{{ $cancelledCount ?? 0 }}</span>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 custom-estimate-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th class="text-start">Estimate Details</th>
                        <th class="text-start">Customer Details</th>
                        <th class="text-start">Location / Address</th>
                        <th class="text-end">Total Amount (₹)</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="min-width: 220px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($estimates as $key => $estimate)
                    @php
                    $isConverted = in_array($estimate->id, $convertedEstimates ?? []);
                    $isCancelled = $estimate->lead && strtolower($estimate->lead->Status) == 'cancelled';
                    $initial = strtoupper(substr($estimate->customer_name ?? 'C', 0, 1));
                    @endphp
                    <tr class="{{ $isCancelled ? 'table-danger-subtle' : '' }}">
                        <td class="text-center text-muted small fw-medium">
                            {{ ($estimates->firstItem() ?? 1) + $key }}
                        </td>
                        <td class="text-start">
                            <div class="d-flex align-items-center gap-2">
                                <div class="estimate-doc-icon">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-primary">#{{ $estimate->estimate_no }}</div>
                                    <small class="text-muted d-flex align-items-center gap-1">
                                        <i class="bi bi-calendar-event"></i>
                                        {{ \Carbon\Carbon::parse($estimate->estimate_date)->format('d M Y') }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td class="text-start">
                            <div class="d-flex align-items-center gap-2">
                                <div class="client-avatar">{{ $initial }}</div>
                                <div>
                                    <div class="fw-semibold text-dark">{{ $estimate->customer_name }}</div>
                                    @if($estimate->mobile)
                                    <small class="text-muted">
                                        <a href="tel:{{ $estimate->mobile }}" class="text-muted text-decoration-none">
                                            <i class="bi bi-telephone me-1"></i>{{ $estimate->mobile }}
                                        </a>
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-start text-secondary small" style="max-width: 220px;">
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
                                {{-- View Proposal --}}
                                <a href="{{ route('estimates.show', $estimate->id) }}" class="btn btn-sm btn-light border text-secondary action-icon-btn" title="View Proposal">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- Edit Proposal --}}
                                <a href="{{ route('estimates.edit', $estimate->id) }}" class="btn btn-sm btn-light border text-warning action-icon-btn" title="Edit Proposal">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                {{-- Download PDF --}}
                                <a href="{{ route('estimates.pdf', $estimate->id) }}" class="btn btn-sm btn-light border text-danger action-icon-btn" target="_blank" title="Download PDF">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>

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

        @if($estimates->hasPages())
        <div class="d-flex justify-content-end mt-3">
            {{ $estimates->links() }}
        </div>
        @endif
    </div>

</div>

<style>
    .sales-dashboard-container {
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
        background: #f1f5f9;
        transform: translateY(-1px);
    }

    .custom-estimate-table thead th {
        background-color: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 10px;
    }

    .custom-estimate-table tbody td {
        padding: 12px 10px;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-danger-subtle {
        background-color: #fff5f5 !important;
    }

</style>
@endsection
