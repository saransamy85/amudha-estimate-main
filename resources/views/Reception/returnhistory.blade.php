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

@section('title', 'Materials-In (Return History)')

@section('content')
<div class="return-history-container py-3">

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
                        Materials-In (Return History)
                    </li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon-box">
                    <i class="bi bi-box-arrow-in-left text-success"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Materials-In (Return History)</h4>
                    <span class="text-muted small">
                        Audit log of all surplus materials, tools, and equipment returned from project sites.
                    </span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('materialreturn') }}" class="btn btn-success d-inline-flex align-items-center gap-2 rounded-3 px-3 py-2 shadow-sm text-white">
                <i class="bi bi-plus-lg"></i>
                <span class="fw-medium">Record New Return</span>
            </a>

            <a href="{{ route('receptionmaterialitems') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-box-arrow-right text-primary"></i>
                <span>Materials-Out</span>
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

    {{-- Section: Material Returns Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success px-3 py-2 rounded-pill font-monospace shadow-2xs">
                    <i class="bi bi-receipt me-1"></i> LOGGED VOUCHERS
                </span>
                <span class="text-muted small">Total recorded return vouchers: <strong>{{ $returns->total() }}</strong></span>
            </div>

            <div class="input-group input-group-sm" style="max-width: 250px;">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" id="returnSearchInput" class="form-control bg-light border-start-0 ps-0" placeholder="Filter returns...">
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="returnsHistoryTable">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th class="ps-4" style="width: 5%;">#</th>
                            <th style="width: 18%;">Voucher No</th>
                            <th style="width: 27%;">Project Site / Location</th>
                            <th style="width: 15%;">Return Date</th>
                            <th style="width: 18%;">Personnel</th>
                            <th style="width: 10%;" class="text-center">Items</th>
                            <th style="width: 7%;" class="text-center pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $return)
                        <tr class="return-row">
                            <td class="ps-4 text-muted small fw-bold">
                                {{ ($returns->currentPage() - 1) * $returns->perPage() + $loop->iteration }}
                            </td>

                            <td>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-monospace fw-bold" style="font-size: 0.78rem;">
                                    {{ $return->return_no }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('sitetimeline', $return->customer_id) }}" class="fw-bold text-dark text-decoration-none site-link">
                                    {{ $return->customer->client_name ?? 'Site #' . $return->customer_id }}
                                </a>
                                @if(isset($return->customer->Location))
                                    <div class="text-muted small mt-1">
                                        <i class="bi bi-geo-alt text-danger"></i>
                                        {{ $return->customer->Location }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="fw-semibold text-dark">
                                    <i class="bi bi-calendar2-check text-success me-1"></i>
                                    {{ date('d M, Y', strtotime($return->return_date)) }}
                                </div>
                            </td>

                            <td>
                                <span class="text-dark fw-medium">{{ $return->person_name ?: '—' }}</span>
                                <div class="text-muted small">
                                    Recv: <strong>{{ $return->user_name ?? 'Receptionist' }}</strong>
                                </div>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-boxes me-1 text-success"></i>
                                    {{ $return->items->count() }} {{ Str::plural('item', $return->items->count()) }}
                                </span>
                            </td>

                            <td class="text-center pe-4">
                                <a href="{{ route('materialreturn.view', $return->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 shadow-2xs" title="View Full Return Voucher">
                                    <i class="bi bi-receipt me-1"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="p-4">
                                    <i class="bi bi-box-arrow-in-left display-4 text-muted opacity-50 mb-3 d-block"></i>
                                    <h6 class="fw-bold text-dark">No Material Returns Found</h6>
                                    <p class="text-muted small mb-3">No return receipts have been generated yet.</p>
                                    <a href="{{ route('materialreturn') }}" class="btn btn-sm btn-success text-white rounded-3 px-3">
                                        <i class="bi bi-plus-lg me-1"></i> Record First Return
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($returns->hasPages())
        <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">
                Showing {{ $returns->firstItem() }} to {{ $returns->lastItem() }} of {{ $returns->total() }} return vouchers
            </span>
            <div>
                {{ $returns->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .return-history-container {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    .header-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #ecfdf5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
    }

    .site-link:hover {
        color: #10b981 !important;
        text-decoration: underline !important;
    }

    .shadow-2xs {
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('returnSearchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const query = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('#returnsHistoryTable tbody tr.return-row');

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
