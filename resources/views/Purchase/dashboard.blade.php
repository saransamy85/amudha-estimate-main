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

@section('title', 'Purchase Dashboard')

@section('content')
<div class="purchase-dashboard-container py-3">

    {{-- Top Header & Quick Action Row --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="header-icon-box">
                    <i class="bi bi-cart-check-fill text-primary"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Purchase & Procurement Dashboard</h4>
                    <span class="text-muted small">Monitor vendor procurement, monthly purchase order breakdown, approval pipelines, and spend statements for {{ now()->year }}.</span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button" class="btn btn-outline-danger d-inline-flex align-items-center gap-2 rounded-3 px-3 py-2 shadow-sm"
                    data-bs-toggle="modal" data-bs-target="#customPoReportModal">
                <i class="bi bi-file-earmark-pdf"></i>
                <span class="fw-medium">Export Statements</span>
            </button>
            <a href="{{ route('purchase.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 rounded-3 px-3 py-2 shadow-sm">
                <i class="bi bi-plus-circle"></i>
                <span class="fw-medium">New Purchase Order</span>
            </a>
            <a href="{{ route('vendors') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-building"></i>
                <span>Vendors</span>
            </a>
            <a href="{{ route('purchase.index') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-file-earmark-text"></i>
                <span>All POs</span>
            </a>
        </div>
    </div>

    {{-- Section 1: KPI Metric Cards --}}
    <div class="row g-3 mb-4">
        {{-- Total Vendors --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="modern-card card-estimate">
                <div class="icon-box">
                    <i class="bi bi-building-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="text-muted mb-0 fw-semibold">Total Vendors</h6>
                        <span class="modern-badge bg-primary-subtle text-primary border border-primary-subtle">Suppliers</span>
                    </div>
                    <h2 class="fw-bold mb-1 display-6 counter-val" data-target="{{ $totalVendor }}">{{ $totalVendor }}</h2>
                    <small class="text-muted">Registered vendor network</small>
                </div>
                <div class="card-accent-bar"></div>
            </div>
        </div>

        {{-- Total Purchase Orders --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="modern-card card-lead">
                <div class="icon-box">
                    <i class="bi bi-file-earmark-ruled-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="text-muted mb-0 fw-semibold">Total POs</h6>
                        <span class="modern-badge bg-info-subtle text-info border border-info-subtle">Issued</span>
                    </div>
                    <h2 class="fw-bold mb-1 display-6 counter-val" data-target="{{ $totalPO }}">{{ $totalPO }}</h2>
                    <small class="text-muted">Total purchase orders issued</small>
                </div>
                <div class="card-accent-bar"></div>
            </div>
        </div>

        {{-- Pending POs --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="modern-card card-customer">
                <div class="icon-box">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="text-muted mb-0 fw-semibold">Pending POs</h6>
                        <span class="modern-badge bg-warning-subtle text-warning border border-warning-subtle">Action Required</span>
                    </div>
                    <h2 class="fw-bold mb-1 display-6 counter-val" data-target="{{ $pendingPO }}">{{ $pendingPO }}</h2>
                    <small class="text-muted">Awaiting management approval</small>
                </div>
                <div class="card-accent-bar"></div>
            </div>
        </div>

        {{-- Approved POs --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="modern-card card-converted">
                <div class="icon-box">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="text-muted mb-0 fw-semibold">Approved POs</h6>
                        <span class="modern-badge bg-success-subtle text-success border border-success-subtle">Approved</span>
                    </div>
                    <h2 class="fw-bold mb-1 display-6 counter-val" data-target="{{ $approvedPO }}">{{ $approvedPO }}</h2>
                    <small class="text-muted">Confirmed & active orders</small>
                </div>
                <div class="card-accent-bar"></div>
            </div>
        </div>
    </div>

    {{-- Section 2: Procurement Financial Spend Highlights --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="highlight-stat-card bg-white p-3 rounded-4 shadow-sm border border-light">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Total Procurement Spend</span>
                    <div class="highlight-icon bg-primary-subtle text-primary">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-1">₹{{ number_format($totalPoValue ?? 0, 2) }}</h4>
                <div class="d-flex align-items-center gap-1 small text-muted">
                    <span class="badge bg-primary-subtle text-primary">{{ $totalPO }} Orders</span>
                    <span>cumulative order value</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="highlight-stat-card bg-white p-3 rounded-4 shadow-sm border border-light">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Approved PO Expenditure</span>
                    <div class="highlight-icon bg-success-subtle text-success">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-success mb-1">₹{{ number_format($approvedPoValue ?? 0, 2) }}</h4>
                <div class="d-flex align-items-center gap-1 small text-muted">
                    <span class="badge bg-success-subtle text-success">{{ $approvedPO }} Orders</span>
                    <span>authorized procurement</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="highlight-stat-card bg-white p-3 rounded-4 shadow-sm border border-light">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Pending Authorization</span>
                    <div class="highlight-icon bg-warning-subtle text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-warning mb-1">₹{{ number_format($pendingPoValue ?? 0, 2) }}</h4>
                <div class="d-flex align-items-center gap-1 small text-muted">
                    <span class="badge bg-warning-subtle text-warning">{{ $pendingPO }} Orders</span>
                    <span>pending spend commitment</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="highlight-stat-card bg-white p-3 rounded-4 shadow-sm border border-light">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">PO Approval Rate</span>
                    <div class="highlight-icon bg-info-subtle text-info">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-1">{{ $poApprovalRate ?? 0 }}%</h4>
                <div class="d-flex align-items-center gap-1 small text-muted">
                    <div class="progress flex-grow-1" style="height: 6px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ min($poApprovalRate ?? 0, 100) }}%;"></div>
                    </div>
                    <span class="fw-semibold text-dark">{{ $approvedPO }}/{{ max($totalPO, 1) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 3: Quick Export Options Hub --}}
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-uppercase text-muted fw-bold mb-0 small" style="letter-spacing: 0.8px;">
                <i class="bi bi-file-earmark-arrow-down me-1 text-primary"></i> Procurement Export Options & Reports
            </h6>
            <button type="button" class="btn btn-sm btn-link text-decoration-none text-primary fw-semibold p-0"
                    data-bs-toggle="modal" data-bs-target="#customPoReportModal">
                Advanced Filter & Custom Range <i class="bi bi-chevron-right small"></i>
            </button>
        </div>

        <div class="row g-3">
            {{-- 1. Current Month PO Statement PDF --}}
            <div class="col-xl-3 col-md-6">
                <a href="{{ route('purchase.report.monthly', ['month' => now()->month, 'year' => now()->year]) }}" 
                   class="export-tile text-decoration-none d-block h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="export-tile-icon bg-danger-subtle text-danger">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </div>
                        <span class="badge bg-danger-subtle text-danger rounded-pill fw-semibold" style="font-size: 10px;">{{ now()->format('M Y') }}</span>
                    </div>
                    <div class="fw-bold text-dark fs-6 mb-1">Current Month Statement</div>
                    <div class="text-muted small mb-2" style="font-size: 11px;">Complete PO statement for {{ now()->format('F Y') }}</div>
                    <div class="export-tile-action text-danger fw-semibold small d-flex align-items-center gap-1">
                        <span>Download PDF</span>
                        <i class="bi bi-arrow-down-short fs-6"></i>
                    </div>
                </a>
            </div>

            {{-- 2. Custom Date Filter PDF --}}
            <div class="col-xl-3 col-md-6">
                <div class="export-tile text-decoration-none d-block h-100 export-tile-custom" 
                     role="button" data-bs-toggle="modal" data-bs-target="#customPoReportModal">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="export-tile-icon bg-primary-subtle text-primary">
                            <i class="bi bi-calendar2-range"></i>
                        </div>
                        <span class="badge bg-primary-subtle text-primary rounded-pill fw-semibold" style="font-size: 10px;">Custom Range</span>
                    </div>
                    <div class="fw-bold text-dark fs-6 mb-1">Custom Period Report</div>
                    <div class="text-muted small mb-2" style="font-size: 11px;">Filter by date range, vendor & approval status</div>
                    <div class="export-tile-action text-primary fw-semibold small d-flex align-items-center gap-1">
                        <span>Select Date Range</span>
                        <i class="bi bi-chevron-right fs-6"></i>
                    </div>
                </div>
            </div>

            {{-- 3. Export CSV / Excel --}}
            <div class="col-xl-3 col-md-6">
                <a href="{{ route('purchase.report.csv') }}" class="export-tile text-decoration-none d-block h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="export-tile-icon bg-success-subtle text-success">
                            <i class="bi bi-file-earmark-spreadsheet"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success rounded-pill fw-semibold" style="font-size: 10px;">CSV / Excel</span>
                    </div>
                    <div class="fw-bold text-dark fs-6 mb-1">Export POs (Spreadsheet)</div>
                    <div class="text-muted small mb-2" style="font-size: 11px;">Raw purchase order data export for Excel</div>
                    <div class="export-tile-action text-success fw-semibold small d-flex align-items-center gap-1">
                        <span>Download CSV</span>
                        <i class="bi bi-arrow-down-short fs-6"></i>
                    </div>
                </a>
            </div>

            {{-- 4. Vendors Directory Export --}}
            <div class="col-xl-3 col-md-6">
                <a href="{{ route('vendors') }}" class="export-tile text-decoration-none d-block h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="export-tile-icon bg-info-subtle text-info">
                            <i class="bi bi-buildings"></i>
                        </div>
                        <span class="badge bg-info-subtle text-info rounded-pill fw-semibold" style="font-size: 10px;">{{ $totalVendor }} Vendors</span>
                    </div>
                    <div class="fw-bold text-dark fs-6 mb-1">Vendor Master Directory</div>
                    <div class="text-muted small mb-2" style="font-size: 11px;">View and manage registered suppliers</div>
                    <div class="export-tile-action text-info fw-semibold small d-flex align-items-center gap-1">
                        <span>Manage Vendors</span>
                        <i class="bi bi-arrow-right fs-6"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>

    {{-- Section 4: Monthly PO Details & Counts Breakdown (NEW) --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0">Monthly Purchase Order Breakdown ({{ now()->year }})</h5>
                <small class="text-muted">Month-by-month analysis of purchase order volume, approved sign-offs, pending authorizations, and total spend</small>
            </div>
            <span class="badge bg-light text-primary border px-3 py-2 fw-semibold">
                <i class="bi bi-calendar3 me-1"></i> Year {{ now()->year }}
            </span>
        </div>

        @php
            $poByMonth = ($monthlyPOs ?? collect())->keyBy('month');
            $activeMonths = collect(range(1, 12))->filter(function($m) use ($poByMonth) {
                return $m <= now()->month || isset($poByMonth[$m]);
            });
        @endphp

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 custom-purchase-table">
                <thead>
                    <tr>
                        <th class="text-start">Month</th>
                        <th class="text-center">Total PO Count</th>
                        <th class="text-center">Approved POs</th>
                        <th class="text-center">Pending POs</th>
                        <th class="text-end">Total PO Value (₹)</th>
                        <th class="text-end">Average PO Size (₹)</th>
                        <th class="text-center" style="width: 140px;">Approval Rate</th>
                        <th class="text-center">Monthly Export</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $ytdPoCount = 0;
                        $ytdPoApproved = 0;
                        $ytdPoPending = 0;
                        $ytdPoValue = 0;
                    @endphp
                    @forelse($activeMonths as $m)
                        @php
                            $monthName = \Carbon\Carbon::create(now()->year, $m, 1)->format('F');
                            $data = $poByMonth->get($m);
                            $cnt = $data ? (int)$data->total_count : 0;
                            $app = $data ? (int)$data->approved_count : 0;
                            $pnd = $data ? (int)$data->pending_count : 0;
                            $val = $data ? (float)$data->total_value : 0;
                            $avg = $cnt > 0 ? ($val / $cnt) : 0;
                            $rate = $cnt > 0 ? round(($app / $cnt) * 100, 1) : 0;

                            $ytdPoCount += $cnt;
                            $ytdPoApproved += $app;
                            $ytdPoPending += $pnd;
                            $ytdPoValue += $val;
                            $isCurrentMonth = ($m == now()->month);
                        @endphp
                        <tr>
                            <td class="text-start">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="month-bullet {{ $isCurrentMonth ? 'current' : '' }}"></div>
                                    <span class="fw-semibold text-dark">{{ $monthName }}</span>
                                    @if($isCurrentMonth)
                                        <span class="badge bg-primary-subtle text-primary" style="font-size: 10px;">Current</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-3 py-1 fw-semibold">
                                    {{ $cnt }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success-subtle text-success px-2 py-1 fw-semibold">
                                    {{ $app }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($pnd > 0)
                                    <span class="badge bg-warning-subtle text-warning px-2 py-1 fw-semibold">{{ $pnd }}</span>
                                @else
                                    <span class="text-muted small">0</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-dark">
                                {{ $val > 0 ? '₹ ' . number_format($val, 2) : '—' }}
                            </td>
                            <td class="text-end text-muted">
                                {{ $avg > 0 ? '₹ ' . number_format($avg, 2) : '—' }}
                            </td>
                            <td class="text-center">
                                @if($cnt > 0)
                                    <div class="d-flex align-items-center gap-1 justify-content-center">
                                        <span class="badge bg-info-subtle text-info rounded-pill px-2 py-1 fw-semibold" style="font-size: 11px;">
                                            {{ $rate }}%
                                        </span>
                                    </div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($cnt > 0)
                                    <a href="{{ route('purchase.report.monthly', ['month' => $m, 'year' => now()->year]) }}" 
                                       class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1 shadow-sm"
                                       title="Download {{ $monthName }} PO Statement PDF">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                        <span>PDF</span>
                                    </a>
                                @else
                                    <span class="text-muted small">No POs</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No monthly purchase orders recorded for {{ now()->year }}</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-light fw-bold border-top-2">
                        <td class="text-start text-dark">Year-to-Date Total ({{ now()->year }})</td>
                        <td class="text-center text-primary">{{ $ytdPoCount }} POs</td>
                        <td class="text-center text-success">{{ $ytdPoApproved }} Approved</td>
                        <td class="text-center text-warning">{{ $ytdPoPending }} Pending</td>
                        <td class="text-end text-primary">₹ {{ number_format($ytdPoValue, 2) }}</td>
                        <td class="text-end text-muted">
                            {{ $ytdPoCount > 0 ? '₹ ' . number_format($ytdPoValue / $ytdPoCount, 2) : '—' }}
                        </td>
                        <td class="text-center text-info">
                            {{ $ytdPoCount > 0 ? round(($ytdPoApproved / $ytdPoCount) * 100, 1) : 0 }}%
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger rounded-pill px-3 py-1 shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#customPoReportModal">
                                <i class="bi bi-download me-1"></i> Export
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Section 5: Visual Analytics & Intelligence --}}
    <div class="row g-4 mb-4">
        {{-- Chart 1: Top Vendors by Procurement Spend --}}
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Top Vendors by Procurement Value</h5>
                        <small class="text-muted">Procurement spend distributed among leading suppliers</small>
                    </div>
                    <span class="badge bg-light text-secondary border px-3 py-1">Leading Suppliers</span>
                </div>

                <div class="chart-container position-relative" style="height: 280px; width: 100%;">
                    <canvas id="vendorSpendChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Chart 2: PO Status & Spend Distribution --}}
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">PO Status Distribution</h5>
                        <small class="text-muted">Approved vs Pending breakdown</small>
                    </div>
                </div>

                <div class="chart-container position-relative mb-3" style="height: 200px;">
                    <canvas id="poStatusDoughnutChart"></canvas>
                </div>

                {{-- Status Stats Breakdown --}}
                <div class="mt-auto pt-2 border-top">
                    <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <span class="legend-dot" style="background-color: #10b981;"></span>
                            <span class="small fw-semibold text-dark">Approved Orders</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success-subtle text-success small">{{ $approvedPO }}</span>
                            <span class="small fw-bold text-success">₹{{ number_format($approvedPoValue ?? 0, 2) }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between py-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="legend-dot" style="background-color: #f59e0b;"></span>
                            <span class="small fw-semibold text-dark">Pending Orders</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning-subtle text-warning small">{{ $pendingPO }}</span>
                            <span class="small fw-bold text-warning">₹{{ number_format($pendingPoValue ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 6: Vendor-Based PO Performance Table --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0">Vendor Procurement Summary</h5>
                <small class="text-muted">Total purchase orders and accumulated procurement spend per vendor</small>
            </div>

            {{-- Live Search Filter --}}
            <div class="position-relative" style="min-width: 260px;">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <input type="text" id="vendorSearchInput" class="form-control rounded-pill ps-5 py-2" 
                       placeholder="Search vendor name..." style="font-size: 13.5px; border-color: #e2e8f0;">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 custom-purchase-table" id="vendorSummaryTable">
                <thead>
                    <tr>
                        <th class="text-start">Vendor / Supplier Name</th>
                        <th class="text-center">Total PO Count</th>
                        <th class="text-end">Total PO Value (₹)</th>
                        <th class="text-end">Average PO Size (₹)</th>
                        <th class="text-center" style="width: 180px;">Spend Share</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandSpend = max($totalPoValue, 1);
                    @endphp
                    @forelse($vendorPoCounts as $vendor)
                        @php
                            $vCount = $vendor->purchase_orders_count ?? 0;
                            $vTotal = $vendor->purchase_orders_sum_grand_total ?? 0;
                            $vAvg = $vCount > 0 ? ($vTotal / $vCount) : 0;
                            $vShare = round(($vTotal / $grandSpend) * 100, 1);
                            $initial = strtoupper(substr($vendor->company_name ?? 'V', 0, 1));
                        @endphp
                        <tr class="vendor-row">
                            <td class="text-start">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="vendor-avatar">
                                        {{ $initial }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark vendor-name">{{ $vendor->company_name }}</div>
                                        @if($vendor->contact_person)
                                            <small class="text-muted"><i class="bi bi-person me-1"></i>{{ $vendor->contact_person }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-semibold">
                                    {{ $vCount }}
                                </span>
                            </td>
                            <td class="text-end fw-bold text-dark">
                                ₹{{ number_format($vTotal, 2) }}
                            </td>
                            <td class="text-end text-muted">
                                ₹{{ number_format($vAvg, 2) }}
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 6px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min($vShare, 100) }}%;"></div>
                                    </div>
                                    <span class="small fw-semibold text-muted" style="min-width: 38px;">{{ $vShare }}%</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('vendor.purchase_orders', $vendor->id) }}" 
                                   class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1 shadow-sm">
                                    <i class="bi bi-eye"></i>
                                    <span>View POs</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No vendors found.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-light fw-bold">
                        <td class="text-start text-dark">Total Spend</td>
                        <td class="text-center text-primary">{{ $totalPO }} Orders</td>
                        <td class="text-end text-primary">₹{{ number_format($totalPoValue ?? 0, 2) }}</td>
                        <td class="text-end text-muted">—</td>
                        <td class="text-center text-muted">100%</td>
                        <td class="text-center">—</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Section 7: Recent Purchase Orders Stream --}}
    @if(isset($recentPOs) && $recentPOs->count() > 0)
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0">Recent Purchase Orders</h5>
                <small class="text-muted">Latest procurement records and status updates</small>
            </div>
            <a href="{{ route('purchase.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 py-1 text-primary fw-medium">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 custom-purchase-table">
                <thead>
                    <tr>
                        <th class="text-start">PO Number</th>
                        <th class="text-start">Vendor / Supplier</th>
                        <th class="text-start">Site / Customer</th>
                        <th class="text-center">Date</th>
                        <th class="text-end">Grand Total (₹)</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentPOs as $po)
                    <tr>
                        <td class="text-start">
                            <span class="fw-bold text-primary">#{{ $po->po_no }}</span>
                        </td>
                        <td class="text-start">
                            <span class="fw-medium text-dark">{{ $po->vendor->company_name ?? ($po->contractor_name ?? '—') }}</span>
                        </td>
                        <td class="text-start text-muted">
                            {{ $po->customer->client_name ?? '—' }}
                        </td>
                        <td class="text-center text-muted small">
                            {{ $po->po_date ? \Carbon\Carbon::parse($po->po_date)->format('d M Y') : $po->created_at->format('d M Y') }}
                        </td>
                        <td class="text-end fw-bold text-dark">
                            ₹{{ number_format($po->grand_total, 2) }}
                        </td>
                        <td class="text-center">
                            @if(strtolower($po->status) === 'approved')
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 fw-semibold">Approved</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1 fw-semibold">{{ $po->status ?? 'Pending' }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('purchase.view', $po->id) }}" class="btn btn-light border btn-sm text-secondary" title="View PO Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('purchase.pdf', $po->id) }}" class="btn btn-light border btn-sm text-danger" title="Download PDF" target="_blank">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

{{-- MODAL: Custom PO Report & Export Generator --}}
<div class="modal fade" id="customPoReportModal" tabindex="-1" aria-labelledby="customPoReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="export-tile-icon bg-danger-subtle text-danger">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark" id="customPoReportModalLabel">Export Purchase Orders</h5>
                        <p class="text-muted small mb-0">Generate custom PO statement by date range, vendor, or format</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('purchase.report.custom') }}" method="POST">
                @csrf
                <div class="modal-body py-3">

                    {{-- Quick Date Range Presets --}}
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold small mb-1">Quick Date Presets</label>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 po-preset-btn" data-preset="today">Today</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 po-preset-btn" data-preset="last7">Last 7 Days</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 po-preset-btn active" data-preset="thisMonth">This Month</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 po-preset-btn" data-preset="lastMonth">Last Month</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 po-preset-btn" data-preset="thisYear">This Year</button>
                        </div>
                    </div>

                    {{-- Date Pickers --}}
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary fw-semibold small mb-1">From Date</label>
                            <input type="date" name="from_date" id="poModalFromDate" class="form-control rounded-3 py-2 px-3" required
                                   value="{{ now()->startOfMonth()->format('Y-m-d') }}" style="font-size: 14px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary fw-semibold small mb-1">To Date</label>
                            <input type="date" name="to_date" id="poModalToDate" class="form-control rounded-3 py-2 px-3" required
                                   value="{{ now()->format('Y-m-d') }}" style="font-size: 14px;">
                        </div>
                    </div>

                    {{-- Vendor Filter --}}
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold small mb-1">Filter by Vendor / Supplier</label>
                        <select name="vendor_id" class="form-select rounded-3 py-2 px-3" style="font-size: 14px;">
                            <option value="all" selected>All Vendors (Consolidated)</option>
                            @if(isset($allVendors))
                                @foreach($allVendors as $v)
                                    <option value="{{ $v->id }}">{{ $v->company_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Status & Format --}}
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label text-secondary fw-semibold small mb-1">PO Status</label>
                            <select name="status" class="form-select rounded-3 py-2 px-3" style="font-size: 14px;">
                                <option value="all" selected>All Statuses</option>
                                <option value="Approved">Approved Only</option>
                                <option value="Pending">Pending Only</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary fw-semibold small mb-1">Export Format</label>
                            <select name="export_format" class="form-select rounded-3 py-2 px-3" style="font-size: 14px;">
                                <option value="pdf" selected>PDF Document</option>
                                <option value="csv">CSV (Excel)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3 py-2 text-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-3 px-4 py-2 d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-download"></i> Generate Export
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scoped Styles --}}
<style>
.purchase-dashboard-container {
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

.vendor-avatar {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: #f1f5f9;
    color: #4f46e5;
    font-weight: 700;
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* Quick Export Tiles */
.export-tile {
    background: #ffffff;
    border: 1px solid #edf2f7;
    border-radius: 16px;
    padding: 16px 18px;
    transition: all 0.25s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
}

.export-tile:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px -5px rgba(15, 23, 42, 0.08);
    border-color: #cbd5e1;
}

.export-tile-custom {
    cursor: pointer;
    background: #f8fafc;
    border-style: dashed;
}

.export-tile-custom:hover {
    background: #f1f5f9;
}

.export-tile-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.month-bullet {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #cbd5e1;
}

.month-bullet.current {
    background-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.2);
}

.po-preset-btn.active {
    background-color: #eef2ff !important;
    color: #4f46e5 !important;
    border-color: #c7d2fe !important;
    font-weight: 600;
}

/* Custom Table */
.custom-purchase-table thead th {
    background-color: #f8fafc;
    color: #475569;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    border-bottom: 1px solid #e2e8f0;
    padding: 12px 14px;
}

.custom-purchase-table tbody td {
    padding: 12px 14px;
    font-size: 13.5px;
    border-bottom: 1px solid #f1f5f9;
}

.custom-purchase-table tfoot td {
    padding: 14px 14px;
    font-size: 13.5px;
    border-top: 2px solid #cbd5e1;
}
</style>

{{-- Chart & Script Logic --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // 1. Prepare Top Vendors Data for Chart
    @php
        $topVendors = $vendorPoCounts->take(7);
        $vendorNames = $topVendors->pluck('company_name')->toArray();
        $vendorValues = $topVendors->pluck('purchase_orders_sum_grand_total')->map(function($v) { return (float)($v ?? 0); })->toArray();
    @endphp

    const vNames = {!! json_encode($vendorNames) !!};
    const vValues = {!! json_encode($vendorValues) !!};

    const vendorChartCtx = document.getElementById('vendorSpendChart');
    if (vendorChartCtx && vNames.length > 0) {
        new Chart(vendorChartCtx, {
            type: 'bar',
            data: {
                labels: vNames,
                datasets: [{
                    label: 'Procurement Spend (₹)',
                    data: vValues,
                    backgroundColor: 'rgba(79, 70, 229, 0.8)',
                    hoverBackgroundColor: 'rgba(79, 70, 229, 1)',
                    borderRadius: 8,
                    barThickness: 22
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        callbacks: {
                            label: function (ctx) {
                                return ' Spend: ₹' + Number(ctx.parsed.y).toLocaleString('en-IN', { maximumFractionDigits: 2 });
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(226, 232, 240, 0.6)' },
                        ticks: {
                            callback: function (val) {
                                if (val >= 100000) return '₹' + (val / 100000).toFixed(1) + 'L';
                                if (val >= 1000) return '₹' + (val / 1000).toFixed(0) + 'k';
                                return '₹' + val;
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 11 },
                            callback: function (val, index) {
                                let label = this.getLabelForValue(val);
                                return label.length > 15 ? label.substring(0, 15) + '...' : label;
                            }
                        }
                    }
                }
            }
        });
    }

    // 2. PO Status Doughnut Chart
    const statusCtx = document.getElementById('poStatusDoughnutChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending'],
                datasets: [{
                    data: [{{ $approvedPO }}, {{ $pendingPO }}],
                    backgroundColor: ['#10b981', '#f59e0b'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        callbacks: {
                            label: function (ctx) {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const val = ctx.parsed;
                                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return ` ${ctx.label}: ${val} orders (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // 3. Live Vendor Table Search Filter
    const searchInput = document.getElementById('vendorSearchInput');
    const tableRows = document.querySelectorAll('#vendorSummaryTable .vendor-row');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            tableRows.forEach(row => {
                const nameEl = row.querySelector('.vendor-name');
                const name = nameEl ? nameEl.textContent.toLowerCase() : '';
                if (name.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // 4. Modal Date Range Presets Handler
    const fromInput = document.getElementById('poModalFromDate');
    const toInput = document.getElementById('poModalToDate');
    const presetButtons = document.querySelectorAll('.po-preset-btn');

    function formatDate(d) {
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${day}`;
    }

    presetButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            presetButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const preset = this.getAttribute('data-preset');
            const now = new Date();
            let from = new Date();
            let to = new Date();

            if (preset === 'today') {
                from = now;
                to = now;
            } else if (preset === 'last7') {
                from.setDate(now.getDate() - 6);
                to = now;
            } else if (preset === 'thisMonth') {
                from = new Date(now.getFullYear(), now.getMonth(), 1);
                to = now;
            } else if (preset === 'lastMonth') {
                from = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                to = new Date(now.getFullYear(), now.getMonth(), 0);
            } else if (preset === 'thisYear') {
                from = new Date(now.getFullYear(), 0, 1);
                to = now;
            }

            if (fromInput) fromInput.value = formatDate(from);
            if (toInput) toInput.value = formatDate(to);
        });
    });

});
</script>
@endsection
