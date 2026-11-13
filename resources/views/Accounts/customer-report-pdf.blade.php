<h2 style="text-align:center">
    Amudha Decors
</h2>

<h3 style="text-align:center">
    Customer Project Report
</h3>

<hr>

<h4>1. Lead Details</h4>

<table width="100%" border="1" cellpadding="5">
<tr>
    <td><strong>Lead ID</strong></td>
    <td>{{ $lead->id }}</td>

    <td><strong>Customer</strong></td>
    <td>{{ $lead->Name }}</td>
</tr>

<tr>
    <td><strong>Mobile</strong></td>
    <td>{{ $lead->Mobile }}</td>

    <td><strong>Location</strong></td>
    <td>{{ $lead->Site_location }}</td>
</tr>

<tr>
    <td><strong>Product</strong></td>
    <td colspan="3">{{ $lead->Product }}</td>
</tr>
</table>

<br>

<h4>2. Estimate Details</h4>

<table width="100%" border="1" cellpadding="5">
<thead>
<tr>
    <th>No</th>
    <th>Estimate No</th>
    <th>Date</th>
    <th>Total</th>
</tr>
</thead>

<tbody>

@foreach($estimates as $estimate)

<tr>

<td>{{ $loop->iteration }}</td>

<td>{{ $estimate->estimate_no }}</td>

<td>{{ $estimate->estimate_date }}</td>

<td>{{ number_format($estimate->net_total,2) }}</td>

</tr>

@endforeach

</tbody>

</table>

<br>

<h4>3. Purchase Orders</h4>

<table width="100%" border="1" cellpadding="5">

<thead>

<tr>

<th>PO No</th>

<th>Vendor</th>

<th>Total</th>

<th>Status</th>

</tr>

</thead>

<tbody>

@foreach($purchaseOrders as $po)

<tr>

<td>{{ $po->po_no }}</td>

<td>{{ optional($po->vendor)->company_name }}</td>

<td>{{ number_format($po->grand_total,2) }}</td>

<td>{{ $po->status }}</td>

</tr>

@endforeach

</tbody>

</table>

<br>

<h4>4. Material Dispatch Details</h4>

<table width="100%" border="1" cellpadding="5">

<thead>

<tr>

<th>Dispatch Date</th>

<th>Person Name</th>

<th>From</th>

<th>To</th>

<th>Transport</th>

<th>Vehicle No</th>

<th>Transport Charge</th>

</tr>

</thead>

<tbody>

@forelse($materialDispatches as $dispatch)

<tr>

<td>{{ $dispatch->dispatch_date }}</td>

<td>{{ $dispatch->person_name }}</td>

<td>{{ $dispatch->from_location }}</td>

<td>{{ $dispatch->to_location }}</td>

<td>{{ $dispatch->transport_type }}</td>

<td>{{ $dispatch->vehicle_no }}</td>

<td>₹ {{ number_format($dispatch->transport_charge, 2) }}</td>

</tr>

@empty

<tr>

<td colspan="7" style="text-align: center;">No dispatch records found.</td>

</tr>

@endforelse

</tbody>

</table>