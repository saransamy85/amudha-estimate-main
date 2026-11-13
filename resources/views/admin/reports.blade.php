@extends('layouts.adminbase')

@section('title', 'Reports & Analytics')

@section('content')
<div class="reports-container py-3">

    {{-- Top Header & Action Row --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="header-icon-box">
                    <i class="bi bi-bar-chart-line-fill text-primary"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Analytics & Business Reports</h4>
                    <span class="text-muted small">Real-time performance metrics, sales pipeline, and exportable business statements for {{ now()->year }}</span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2 rounded-3 px-3 py-2 shadow-sm"
                    data-bs-toggle="modal" data-bs-target="#customReportModal">
                <i class="bi bi-sliders2"></i>
                <span class="fw-medium">Generate Custom Report</span>
            </button>
            <button type="button" class="btn btn-light border rounded-3 px-3 py-2 text-secondary" onclick="window.print()" title="Print Summary">
                <i class="bi bi-printer"></i>
            </button>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Section 1: Quick Export Hub (Download Statements & PDF Reports) --}}
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-uppercase text-muted fw-bold mb-0 small" style="letter-spacing: 0.8px;">
                <i class="bi bi-file-earmark-arrow-down me-1 text-primary"></i> Downloadable Statements & PDF Exports
            </h6>
            <a href="{{ route('monthlyreport') }}" class="text-decoration-none small text-primary fw-semibold">
                More Custom Options <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-3">
            {{-- 1. Daily Activity Report --}}
            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <a href="{{ route('dailyreport') }}" class="export-tile text-decoration-none d-block h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="export-tile-icon bg-danger-subtle text-danger">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <span class="badge bg-danger-subtle text-danger rounded-pill fw-semibold" style="font-size: 10px;">Daily</span>
                    </div>
                    <div class="fw-bold text-dark fs-6 mb-1">Daily Activity</div>
                    <div class="text-muted small mb-2" style="font-size: 11px;">Today's operational logs & updates</div>
                    <div class="export-tile-action text-danger fw-semibold small d-flex align-items-center gap-1">
                        <span>Download PDF</span>
                        <i class="bi bi-arrow-down-short fs-6"></i>
                    </div>
                </a>
            </div>

            {{-- 2. Weekly Performance Report --}}
            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <a href="{{ route('weeklyreport') }}" class="export-tile text-decoration-none d-block h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="export-tile-icon bg-warning-subtle text-warning">
                            <i class="bi bi-calendar-week"></i>
                        </div>
                        <span class="badge bg-warning-subtle text-warning rounded-pill fw-semibold" style="font-size: 10px;">7 Days</span>
                    </div>
                    <div class="fw-bold text-dark fs-6 mb-1">Weekly Report</div>
                    <div class="text-muted small mb-2" style="font-size: 11px;">Past 7 days consolidated report</div>
                    <div class="export-tile-action text-warning fw-semibold small d-flex align-items-center gap-1">
                        <span>Download PDF</span>
                        <i class="bi bi-arrow-down-short fs-6"></i>
                    </div>
                </a>
            </div>

            {{-- 3. Monthly Estimates Report --}}
            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <a href="{{ route('estimatemonthlyreportpdf') }}" class="export-tile text-decoration-none d-block h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="export-tile-icon bg-primary-subtle text-primary">
                            <i class="bi bi-file-earmark-spreadsheet"></i>
                        </div>
                        <span class="badge bg-primary-subtle text-primary rounded-pill fw-semibold" style="font-size: 10px;">Monthly</span>
                    </div>
                    <div class="fw-bold text-dark fs-6 mb-1">Estimate Report</div>
                    <div class="text-muted small mb-2" style="font-size: 11px;">Estimates with quoted values</div>
                    <div class="export-tile-action text-primary fw-semibold small d-flex align-items-center gap-1">
                        <span>Download PDF</span>
                        <i class="bi bi-arrow-down-short fs-6"></i>
                    </div>
                </a>
            </div>

            {{-- 4. Monthly Customer Report --}}
            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <a href="{{ route('customermonthlyreportpdf') }}" class="export-tile text-decoration-none d-block h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="export-tile-icon bg-success-subtle text-success">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success rounded-pill fw-semibold" style="font-size: 10px;">Monthly</span>
                    </div>
                    <div class="fw-bold text-dark fs-6 mb-1">Customer Report</div>
                    <div class="text-muted small mb-2" style="font-size: 11px;">Closed client conversions & values</div>
                    <div class="export-tile-action text-success fw-semibold small d-flex align-items-center gap-1">
                        <span>Download PDF</span>
                        <i class="bi bi-arrow-down-short fs-6"></i>
                    </div>
                </a>
            </div>

            {{-- 5. Lead Source Report --}}
            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="export-tile text-decoration-none d-block h-100 export-tile-custom" 
                     role="button" data-bs-toggle="modal" data-bs-target="#customReportModal" data-report-type="source">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="export-tile-icon bg-info-subtle text-info">
                            <i class="bi bi-funnel-fill"></i>
                        </div>
                        <span class="badge bg-info-subtle text-info rounded-pill fw-semibold" style="font-size: 10px;">Custom Date</span>
                    </div>
                    <div class="fw-bold text-dark fs-6 mb-1">Source Breakdown</div>
                    <div class="text-muted small mb-2" style="font-size: 11px;">Marketing & lead channels by date</div>
                    <div class="export-tile-action text-info fw-semibold small d-flex align-items-center gap-1">
                        <span>Select Date & Export</span>
                        <i class="bi bi-chevron-right fs-6"></i>
                    </div>
                </div>
            </div>

            {{-- 6. Custom Generator Modal Trigger --}}
            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="export-tile text-decoration-none d-block h-100 export-tile-custom" 
                     role="button" data-bs-toggle="modal" data-bs-target="#customReportModal">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="export-tile-icon bg-secondary-subtle text-secondary">
                            <i class="bi bi-sliders"></i>
                        </div>
                        <span class="badge bg-secondary-subtle text-secondary rounded-pill fw-semibold" style="font-size: 10px;">Custom</span>
                    </div>
                    <div class="fw-bold text-dark fs-6 mb-1">Custom Statement</div>
                    <div class="text-muted small mb-2" style="font-size: 11px;">Filter by date range & type</div>
                    <div class="export-tile-action text-primary fw-semibold small d-flex align-items-center gap-1">
                        <span>Select Filter</span>
                        <i class="bi bi-chevron-right fs-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 2: Key Metric KPI Cards (Operational Activity) --}}
    <div class="mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h6 class="text-uppercase text-muted fw-bold mb-0 small" style="letter-spacing: 0.8px;">
                    <i class="bi bi-activity me-1 text-primary"></i> Operational Activity & Key Metrics
                </h6>
            </div>
            {{-- Period Filter Switch (Today vs This Month) --}}
            <div class="btn-group btn-group-sm rounded-3 p-1 bg-white border shadow-sm" role="group">
                <button type="button" class="btn btn-sm rounded-2 active kpi-period-btn fw-medium px-3" id="btnKpiToday" data-period="today">
                    <i class="bi bi-calendar-event me-1"></i> Today
                </button>
                <button type="button" class="btn btn-sm rounded-2 kpi-period-btn fw-medium text-secondary px-3" id="btnKpiMonth" data-period="month">
                    <i class="bi bi-calendar3 me-1"></i> This Month ({{ now()->format('F') }})
                </button>
            </div>
        </div>

        <div class="row g-3">
            {{-- 1. Leads Card --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-lead">
                    <div class="icon-box">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold" id="labelCardLead">Today's Leads</h6>
                            <span class="modern-badge bg-info-subtle text-info border border-info-subtle" id="badgeCardLead">Today</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val" id="valCardLead" 
                            data-target="{{ $todayLeads }}" 
                            data-val-today="{{ $todayLeads }}" 
                            data-val-month="{{ $monthLeads ?? 0 }}">{{ $todayLeads }}</h2>
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <small class="text-muted" id="subCardLead">Inquiries received today</small>
                            <span class="badge bg-light text-primary border" style="font-size: 11px;" title="Current Month Total">
                                {{ $monthLeads ?? 0 }} in {{ now()->format('M') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 2. Estimates Card --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-estimate">
                    <div class="icon-box">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold" id="labelCardEstimate">Today's Estimates</h6>
                            <span class="modern-badge bg-primary-subtle text-primary border border-primary-subtle" id="badgeCardEstimate">Generated</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val" id="valCardEstimate" 
                            data-target="{{ $todayEstimates }}" 
                            data-val-today="{{ $todayEstimates }}" 
                            data-val-month="{{ $monthEstimates ?? 0 }}">{{ $todayEstimates }}</h2>
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <small class="text-muted" id="subCardEstimate">Quotes created today</small>
                            <span class="badge bg-light text-primary border" style="font-size: 11px;" title="Current Month Total">
                                {{ $monthEstimates ?? 0 }} in {{ now()->format('M') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 3. Feedbacks Card --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-today">
                    <div class="icon-box">
                        <i class="bi bi-chat-square-dots-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold" id="labelCardFeedback">Feedback Updated</h6>
                            <span class="modern-badge bg-secondary-subtle text-secondary border border-secondary-subtle" id="badgeCardFeedback">Follow-ups</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val" id="valCardFeedback" 
                            data-target="{{ $todayFeedbacks }}" 
                            data-val-today="{{ $todayFeedbacks }}" 
                            data-val-month="{{ $monthFeedbacks ?? 0 }}">{{ $todayFeedbacks }}</h2>
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <small class="text-muted" id="subCardFeedback">Customer notes today</small>
                            <span class="badge bg-light text-secondary border" style="font-size: 11px;" title="Current Month Total">
                                {{ $monthFeedbacks ?? 0 }} in {{ now()->format('M') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 4. Customers Converted Card --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-converted">
                    <div class="icon-box">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold" id="labelCardCustomer">Customers Added</h6>
                            <span class="modern-badge bg-success-subtle text-success border border-success-subtle" id="badgeCardCustomer">Converted</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val" id="valCardCustomer" 
                            data-target="{{ $todayCustomers }}" 
                            data-val-today="{{ $todayCustomers }}" 
                            data-val-month="{{ $monthCustomers ?? 0 }}">{{ $todayCustomers }}</h2>
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <small class="text-muted" id="subCardCustomer">Deals closed today</small>
                            <span class="badge bg-light text-success border" style="font-size: 11px;" title="Current Month Total">
                                {{ $monthCustomers ?? 0 }} in {{ now()->format('M') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 3: Financial & Pipeline Highlights (Year-to-Date Banner) --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="highlight-stat-card bg-white p-3 rounded-4 shadow-sm border border-light">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Total Quoted Pipeline ({{ now()->year }})</span>
                    <div class="highlight-icon bg-primary-subtle text-primary">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-1">₹{{ number_format($ytdEstimateValue ?? 0, 2) }}</h4>
                <div class="d-flex align-items-center gap-1 small text-muted">
                    <span class="badge bg-primary-subtle text-primary">{{ number_format($ytdEstimates ?? 0) }} Quotes</span>
                    <span>total estimates value</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="highlight-stat-card bg-white p-3 rounded-4 shadow-sm border border-light">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Total Converted Revenue</span>
                    <div class="highlight-icon bg-success-subtle text-success">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-success mb-1">₹{{ number_format($ytdCustomerValue ?? 0, 2) }}</h4>
                <div class="d-flex align-items-center gap-1 small text-muted">
                    <span class="badge bg-success-subtle text-success">{{ number_format($ytdCustomers ?? 0) }} Orders</span>
                    <span>confirmed business value</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="highlight-stat-card bg-white p-3 rounded-4 shadow-sm border border-light">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Total Leads ({{ now()->year }})</span>
                    <div class="highlight-icon bg-info-subtle text-info">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-1">{{ number_format($ytdLeads ?? 0) }}</h4>
                <div class="d-flex align-items-center gap-1 small text-muted">
                    <span class="badge bg-info-subtle text-info">{{ $lc ?? 0 }} Cumulative</span>
                    <span>inquiries registered</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="highlight-stat-card bg-white p-3 rounded-4 shadow-sm border border-light">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Lead Conversion Ratio</span>
                    <div class="highlight-icon bg-warning-subtle text-warning">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-1">{{ $ytdConversionRate ?? 0 }}%</h4>
                <div class="d-flex align-items-center gap-1 small text-muted">
                    <div class="progress flex-grow-1" style="height: 6px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ min($ytdConversionRate ?? 0, 100) }}%;"></div>
                    </div>
                    <span class="fw-semibold text-dark">{{ $ytdCustomers ?? 0 }}/{{ $ytdLeads ?? 1 }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 4: Interactive Charts (Monthly Trends & Sources) --}}
    <div class="row g-4 mb-4">
        {{-- Chart 1: Monthly Performance Trend --}}
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Monthly Business Trends ({{ now()->year }})</h5>
                        <small class="text-muted">Compare incoming leads, estimates quoted, and customer conversions</small>
                    </div>

                    {{-- Chart Dataset Selector Tabs --}}
                    <div class="btn-group btn-group-sm rounded-3 p-1 bg-light border" role="group">
                        <button type="button" class="btn btn-sm rounded-2 active chart-toggle-btn fw-medium" id="btnChartVolume">
                            Volume (Counts)
                        </button>
                        <button type="button" class="btn btn-sm rounded-2 chart-toggle-btn fw-medium text-secondary" id="btnChartFinancial">
                            Values (₹)
                        </button>
                    </div>
                </div>

                <div class="chart-container position-relative" style="height: 320px; width: 100%;">
                    <canvas id="monthlyTrendChart"></canvas>
                </div>

                {{-- Chart Bottom Stats Legends --}}
                <div class="d-flex flex-wrap justify-content-around align-items-center pt-3 mt-3 border-top gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="legend-dot" style="background-color: #0ea5e9;"></span>
                        <span class="text-muted small">Leads: <strong class="text-dark">{{ $ytdLeads ?? 0 }}</strong></span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="legend-dot" style="background-color: #4f46e5;"></span>
                        <span class="text-muted small">Estimates: <strong class="text-dark">{{ $ytdEstimates ?? 0 }}</strong></span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="legend-dot" style="background-color: #10b981;"></span>
                        <span class="text-muted small">Customers: <strong class="text-dark">{{ $ytdCustomers ?? 0 }}</strong></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart 2: Lead Acquisition Sources --}}
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Lead Acquisition</h5>
                        <small class="text-muted">Marketing channel distribution</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-light border rounded-3 p-1 px-2 text-secondary" data-bs-toggle="modal" data-bs-target="#customReportModal" data-report-type="source" title="Custom Date Source Report PDF">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </button>
                </div>

                <div class="chart-container position-relative mb-3" style="height: 220px;">
                    <canvas id="sourceDoughnutChart"></canvas>
                </div>

                {{-- Source Breakdown List --}}
                <div class="source-list mt-auto pt-2 border-top">
                    @php
                        $sourceColors = [
                            'Website' => '#2563eb',
                            'Google Ads' => '#f97316',
                            'Phone Call' => '#84cc16',
                            'WhatsApp' => '#10b981',
                            'Reference' => '#9333ea',
                            'Showroom-walk-in' => '#f59e0b',
                            'Old Database' => '#64748b',
                        ];
                        $totalSourceLeads = max($lsc->sum(), 1);
                    @endphp

                    <div class="d-flex flex-column gap-2" style="max-height: 160px; overflow-y: auto;">
                        @forelse($lsc as $sourceName => $count)
                            @php
                                $percent = round(($count / $totalSourceLeads) * 100, 1);
                                $color = $sourceColors[$sourceName] ?? '#0d6efd';
                            @endphp
                            <div class="d-flex align-items-center justify-content-between py-1">
                                <div class="d-flex align-items-center gap-2" style="max-width: 60%;">
                                    <span class="legend-dot" style="background-color: {{ $color }};"></span>
                                    <span class="text-dark small fw-medium text-truncate" title="{{ $sourceName }}">{{ $sourceName }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-light text-dark border small fw-normal">{{ $count }}</span>
                                    <span class="text-muted small" style="min-width: 40px; text-align: right;">{{ $percent }}%</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted small py-3">No source data recorded</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 5: Monthly Performance Table (Consolidated Statement) --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0">Monthly Performance Breakdown ({{ now()->year }})</h5>
                <small class="text-muted">Consolidated overview of inquiries, estimates quoted, deal values, and customer conversions</small>
            </div>

            {{-- Tab pills for views --}}
            <ul class="nav nav-pills custom-table-pills" id="reportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-consolidated-btn" data-bs-toggle="pill" data-bs-target="#tab-consolidated" type="button" role="tab">
                        <i class="bi bi-grid-1x2 me-1"></i> All-in-One Statement
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-estimates-btn" data-bs-toggle="pill" data-bs-target="#tab-estimates" type="button" role="tab">
                        <i class="bi bi-file-earmark-text me-1"></i> Estimates
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-customers-btn" data-bs-toggle="pill" data-bs-target="#tab-customers" type="button" role="tab">
                        <i class="bi bi-people me-1"></i> Converted Customers
                    </button>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="reportTabsContent">
            {{-- TAB 1: Consolidated Performance --}}
            <div class="tab-pane fade show active" id="tab-consolidated" role="tabpanel">
                @php
                    $leadsByMonth = $monthlyLeads->keyBy('month');
                    $estimatesByMonth = $monthlyEstimates->keyBy('month');
                    $customersByMonth = $monthlyCustomers->keyBy('month');

                    // Filter to months up to current month or active months
                    $activeMonths = collect(range(1, 12))->filter(function($m) use ($leadsByMonth, $estimatesByMonth, $customersByMonth) {
                        return $m <= now()->month || isset($leadsByMonth[$m]) || isset($estimatesByMonth[$m]) || isset($customersByMonth[$m]);
                    });
                @endphp

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 custom-report-table">
                        <thead>
                            <tr>
                                <th class="text-start">Month</th>
                                <th class="text-center">New Leads</th>
                                <th class="text-center">Estimates Created</th>
                                <th class="text-end">Pipeline Value (₹)</th>
                                <th class="text-center">Customers Converted</th>
                                <th class="text-end">Realized Revenue (₹)</th>
                                <th class="text-center">Conv. Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeMonths as $m)
                                @php
                                    $monthName = \Carbon\Carbon::create()->month($m)->format('F');
                                    $lTotal = $leadsByMonth->has($m) ? $leadsByMonth[$m]->total : 0;
                                    $eTotal = $estimatesByMonth->has($m) ? $estimatesByMonth[$m]->total : 0;
                                    $eValue = $estimatesByMonth->has($m) ? $estimatesByMonth[$m]->value : 0;
                                    $cTotal = $customersByMonth->has($m) ? $customersByMonth[$m]->total : 0;
                                    $cValue = $customersByMonth->has($m) ? $customersByMonth[$m]->value : 0;
                                    $mRate = $lTotal > 0 ? round(($cTotal / $lTotal) * 100, 1) : 0;
                                @endphp
                                <tr>
                                    <td class="text-start">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="month-bullet {{ $m == now()->month ? 'current' : '' }}"></div>
                                            <span class="fw-semibold text-dark">{{ $monthName }}</span>
                                            @if($m == now()->month)
                                                <span class="badge bg-primary-subtle text-primary" style="font-size: 10px;">Current</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-2 py-1 fw-semibold">{{ $lTotal }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-2 py-1 fw-semibold">{{ $eTotal }}</span>
                                    </td>
                                    <td class="text-end fw-semibold text-secondary">
                                        {{ $eValue > 0 ? '₹' . number_format($eValue, 2) : '—' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success px-2 py-1 fw-semibold">{{ $cTotal }}</span>
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        {{ $cValue > 0 ? '₹' . number_format($cValue, 2) : '—' }}
                                    </td>
                                    <td class="text-center">
                                        @if($mRate > 0)
                                            <span class="badge bg-info-subtle text-info rounded-pill px-2 py-1 fw-semibold">{{ $mRate }}%</span>
                                        @else
                                            <span class="text-muted small">0%</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No monthly records available for {{ now()->year }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="table-light fw-bold border-top-2">
                                <td class="text-start text-dark">Year-to-Date Total</td>
                                <td class="text-center text-primary">{{ number_format($ytdLeads ?? 0) }}</td>
                                <td class="text-center text-primary">{{ number_format($ytdEstimates ?? 0) }}</td>
                                <td class="text-end text-primary">₹{{ number_format($ytdEstimateValue ?? 0, 2) }}</td>
                                <td class="text-center text-success">{{ number_format($ytdCustomers ?? 0) }}</td>
                                <td class="text-end text-success">₹{{ number_format($ytdCustomerValue ?? 0, 2) }}</td>
                                <td class="text-center text-info">{{ $ytdConversionRate ?? 0 }}%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- TAB 2: Estimates Breakdown --}}
            <div class="tab-pane fade" id="tab-estimates" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 custom-report-table">
                        <thead>
                            <tr>
                                <th class="text-start">Month</th>
                                <th class="text-center">Estimates Created</th>
                                <th class="text-end">Total Quoted Value (₹)</th>
                                <th class="text-end">Average Deal Size (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monthlyEstimates as $row)
                                @php
                                    $avg = $row->total > 0 ? ($row->value / $row->total) : 0;
                                @endphp
                                <tr>
                                    <td class="text-start fw-semibold text-dark">
                                        {{ \Carbon\Carbon::create()->month($row->month)->format('F') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary px-3 py-1 fw-semibold">{{ $row->total }}</span>
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        ₹{{ number_format($row->value, 2) }}
                                    </td>
                                    <td class="text-end text-muted">
                                        ₹{{ number_format($avg, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No estimates recorded this year</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td class="text-start text-dark">Total</td>
                                <td class="text-center text-primary">{{ number_format($monthlyEstimates->sum('total')) }}</td>
                                <td class="text-end text-primary">₹{{ number_format($monthlyEstimates->sum('value'), 2) }}</td>
                                <td class="text-end text-muted">—</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- TAB 3: Converted Customers Breakdown --}}
            <div class="tab-pane fade" id="tab-customers" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 custom-report-table">
                        <thead>
                            <tr>
                                <th class="text-start">Month</th>
                                <th class="text-center">Customers Converted</th>
                                <th class="text-end">Total Realized Value (₹)</th>
                                <th class="text-end">Average Revenue per Customer (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monthlyCustomers as $row)
                                @php
                                    $avgC = $row->total > 0 ? ($row->value / $row->total) : 0;
                                @endphp
                                <tr>
                                    <td class="text-start fw-semibold text-dark">
                                        {{ \Carbon\Carbon::create()->month($row->month)->format('F') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success px-3 py-1 fw-semibold">{{ $row->total }}</span>
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        ₹{{ number_format($row->value, 2) }}
                                    </td>
                                    <td class="text-end text-muted">
                                        ₹{{ number_format($avgC, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No converted customers recorded this year</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td class="text-start text-dark">Total</td>
                                <td class="text-center text-success">{{ number_format($monthlyCustomers->sum('total')) }}</td>
                                <td class="text-end text-success">₹{{ number_format($monthlyCustomers->sum('value'), 2) }}</td>
                                <td class="text-end text-muted">—</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- MODAL: Custom Report Generator --}}
<div class="modal fade" id="customReportModal" tabindex="-1" aria-labelledby="customReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="export-tile-icon bg-primary-subtle text-primary">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark" id="customReportModalLabel">Generate Custom Report</h5>
                        <p class="text-muted small mb-0">Select date range and statement category to export PDF</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('customreport.pdf') }}" method="POST" id="customReportForm">
                @csrf
                <div class="modal-body py-3">

                    {{-- Report Type --}}
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold small mb-1">Report Category</label>
                        <select name="report_type" id="modalReportType" class="form-select rounded-3 py-2 px-3" required style="font-size: 14px;">
                            <option value="business" selected>Business Summary Report (Complete Overview)</option>
                            <option value="source">Lead Source & Marketing Channels Report</option>
                            <option value="estimate">Estimate Statement (All Quoted Estimates)</option>
                            <option value="customer">Customer Conversion Statement (Confirmed Clients)</option>
                        </select>
                    </div>

                    {{-- Channel Scope Filter (Multiple Choice for Lead Source Report) --}}
                    <div class="mb-3" id="modalSourceFilterGroup" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label text-secondary fw-semibold small mb-0">
                                Acquisition Channel Scope <span class="text-primary fw-normal">(Multiple Choice)</span>
                            </label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none small text-primary" id="btnSelectAllSources">Select All</button>
                                <span class="text-muted small">&bull;</span>
                                <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none small text-secondary" id="btnClearAllSources">Clear</button>
                            </div>
                        </div>

                        <div class="p-2.5 rounded-3 border bg-light">
                            <div class="row g-2">
                                @php
                                    $sourceCheckboxes = [
                                        'Website' => ['icon' => 'bi-globe', 'color' => 'text-primary'],
                                        'WhatsApp' => ['icon' => 'bi-whatsapp', 'color' => 'text-success'],
                                        'Phone Call' => ['icon' => 'bi-telephone-fill', 'color' => 'text-info'],
                                        'Showroom-walk-in' => ['icon' => 'bi-shop', 'color' => 'text-warning'],
                                        'Reference' => ['icon' => 'bi-people-fill', 'color' => 'text-purple'],
                                        'Old Database' => ['icon' => 'bi-database-fill', 'color' => 'text-secondary'],
                                    ];
                                @endphp
                                @foreach($sourceCheckboxes as $sName => $sInfo)
                                    <div class="col-sm-6">
                                        <label class="channel-choice-chip d-flex align-items-center gap-2 p-2 rounded-2 border bg-white cursor-pointer w-100 m-0">
                                            <input class="form-check-input channel-source-cb m-0" type="checkbox" name="sources[]" value="{{ $sName }}" checked>
                                            <i class="bi {{ $sInfo['icon'] }} {{ $sInfo['color'] }}"></i>
                                            <span class="small fw-medium text-dark text-truncate">{{ $sName }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">
                            <i class="bi bi-info-circle me-1"></i>Select any combination of channels to include in your PDF statement.
                        </small>
                    </div>

                    {{-- Quick Date Range Presets --}}
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-semibold small mb-1">Quick Presets</label>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 preset-btn" data-preset="today">Today</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 preset-btn" data-preset="last7">Last 7 Days</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 preset-btn active" data-preset="thisMonth">This Month</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 preset-btn" data-preset="lastMonth">Last Month</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 preset-btn" data-preset="thisYear">This Year</button>
                        </div>
                    </div>

                    {{-- Date Pickers --}}
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label text-secondary fw-semibold small mb-1">From Date</label>
                            <input type="date" name="from_date" id="modalFromDate" class="form-control rounded-3 py-2 px-3" required
                                   value="{{ now()->startOfMonth()->format('Y-m-d') }}" style="font-size: 14px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary fw-semibold small mb-1">To Date</label>
                            <input type="date" name="to_date" id="modalToDate" class="form-control rounded-3 py-2 px-3" required
                                   value="{{ now()->format('Y-m-d') }}" style="font-size: 14px;">
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3 py-2 text-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-3 px-4 py-2 d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-file-earmark-pdf"></i> Download PDF Statement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scoped Styles --}}
<style>
.reports-container {
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

/* Highlight Stat Cards */
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

.chart-toggle-btn.active {
    background-color: #ffffff !important;
    color: #0d6efd !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
}

/* Custom Table */
.custom-table-pills .nav-link {
    color: #64748b;
    font-size: 13px;
    font-weight: 500;
    border-radius: 10px;
    padding: 6px 14px;
    transition: all 0.2s;
}

.custom-table-pills .nav-link.active {
    background-color: #eef2ff;
    color: #4f46e5;
    font-weight: 600;
}

.custom-report-table thead th {
    background-color: #f8fafc;
    color: #475569;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    border-bottom: 1px solid #e2e8f0;
    padding: 12px 14px;
}

.custom-report-table tbody td {
    padding: 12px 14px;
    font-size: 13.5px;
    border-bottom: 1px solid #f1f5f9;
}

.custom-report-table tfoot td {
    padding: 14px 14px;
    font-size: 13.5px;
    border-top: 2px solid #cbd5e1;
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

.preset-btn.active {
    background-color: #eef2ff !important;
    color: #4f46e5 !important;
    border-color: #c7d2fe !important;
    font-weight: 600;
}

.kpi-period-btn.active {
    background-color: #0d6efd !important;
    color: #ffffff !important;
    box-shadow: 0 2px 4px rgba(13, 110, 253, 0.25);
}

.channel-choice-chip {
    transition: all 0.15s ease;
    user-select: none;
    cursor: pointer;
}

.channel-choice-chip:hover {
    background-color: #f8fafc !important;
    border-color: #94a3b8 !important;
}

.text-purple {
    color: #9333ea !important;
}
</style>

{{-- Chart.js & Script Logic --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // 1. Chart Data from Controller
    const allLabels = {!! json_encode($allMonthLabels ?? ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']) !!};
    const leadSeries = {!! json_encode($leadSeries ?? []) !!};
    const estimateSeries = {!! json_encode($estimateSeries ?? []) !!};
    const customerSeries = {!! json_encode($customerSeries ?? []) !!};
    const estimateValueSeries = {!! json_encode($estimateValueSeries ?? []) !!};
    const customerValueSeries = {!! json_encode($customerValueSeries ?? []) !!};

    const sourceLabels = {!! json_encode($lsc ? $lsc->keys() : []) !!};
    const sourceValues = {!! json_encode($lsc ? $lsc->values() : []) !!};

    // Color palettes
    const palette = [
        '#2563eb', '#f97316', '#84cc16', '#10b981', '#9333ea', 
        '#f59e0b', '#06b6d4', '#64748b', '#ec4899', '#6366f1'
    ];

    // 2. Initialize Monthly Business Trends Chart
    const trendCtx = document.getElementById('monthlyTrendChart');
    let currentMode = 'volume'; // 'volume' or 'financial'
    let trendChart = null;

    if (trendCtx) {
        const volumeData = {
            labels: allLabels,
            datasets: [
                {
                    label: 'Leads',
                    data: leadSeries,
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14, 165, 233, 0.1)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#0ea5e9',
                    pointBorderWidth: 2
                },
                {
                    label: 'Estimates',
                    data: estimateSeries,
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.08)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2
                },
                {
                    label: 'Customers Converted',
                    data: customerSeries,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.08)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 2
                }
            ]
        };

        const financialData = {
            labels: allLabels,
            datasets: [
                {
                    type: 'bar',
                    label: 'Quoted Pipeline (₹)',
                    data: estimateValueSeries,
                    backgroundColor: 'rgba(79, 70, 229, 0.75)',
                    borderRadius: 6,
                    barThickness: 16
                },
                {
                    type: 'bar',
                    label: 'Closed Revenue (₹)',
                    data: customerValueSeries,
                    backgroundColor: 'rgba(16, 185, 129, 0.85)',
                    borderRadius: 6,
                    barThickness: 16
                }
            ]
        };

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8,
                        font: { size: 12, family: 'inherit' }
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 12, weight: '600' },
                    bodyFont: { size: 12 },
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function (context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            if (currentMode === 'financial') {
                                label += '₹' + Number(context.parsed.y).toLocaleString('en-IN', { maximumFractionDigits: 2 });
                            } else {
                                label += Number(context.parsed.y).toLocaleString();
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(226, 232, 240, 0.6)' },
                    ticks: {
                        precision: 0,
                        font: { size: 11 },
                        callback: function (val) {
                            if (currentMode === 'financial') {
                                if (val >= 100000) return '₹' + (val / 100000).toFixed(1) + 'L';
                                if (val >= 1000) return '₹' + (val / 1000).toFixed(0) + 'k';
                                return '₹' + val;
                            }
                            return val;
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            }
        };

        trendChart = new Chart(trendCtx, {
            type: 'line',
            data: volumeData,
            options: chartOptions
        });

        // Toggle volume vs financial
        const btnVolume = document.getElementById('btnChartVolume');
        const btnFinancial = document.getElementById('btnChartFinancial');

        if (btnVolume && btnFinancial) {
            btnVolume.addEventListener('click', function () {
                if (currentMode === 'volume') return;
                currentMode = 'volume';
                btnVolume.classList.add('active');
                btnVolume.classList.remove('text-secondary');
                btnFinancial.classList.remove('active');
                btnFinancial.classList.add('text-secondary');

                trendChart.config.type = 'line';
                trendChart.data = volumeData;
                trendChart.update();
            });

            btnFinancial.addEventListener('click', function () {
                if (currentMode === 'financial') return;
                currentMode = 'financial';
                btnFinancial.classList.add('active');
                btnFinancial.classList.remove('text-secondary');
                btnVolume.classList.remove('active');
                btnVolume.classList.add('text-secondary');

                trendChart.config.type = 'bar';
                trendChart.data = financialData;
                trendChart.update();
            });
        }
    }

    // 3. Initialize Lead Acquisition Source Doughnut Chart
    const sourceCtx = document.getElementById('sourceDoughnutChart');
    if (sourceCtx) {
        new Chart(sourceCtx, {
            type: 'doughnut',
            data: {
                labels: sourceLabels,
                datasets: [{
                    data: sourceValues,
                    backgroundColor: palette.slice(0, sourceLabels.length),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const val = context.parsed;
                                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return ` ${context.label}: ${val} (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // 4. Modal Date Range Presets Handler
    const fromInput = document.getElementById('modalFromDate');
    const toInput = document.getElementById('modalToDate');
    const presetButtons = document.querySelectorAll('.preset-btn');

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

            fromInput.value = formatDate(from);
            toInput.value = formatDate(to);
        });
    });

    // Dynamic Channel Filter visibility & smart routing
    const customReportForm = document.getElementById('customReportForm');
    const modalReportType = document.getElementById('modalReportType');
    const modalSourceFilterGroup = document.getElementById('modalSourceFilterGroup');

    function updateCustomReportFormState() {
        if (!modalReportType) return;
        const isSource = (modalReportType.value === 'source');
        if (modalSourceFilterGroup) {
            modalSourceFilterGroup.style.display = isSource ? 'block' : 'none';
        }
        // Always submit via POST to customreport.pdf (handled centrally by ReportController@customReportPdf)
        if (customReportForm) {
            customReportForm.method = 'POST';
            customReportForm.action = "{{ route('customreport.pdf') }}";
        }
    }

    if (modalReportType) {
        modalReportType.addEventListener('change', updateCustomReportFormState);
    }

    document.querySelectorAll('[data-bs-target="#customReportModal"]').forEach(trigger => {
        trigger.addEventListener('click', function () {
            const rType = this.getAttribute('data-report-type');
            if (rType && modalReportType) {
                modalReportType.value = rType;
                updateCustomReportFormState();
            }
        });
    });

    if (customReportForm) {
        customReportForm.addEventListener('submit', function () {
            updateCustomReportFormState();
        });
    }

    // Select All & Clear Channel Source Checkboxes
    const btnSelectAllSources = document.getElementById('btnSelectAllSources');
    const btnClearAllSources = document.getElementById('btnClearAllSources');
    const sourceCheckboxes = document.querySelectorAll('.channel-source-cb');

    if (btnSelectAllSources) {
        btnSelectAllSources.addEventListener('click', function(e) {
            e.preventDefault();
            sourceCheckboxes.forEach(cb => cb.checked = true);
        });
    }

    if (btnClearAllSources) {
        btnClearAllSources.addEventListener('click', function(e) {
            e.preventDefault();
            sourceCheckboxes.forEach(cb => cb.checked = false);
        });
    }

    // 5. Period Toggle for Activity Cards (Today vs This Month)
    const btnKpiToday = document.getElementById('btnKpiToday');
    const btnKpiMonth = document.getElementById('btnKpiMonth');

    if (btnKpiToday && btnKpiMonth) {
        function switchKpiPeriod(period) {
            const isMonth = (period === 'month');

            btnKpiToday.classList.toggle('active', !isMonth);
            btnKpiToday.classList.toggle('text-secondary', isMonth);
            btnKpiMonth.classList.toggle('active', isMonth);
            btnKpiMonth.classList.toggle('text-secondary', !isMonth);

            const configs = [
                {
                    id: 'valCardLead', labelId: 'labelCardLead', badgeId: 'badgeCardLead', subId: 'subCardLead',
                    todayLabel: "Today's Leads", monthLabel: "{{ now()->format('F') }} Leads",
                    todayBadge: "Today", monthBadge: "{{ now()->format('M') }}",
                    todaySub: "Inquiries received today", monthSub: "Inquiries in {{ now()->format('F') }}"
                },
                {
                    id: 'valCardEstimate', labelId: 'labelCardEstimate', badgeId: 'badgeCardEstimate', subId: 'subCardEstimate',
                    todayLabel: "Today's Estimates", monthLabel: "{{ now()->format('F') }} Estimates",
                    todayBadge: "Generated", monthBadge: "{{ now()->format('M') }}",
                    todaySub: "Quotes created today", monthSub: "Quotes in {{ now()->format('F') }}"
                },
                {
                    id: 'valCardFeedback', labelId: 'labelCardFeedback', badgeId: 'badgeCardFeedback', subId: 'subCardFeedback',
                    todayLabel: "Feedback Updated", monthLabel: "{{ now()->format('F') }} Feedbacks",
                    todayBadge: "Follow-ups", monthBadge: "{{ now()->format('M') }}",
                    todaySub: "Customer notes today", monthSub: "Customer notes in {{ now()->format('F') }}"
                },
                {
                    id: 'valCardCustomer', labelId: 'labelCardCustomer', badgeId: 'badgeCardCustomer', subId: 'subCardCustomer',
                    todayLabel: "Customers Added", monthLabel: "{{ now()->format('F') }} Customers",
                    todayBadge: "Converted", monthBadge: "{{ now()->format('M') }}",
                    todaySub: "Deals closed today", monthSub: "Deals closed in {{ now()->format('F') }}"
                }
            ];

            configs.forEach(cfg => {
                const el = document.getElementById(cfg.id);
                const label = document.getElementById(cfg.labelId);
                const badge = document.getElementById(cfg.badgeId);
                const sub = document.getElementById(cfg.subId);

                if (el) {
                    const target = isMonth ? el.getAttribute('data-val-month') : el.getAttribute('data-val-today');
                    el.setAttribute('data-target', target);
                    el.textContent = target;
                    el.classList.remove('pop');
                    void el.offsetWidth;
                    el.classList.add('pop');
                }
                if (label) label.textContent = isMonth ? cfg.monthLabel : cfg.todayLabel;
                if (badge) badge.textContent = isMonth ? cfg.monthBadge : cfg.todayBadge;
                if (sub) sub.textContent = isMonth ? cfg.monthSub : cfg.todaySub;
            });
        }

        btnKpiToday.addEventListener('click', () => switchKpiPeriod('today'));
        btnKpiMonth.addEventListener('click', () => switchKpiPeriod('month'));
    }

});
</script>
@endpush
@endsection
