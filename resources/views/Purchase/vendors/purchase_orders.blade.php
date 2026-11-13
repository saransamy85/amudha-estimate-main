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

@section('title', 'Vendor Purchase Orders')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>{{ $vendor->company_name }} - Purchase Orders</h3>
        <a href="{{ route('purchasedashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            Purchase Order Details
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="vendorPOTable">
                    <thead>
                    <tr>
                        <th>PO No</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Subtotal</th>
                        <th>GST (%)</th>
                        <th>GST Amount</th>
                        <th>Grand Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendor->purchaseOrders as $po)
                    <tr>
                        <td>{{ $po->po_no }}</td>
                        <td>{{ \Carbon\Carbon::parse($po->po_date)->format('d-m-Y') }}</td>
                        <td>
                            <span class="badge {{ $po->status == 'Approved' ? 'bg-success' : ($po->status == 'Pending' ? 'bg-warning' : 'bg-secondary') }}">
                                {{ $po->status }}
                            </span>
                        </td>
                        <td>₹ {{ number_format($po->subtotal, 2) }}</td>
                        <td>{{ $po->gst_percent }} %</td>
                        <td>₹ {{ number_format($po->gst_amount, 2) }}</td>
                        <td>₹ {{ number_format($po->grand_total, 2) }}</td>
                        <td>
                            {{-- <a href="{{ route('purchase.view', $po->id) }}" class="btn btn-sm btn-info">View</a> --}}
                            <a href="{{ route('purchase.pdf', $po->id) }}" class="btn btn-sm btn-danger" target="_blank"><i class="bi bi-file-earmark-pdf"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('#vendorPOTable')) {
            $('#vendorPOTable').DataTable({
                responsive: true,
                order: [[1, 'desc']],
                language: {
                    emptyTable: "No Purchase Orders found for this vendor."
                }
            });
        }
    });
</script>

@endsection
