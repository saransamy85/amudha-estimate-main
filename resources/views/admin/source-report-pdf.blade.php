<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Lead Source & Marketing Acquisition Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222222;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        h2, h3, h4 {
            margin: 4px 0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .header-box {
            text-align: center;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }

        .header-box h2 {
            font-size: 20px;
            color: #0f172a;
            letter-spacing: 0.5px;
            margin: 0 0 3px 0;
        }

        .header-box h3 {
            font-size: 13.5px;
            color: #0d6efd;
            margin: 0 0 4px 0;
            text-transform: uppercase;
        }

        .period-text {
            font-size: 11px;
            color: #475569;
            margin: 0;
        }

        .scope-badge {
            font-size: 10px;
            color: #0d6efd;
            font-weight: bold;
            margin: 3px 0 0 0;
        }

        /* Executive KPI Summary Table */
        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .summary td {
            border: 1px solid #cbd5e1;
            padding: 7px 10px;
            background-color: #f8fafc;
        }

        .summary td b {
            color: #334155;
            font-size: 10.5px;
        }

        .summary .val {
            font-weight: bold;
            font-size: 12px;
            color: #0f172a;
        }

        /* Section Titles matching Business Report */
        .section-title {
            background: #0d6efd;
            color: white;
            padding: 5px 8px;
            margin-top: 14px;
            margin-bottom: 6px;
            font-size: 11.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Data Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
        }

        .table th {
            background: #f1f5f9;
            color: #1e293b;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }

        .table tr:nth-child(even) td {
            background-color: #fafafa;
        }

        .table-total td {
            background: #e2e8f0 !important;
            font-weight: bold;
            color: #0f172a;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            font-size: 9.5px;
            color: #64748b;
        }

        .badge-status {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8.5px;
            font-weight: bold;
            border-radius: 3px;
            background: #e2e8f0;
            color: #334155;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <div class="header-box">
        <h2>AMUDHA DECORS</h2>
        <h3>Lead Source & Marketing Acquisition Statement</h3>
        <p class="period-text">
            <b>Date Period:</b> {{ $from->format('d-m-Y') }} to {{ $to->format('d-m-Y') }}
        </p>
        <p class="scope-badge">
            <b>Channels Scope:</b> {{ $scopeText ?? 'All Marketing Channels' }}
        </p>
    </div>

    {{-- Source-Focused Summary Table --}}
    <table class="summary">
        <tr>
            <td width="25%"><b>Filtered Enquiries:</b></td>
            <td width="25%" class="val">{{ $totalLeads }}</td>
            <td width="25%"><b>Active Channels:</b></td>
            <td width="25%" class="val">
                {{ $leadSources->count() }} of {{ !empty($selectedSources) && !($isAllSelected ?? false) ? count($selectedSources) : 6 }}
            </td>
        </tr>
        <tr>
            <td width="25%"><b>Confirmed Orders:</b></td>
            <td width="25%" class="val text-success">
                {{ $confirmedCount }}
                @if($totalLeads > 0)
                    <span style="font-size: 10px; color: #16a34a; font-weight: normal;">
                        ({{ round(($confirmedCount / $totalLeads) * 100, 1) }}%)
                    </span>
                @endif
            </td>
            <td width="25%"><b>Quoted Pipeline:</b></td>
            <td width="25%" class="val">{{ $quotedCount }}</td>
        </tr>
    </table>

    {{-- 1. Marketing Channels Breakdown --}}
    <div class="section-title">
        Selected Channel Acquisition Breakdown
    </div>

    <table class="table">
        <thead>
            <tr>
                <th class="text-center" style="width: 35px;">#</th>
                <th class="text-left">Marketing Channel / Source</th>
                <th class="text-center" style="width: 90px;">Total Leads</th>
                <th class="text-center" style="width: 90px;">Volume Share</th>
                <th class="text-center" style="width: 100px;">Confirmed Orders</th>
                <th class="text-center" style="width: 100px;">Conversion Rate</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leadSources as $row)
                @php
                    $sourceTotal = $row->total;
                    $sourceShare = $totalLeads > 0 ? round(($sourceTotal / $totalLeads) * 100, 1) : 0;
                    $conf = $sourceConfirmed[$row->source] ?? 0;
                    $convRate = $sourceTotal > 0 ? round(($conf / $sourceTotal) * 100, 1) : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-left"><b>{{ $row->source ?: 'Direct / Unspecified' }}</b></td>
                    <td class="text-center">{{ $sourceTotal }}</td>
                    <td class="text-center">{{ $sourceShare }}%</td>
                    <td class="text-center">{{ $conf }}</td>
                    <td class="text-center">{{ $convRate }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 12px; color: #64748b;">
                        No enquiries registered within this period for the selected channels.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($totalLeads > 0)
            <tfoot>
                <tr class="table-total">
                    <td colspan="2" class="text-left"><b>TOTAL FILTERED ENQUIRIES</b></td>
                    <td class="text-center"><b>{{ $totalLeads }}</b></td>
                    <td class="text-center"><b>100%</b></td>
                    <td class="text-center"><b>{{ $confirmedCount }}</b></td>
                    <td class="text-center"><b>{{ round(($confirmedCount / $totalLeads) * 100, 1) }}%</b></td>
                </tr>
            </tfoot>
        @endif
    </table>

    {{-- 2. Side by Side: Product Wise Leads & Status Report --}}
    <table style="width: 100%; border: none; margin-bottom: 10px;">
        <tr>
            {{-- Product Wise Leads --}}
            <td style="border: none; padding: 0; vertical-align: top; width: 48%;">
                <div class="section-title">
                    Product Requirements
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-left">Product</th>
                            <th class="text-center" style="width: 60px;">Count</th>
                            <th class="text-center" style="width: 60px;">Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productReport as $row)
                            @php
                                $pShare = $totalLeads > 0 ? round(($row->total / $totalLeads) * 100, 1) : 0;
                            @endphp
                            <tr>
                                <td class="text-left">{{ $row->Product ?: 'General / Unspecified' }}</td>
                                <td class="text-center">{{ $row->total }}</td>
                                <td class="text-center">{{ $pShare }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center" style="color: #64748b;">No Records</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </td>

            <td style="border: none; padding: 0; width: 4%;"></td>

            {{-- Lead Status Report --}}
            <td style="border: none; padding: 0; vertical-align: top; width: 48%;">
                <div class="section-title">
                    Pipeline Status Progression
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-left">Status Stage</th>
                            <th class="text-center" style="width: 60px;">Count</th>
                            <th class="text-center" style="width: 60px;">Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($statusReport as $row)
                            @php
                                $sShare = $totalLeads > 0 ? round(($row->total / $totalLeads) * 100, 1) : 0;
                            @endphp
                            <tr>
                                <td class="text-left">{{ $row->Status ?: 'Unassigned' }}</td>
                                <td class="text-center">{{ $row->total }}</td>
                                <td class="text-center">{{ $sShare }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center" style="color: #64748b;">No Records</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    {{-- 3. Detailed Lead Register --}}
    <div class="section-title">
        Filtered Enquiries Register ({{ $leadList->count() }} Records)
    </div>

    <table class="table">
        <thead>
            <tr>
                <th class="text-center" style="width: 25px;">#</th>
                <th class="text-center" style="width: 65px;">Date</th>
                <th class="text-left">Client Name</th>
                <th class="text-center" style="width: 80px;">Mobile</th>
                <th class="text-left">Product</th>
                <th class="text-center" style="width: 85px;">Source</th>
                <th class="text-center" style="width: 80px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leadList as $lead)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $lead->created_at ? \Carbon\Carbon::parse($lead->created_at)->format('d-m-Y') : '—' }}</td>
                    <td class="text-left"><b>{{ $lead->Name }}</b></td>
                    <td class="text-center">{{ $lead->Mobile }}</td>
                    <td class="text-left">{{ $lead->Product ?: '—' }}</td>
                    <td class="text-center">{{ $lead->source ?: 'Direct' }}</td>
                    <td class="text-center">
                        <span class="badge-status">{{ $lead->Status }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 14px; color: #64748b;">
                        No enquiries found for the selected period and source filters.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="footer">
        Amudha Decors CRM Management System &bull; Lead Source & Acquisition Statement &bull; Generated on {{ now('Asia/Kolkata')->format('d-m-Y h:i A') }}
    </div>

</body>

</html>
