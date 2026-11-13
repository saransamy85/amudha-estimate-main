
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

@section('title', 'Purchase Order View')

@section('content')

    <div class="container-fluid">

        <div class="card shadow">

            <div class="card-header bg-primary text-white">

                <div class="d-flex justify-content-between">

                    <h4>

                        Purchase Order

                    </h4>

                    <div>

                        <a href="{{ route('purchase.index') }}" class="btn btn-light btn-sm">

                            <i class="bi bi-arrow-left"></i>

                            Back

                        </a>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">

                        <strong>PO No</strong>

                        <br>

                        {{ $po->po_no }}

                    </div>

                    <div class="col-md-3">

                        <strong>Company</strong>

                        <br>

                        {{ $po->company }}

                    </div>

                    <div class="col-md-3">

                        <strong>Vendor</strong>

                        <br>

                        {{ $po->vendor->company_name }}

                    </div>

                    <div class="col-md-3">

                        <strong>Date</strong>

                        <br>

                        {{ date('d-m-Y', strtotime($po->po_date)) }}

                    </div>

                </div>

                <br>

                <div class="row">

                    <div class="col-md-6">

                        <strong>Project Site</strong>

                        <br>

                        {{ $po->customer->client_name }}

                    </div>

                    <div class="col-md-3">

                        <strong>Status</strong>

                        <br>

                        <span class="badge bg-warning">

                            {{ $po->status }}

                        </span>

                    </div>

                    <div class="col-md-3">

                        <strong>Template</strong>

                        <br>

                        {{ ucfirst($po->po_template) }}

                    </div>

                </div>

                <hr>

                @if ($po->po_template == 'anchor')
                    @include('Purchase.purchaseorders.viewtemplates.anchor')
                @elseif($po->po_template == 'MSpipe')
                    @include('Purchase.purchaseorders.viewtemplates.MSpipe')
                @elseif($po->po_template == 'fabrication')
                    @include('Purchase.purchaseorders.viewtemplates.fabrication')
                @elseif($po->po_template == 'sandwichpanel')
                    @include('Purchase.purchaseorders.viewtemplates.sandwichpanel')
                @elseif($po->po_template == 'paint')
                    @include('Purchase.purchaseorders.viewtemplates.paint')
                @elseif($po->po_template == 'retractableawnings')
                    @include('Purchase.purchaseorders.viewtemplates.retractableawnings')
                @endif

                <hr>

                <div class="row">

                    <div class="col-md-3">

                        <label>

                            Sub Total

                        </label>

                        <input class="form-control" readonly value="{{ number_format($po->subtotal, 2) }}">

                    </div>

                    <div class="col-md-3">

                        <label>

                            GST %

                        </label>

                        <input class="form-control" readonly value="{{ $po->gst_percent }}">

                    </div>

                    <div class="col-md-3">

                        <label>

                            GST Amount

                        </label>

                        <input class="form-control" readonly value="{{ number_format($po->gst_amount, 2) }}">

                    </div>

                    <div class="col-md-3">

                        <label>

                            Grand Total

                        </label>

                        <input class="form-control fw-bold" readonly value="{{ number_format($po->grand_total, 2) }}">

                    </div>

                </div>

                <br>

                <label>

                    Remarks

                </label>

                <textarea class="form-control" rows="3" readonly>{{ $po->remarks }}</textarea>

            </div>

        </div>

    </div>

@endsection
