<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        * {
            box-sizing: border-box;
        }

        @page {
            margin: 15px 25px;
        }

        body {
            font-family: DejaVu Sans;
            font-size: 11px;
            /* Reduced from 12px to save space */
            color: #2b2b2b;
            margin: 0;
            padding: 0;
        }

        /* ---------- HEADER ---------- */
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            padding: 0;
            line-height: 0;
        }

        .header-table img {
            display: block;
            vertical-align: top;
        }

        .brand-name {
            font-size: 20px;
            /* Reduced */
            font-weight: bold;
            color: #7a1f1f;
            letter-spacing: 1px;
            margin: 0;
        }

        .brand-sub {
            font-size: 9px;
            /* Reduced */
            color: #6b6b6b;
            margin-top: 1px;
        }

        .doc-title {
            text-align: center;
            font-size: 14px;
            /* Reduced */
            font-weight: bold;
            letter-spacing: 2px;
            color: #ffffff;
            background: #7a1f1f;
            padding: 4px 0;
            /* Reduced padding */
            margin: 0 0 10px 0;
            /* Reduced margin */
            border-radius: 3px;
        }

        /* ---------- TO / META BOX ---------- */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            /* Reduced margin */
        }

        .meta-table td {
            border: none;
            vertical-align: top;
            padding: 6px 10px;
            /* Reduced padding */
            background: #f7f5f2;
        }

        .meta-table .meta-right {
            border-left: 1px solid #e2ddd6;
        }

        .meta-label {
            font-size: 8px;
            /* Reduced */
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9a9a9a;
            margin-bottom: 1px;
            /* Reduced */
        }

        .meta-value {
            font-size: 11px;
            /* Reduced */
            font-weight: bold;
            color: #2b2b2b;
        }

        .meta-line {
            margin-bottom: 4px;
            /* Reduced */
            line-height: 1.3;
        }

        /* ---------- DESCRIPTION ---------- */
        .description {
            margin: 0 0 8px 0;
            /* Reduced margin */
            line-height: 1.4;
            /* Reduced line height */
            color: #4a4a4a;
        }

        /* ---------- ITEMS TABLE ---------- */
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
            /* Reduced margin */
        }

        table.items thead th {
            background: #7a1f1f;
            color: #ffffff;
            font-size: 9px;
            /* Reduced */
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 5px 4px;
            /* Reduced padding */
            border: 1px solid #7a1f1f;
        }

        table.items tbody td {
            border: 1px solid #e2ddd6;
            padding: 4px 5px;
            /* Reduced padding */
            font-size: 10px;
            /* Reduced */
        }

        table.items tbody tr:nth-child(even) td {
            background: #f7f5f2;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* ---------- TOTALS ---------- */
        table.totals {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }

        table.totals td {
            padding: 4px 6px;
            /* Reduced padding */
            font-size: 10px;
            /* Reduced */
            border: none;
        }

        table.totals .totals-label {
            text-align: right;
            color: #6b6b6b;
        }

        table.totals .totals-value {
            text-align: right;
            width: 110px;
        }

        table.totals .grand-row td {
            border-top: 2px solid #7a1f1f;
            padding-top: 6px;
            /* Reduced padding */
            font-size: 12px;
            /* Reduced */
            font-weight: bold;
            color: #7a1f1f;
        }

        /* ---------- NOTES ---------- */
        .notes-box {
            background: #f7f5f2;
            border-left: 3px solid #7a1f1f;
            padding: 6px 10px;
            /* Reduced padding */
            margin: 8px 0;
            /* Reduced margin */
        }

        .notes-box ul {
            margin: 0;
            padding-left: 14px;
            /* Reduced */
        }

        .notes-box li {
            margin-bottom: 2px;
            /* Reduced */
            line-height: 1.3;
        }

        .red {
            color: #b02a2a;
            font-weight: bold;
        }

        .section-heading {
            font-size: 10px;
            /* Reduced */
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #7a1f1f;
            margin: 10px 0 4px 0;
            /* Reduced margin */
        }

        .terms-list {
            margin: 0;
            padding-left: 14px;
            line-height: 1.4;
        }

        /* ---------- SIGNATURE ---------- */
        .sign-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            /* Reduced from 34px */
        }

        .sign-table td {
            border: none;
            padding: 0;
            font-size: 10px;
            /* Reduced */
        }

        .sign-table .sign-name {
            font-size: 12px;
            /* Reduced */
            font-weight: bold;
            color: #7a1f1f;
            margin: 12px 0 2px 0;
            /* Reduced margin */
        }

        .sign-line {
            border-top: 1px solid #2b2b2b;
            width: 70%;
            margin-top: 15px;
            /* Reduced from 30px */
            padding-top: 3px;
            font-size: 9px;
            color: #6b6b6b;
        }

        /* ---------- BANK DETAILS ---------- */
        .bank-box {
            margin-top: 10px;
            /* Reduced from 22px */
            background: #f7f5f2;
            padding: 6px 10px;
            /* Reduced */
            width: 55%;
            border-radius: 3px;
        }

        .bank-box .bank-title {
            font-size: 9px;
            /* Reduced */
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #7a1f1f;
            margin-bottom: 4px;
            /* Reduced */
        }

        .bank-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .bank-box td {
            border: none;
            padding: 1px 0;
            /* Reduced */
            font-size: 9px;
            /* Reduced */
        }

        .bank-box .bank-key {
            color: #6b6b6b;
            width: 90px;
        }

        .discount-row td {
            background: #fff3f3;
            border-top: 1px solid #e5b5b5;
            border-bottom: 1px solid #e5b5b5;
            padding: 8px 6px;
        }

        .discount-label {
            color: #b02a2a;
            font-weight: bold;
        }

        .discount-value {
            color: #b02a2a;
            font-weight: bold;
        }

    </style>
</head>

<body>

    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td width="100%">
                <img src="{{ public_path('images/header_2.jpg') }}" width="100%">
            </td>
        </tr>
    </table>

    <div class="doc-title">ESTIMATE</div>

    <!-- TO DETAILS -->
    <table class="meta-table">
        <tr>
            <td width="55%">
                <div class="meta-label">Estimate For</div>
                <div class="meta-line meta-value">{{ $estimate->customer_name }}</div>
                <div class="meta-line">{{ $estimate->customer_address }}</div>
                <div class="meta-line">Mobile: {{ $estimate->mobile }}</div>
            </td>
            <td width="45%" class="meta-right">
                <div class="meta-line">
                    <span class="meta-label">Estimate No.</span><br>
                    <span class="meta-value">{{ $estimate->estimate_no }}</span>
                </div>
                <div class="meta-line">
                    <span class="meta-label">Date</span><br>
                    <span class="meta-value">{{ $estimate->estimate_date }}</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- DESCRIPTION -->
    @if($estimate->description)
    <p class="description">
        {!! nl2br(e($estimate->description)) !!}
    </p>
    @endif

    <!-- ITEMS TABLE -->
    <table class="items">
        <thead>
            <tr>
                <th style="text-align:left;">Location</th>
                <th class="text-center">Area</th>
                <th class="text-right">Rate</th>
                <th class="text-right">Value (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estimate->items as $item)
            <tr>
                <td>{{ $item->location }}</td>
                <td class="text-center">{{ rtrim(rtrim(number_format($item->area, 2), '0'), '.') }} {{ $item->unit }}</td>
                <td class="text-right">{{ number_format($item->rate,2) }}/- (Per {{ $item->unit }})</td>
                <td class="text-right">{{ number_format($item->value,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- TOTALS -->
    <table class="totals">
        @if($estimate->discount > 0)

        <tr class="discount-row">
            <td colspan="2"></td>

            <td class="totals-label discount-label">
                Discount
            </td>

            <td class="totals-value discount-value">
                - {{ number_format($estimate->discount, 2) }}
            </td>
        </tr>

        @endif
        <tr>
            <td colspan="2"></td>
            <td class="totals-label">GST {{ (int) $estimate->gst_percent }}%</td>
            <td class="totals-value">{{ number_format($estimate->gst_amount,2) }}</td>
        </tr>

        @if($estimate->transport_charges > 0)
        <tr>
            <td colspan="2"></td>
            <td class="totals-label">Transportation Charges</td>
            <td class="totals-value">{{ number_format($estimate->transport_charges,2) }}</td>
        </tr>
        @endif

        <tr class="grand-row">
            <td colspan="2"></td>
            <td class="totals-label">TOTAL</td>
            <td class="totals-value">₹ {{ number_format($estimate->net_total,2) }}</td>
        </tr>
    </table>

    <!-- NOTES -->
    <div class="notes-box">
        <ul>
            <li><b>Total Value:</b> ₹ {{ number_format($estimate->net_total,2) }}/-</li>
            <li>(Rupees: {{ $amountWords }})</li>
            <li class="red">FINISHED AREA WILL BE TAKEN FOR FINAL MEASUREMENT.</li>
            <li class="red">Transportation charges will be extra.</li>
            <li><b>NOTE:</b> CIVIL WORK  IS UNDER THE CLIENT'S SCOPE OF WORK.</li>
            {{-- ADDITIONAL USER NOTES --}}

            @if($estimate->notes && $estimate->notes->count())

            @foreach($estimate->notes as $note)

            <li>
                {{ $note->note }}
            </li>

            @endforeach

            @endif
        </ul>
    </div>

    <!-- PAYMENT TERMS -->
    <div class="section-heading">Payment Terms</div>
    <ul class="terms-list">
        <li>60% Advance</li>
        <li>30% on Progress</li>
        <li>10% on Completion</li>
    </ul>

    <!-- SIGNATURE -->
    <table class="sign-table">
        <tr>
            <td width="50%">
                <div>For Amudha Decors</div>
                <div class="sign-name">{{ $estimate->estimatedby }}</div>
                <div class="sign-line">Authorised Signatory</div>
            </td>
            <td width="50%" class="text-right">
                <div class="sign-line" style="margin-left:auto;">Customer Seal &amp; Signature</div>
            </td>
        </tr>
    </table>

    <!-- BANK DETAILS -->
    <div class="bank-box">
        <div class="bank-title">RTGS Details</div>
        <table>
            <tr>
                <td class="bank-key">Name</td>
                <td>AMUDHA DECORS</td>
            </tr>
            <tr>
                <td class="bank-key">A/C No.</td>
                <td>610 930 7000 1293</td>
            </tr>
            <tr>
                <td class="bank-key">Bank</td>
                <td>Canara Bank</td>
            </tr>
            <tr>
                <td class="bank-key">Branch</td>
                <td>Porur</td>
            </tr>
            <tr>
                <td class="bank-key">IFSC Code</td>
                <td>CNRB0002950</td>
            </tr>
        </table>
    </div>

</body>

</html>
