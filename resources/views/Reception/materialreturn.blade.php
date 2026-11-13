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

@section('title', 'Record Material Return')

@section('content')
<div class="material-return-container py-3">

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
                    <li class="breadcrumb-item">
                        <a href="{{ route('materialreturn.history') }}" class="text-decoration-none text-muted">
                            <i class="bi bi-box-arrow-in-left me-1"></i>Materials-In
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">
                        Record Return
                    </li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon-box">
                    <i class="bi bi-arrow-return-left text-success"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Record Material Return (Materials-In)</h4>
                    <span class="text-muted small">
                        Receive returned or surplus materials, tools, and consumables back from active project sites.
                    </span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('materialreturn.history') }}" class="btn btn-outline-success rounded-3 px-3 py-2 d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-clock-history"></i>
                <span class="fw-medium">Return History</span>
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

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-exclamation-octagon-fill fs-5"></i>
            <strong>Please resolve the following errors:</strong>
        </div>
        <ul class="mb-0 ps-3 small">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Section 1: Executive KPI Stat Cards --}}
    <div class="mb-4">
        <div class="row g-3">
            {{-- 1. Today's Returns --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-converted" style="animation-delay: 0.05s;">
                    <div class="icon-box">
                        <i class="bi bi-calendar2-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Today's Returns</h6>
                            <span class="modern-badge bg-success-subtle text-success border border-success-subtle">Today</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $todayReturnsCount ?? 0 }}">
                            {{ $todayReturnsCount ?? 0 }}
                        </h2>
                        <small class="text-muted">Vouchers logged today</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 2. Total Inward Returns --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-lead" style="animation-delay: 0.1s;">
                    <div class="icon-box">
                        <i class="bi bi-arrow-return-left"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Total Inward Returns</h6>
                            <span class="modern-badge bg-info-subtle text-info border border-info-subtle">Materials-In</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $totalReturnsCount ?? 0 }}">
                            {{ $totalReturnsCount ?? 0 }}
                        </h2>
                        <small class="text-muted">Return receipts recorded</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 3. Recovered Line Items --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-estimate" style="animation-delay: 0.15s;">
                    <div class="icon-box">
                        <i class="bi bi-boxes"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Recovered Items</h6>
                            <span class="modern-badge bg-primary-subtle text-primary border border-primary-subtle">Audit</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 counter-val text-dark" data-target="{{ $totalReturnedItemsCount ?? 0 }}">
                            {{ $totalReturnedItemsCount ?? 0 }}
                        </h2>
                        <small class="text-muted">Line items returned to warehouse</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>

            {{-- 4. Total Return Transport Freight --}}
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="modern-card card-customer" style="animation-delay: 0.2s;">
                    <div class="icon-box">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="text-muted mb-0 fw-semibold">Return Freight</h6>
                            <span class="modern-badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Logistics</span>
                        </div>
                        <h2 class="fw-bold mb-1 display-6 text-dark">
                            ₹ {{ number_format($totalReturnTransport ?? 0, 0) }}
                        </h2>
                        <small class="text-muted">Total transport charges for returns</small>
                    </div>
                    <div class="card-accent-bar"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 2: Material Return Voucher Form --}}
    <form method="POST" action="{{ route('materialreturn.store') }}" id="materialReturnForm" class="mb-5">
        @csrf

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success px-3 py-2 rounded-pill font-monospace shadow-2xs">
                        <i class="bi bi-receipt me-1"></i> RETURN VOUCHER
                    </span>
                    <h5 class="mb-0 fw-bold text-dark">Inward Material Return Voucher</h5>
                </div>
                <span class="text-muted small">
                    <i class="bi bi-person-check text-success me-1"></i> Receiving Staff: <strong>{{ Auth::user()->name }}</strong>
                </span>
            </div>

            <div class="card-body p-4">
                {{-- Part A: Project Site & Returning Person --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-secondary text-uppercase small mb-3 letter-spacing">
                        <i class="bi bi-building text-primary me-1"></i> 1. Project Site & Returning Personnel
                    </h6>

                    <div class="row g-3">
                        {{-- Site Selector --}}
                        <div class="col-md-5">
                            <label class="form-label fw-semibold text-dark">
                                Project Site <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-building"></i></span>
                                <select name="customer_id" id="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>-- Select Returning Project Site --</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->client_name }} — {{ $customer->Location ?: 'Location Unset' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                Selecting a site will automatically load its remaining dispatched material balances.
                            </small>
                        </div>

                        {{-- Returned By Person --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">
                                Returned By (Person Name) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control @error('person_name') is-invalid @enderror" name="person_name" placeholder="e.g. Site Supervisor / Driver Name" value="{{ old('person_name') }}" required>
                            </div>
                            <small class="text-muted mt-1 d-block">Name of the person delivering the materials back.</small>
                        </div>

                        {{-- Return Date --}}
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark">
                                Return Date <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" class="form-control @error('return_date') is-invalid @enderror" name="return_date" value="{{ old('return_date', date('Y-m-d')) }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="text-muted opacity-25 my-4">

                {{-- Part B: Transit & Freight Details --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-secondary text-uppercase small mb-3 letter-spacing">
                        <i class="bi bi-truck text-success me-1"></i> 2. Return Transit & Freight Details
                    </h6>

                    <div class="row g-3">
                        {{-- Vehicle No --}}
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark">
                                Vehicle Reg. No
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted font-monospace"><i class="bi bi-card-heading"></i></span>
                                <input type="text" class="form-control font-monospace text-uppercase" name="vehicle_no" placeholder="TN 38 AB 1234" value="{{ old('vehicle_no') }}">
                            </div>
                        </div>

                        {{-- Transport Type --}}
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark">
                                Transport Mode <span class="text-danger">*</span>
                            </label>
                            <select name="transport_type" class="form-select @error('transport_type') is-invalid @enderror" required>
                                <option value="Company Vehicle" {{ old('transport_type') == 'Company Vehicle' ? 'selected' : '' }}>Company Vehicle</option>
                                <option value="Customer Vehicle" {{ old('transport_type') == 'Customer Vehicle' ? 'selected' : '' }}>Customer Vehicle</option>
                                <option value="Porter" {{ old('transport_type') == 'Porter' ? 'selected' : '' }}>Porter</option>
                                <option value="Auto" {{ old('transport_type') == 'Auto' ? 'selected' : '' }}>Auto</option>
                                <option value="Abi Auto" {{ old('transport_type') == 'Abi Auto' ? 'selected' : '' }}>Abi Auto</option>
                                <option value="Pickup / Mini Truck" {{ old('transport_type') == 'Pickup / Mini Truck' ? 'selected' : '' }}>Pickup / Mini Truck</option>
                                <option value="Transport / Lorry" {{ old('transport_type') == 'Transport / Lorry' ? 'selected' : '' }}>Transport / Lorry</option>
                                <option value="Courier" {{ old('transport_type') == 'Courier' ? 'selected' : '' }}>Courier</option>
                                <option value="Rapido" {{ old('transport_type') == 'Rapido' ? 'selected' : '' }}>Rapido</option>
                                <option value="Other" {{ old('transport_type') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        {{-- Transport Charge --}}
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark">
                                Transport Freight (₹)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">₹</span>
                                <input type="number" step="0.01" min="0" value="{{ old('transport_charge', '0.00') }}" name="transport_charge" class="form-control">
                            </div>
                        </div>

                        {{-- Remarks / Notes --}}
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark">
                                Return Reason / Remarks
                            </label>
                            <input type="text" class="form-control" name="remarks" placeholder="e.g. Surplus recovery, Job completed" value="{{ old('remarks') }}">
                        </div>
                    </div>
                </div>

                <hr class="text-muted opacity-25 my-4">

                {{-- Part C: Dispatched Material Balances & Return Quantities (Dynamic AJAX Table) --}}
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div>
                            <h6 class="fw-bold text-secondary text-uppercase small mb-1 letter-spacing">
                                <i class="bi bi-arrow-return-left text-success me-1"></i> 3. Dispatched Items & Return Quantities
                            </h6>
                            <span class="text-muted small">Specify the exact quantities of materials returned for each dispatched line item.</span>
                        </div>

                        <div id="quickActionControls" class="d-none">
                            <button type="button" class="btn btn-sm btn-outline-success rounded-3 px-3 me-2" id="returnAllBtn">
                                <i class="bi bi-check-all me-1"></i> Fill All Balances
                            </button>
                            <button type="button" class="btn btn-sm btn-light border rounded-3 px-3 text-secondary" id="clearAllBtn">
                                <i class="bi bi-x-circle me-1"></i> Clear Quantities
                            </button>
                        </div>
                    </div>

                    {{-- Dynamic Container for AJAX Loaded Items --}}
                    <div id="materialTable">
                        <div class="p-5 text-center bg-light rounded-4 border border-dashed">
                            <i class="bi bi-arrow-return-left display-4 text-muted opacity-50 mb-3 d-block"></i>
                            <h6 class="fw-bold text-dark mb-1">Select a Project Site to Load Material Balances</h6>
                            <p class="text-muted small mb-0">Choose a site from the dropdown above to view all materials dispatched to it with their returnable balances.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Submit Footer --}}
            <div class="card-footer bg-light border-top py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <a href="{{ route('materialreturn.history') }}" class="btn btn-light border rounded-3 px-4 text-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn btn-success d-inline-flex align-items-center gap-2 rounded-3 px-4 py-2 shadow-sm text-white fw-semibold" id="submitReturnBtn" disabled>
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Save & Generate Return Voucher</span>
                </button>
            </div>
        </div>
    </form>

    {{-- Section 3: Recent Material Returns Activity Log --}}
    @if(isset($recentReturns) && $recentReturns->count() > 0)
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="p-2 rounded-3 bg-success-subtle text-success">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">Recent Inward Returns Log</h5>
                    <span class="text-muted small">Previously recorded material recovery receipts</span>
                </div>
            </div>

            <a href="{{ route('materialreturn.history') }}" class="btn btn-sm btn-light border rounded-3 px-3 py-1 text-secondary">
                <span>View Full Return History</span>
                <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th class="ps-4">Voucher No</th>
                            <th>Site / Project</th>
                            <th>Return Date</th>
                            <th>Returned By</th>
                            <th>Items Returned</th>
                            <th class="text-end">Freight</th>
                            <th class="text-center pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentReturns as $return)
                        <tr>
                            <td class="ps-4 py-3">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-monospace fw-bold">
                                    {{ $return->return_no }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('sitetimeline', $return->customer_id) }}" class="fw-bold text-dark text-decoration-none site-link">
                                    {{ $return->customer->client_name ?? 'Site #' . $return->customer_id }}
                                </a>
                                @if(isset($return->customer->Location))
                                    <div class="text-muted small">
                                        <i class="bi bi-geo-alt text-danger"></i>
                                        {{ Str::limit($return->customer->Location, 25) }}
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
                                    Recv: {{ $return->user_name ?? 'Reception Staff' }}
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-boxes me-1 text-success"></i>
                                    {{ $return->items->count() }} {{ Str::plural('item', $return->items->count()) }}
                                </span>
                            </td>

                            <td class="text-end">
                                <div class="fw-bold text-dark">
                                    ₹ {{ number_format($return->transport_charge, 2) }}
                                </div>
                            </td>

                            <td class="text-center pe-4">
                                <a href="{{ route('materialreturn.view', $return->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1">
                                    <i class="bi bi-receipt me-1"></i> Voucher
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .material-return-container {
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

    .letter-spacing {
        letter-spacing: 0.5px;
    }

    .site-link:hover {
        color: #10b981 !important;
        text-decoration: underline !important;
    }

    .shadow-xs {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .shadow-2xs {
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .return-qty-input:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.2);
    }

    .return-qty-input.is-overlimit {
        border-color: #dc3545 !important;
        background-color: #fff8f8 !important;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const customerSelect = document.getElementById('customer_id');
        const materialTable = document.getElementById('materialTable');
        const submitBtn = document.getElementById('submitReturnBtn');
        const quickActionControls = document.getElementById('quickActionControls');

        if (customerSelect) {
            customerSelect.addEventListener('change', function() {
                const customerId = this.value;

                if (!customerId) {
                    materialTable.innerHTML = `
                        <div class="p-5 text-center bg-light rounded-4 border border-dashed">
                            <i class="bi bi-arrow-return-left display-4 text-muted opacity-50 mb-3 d-block"></i>
                            <h6 class="fw-bold text-dark mb-1">Select a Project Site to Load Material Balances</h6>
                            <p class="text-muted small mb-0">Choose a site from the dropdown above to view all materials dispatched to it with their returnable balances.</p>
                        </div>
                    `;
                    submitBtn.disabled = true;
                    if (quickActionControls) quickActionControls.classList.add('d-none');
                    return;
                }

                // Show loading spinner
                materialTable.innerHTML = `
                    <div class="p-5 text-center bg-light rounded-4 border">
                        <div class="spinner-border text-success mb-3" role="status" style="width: 2.5rem; height: 2.5rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h6 class="fw-semibold text-dark mb-1">Fetching Dispatched Material Balances...</h6>
                        <p class="text-muted small mb-0">Retrieving outward challan items and remaining quantities for this site.</p>
                    </div>
                `;
                submitBtn.disabled = true;
                if (quickActionControls) quickActionControls.classList.add('d-none');

                // Perform AJAX request
                fetch(`/material-return/items/${customerId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network error loading dispatched items.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data || data.length === 0) {
                            materialTable.innerHTML = `
                                <div class="p-5 text-center bg-light rounded-4 border">
                                    <i class="bi bi-check-circle-fill display-4 text-success opacity-75 mb-3 d-block"></i>
                                    <h6 class="fw-bold text-dark mb-1">No Returnable Materials Found for this Site</h6>
                                    <p class="text-muted small mb-3">All dispatched materials for this site have either already been returned in full, or no dispatches have been issued yet.</p>
                                    <a href="{{ route('receptionmaterialitems') }}" class="btn btn-sm btn-outline-primary rounded-3 px-3">
                                        <i class="bi bi-box-arrow-right me-1"></i> Issue New Outward Dispatch
                                    </a>
                                </div>
                            `;
                            submitBtn.disabled = true;
                            if (quickActionControls) quickActionControls.classList.add('d-none');
                            return;
                        }

                        // Build clean table
                        let html = `
                            <div class="table-responsive rounded-3 border">
                                <table class="table table-hover align-middle mb-0" id="returnItemsTable">
                                    <thead class="table-light text-secondary small text-uppercase">
                                        <tr>
                                            <th class="ps-3 text-center" style="width: 5%;">#</th>
                                            <th style="width: 32%;">Material / Item Description</th>
                                            <th style="width: 14%;" class="text-center">Dispatched</th>
                                            <th style="width: 14%;" class="text-center">Returned</th>
                                            <th style="width: 15%;" class="text-center">Available Balance</th>
                                            <th style="width: 13%;" class="text-end">Return Qty</th>
                                            <th style="width: 7%;" class="text-center pe-3">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;

                        data.forEach((row, idx) => {
                            const formattedBal = parseFloat(row.balance).toFixed(2).replace(/\.?0+$/, '');
                            const formattedDisp = parseFloat(row.dispatch_qty).toFixed(2).replace(/\.?0+$/, '');
                            const formattedRet = parseFloat(row.returned_qty).toFixed(2).replace(/\.?0+$/, '');

                            html += `
                                <tr class="return-item-row" data-balance="${row.balance}">
                                    <td class="ps-3 text-center text-muted fw-bold small">${idx + 1}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">
                                            <i class="bi bi-box-seam me-1 text-primary opacity-75"></i>
                                            ${row.item}
                                        </div>
                                        ${row.description ? `<small class="text-muted d-block">${row.description}</small>` : ''}
                                        ${row.dispatch_date ? `<span class="badge bg-light text-secondary border font-monospace mt-1" style="font-size: 0.68rem;">Sent: ${row.dispatch_date}</span>` : ''}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border">
                                            ${formattedDisp} ${row.unit}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-secondary border">
                                            ${formattedRet} ${row.unit}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill fw-bold" style="font-size: 0.8rem;">
                                            ${formattedBal} ${row.unit}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <input type="hidden" name="dispatch_item_id[]" value="${row.dispatch_item_id}">
                                        <div class="input-group input-group-sm">
                                            <input type="number"
                                                min="0"
                                                max="${row.balance}"
                                                step="0.01"
                                                name="return_quantity[]"
                                                class="form-control text-end fw-bold return-qty-input"
                                                placeholder="0.00"
                                                data-max="${row.balance}"
                                                data-unit="${row.unit}">
                                            <span class="input-group-text bg-light text-muted small" style="font-size: 0.75rem;">${row.unit}</span>
                                        </div>
                                    </td>
                                    <td class="text-center pe-3">
                                        <button type="button" class="btn btn-xs btn-outline-success rounded-pill px-2 py-1 fill-max-btn" title="Return full available balance">
                                            All
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });

                        html += `
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-2">
                                <span class="text-muted small">
                                    <i class="bi bi-info-circle me-1 text-info"></i>
                                    Enter returned quantities above. Items with blank or 0 quantity will be skipped.
                                </span>
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill" id="returnStatusPill">
                                    ${data.length} line ${data.length === 1 ? 'item' : 'items'} available
                                </span>
                            </div>
                        `;

                        materialTable.innerHTML = html;
                        submitBtn.disabled = false;
                        if (quickActionControls) quickActionControls.classList.remove('d-none');
                        attachDynamicEvents();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        materialTable.innerHTML = `
                            <div class="alert alert-danger rounded-3 p-3">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                Error fetching dispatched material balances. Please check your connection and try again.
                            </div>
                        `;
                        submitBtn.disabled = true;
                        if (quickActionControls) quickActionControls.classList.add('d-none');
                    });
            });
        }

        function attachDynamicEvents() {
            const inputs = document.querySelectorAll('.return-qty-input');
            const fillMaxBtns = document.querySelectorAll('.fill-max-btn');
            const returnStatusPill = document.getElementById('returnStatusPill');

            function updateSummaryPill() {
                let filledCount = 0;
                let totalItems = inputs.length;

                inputs.forEach(input => {
                    const val = parseFloat(input.value);
                    if (!isNaN(val) && val > 0) {
                        filledCount++;
                    }
                });

                if (returnStatusPill) {
                    returnStatusPill.innerHTML = `<strong>${filledCount}</strong> of ${totalItems} items entered for return`;
                }
            }

            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    const max = parseFloat(this.getAttribute('data-max'));
                    const val = parseFloat(this.value);

                    if (!isNaN(val) && val > max) {
                        this.classList.add('is-overlimit');
                        this.setCustomValidity(`Cannot exceed available balance of ${max}`);
                    } else {
                        this.classList.remove('is-overlimit');
                        this.setCustomValidity('');
                    }
                    updateSummaryPill();
                });
            });

            // Single row "All" button
            fillMaxBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const input = row.querySelector('.return-qty-input');
                    const max = input.getAttribute('data-max');
                    input.value = max;
                    input.classList.remove('is-overlimit');
                    input.setCustomValidity('');
                    updateSummaryPill();
                });
            });

            // Bulk "Fill All Balances"
            const returnAllBtn = document.getElementById('returnAllBtn');
            if (returnAllBtn) {
                returnAllBtn.onclick = function() {
                    inputs.forEach(input => {
                        const max = input.getAttribute('data-max');
                        input.value = max;
                        input.classList.remove('is-overlimit');
                        input.setCustomValidity('');
                    });
                    updateSummaryPill();
                };
            }

            // Bulk "Clear Quantities"
            const clearAllBtn = document.getElementById('clearAllBtn');
            if (clearAllBtn) {
                clearAllBtn.onclick = function() {
                    inputs.forEach(input => {
                        input.value = '';
                        input.classList.remove('is-overlimit');
                        input.setCustomValidity('');
                    });
                    updateSummaryPill();
                };
            }
        }
    });
</script>
@endpush
@endsection
