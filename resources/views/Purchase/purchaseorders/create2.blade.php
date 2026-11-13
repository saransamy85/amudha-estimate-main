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

                    {{-- VENDOR --}}

                    <div class="col-md-3" id="vendorField">

                        <label>
                            Vendor
                        </label>

                        <select class="form-select" name="vendor_id" id="vendor_id">

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


                    {{-- CONTRACTOR NAME --}}

                    <div class="col-md-3" id="contractorNameField" style="display:none;">

                        <label>
                            Contractor Name
                        </label>

                        <input type="text" class="form-control" name="contractor_name" id="contractor_name" placeholder="Enter Contractor Name" disabled>

                    </div>


                    <div class="col-md-6" id="contractorAddressField" style="display:none;">

                        <label>
                            Contractor Address
                        </label>

                        <textarea name="contractor_address" id="contractor_address" class="form-control" rows="2" placeholder="Enter Contractor Address" disabled></textarea>

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

                                <option value="steelplate">
                                    Steel Plate
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
                <div id="contractorTermsSection" style="display: none;">

                    <div class="card mt-4">

                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

                            <strong>
                                Terms & Conditions
                            </strong>

                            <button type="button" class="btn btn-success btn-sm" id="addTerm">

                                <i class="bi bi-plus-lg"></i>
                                Add Term

                            </button>

                        </div>

                        <div class="card-body">

                            <div id="termsBody">

                                <div class="row mb-2 termRow">

                                    <div class="col-md-11">

                                        <input type="text" name="terms[]" class="form-control" placeholder="Enter Terms & Conditions">

                                    </div>

                                    <div class="col-md-1">

                                        <button type="button" class="btn btn-danger removeTerm">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


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
    // =====================================================
    // TERMS & CONDITIONS
    // =====================================================

    $(document)
        .off('click.terms', '#addTerm')
        .on('click.terms', '#addTerm', function() {

            let row = `
            <div class="row mb-2 termRow">

                <div class="col-md-11">

                    <input
                        type="text"
                        name="terms[]"
                        class="form-control"
                        placeholder="Enter Terms & Conditions">

                </div>

                <div class="col-md-1">

                    <button
                        type="button"
                        class="btn btn-danger removeTerm">

                        <i class="bi bi-trash"></i>

                    </button>

                </div>

            </div>
        `;

            $('#termsBody').append(row);

        });


    $(document)
        .off('click.terms', '.removeTerm')
        .on('click.terms', '.removeTerm', function() {

            if ($('#termsBody .termRow').length > 1) {

                $(this)
                    .closest('.termRow')
                    .remove();

            } else {

                $(this)
                    .closest('.termRow')
                    .find('input')
                    .val('');

            }

        });
    $(document).ready(function() {

        // =====================================================
        // INITIAL TEMPLATE
        // =====================================================

        let initialTemplate = $('#template').val();

        loadTemplateJS(initialTemplate);


        // =====================================================
        // PO TYPE CHANGE
        // =====================================================

        $('input[name="po_type"]').on('change', function() {

            switchPOType();

        });


        // =====================================================
        // MATERIAL TEMPLATE CHANGE
        // =====================================================

        $('#template').on('change', function() {

            let template = $(this).val();

            $('#itemTable').load(
                "{{ url('purchase/template') }}/" + template
                , function() {

                    loadTemplateJS(template);

                }
            );

        });


        // =====================================================
        // CONTRACTOR TYPE CHANGE
        // =====================================================

        $('#contractor_template').on('change', function() {

            switchContractorType();

        });


        // =====================================================
        // CONTRACTOR - ADD ROW
        // =====================================================

        $(document)
            .off('click.contractor', '#addContractorRow')
            .on('click.contractor', '#addContractorRow', function() {

                let row = $('#contractorBody tr:first').clone(false);

                row.find('input').val('');

                $('#contractorBody').append(row);

                calculateContractor();

            });


        // =====================================================
        // CONTRACTOR - REMOVE ROW
        // =====================================================

        $(document)
            .off('click.contractor', '.removeContractorRow')
            .on('click.contractor', '.removeContractorRow', function() {

                if ($('#contractorBody tr').length > 1) {

                    $(this).closest('tr').remove();

                } else {

                    $(this)
                        .closest('tr')
                        .find('input')
                        .val('');

                }

                calculateContractor();

            });


        // =====================================================
        // CONTRACTOR - CALCULATION
        // =====================================================

        $(document)
            .off(
                'input.contractor change.contractor'
                , '.contractorQty, .contractorRate'
            )
            .on(
                'input.contractor change.contractor'
                , '.contractorQty, .contractorRate'
                , function() {

                    calculateContractor();

                }
            );


        // =====================================================
        // LUMPSUM - ADD ROW
        // =====================================================

        $(document)
            .off('click.lumpsum', '#addLumpsumRow')
            .on('click.lumpsum', '#addLumpsumRow', function() {

                let row = $('#lumpsumBody tr:first').clone(false);

                row.find('input, textarea').val('');

                $('#lumpsumBody').append(row);

                calculateLumpsum();

            });


        // =====================================================
        // LUMPSUM - REMOVE ROW
        // =====================================================

        $(document)
            .off('click.lumpsum', '.removeLumpsumRow')
            .on('click.lumpsum', '.removeLumpsumRow', function() {

                if ($('#lumpsumBody tr').length > 1) {

                    $(this).closest('tr').remove();

                } else {

                    $(this)
                        .closest('tr')
                        .find('input, textarea')
                        .val('');

                }

                calculateLumpsum();

            });


        // =====================================================
        // LUMPSUM - CALCULATION
        // =====================================================

        $(document)
            .off(
                'input.lumpsum change.lumpsum'
                , '.lumpsumAmount'
            )
            .on(
                'input.lumpsum change.lumpsum'
                , '.lumpsumAmount'
                , function() {

                    calculateLumpsum();

                }
            );


        // =====================================================
        // GST CHANGE
        // =====================================================

        $(document)
            .off(
                'input.gst change.gst'
                , '#gst_percent'
            )
            .on(
                'input.gst change.gst'
                , '#gst_percent'
                , function() {

                    let poType =
                        $('input[name="po_type"]:checked').val();


                    // Contractor
                    if (poType === 'contractor') {

                        if (
                            $('#contractor_template').val() ===
                            'common'
                        ) {

                            calculateContractor();

                        } else {

                            calculateLumpsum();

                        }

                    }

                    // Material
                    // Material template JS handles its own GST.

                }
            );


        // =====================================================
        // INITIAL STATE
        // =====================================================

        switchPOType();

    });


    // =========================================================
    // LOAD MATERIAL TEMPLATE JS
    // =========================================================

    function loadTemplateJS(template) {

        // -----------------------------------------------------
        // Remove OLD template event handlers
        // -----------------------------------------------------

        $(document).off('.anchor');
        $(document).off('.paint');
        $(document).off('.retractableawnings');
        $(document).off('.fabrication');
        $(document).off('.steelplate');
        $(document).off('.sandwichpanel');
        $(document).off('.gutter');
        $(document).off('.polycarbonate');
        $(document).off('.metalsheet');


        // -----------------------------------------------------
        // Load selected template JS
        // -----------------------------------------------------

        $.getScript(
                "{{ asset('js') }}/" + template + ".js"
            )
            .done(function() {

                console.log(
                    template + '.js loaded successfully'
                );

            })
            .fail(function(jqxhr, settings, exception) {

                console.error(
                    'Failed to load ' +
                    template +
                    '.js'
                    , exception
                );

            });

    }


    // =========================================================
    // SWITCH PO TYPE
    // =========================================================

    function switchPOType() {

        let type =
            $('input[name="po_type"]:checked').val();


        if (type === 'material') {

            $('#materialSection').show();

            $('#contractorSection').hide();

            $('#contractorTermsSection').hide();
            // Vendor SHOW
            $('#vendorField').show();

            // Hide contractor fields
            $('#contractorNameField').hide();
            $('#contractorAddressField').hide();

            // Disable and clear contractor fields
            $('#contractor_name')
                .prop('disabled', true)
                .prop('required', false)
                .val('');

            $('#contractor_address')
                .prop('disabled', true)
                .prop('required', false)
                .val('');

            // Enable vendor
            $('select[name="vendor_id"]')
                .prop('disabled', false)
                .prop('required', true);

        } else if (type === 'contractor') {

            $('#materialSection').hide();

            $('#contractorSection').show();

            $('#contractorTermsSection').show();

            // Vendor HIDE + DISABLE
            $('#vendorField').hide();

            $('#vendor_id')
                .prop('disabled', true)
                .prop('required', false)
                .val('');


            // Show contractor fields
            $('#contractorNameField').show();
            $('#contractorAddressField').show();

            // Enable contractor fields
            $('#contractor_name')
                .prop('disabled', false)
                .prop('required', true);

            $('#contractor_address')
                .prop('disabled', false)
                .prop('required', true);

            // Disable vendor
            $('select[name="vendor_id"]')
                .prop('disabled', true)
                .prop('required', false)
                .val('');

            switchContractorType();

        }

    }


    // =========================================================
    // SWITCH CONTRACTOR TYPE
    // =========================================================

    function switchContractorType() {

        let type =
            $('#contractor_template').val();


        if (type === 'common') {

            $('#commonContractorSection').show();

            $('#lumpsumContractorSection').hide();

            calculateContractor();

        } else if (type === 'lumpsum') {

            $('#commonContractorSection').hide();

            $('#lumpsumContractorSection').show();

            calculateLumpsum();

        }

    }


    // =========================================================
    // COMMON CONTRACTOR CALCULATION
    // =========================================================

    function calculateContractor() {

        let subtotal = 0;


        $('#contractorBody tr').each(function() {

            let qty =
                parseFloat(
                    $(this)
                    .find('.contractorQty')
                    .val()
                ) || 0;


            let rate =
                parseFloat(
                    $(this)
                    .find('.contractorRate')
                    .val()
                ) || 0;


            let amount =
                qty * rate;


            $(this)
                .find('.contractorAmount')
                .val(
                    amount.toFixed(2)
                );


            subtotal += amount;

        });


        calculateGST(subtotal);

    }


    // =========================================================
    // LUMPSUM CALCULATION
    // =========================================================

    function calculateLumpsum() {

        let subtotal = 0;


        $('#lumpsumBody .lumpsumAmount')
            .each(function() {

                subtotal +=
                    parseFloat($(this).val()) || 0;

            });


        calculateGST(subtotal);

    }


    // =========================================================
    // CONTRACTOR GST CALCULATION
    // =========================================================

    function calculateGST(subtotal) {

        $('#subtotal').val(
            subtotal.toFixed(2)
        );


        let gst =
            parseFloat(
                $('#gst_percent').val()
            ) || 0;


        let gstAmount =
            subtotal * gst / 100;


        $('#gst_amount').val(
            gstAmount.toFixed(2)
        );


        let grandTotal =
            subtotal + gstAmount;


        $('#grand_total').val(
            grandTotal.toFixed(2)
        );

    }

</script>

@endpush

@endsection
