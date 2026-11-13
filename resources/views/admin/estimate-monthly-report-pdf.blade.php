<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Estimate Monthly Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; }
        h2, h3 { margin-bottom: 5px; }
    </style>
</head>
<body>

<h2>AMUDHA DECORS</h2>
<h3>Estimate Report</h3>
<p>Period: {{ $dateString }}</p>

<hr>

<table>
    <tr>
        <td><strong>Total Estimates Created This Month</strong></td>
        <td>{{ $totalCount }}</td>
    </tr>
    <tr>
        <td><strong>Total Value This Month</strong></td>
        <td>{{ number_format($totalValue, 2) }}</td>
    </tr>
</table>

<br>

<h3>Estimate Details</h3>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Customer Name</th>
            <th>Phone</th>
            <th>Location / Address</th>
            <th>Estimate Value</th>
        </tr>
    </thead>
    <tbody>
    @forelse($estimates as $est)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $est->customer_name }}</td>
            <td>{{ $est->mobile }}</td>
            <td>{{ $est->customer_address }}</td>
            <td>{{ number_format($est->net_total, 2) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" style="text-align:center;">No Estimates Created This Month</td>
        </tr>
    @endforelse
    </tbody>
</table>

</body>
</html>
