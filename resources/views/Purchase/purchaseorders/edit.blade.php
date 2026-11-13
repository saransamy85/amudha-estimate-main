@php
if(Auth::user()->Role == 'Admin'){
    $layout = 'layouts.adminbase';
} elseif(Auth::user()->Role == 'Sales'){
    $layout = 'layouts.salesbase';
} elseif(Auth::user()->Role == 'Purchase'){
    $layout = 'layouts.purchasebase';
} elseif(Auth::user()->Role == 'Accounts'){
    $layout = 'layouts.accountsbase';
} elseif(Auth::user()->Role == 'Receptionist'){
    $layout = 'layouts.receptionbase';
} else {
    $layout = 'layouts.adminbase';
}

$effectiveType = $po->po_type ?: (in_array(strtolower(trim($po->po_template ?? '')), ['common', 'lumpsum']) ? 'contractor' : 'material');
@endphp

@extends($layout)

@section('title', 'Edit Purchase Order - ' . ($po->po_number ?? 'PO #' . $po->id))

@section('content')

<div class="container-fluid py-3">

    {{-- Session & Validation Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-circle-fill me-2"></i>Please resolve the following errors:</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('purchase.update', $po->id) }}">
        @csrf

        <div class="card shadow-sm border-0 rounded-3">

            <div class="card-header bg-dark text-white py-3 px-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square fs-5 text-warning"></i>
                    <div>
                        <h5 class="mb-0 fw-bold">Edit Purchase Order: <span class="text-warning">{{ $po->po_number ?? 'PO-' . str_pad($po->id, 5, '0', STR_PAD_LEFT) }}</span></h5>
                        <small class="text-white-50">Type: <span class="badge bg-primary text-uppercase">{{ $effectiveType }}</span> &bull; Template: <span class="badge bg-secondary text-uppercase">{{ $po->po_template }}</span></small>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('purchase.pdf', $po->id) }}" target="_blank" class="btn btn-outline-light btn-sm d-inline-flex align-items-center gap-1">
                        <i class="bi bi-file-earmark-pdf"></i> View PDF
                    </a>
                    <a href="{{ route('purchase.index') }}" class="btn btn-light btn-sm d-inline-flex align-items-center gap-1">
                        <i class="bi bi-arrow-left"></i> Back to Orders
                    </a>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- Header Fields --}}
                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary small">Company</label>
                        <select name="company" class="form-select rounded-2" required>
                            <option value="Amudha Decors" {{ $po->company == 'Amudha Decors' ? 'selected' : '' }}>
                                Amudha Decors
                            </option>
                            <option value="Arasuvel Roofings" {{ $po->company == 'Arasuvel Roofings' ? 'selected' : '' }}>
                                Arasuvel Roofings
                            </option>
                        </select>
                    </div>

                    @if($effectiveType == 'material')
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-secondary small">Vendor</label>
                            <select name="vendor_id" class="form-select rounded-2" required>
                                <option value="">-- Select Vendor --</option>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ $vendor->id == $po->vendor_id ? 'selected' : '' }}>
                                        {{ $vendor->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @elseif($effectiveType == 'contractor')
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-secondary small">Contractor Name</label>
                            <input type="text" class="form-control rounded-2" name="contractor_name" value="{{ $po->contractor_name }}" placeholder="Enter Contractor Name" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-secondary small">Contractor Address</label>
                            <textarea name="contractor_address" class="form-control rounded-2" rows="1" placeholder="Enter Contractor Address" required>{{ $po->contractor_address }}</textarea>
                        </div>
                    @endif

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary small">Client / Site</label>
                        <select name="site_id" class="form-select rounded-2" required>
                            <option value="">-- Select Site --</option>
                            @foreach ($sites as $site)
                                <option value="{{ $site->id }}" {{ $site->id == $po->site_id ? 'selected' : '' }}>
                                    {{ $site->client_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary small">PO Date</label>
                        <input type="date" name="po_date" class="form-control rounded-2" value="{{ $po->po_date }}" required>
                    </div>

                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary small">PO Type</label>
                        <input readonly class="form-control rounded-2 bg-light text-uppercase fw-semibold" value="{{ $effectiveType }}">
                        <input type="hidden" name="po_type" value="{{ $effectiveType }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary small">PO Template</label>
                        <input readonly class="form-control rounded-2 bg-light text-uppercase fw-semibold" value="{{ $po->po_template }}">
                        <input type="hidden" name="po_template" value="{{ $po->po_template }}">
                        @if($effectiveType === 'contractor')
                            <input type="hidden" name="contractor_template" value="{{ $po->po_template }}">
                        @endif
                    </div>
                </div>

                <hr class="my-4">

                {{-- Dynamic Items Template Inclusion --}}
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="bi bi-list-check text-primary me-1"></i> Order Items & Particulars
                        </h6>
                        <span class="badge bg-light text-dark border px-2.5 py-1.5">Template: <strong>{{ strtoupper($po->po_template) }}</strong></span>
                    </div>

                    @if ($effectiveType == 'material')
                        @php
                            $tpl = strtolower(trim($po->po_template ?? ''));
                        @endphp
                        @if ($tpl === 'anchor' || str_contains($tpl, 'anchor'))
                            @include('Purchase.purchaseorders.edittemplates.anchor')
                        @elseif($tpl === 'steelplate' || $tpl === 'mspipe' || str_contains($tpl, 'steel') || str_contains($tpl, 'pipe'))
                            @include('Purchase.purchaseorders.edittemplates.steelplate')
                        @elseif($tpl === 'fabrication' || str_contains($tpl, 'fabricat'))
                            @include('Purchase.purchaseorders.edittemplates.fabrication')
                        @elseif($tpl === 'sandwichpanel' || str_contains($tpl, 'sandwich') || str_contains($tpl, 'panel'))
                            @include('Purchase.purchaseorders.edittemplates.sandwichpanel')
                        @elseif($tpl === 'metalsheet' || str_contains($tpl, 'metal') || str_contains($tpl, 'sheet'))
                            @include('Purchase.purchaseorders.edittemplates.metalsheet')
                        @elseif($tpl === 'retractableawnings' || str_contains($tpl, 'awning') || str_contains($tpl, 'retractable'))
                            @include('Purchase.purchaseorders.edittemplates.retractableawnings')
                        @elseif($tpl === 'paint' || str_contains($tpl, 'paint'))
                            @include('Purchase.purchaseorders.edittemplates.paint')
                        @elseif($tpl === 'gutter' || str_contains($tpl, 'gutter'))
                            @include('Purchase.purchaseorders.edittemplates.gutter')
                        @elseif($tpl === 'polycarbonate' || str_contains($tpl, 'polycarbonate') || str_contains($tpl, 'poly'))
                            @include('Purchase.purchaseorders.edittemplates.polycarbonate')
                        @else
                            @include('Purchase.purchaseorders.edittemplates.anchor')
                        @endif
                    @elseif ($effectiveType == 'contractor')
                        @php
                            $cTpl = strtolower(trim($po->po_template ?? ''));
                        @endphp
                        @if ($cTpl === 'lumpsum' || str_contains($cTpl, 'lump'))
                            @include('Purchase.purchaseorders.edittemplates.contractor_lumpsum')
                        @else
                            @include('Purchase.purchaseorders.edittemplates.contractor_common')
                        @endif

                        @include('Purchase.purchaseorders.edittemplates.terms')
                    @endif
                </div>

                <hr class="my-4">

                {{-- Totals Calculation Block --}}
                <div class="row g-3 bg-light p-3 rounded-3 border mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary small">Subtotal (₹)</label>
                        <input type="text" id="subtotal" name="subtotal" class="form-control rounded-2 bg-white fw-bold" value="{{ $po->subtotal }}" readonly>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary small">GST (%)</label>
                        <input type="number" step="0.01" id="gst_percent" name="gst_percent" class="form-control rounded-2" value="{{ $po->gst_percent ?? 0 }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary small">GST Amount (₹)</label>
                        <input type="text" id="gst_amount" name="gst_amount" class="form-control rounded-2 bg-white fw-bold" value="{{ $po->gst_amount }}" readonly>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary small">Grand Total (₹)</label>
                        <input type="text" id="grand_total" name="grand_total" class="form-control rounded-2 bg-white text-success fw-bold fs-6" value="{{ $po->grand_total }}" readonly>
                    </div>
                </div>

                {{-- Remarks --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold text-secondary small">Remarks / Notes</label>
                    <textarea rows="3" name="remarks" class="form-control rounded-2" placeholder="Add any special instructions or remarks...">{{ $po->remarks }}</textarea>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex justify-content-between align-items-center pt-2">
                    <a href="{{ route('purchase.index') }}" class="btn btn-outline-secondary px-4 py-2">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-success btn-lg px-5 py-2.5 shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="bi bi-check2-circle fs-5"></i> Update Purchase Order
                    </button>
                </div>

            </div>

        </div>

    </form>

</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Universal live recalculation when GST % is changed
        $(document).off('keyup.gst change.gst', '#gst_percent').on('keyup.gst change.gst', '#gst_percent', function() {
            let subtotal = parseFloat($('#subtotal').val()) || 0;
            let gst = parseFloat($('#gst_percent').val()) || 0;
            let gstAmount = subtotal * gst / 100;
            $('#gst_amount').val(gstAmount.toFixed(2));
            $('#grand_total').val((subtotal + gstAmount).toFixed(2));
        });
    });
</script>
@endpush

@endsection
