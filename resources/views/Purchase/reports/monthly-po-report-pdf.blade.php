<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Purchase Order Report - {{ $dateString ?? 'Monthly Statement' }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            line-height: 1.4;
            margin: 15px 20px;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 0;
        }

        .company-tagline {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        .report-title {
            text-align: right;
        }

        .report-title h2 {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 3px 0;
            text-transform: uppercase;
        }

        .report-title p {
            margin: 0;
            font-size: 10.5px;
            color: #64748b;
        }

        /* Summary Cards / Box */
        .summary-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .summary-box td {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            font-size: 10.5px;
        }

        .summary-label {
            color: #64748b;
            font-weight: normal;
        }

        .summary-value {
            font-weight: bold;
            color: #0f172a;
        }

        /* Main Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 7px 8px;
            border: 1px solid #1e3a8a;
            text-align: left;
        }

        .data-table td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
        }

        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .badge-approved {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        .footer-note {
            margin-top: 25px;
            border-top: 1px solid #cbd5e1;
            padding-top: 10px;
            font-size: 9px;
            color: #64748b;
            width: 100%;
        }

        .sign-area {
            width: 100%;
            margin-top: 35px;
        }

        .sign-box {
            text-align: center;
            width: 200px;
            border-top: 1px dashed #94a3b8;
            padding-top: 5px;
            font-size: 10px;
            color: #475569;
        }
    </style>
</head>
<body>

    {{-- Company & Document Header --}}
    <table class="header-table">
        <tr>
            <td style="width: 55%; vertical-align: middle;">
                <h1 class="company-name">AMUDHA DECORS</h1>
                <div class="company-tagline">Architectural Tensile & Interior Decor Solutions</div>
                <div style="font-size: 9.5px; color: #475569; margin-top: 3px;">
                    Purchase & Procurement Division | Generated on {{ now()->format('d M Y, h:i A') }}
                </div>
            </td>
            <td class="report-title" style="width: 45%; vertical-align: middle;">
                <h2>Purchase Order Report</h2>
                <p>Period: <strong>{{ $dateString ?? 'Monthly Overview' }}</strong></p>
                @if(isset($vendorName) && $vendorName !== 'All Vendors')
                    <p>Vendor: <strong>{{ $vendorName }}</strong></p>
                @endif
                @if(isset($filterStatus) && $filterStatus !== 'All Statuses')
                    <p>Status Filter: <strong>{{ $filterStatus }}</strong></p>
                @endif
            </td>
        </tr>
    </table>

    {{-- Performance Summary Metrics Box --}}
    <table class="summary-box">
        <tr>
            <td style="width: 25%;">
                <span class="summary-label">Total Purchase Orders:</span><br>
                <span class="summary-value" style="font-size: 13px;">{{ $totalCount ?? 0 }}</span>
            </td>
            <td style="width: 25%;">
                <span class="summary-label">Approved Orders:</span><br>
                <span class="summary-value" style="font-size: 13px; color: #059669;">{{ $approvedCount ?? 0 }}</span>
            </td>
            <td style="width: 25%;">
                <span class="summary-label">Pending Sign-off:</span><br>
                <span class="summary-value" style="font-size: 13px; color: #d97706;">{{ $pendingCount ?? 0 }}</span>
            </td>
            <td style="width: 25%;">
                <span class="summary-label">Total Procurement Value:</span><br>
                <span class="summary-value" style="font-size: 13px; color: #1e3a8a;">₹ {{ number_format($totalValue ?? 0, 2) }}</span>
            </td>
        </tr>
    </table>

    {{-- Purchase Orders Data Table --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%;" class="text-center">#</th>
                <th style="width: 14%;">PO Number</th>
                <th style="width: 10%;">Date</th>
                <th style="width: 23%;">Vendor / Supplier</th>
                <th style="width: 19%;">Site / Customer</th>
                <th style="width: 10%;">PO Type</th>
                <th style="width: 12%;" class="text-end">Grand Total (₹)</th>
                <th style="width: 9%;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $po)
                @php
                    $vendorTitle = $po->vendor->company_name ?? ($po->contractor_name ?? '—');
                    $customerTitle = $po->customer->client_name ?? '—';
                    $orderDate = $po->po_date ? \Carbon\Carbon::parse($po->po_date)->format('d-m-Y') : $po->created_at->format('d-m-Y');
                    $isApproved = strtolower($po->status) === 'approved';
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="fw-bold" style="color: #1e3a8a;">#{{ $po->po_no }}</td>
                    <td>{{ $orderDate }}</td>
                    <td><strong>{{ $vendorTitle }}</strong></td>
                    <td>{{ $customerTitle }}</td>
                    <td>{{ $po->po_type ?? 'Standard' }}</td>
                    <td class="text-end fw-bold">₹ {{ number_format($po->grand_total, 2) }}</td>
                    <td class="text-center">
                        @if($isApproved)
                            <span class="badge badge-approved">Approved</span>
                        @else
                            <span class="badge badge-pending">{{ $po->status ?? 'Pending' }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px; color: #64748b;">
                        No purchase orders recorded for this period.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($orders) > 0)
        <tfoot>
            <tr style="background-color: #e2e8f0; font-weight: bold;">
                <td colspan="6" class="text-end" style="padding: 8px;">Total Spend ({{ count($orders) }} POs):</td>
                <td class="text-end" style="padding: 8px; font-size: 11px; color: #1e3a8a;">
                    ₹ {{ number_format($totalValue ?? 0, 2) }}
                </td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- Sign-off & Footer --}}
    <table class="sign-area">
        <tr>
            <td style="width: 50%;">
                <div class="sign-box">Prepared By (Procurement Dept)</div>
            </td>
            <td style="width: 50%; text-align: right;">
                <div class="sign-box" style="margin-left: auto;">Authorized Signature / Management</div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        This is a computer-generated procurement statement from Amudha Decors ERP &copy; {{ now()->year }}.
    </div>

</body>
</html>

