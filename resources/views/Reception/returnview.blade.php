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

@section('title', 'Return Voucher - ' . $return->return_no)

@section('content')
<div class="return-voucher-container py-3">

    {{-- Breadcrumb & Quick Actions --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 no-print">
        <div>
            <nav aria-label="breadcrumb" class="mb-1">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item">
                        <a href="{{ route('receptiondashboard') }}" class="text-decoration-none text-muted">
                            <i class="bi bi-speedometer2 me-1"></i>Reception
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('materialreturn.history') }}" class="text-decoration-none text-muted">
                            <i class="bi bi-box-arrow-in-left me-1"></i>Materials-In
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">
                        {{ $return->return_no }}
                    </li>
                </ol>
            </nav>
            <h4 class="mb-0 fw-bold text-dark">Material Return Voucher Receipt</h4>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('materialreturn.history') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-arrow-left"></i>
                <span>Return History</span>
            </a>

            <a href="{{ route('sitetimeline', $return->customer_id) }}" class="btn btn-light border rounded-3 px-3 py-2 text-primary d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-clock-history"></i>
                <span>Site Timeline</span>
            </a>

            <button type="button" onclick="window.print()" class="btn btn-success d-inline-flex align-items-center gap-2 rounded-3 px-3 py-2 shadow-sm text-white">
                <i class="bi bi-printer"></i>
                <span>Print Voucher</span>
            </button>
        </div>
    </div>

    {{-- Voucher Card Presentation --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white print-card mb-4">
        {{-- Header Strip --}}
        <div class="card-header bg-white border-bottom p-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 38px; width: auto;" onerror="this.style.display='none'">
                        <span class="fs-4 fw-bold text-dark">Amudha Decors</span>
                    </div>
                    <span class="text-muted small">Material Recovery & Warehouse Inward Receipt</span>
                </div>

                <div class="text-end">
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill font-monospace fw-bold fs-6">
                        {{ $return->return_no }}
                    </span>
                    <div class="text-muted small mt-1">
                        Return Date: <strong>{{ date('d-m-Y', strtotime($return->return_date)) }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            {{-- Site & Personnel Information Strip --}}
            <div class="row g-3 p-3 bg-light rounded-3 mb-4">
                <div class="col-md-4">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-building me-1 text-primary"></i> Project Site / Client
                    </small>
                    <span class="fw-bold text-dark fs-6">{{ $return->customer->client_name ?? 'N/A' }}</span>
                    @if(isset($return->customer->Location))
                        <div class="text-muted small">
                            <i class="bi bi-geo-alt text-danger"></i> {{ $return->customer->Location }}
                        </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-person me-1 text-info"></i> Returned By (Personnel)
                    </small>
                    <span class="fw-semibold text-dark">{{ $return->person_name ?: 'Site Representative' }}</span>
                    <div class="text-muted small">
                        Recorded By: <strong>{{ $return->user_name ?? 'Reception Staff' }}</strong>
                    </div>
                </div>

                <div class="col-md-4">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-truck me-1 text-success"></i> Transit & Freight
                    </small>
                    <span class="fw-semibold text-dark">{{ $return->transport_type ?: 'Standard Transport' }}</span>
                    @if($return->vehicle_no)
                        <span class="badge bg-white text-secondary border font-monospace ms-1">{{ $return->vehicle_no }}</span>
                    @endif
                    <div class="text-dark small fw-medium mt-1">
                        Freight Paid: <strong>₹ {{ number_format($return->transport_charge, 2) }}</strong>
                    </div>
                </div>

                @if($return->remarks)
                <div class="col-12 pt-2 border-top">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-chat-quote me-1 text-secondary"></i> Return Remarks & Notes:
                    </small>
                    <span class="text-dark">{{ $return->remarks }}</span>
                </div>
                @endif
            </div>

            {{-- Returned Materials Table --}}
            <h6 class="fw-bold text-secondary text-uppercase small mb-3 letter-spacing">
                <i class="bi bi-boxes text-success me-1"></i> Particulars of Returned Materials
            </h6>

            <div class="table-responsive rounded-3 border mb-4">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th class="ps-3" style="width: 5%;">#</th>
                            <th style="width: 45%;">Item Description</th>
                            <th style="width: 25%;">Remarks / Specifications</th>
                            <th style="width: 25%;" class="text-end pe-3">Quantity Returned</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($return->items as $idx => $item)
                        <tr>
                            <td class="ps-3 text-muted small fw-bold">{{ $idx + 1 }}</td>
                            <td>
                                <div class="fw-semibold text-dark">
                                    <i class="bi bi-box-seam me-2 text-success opacity-75"></i>
                                    {{ $item->dispatchItem->item ?? 'Item #' . $item->dispatch_item_id }}
                                </div>
                            </td>
                            <td class="text-muted small">
                                {{ $item->dispatchItem->description ?: '—' }}
                            </td>
                            <td class="text-end pe-3">
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill fw-bold" style="font-size: 0.85rem;">
                                    {{ rtrim(rtrim(number_format($item->return_quantity, 2), '0'), '.') }}
                                    {{ $item->dispatchItem->unit ?? 'Units' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Sign-off section for printed voucher --}}
            <div class="row pt-5 mt-4 text-center">
                <div class="col-6">
                    <div style="border-top: 1px dashed #cbd5e1; max-width: 220px; margin: 0 auto; padding-top: 8px;">
                        <small class="text-muted fw-semibold">Returned By / Driver Sign</small>
                    </div>
                </div>
                <div class="col-6">
                    <div style="border-top: 1px dashed #cbd5e1; max-width: 220px; margin: 0 auto; padding-top: 8px;">
                        <small class="text-muted fw-semibold">Warehouse / Reception In-Charge</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .return-voucher-container {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    .letter-spacing {
        letter-spacing: 0.5px;
    }

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
        .return-voucher-container {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        .print-card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection
