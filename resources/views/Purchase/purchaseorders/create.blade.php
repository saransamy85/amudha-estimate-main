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

@section('title', 'Create Purchase Order')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h4>

                <i class="bi bi-cart4"></i>

                Create Purchase Order

            </h4>

        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('purchase.store') }}">

                @csrf

                <div class="mb-4">

                    <label class="form-label fw-bold">
                        Purchase Order Type
                    </label>

                    <div class="d-flex gap-4">

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="po_type" id="materialPO" value="material" checked>

                            <label class="form-check-label" for="materialPO">
                                Material
                            </label>
                        </div>


                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="po_type" id="contractorPO" value="contractor">

                            <label class="form-check-label" for="contractorPO">
                                Contractor
                            </label>
                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-3">

                        <label>

                            Company

                        </label>

                        <select name="company" id="company" class="form-select">
                            <option value="">Select Company</option>
                            <option value="Amudha Decors">Amudha Decors</option>
                            <option value="Arasuvel Roofings">Arasuvel Roofings</option>
                        </select>

                    </div>

                    <div class="col-md-3">

                        <label>

                            Vendor

                        </label>

                        <select class="form-select" name="vendor_id" required>

                            <option value="">

                                Select Vendor

                            </option>

                            @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}">

                                {{ $vendor->company_name }}

                            </option>
                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <label>

                            Project Site

                        </label>

                        <select class="form-select" name="site_id">

                            <option>

                                Select Site

                            </option>

                            @foreach ($sites as $site)
                            <option value="{{ $site->id }}">

                                {{ $site->client_name }}

                            </option>
                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label>
                            PO Date
                        </label>

                        <input type="date" class="form-control" name="po_date" value="{{ date('Y-m-d') }}">

                    </div>

                    <div id="po_no_div" class="col-md-3">
                        <label>PO Number</label>
                        <input type="text" name="po_no" class="form-control">
                    </div>

                </div>
                <br>

                <!-- =========================
     MATERIAL SECTION
========================= -->

                <div id="materialSection">

                    <div class="row">

                        <div class="col-md-4">

                            <label class="form-label">
                                Purchase Template
                            </label>

                            <select id="template" class="form-select" name="po_template">

                                <option value="anchor">
                                    Anchor Bolt
                                </option>

                                <option value="MSpipe">
                                    MS Pipe
                                </option>

                                <option value="fabrication">
                                    Fabrication
                                </option>

                                <option value="sandwichpanel">
                                    Sandwich Panel
                                </option>

                                <option value="paint">
                                    Paint
                                </option>

                                <option value="retractableawnings">
                                    Retractable Awnings
                                </option>

                                <option value="gutter">
                                    UPVC Gutter
                                </option>

                                <option value="polycarbonate">
                                    Polycarbonate Sheet
                                </option>

                                <option value="metalsheet">
                                    Metal Sheet
                                </option>

                            </select>

                        </div>

                    </div>

                    <hr>

                    <div id="itemTable">

                        @include('Purchase.purchaseorders.templates.anchor')

                    </div>

                </div>


                <!-- =========================
     CONTRACTOR SECTION
========================= -->

                <div id="contractorSection" style="display:none;">

                    <div class="row">

                        <div class="col-md-4">

                            <label class="form-label">
                                Contractor Type
                            </label>

                            <select id="contractor_template" name="contractor_template" class="form-select">

                                <option value="common">
                                    Common Contractor
                                </option>

                                <option value="lumpsum">
                                    Lumpsum Contractor
                                </option>

                            </select>

                        </div>

                    </div>

                    <hr>


                    <!-- COMMON CONTRACTOR -->

                    <div id="commonContractorSection">

                        <table class="table table-bordered align-middle">

                            <thead class="table-dark">

                                <tr>

                                    <th width="40%">
                                        Scope of Work
                                    </th>

                                    <th width="15%">
                                        Quantity
                                    </th>

                                    <th width="10%">
                                        Unit
                                    </th>

                                    <th width="15%">
                                        Rate
                                    </th>

                                    <th width="15%">
                                        Amount
                                    </th>

                                    <th width="5%">

                                        <button type="button" class="btn btn-success btn-sm" id="addContractorRow">

                                            <i class="bi bi-plus-lg"></i>

                                        </button>

                                    </th>

                                </tr>

                            </thead>


                            <tbody id="contractorBody">

                                <tr>

                                    <td>

                                        <input type="text" name="scope_of_work[]" class="form-control" placeholder="Scope of Work">

                                    </td>


                                    <td>

                                        <input type="number" name="contractor_qty[]" class="form-control contractorQty" min="0" step="0.01">

                                    </td>


                                    <td>

                                        <select name="contractor_unit[]" class="form-select">

                                            <option value="Kg">
                                                Kg
                                            </option>

                                            <option value="Nos">
                                                Nos
                                            </option>

                                            <option value="Sq.ft">
                                                Sq.ft
                                            </option>

                                            <option value="Sq.m">
                                                Sq.m
                                            </option>

                                        </select>

                                    </td>


                                    <td>

                                        <input type="number" name="contractor_rate[]" class="form-control contractorRate" min="0" step="0.01">

                                    </td>


                                    <td>

                                        <input type="number" name="contractor_amount[]" class="form-control contractorAmount" readonly>

                                    </td>


                                    <td>

                                        <button type="button" class="btn btn-danger btn-sm removeContractorRow">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>


                    <!-- LUMPSUM CONTRACTOR -->

                    <div id="lumpsumContractorSection" style="display:none;">

                        <table class="table table-bordered align-middle">

                            <thead class="table-dark">

                                <tr>

                                    <th width="65%">
                                        Scope of Work
                                    </th>

                                    <th width="25%">
                                        Lumpsum Amount
                                    </th>

                                    <th width="10%">

                                        <button type="button" class="btn btn-success btn-sm" id="addLumpsumRow">

                                            <i class="bi bi-plus-lg"></i>

                                        </button>

                                    </th>

                                </tr>

                            </thead>


                            <tbody id="lumpsumBody">

                                <tr>

                                    <td>

                                        <textarea name="lumpsum_scope[]" class="form-control" rows="2" placeholder="Scope of Work"></textarea>

                                    </td>


                                    <td>

                                        <input type="number" name="lumpsum_amount[]" class="form-control lumpsumAmount" min="0" step="0.01">

                                    </td>


                                    <td>

                                        <button type="button" class="btn btn-danger btn-sm removeLumpsumRow">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

                <hr>


                <div class="row">

                    <div class="col-md-3">

                        <label>

                            Subtotal

                        </label>

                        <input readonly class="form-control" id="subtotal" name="subtotal">

                    </div>

                    <div class="col-md-3">

                        <label>

                            GST %

                        </label>

                        <input type="number" class="form-control" id="gst_percent" name="gst_percent" value="18">

                    </div>

                    <div class="col-md-3">

                        <label>

                            GST Amount

                        </label>

                        <input readonly class="form-control" id="gst_amount" name="gst_amount">

                    </div>

                    <div class="col-md-3">

                        <label>

                            Grand Total

                        </label>

                        <input readonly class="form-control" id="grand_total" name="grand_total">

                    </div>

                </div>

                <br>

                <label>

                    Remarks

                </label>

                <textarea rows="3" class="form-control" name="remarks"></textarea>

                <br>

                <div class="text-end">

                    <button class="btn btn-success btn-lg">

                        <i class="bi bi-check-circle"></i>

                        Save Purchase Order

                    </button>

                </div>
            </form>

        </div>

    </div>

</div>

@push('scripts')
<script>
    function bindAnchorEvents() {

        // Add Row
        $(document).off('click', '#addRow').on('click', '#addRow', function() {

            let row = $('#tbody tr:first').clone();

            row.find('input').val('');

            $('#tbody').append(row);

        });

        // Remove Row
        $(document).off('click', '.removeRow').on('click', '.removeRow', function() {

            if ($('#tbody tr').length > 1) {

                $(this).closest('tr').remove();

                calculateTotal();

            }

        });

        // Calculate
        $(document).off('keyup change', '.qty,.rate,#gst_percent')
            .on('keyup change', '.qty,.rate,#gst_percent', function() {

                calculateTotal();

            });

        calculateTotal();

    }

    function calculateTotal() {

        if ($('#tbody').length === 0) {
            return; // anchor table not loaded — don't touch totals
        }

        let subtotal = 0;

        $('#tbody tr').each(function() {

            let qty = parseFloat($(this).find('.qty').val()) || 0;
            let rate = parseFloat($(this).find('.rate').val()) || 0;
            let amount = qty * rate;

            $(this).find('.amount').val(amount.toFixed(2));
            subtotal += amount;

        });

        $('#subtotal').val(subtotal.toFixed(2));

        let gst = parseFloat($('[name="gst_percent"]').val()) || 0;
        let gstAmount = subtotal * gst / 100;

        $('#gst_amount').val(gstAmount.toFixed(2));
        $('#grand_total').val((subtotal + gstAmount).toFixed(2));

    }

    $(document).ready(function() {

        bindAnchorEvents();

        $('#template').change(function() {

            let template = $(this).val();

            $('#itemTable').load("{{ url('purchase/template') }}/" + template, function() {


                $.getScript('/js/' + template + '.js');
                bindAnchorEvents();

            });

        });

    });

</script>
@endpush
<script>

</script>
@endsection
