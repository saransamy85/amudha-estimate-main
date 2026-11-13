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
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #252525;
            margin: 0;
            padding: 0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .header-table td {
            border: none;
            padding: 0;
        }

        .header-table img {
            display: block;
            width: 100%;
            height: auto;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .meta-table td {
            border: none;
            vertical-align: top;
        }

        .meta-left {
            width: 40%;
            padding-right: 10px;
        }

        .meta-center {
            width: 25%;
            text-align: center;
        }

        .meta-right {
            width: 35%;
            padding-left: 20px;
        }

        .doc-title {
            color: #292961;
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .title-line {
            width: 100%;
            height: 2px;
            background: #8B1E1E;
            margin: 0 auto;
        }

        .estimate-to {
            color: #292961;
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 6px;
        }

        .customer-name {
            color: #8B1E1E;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .meta-line {
            line-height: 1.6;
            color: #333;
            font-size: 10px;
        }

        .meta-right table {
            width: 100%;
            font-size: 10px;
            line-height: 1.8;
        }

        .meta-right td.label {
            font-weight: bold;
            color: #252525;
            width: 40%;
        }

        .meta-right td.value {
            color: #333;
        }

        .section-title {
            /* background: #292961; */
            background: #7a1717ff !important;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 15px;
            margin-bottom: 10px;
            display: inline-block;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
            letter-spacing: 0.5px;
        }

        .description-box {
            margin-bottom: 15px;
        }

        .description-text {
            padding: 0 5px;
            color: #333;
            line-height: 1.5;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table.items thead th {
            /* background: #292961; */
            background: #7a1717ff !important;
            color: #ffffff;
            font-size: 9px;
            text-transform: uppercase;
            padding: 8px 5px;
            border: 1px solid #292961;
        }

        table.items tbody td {
            border: 1px solid #d5dce2;
            padding: 8px 6px;
            font-size: 10px;
            vertical-align: top;
        }

        .items .col-no {
            width: 6%;
            text-align: center;
            color: #8B1E1E;
            font-weight: bold;
        }

        .items .col-desc {
            width: 44%;
            text-align: left;
        }

        .items .col-area {
            width: 16%;
            text-align: center;
        }

        .items .col-rate {
            width: 17%;
            text-align: center;
        }

        .items .col-val {
            width: 17%;
            text-align: right;
            font-weight: bold;
        }

        .item-title {
            color: #292961;
            font-weight: bold;
            margin-bottom: 3px;
            font-size: 10.5px;
        }

        table.totals-wrapper {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.totals-wrapper td {
            vertical-align: top;
        }

        .totals-left {
            width: 55%;
            padding-right: 15px;
        }

        .totals-right {
            width: 45%;
        }

        .amount-words-box {
            border: 1px solid #d5dce2;
            border-radius: 4px;
            padding: 10px;
            display: flex;
            align-items: flex-start;
        }

        .amount-words-title {
            /* color: #8B1E1E; */
            color: #7a1717ff !important;
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 4px;
        }

        .amount-words-text {
            color: #333;
            line-height: 1.4;
        }

        table.totals {
            width: 100%;
            border-collapse: collapse;
        }

        table.totals td {
            border: 1px solid #d5dce2;
            padding: 7px 10px;
            font-size: 10px;
        }

        .totals-label {
            text-align: left;
            font-weight: bold;
            color: #292961;
            width: 50%;
        }

        .totals-value {
            text-align: right;
            font-weight: bold;
            width: 50%;
        }

        .discount-label,
        .discount-value {
            /* color: #8B1E1E !important; */
            color: #7a1717ff !important;
        }

        .grand-row td {
            /* background: #8B1E1E !important; */
            background: #7a1717ff !important;
            color: #ffffff !important;
            /* border: 1px solid #8B1E1E !important; */
            border: 1px solid #7a1717ff !important;
            font-size: 12px;
            padding: 10px !important;
        }

        .grand-label {
            text-align: left;
            font-weight: bold;
        }

        .grand-value {
            text-align: right;
            font-weight: bold;
        }

        .bottom-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .bottom-table td {
            vertical-align: top;
            border: none;
        }

        .notes-column {
            width: 55%;
            padding-right: 15px;
        }

        .payment-column {
            width: 45%;
        }

        .notes-list {
            margin: 0;
            padding-left: 15px;
        }

        .notes-list li {
            margin-bottom: 5px;
            line-height: 1.4;
            font-size: 9px;
        }

        .red {
            /* color: #8B1E1E; */
            color: #7a1717ff !important;
            text-transform: uppercase;
            font-weight: bold;
        }

        .payment-item {
            margin-bottom: 8px;
            font-size: 9.5px;
        }

        .payment-number {
            display: inline-block;
            /* background: #8B1E1E; */
            background: #7a1717ff !important;
            color: #ffffff;
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: bold;
            margin-right: 8px;
            font-size: 9px;
        }

        .signature-section {
            margin-top: 30px;
            text-align: right;
        }

        .signature-company {
            /* color: #8B1E1E; */
            color: #7a1717ff !important;
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 40px;
        }

        .signature-line {
            border-top: 1px dashed #777;
            display: inline-block;
            width: 180px;
            padding-top: 5px;
            font-size: 10px;
            color: #333;
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            width: 100%;
            height: 8px;
        }

        .footer-left {
            display: inline-block;
            width: 60%;
            height: 100%;
            background: #292961;
        }

        .footer-right {
            display: inline-block;
            width: 40%;
            height: 100%;
            background: #7a1717ff !important;
        }

    </style>
</head>
<body>
    <div class="page">
        <!-- HEADER -->
        <table class="header-table">
            <tr>
                <td><img src="{{ public_path('images/amudha_decors.jpg') }}"></td>
            </tr>
        </table>

        <!-- CUSTOMER & META -->
        <table class="meta-table">
            <tr>
                <td class="meta-left">
                    <div class="estimate-to">To,</div>
                    <div class="customer-name">{{ $estimate->customer_name }}</div>
                    <div class="meta-line">
                        @if($estimate->customer_address)
                        {!! nl2br(e($estimate->customer_address)) !!}<br>
                        @endif
                        @if($estimate->mobile)
                        Mobile &nbsp;&nbsp;&nbsp;: {{ $estimate->mobile }}<br>
                        @endif
                        @if(isset($estimate->email) && $estimate->email)
                        Email &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $estimate->email }}<br>
                        @endif
                    </div>
                </td>
                <td class="meta-center">
                    <div class="doc-title">ESTIMATE</div>
                    <div class="title-line"></div>
                </td>
                <td class="meta-right">
                    <table>
                        <tr>
                            <td class="label">Estimate No.</td>
                            <td class="value">: {{ $estimate->estimate_no }}</td>
                        </tr>
                        <tr>
                            <td class="label">Date</td>
                            <td class="value">: {{ \Carbon\Carbon::parse($estimate->estimate_date)->format('d / m / Y') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Valid Till</td>
                            <td class="value">: {{ \Carbon\Carbon::parse($estimate->estimate_date)->addDays(30)->format('d / m / Y') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Prepared By</td>
                            <td class="value">: {{ $estimate->estimatedby ?? 'Saravanan' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- DESCRIPTION -->
        @if($estimate->description)
        <div class="description-box">
            <div class="section-title">PROJECT DESCRIPTION</div>
            <div class="description-text">
                {!! nl2br(e($estimate->description)) !!}
            </div>
        </div>
        @endif

        <!-- ITEMS -->
        <table class="items">
            <thead>
                <tr>
                    <th class="col-no">#</th>
                    <th class="col-desc">DESCRIPTION / LOCATION</th>
                    <th class="col-area">AREA / QTY</th>
                    <th class="col-rate">RATE</th>
                    <th class="col-val">AMOUNT (₹)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estimate->items as $index => $item)
                <tr>
                    <td class="col-no">{{ sprintf('%02d', $index + 1) }}</td>
                    <td class="col-desc">
                        <div class="item-title">{{ $item->location }}</div>
                        @if(!empty($item->description))
                        <div style="font-size: 9px; color: #555;">{!! nl2br(e($item->description)) !!}</div>
                        @endif
                    </td>
                    <td class="col-area">{{ rtrim(rtrim(number_format($item->area, 2), '0'), '.') }} {{ $item->unit }}</td>
                    <td class="col-rate">₹ {{ number_format($item->rate, 2) }} / {{ $item->unit }}</td>
                    <td class="col-val">₹ {{ number_format($item->value, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- TOTALS WRAPPER -->
        <table class="totals-wrapper">
            <tr>
                <td class="totals-left">
                    <div class="amount-words-box">
                        <table style="width:100%; border:none;">
                            <tr>
                                <td style="width:12%; vertical-align:top; text-align:center;">
                                    <div style="background:#292961; color:#fff; border-radius:50%; width:22px; height:22px; line-height:22px; font-weight:bold; font-size: 11px; margin: 0 auto;">₹</div>
                                </td>
                                <td style="width:88%; padding-left:10px;">
                                    <div class="amount-words-title">Total Amount (in Words):</div>
                                    <div class="amount-words-text">Rupees {{ $amountWords }} Only.</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td class="totals-right">
                    <table class="totals">
                        <tr>
                            <td class="totals-label">SUB TOTAL</td>
                            <td class="totals-value">₹ {{ number_format($estimate->sub_total ?? $estimate->items->sum('value'), 2) }}</td>
                        </tr>
                        @if(($estimate->discount ?? 0) > 0)
                        <tr>
                            <td class="totals-label discount-label">DISCOUNT</td>
                            <td class="totals-value discount-value">- ₹ {{ number_format($estimate->discount, 2) }}</td>
                        </tr>
                        @endif
                        @if(($estimate->transport_charges ?? 0) > 0)
                        <tr>
                            <td class="totals-label">TRANSPORTATION</td>
                            <td class="totals-value">₹ {{ number_format($estimate->transport_charges, 2) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="totals-label">GST {{ (int) $estimate->gst_percent }}%</td>
                            <td class="totals-value">₹ {{ number_format($estimate->gst_amount, 2) }}</td>
                        </tr>
                        <tr class="grand-row">
                            <td class="grand-label">GRAND TOTAL</td>
                            <td class="grand-value">₹ {{ number_format($estimate->net_total, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- NOTES AND PAYMENT -->
        <table class="bottom-table">
            <tr>
                <td class="notes-column">
                    <div style="margin-bottom: 5px;">
                        <div class="section-title" style="border-radius: 4px; padding: 6px 12px; margin-bottom:0;">NOTES</div>
                    </div>
                    <div style="border: 1px solid #292961; border-radius: 4px; padding: 10px;">
                        <ul class="notes-list">
                            <li><strong>Total Value:</strong> ₹ {{ number_format($estimate->net_total, 2) }}/-</li>
                            <li>(Rupees: {{ $amountWords }} Only)</li>
                            <li class="red">FINISHED AREA WILL BE TAKEN FOR FINAL MEASUREMENT.</li>
                            <li>Transportation charges will be extra.</li>
                            <li><strong>NOTE:</strong> CIVIL WORK &amp; SCAFFOLDING IS UNDER THE CLIENT'S SCOPE OF WORK.</li>
                            @if($estimate->notes && $estimate->notes->count())
                            @foreach($estimate->notes as $note)
                            <li>{{ $note->note }}</li>
                            @endforeach
                            @endif
                        </ul>
                    </div>
                </td>
                <td class="payment-column">
                    <div style="margin-bottom: 5px;">
                        <div class="section-title" style="border-radius: 4px; padding: 6px 12px; margin-bottom:0;">PAYMENT TERMS</div>
                    </div>
                    <div style="border: 1px solid #292961; border-radius: 4px; padding: 10px;">
                        <div class="payment-item"><span class="payment-number">01</span>60% Advance</div>
                        <div class="payment-item" style="border-top: 1px dotted #ccc; padding-top: 8px;"><span class="payment-number">02</span> 30% on Progress</div>
                        <div class="payment-item" style="border-top: 1px dotted #ccc; padding-top: 8px;"><span class="payment-number">03</span> 10% on Completion</div>
                    </div>

                    <div class="signature-section">
                        <div class="signature-company">For AMUDHA DECORS</div>
                        <br><br>
                        <div class="signature-line">Authorised Signatory</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer">
            <span class="footer-left"></span><span class="footer-right"></span>
        </div>
    </div>
</body>
</html>
