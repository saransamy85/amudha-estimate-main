<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Customer Monthly Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; }
        h2, h3 { margin-bottom: 5px; }
    </style>
</head>
<body>

<h2>AMUDHA DECORS</h2>
<h3>Converted Customer Report</h3>
<p>Period: {{ $dateString }}</p>

<hr>

<table>
    <tr>
        <td><strong>Total Customers Converted This Month</strong></td>
        <td>{{ $totalCount }}</td>
    </tr>
    <tr>
        <td><strong>Total Value This Month</strong></td>
        <td>{{ number_format($totalValue, 2) }}</td>
    </tr>
</table>

<br>

<h3>Customer Details</h3>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Customer Name</th>
            <th>Phone No</th>
            <th>Location</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
    @forelse($customers as $cus)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $cus->client_name }}</td>
            <td>{{ $cus->Mobile }}</td>
            <td>{{ $cus->Location }}</td>
            <td>{{ number_format($cus->Total_values, 2) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" style="text-align:center;">No Customers Converted This Month</td>
        </tr>
    @endforelse
    </tbody>
</table>

</body>
</html>
