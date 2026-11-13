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

@section('title', 'Material-Out (Outward Dispatch)')

@section('content')
<div class="material-out-container py-3">

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
                        Material-Out Dispatch
                    </li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon-box">
                    <i class="bi bi-box-arrow-right text-primary"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Material-Out (Outward Dispatch)</h4>
                    <span class="text-muted small">
                        Issue materials, equipment, and consumables from warehouse/factory to active project sites.
                    </span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('receptiondashboard') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-arrow-left"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('materialreturn') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-arrow-return-left text-warning"></i>
                <span>Record Return</span>
            </a>

            <a href="{{ route('receptionsites') }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-building text-info"></i>
                <span>Sites</span>
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


    {{-- Section 2: Outward Material Dispatch Creation Form --}}
    <form action="{{ route('addmaterialitems') }}" method="POST" id="dispatchForm" class="mb-5">
        @csrf

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary px-3 py-2 rounded-pill font-monospace shadow-2xs">
                        <i class="bi bi-file-earmark-plus me-1"></i> NEW CHALLAN
                    </span>
                    <h5 class="mb-0 fw-bold text-dark">Outward Dispatch Particulars</h5>
                </div>
                <span class="text-muted small">
                    <i class="bi bi-shield-check text-success me-1"></i> Recorded by: <strong>{{ Auth::user()->name }}</strong>
                </span>
            </div>

            <div class="card-body p-4">
                {{-- Part A: Destination & Receiver --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-secondary text-uppercase small mb-3 letter-spacing">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> 1. Destination Site & Consignment Receiver
                    </h6>
                    <div class="row g-3">
                        {{-- Site Selector --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                Destination Project Site <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-building"></i></span>
                                <select class="form-select @error('customer_id') is-invalid @enderror" name="customer_id" id="siteSelect" required>
                                    <option value="" selected disabled>-- Select Destination Site --</option>
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" data-location="{{ $customer->Location }}" data-mobile="{{ $customer->Mobile }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->client_name }} — {{ $customer->Location ?: 'Location Unset' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                Selecting a site automatically populates the destination location.
                            </small>
                        </div>

                        {{-- Receiver / In-Charge Name --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                In-Charge / Receiver Name
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" name="person_name" class="form-control" placeholder="e.g. Site Engineer / Supervisor / Driver" value="{{ old('person_name') }}">
                            </div>
                            <small class="text-muted mt-1 d-block">Name of the person receiving the consignment at the site.</small>
                        </div>
                    </div>
                </div>

                <hr class="text-muted opacity-25 my-4">

                {{-- Part B: Transit Route & Logistics Details --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-secondary text-uppercase small mb-3 letter-spacing">
                        <i class="bi bi-truck text-primary me-1"></i> 2. Transit Route & Vehicle Logistics
                    </h6>
                    <div class="row g-3">
                        {{-- From Location --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                Dispatch Origin (From) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-box-seam"></i></span>
                                <input type="text" name="from_location" class="form-control @error('from_location') is-invalid @enderror" placeholder="e.g. Central Warehouse / Factory / Main Branch" value="{{ old('from_location', 'Central Warehouse') }}" required>
                            </div>
                        </div>

                        {{-- To Location --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                Destination Location (To) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" name="to_location" id="toLocationInput" class="form-control @error('to_location') is-invalid @enderror" placeholder="e.g. Project Site Address / Gate No" value="{{ old('to_location') }}" required>
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
                                <option value="Rapido" {{ old('transport_type') == 'Rapido' ? 'selected' : '' }}>Rapido</option>
                                <option value="Courier" {{ old('transport_type') == 'Courier' ? 'selected' : '' }}>Courier</option>
                                <option value="Other" {{ old('transport_type') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        {{-- Vehicle No --}}
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark">
                                Vehicle Reg. No
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted font-monospace"><i class="bi bi-card-heading"></i></span>
                                <input type="text" name="vehicle_no" class="form-control font-monospace text-uppercase" placeholder="TN 38 AB 1234" value="{{ old('vehicle_no') }}">
                            </div>
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

                        {{-- Dispatch Date --}}
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark">
                                Dispatch Date <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" class="form-control @error('dispatch_date') is-invalid @enderror" name="dispatch_date" value="{{ old('dispatch_date', date('Y-m-d')) }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="text-muted opacity-25 my-4">

                {{-- Part C: Dispatched Material Particulars (Dynamic Table) --}}
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div>
                            <h6 class="fw-bold text-secondary text-uppercase small mb-1 letter-spacing">
                                <i class="bi bi-boxes text-success me-1"></i> 3. Dispatched Material Items
                            </h6>
                            <span class="text-muted small">List all materials, tools, or items dispatched in this consignment.</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary rounded-3 px-3 d-inline-flex align-items-center gap-1 shadow-xs" id="addRow">
                            <i class="bi bi-plus-lg"></i>
                            <span>Add Material Line</span>
                        </button>
                    </div>

                    <div class="table-responsive rounded-3 border">
                        <table class="table table-hover align-middle mb-0" id="materialTable">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-3 text-center" style="width: 5%;">#</th>
                                    <th style="width: 38%;">Material / Item Description <span class="text-danger">*</span></th>
                                    <th style="width: 17%;">Quantity <span class="text-danger">*</span></th>
                                    <th style="width: 15%;">Unit <span class="text-danger">*</span></th>
                                    <th style="width: 20%;">Specifications / Notes</th>
                                    <th class="text-center pe-3" style="width: 5%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-3 text-center fw-bold text-muted row-index">1</td>
                                    <td>
                                        <input type="text" name="item[]" class="form-control" placeholder="e.g. MS Pipes 2x2, Cement 43 Grade, Primer" required>
                                    </td>
                                    <td>
                                        <input type="number" name="quantity[]" class="form-control text-end fw-semibold" step="0.01" min="0.01" placeholder="0.00" required>
                                    </td>
                                    <td>
                                        <select name="unit[]" class="form-select" required>
                                            <option value="Nos" selected>Nos</option>
                                            <option value="Kg">Kg</option>
                                            <option value="Sq.ft">Sq.ft</option>
                                            <option value="Meter">Meter</option>
                                            <option value="Box">Box</option>
                                            <option value="Bundle">Bundle</option>
                                            <option value="Bag">Bag</option>
                                            <option value="ltr">ltr</option>
                                            <option value="Set">Set</option>
                                            <option value="Roll">Roll</option>
                                            <option value="Ton">Ton</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="description[]" class="form-control" placeholder="Remarks / Brand / Size">
                                    </td>
                                    <td class="text-center pe-3">
                                        <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle removeRow p-2" title="Remove line item" disabled>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2">
                        <span class="text-muted small">
                            Total line items: <strong id="itemCountBadge" class="text-dark">1 item</strong>
                        </span>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-3 px-3" id="addRowBottom">
                            <i class="bi bi-plus-circle me-1"></i> Add Another Item
                        </button>
                    </div>
                </div>
            </div>

            {{-- Form Submit Footer --}}
            <div class="card-footer bg-light border-top py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <a href="{{ route('receptiondashboard') }}" class="btn btn-light border rounded-3 px-4 text-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn btn-success d-inline-flex align-items-center gap-2 rounded-3 px-4 py-2 shadow-sm text-white fw-semibold">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Generate Outward Challan</span>
                </button>
            </div>
        </div>
    </form>

    {{-- Section 3: Recent Outward Dispatches Activity Log --}}
    @if(isset($recentDispatches) && $recentDispatches->count() > 0)
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="p-2 rounded-3 bg-primary-subtle text-primary">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">Recent Outward Dispatches</h5>
                    <span class="text-muted small">Previously recorded outward consignments sent to project sites</span>
                </div>
            </div>

            <a href="{{ route('reception.reports') }}" class="btn btn-sm btn-light border rounded-3 px-3 py-1 text-secondary">
                <span>View Full Reports</span>
                <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th class="ps-4">Site / Destination</th>
                            <th>Date & Mode</th>
                            <th>Route</th>
                            <th>Items Dispatched</th>
                            <th class="text-end">Freight</th>
                            <th class="text-center pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentDispatches as $dispatch)
                        <tr>
                            <td class="ps-4 py-3">
                                <a href="{{ route('sitetimeline', $dispatch->customer_id) }}" class="fw-bold text-dark text-decoration-none site-link">
                                    {{ $dispatch->customer->client_name ?? 'Site #' . $dispatch->customer_id }}
                                </a>
                                <div class="text-muted small d-flex align-items-center gap-2 mt-1">
                                    <span>
                                        <i class="bi bi-person-fill text-secondary"></i>
                                        {{ $dispatch->user_name ?? 'Receptionist' }}
                                    </span>
                                    @if($dispatch->person_name)
                                    <span>&bull; Recv: {{ Str::limit($dispatch->person_name, 15) }}</span>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <div class="fw-semibold text-dark">
                                    {{ date('d M, Y', strtotime($dispatch->dispatch_date)) }}
                                </div>
                                <small class="text-muted">
                                    <span class="badge bg-light text-dark border">
                                        {{ $dispatch->transport_type }}
                                    </span>
                                    @if($dispatch->vehicle_no)
                                    <span class="ms-1 font-monospace">{{ $dispatch->vehicle_no }}</span>
                                    @endif
                                </small>
                            </td>

                            <td>
                                <div class="small">
                                    <span class="text-secondary">{{ Str::limit($dispatch->from_location, 15) }}</span>
                                    <i class="bi bi-arrow-right text-muted mx-1"></i>
                                    <span class="fw-medium text-dark">{{ Str::limit($dispatch->to_location, 18) }}</span>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill mb-1">
                                    <i class="bi bi-boxes me-1"></i> {{ $dispatch->items->count() }} items
                                </span>
                                <div class="text-muted small">
                                    {{ Str::limit($dispatch->items->pluck('item')->implode(', '), 30) }}
                                </div>
                            </td>

                            <td class="text-end">
                                <div class="fw-bold text-dark">
                                    ₹ {{ number_format($dispatch->transport_charge, 2) }}
                                </div>
                            </td>

                            <td class="text-center pe-4">
                                <a href="{{ route('sitetimeline', $dispatch->customer_id) }}" class="btn btn-sm btn-light border rounded-3 px-2 py-1 text-primary" title="View Site Timeline">
                                    <i class="bi bi-clock-history me-1"></i> Timeline
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
    .material-out-container {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    .header-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #eef2ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.12);
    }

    .letter-spacing {
        letter-spacing: 0.5px;
    }

    .site-link:hover {
        color: #4f46e5 !important;
        text-decoration: underline !important;
    }

    .shadow-xs {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .shadow-2xs {
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-fill Destination Location when a Site is chosen
        const siteSelect = document.getElementById('siteSelect');
        const toLocationInput = document.getElementById('toLocationInput');

        if (siteSelect && toLocationInput) {
            siteSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const location = selectedOption.getAttribute('data-location');
                if (location && (!toLocationInput.value || toLocationInput.value.trim() === '')) {
                    toLocationInput.value = location;
                }
            });
        }

        // Dynamic Table Row Management
        const tableBody = document.querySelector('#materialTable tbody');
        const addRowBtn = document.getElementById('addRow');
        const addRowBottomBtn = document.getElementById('addRowBottom');
        const itemCountBadge = document.getElementById('itemCountBadge');

        function updateRowIndexes() {
            const rows = tableBody.querySelectorAll('tr');
            rows.forEach((row, idx) => {
                const indexCell = row.querySelector('.row-index');
                if (indexCell) {
                    indexCell.innerText = idx + 1;
                }
                const removeBtn = row.querySelector('.removeRow');
                if (removeBtn) {
                    removeBtn.disabled = rows.length <= 1;
                }
            });

            if (itemCountBadge) {
                const count = rows.length;
                itemCountBadge.innerText = count + (count === 1 ? ' item' : ' items');
            }
        }

        function createNewRow() {
            const rowCount = tableBody.querySelectorAll('tr').length + 1;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="ps-3 text-center fw-bold text-muted row-index">${rowCount}</td>
                <td>
                    <input type="text" name="item[]" class="form-control" placeholder="e.g. Material Name / Specification" required>
                </td>
                <td>
                    <input type="number" name="quantity[]" class="form-control text-end fw-semibold" step="0.01" min="0.01" placeholder="0.00" required>
                </td>
                <td>
                    <select name="unit[]" class="form-select" required>
                        <option value="Nos" selected>Nos</option>
                        <option value="Kg">Kg</option>
                        <option value="Sq.ft">Sq.ft</option>
                        <option value="Meter">Meter</option>
                        <option value="Box">Box</option>
                        <option value="Bundle">Bundle</option>
                        <option value="Bag">Bag</option>
                        <option value="ltr">ltr</option>
                        <option value="Set">Set</option>
                        <option value="Roll">Roll</option>
                        <option value="Ton">Ton</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="description[]" class="form-control" placeholder="Remarks / Brand / Size">
                </td>
                <td class="text-center pe-3">
                    <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle removeRow p-2" title="Remove line item">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;

            tableBody.appendChild(tr);
            updateRowIndexes();

            // Focus on newly created item input
            const newInput = tr.querySelector('input[name="item[]"]');
            if (newInput) newInput.focus();
        }

        if (addRowBtn) addRowBtn.addEventListener('click', createNewRow);
        if (addRowBottomBtn) addRowBottomBtn.addEventListener('click', createNewRow);

        tableBody.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.removeRow');
            if (removeBtn && !removeBtn.disabled) {
                const tr = removeBtn.closest('tr');
                if (tr && tableBody.querySelectorAll('tr').length > 1) {
                    tr.remove();
                    updateRowIndexes();
                }
            }
        });

        updateRowIndexes();
    });

</script>
@endpush
@endsection
