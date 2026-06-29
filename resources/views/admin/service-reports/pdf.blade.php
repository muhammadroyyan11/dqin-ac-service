<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Service Report #{{ $report->id }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; margin: 0; padding: 0; }
        .header { text-align: center; padding: 20px 0 10px; border-bottom: 2px solid #2563eb; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; color: #2563eb; }
        .header p { margin: 4px 0 0; color: #666; font-size: 11px; }
        .section { margin-bottom: 16px; }
        .section-title { font-size: 13px; font-weight: 700; color: #2563eb; border-bottom: 1px solid #ddd; padding-bottom: 4px; margin-bottom: 8px; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 3px 6px; font-size: 11px; }
        .info-table td:first-child { color: #888; width: 120px; }
        .info-table td:last-child { font-weight: 500; }
        .two-col { width: 100%; }
        .two-col td { width: 50%; vertical-align: top; padding-right: 10px; }
        .content-box { background: #f9f9f9; border: 1px solid #eee; border-radius: 4px; padding: 8px 10px; font-size: 11px; line-height: 1.6; min-height: 40px; }
        .spareparts-table { width: 100%; border-collapse: collapse; font-size: 11px; }
        .spareparts-table th { background: #2563eb; color: #fff; padding: 6px 8px; text-align: left; }
        .spareparts-table td { padding: 4px 8px; border-bottom: 1px solid #eee; }
        .spareparts-table tr:nth-child(even) { background: #f9f9f9; }
        .footer { text-align: center; padding-top: 20px; font-size: 10px; color: #999; border-top: 1px solid #eee; margin-top: 20px; }
        .signature-area { margin-top: 20px; }
        .signature-area td { padding: 10px; }
        .signature-box { border-top: 1px solid #333; padding-top: 4px; font-size: 10px; text-align: center; min-width: 120px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 600; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SERVICE REPORT</h1>
        <p>DQIN AC Service & Installation</p>
    </div>

    <div class="section">
        <div class="section-title">Work Order Information</div>
        <table class="info-table">
            <tr><td>WO Number</td><td>: {{ $report->workOrder->wo_number ?? '-' }}</td></tr>
            <tr><td>Service Type</td><td>: {{ $report->workOrder ? str_replace('_', ' ', ucfirst($report->workOrder->service_type)) : '-' }}</td></tr>
            <tr><td>Technician</td><td>: {{ $report->technician->full_name ?? '-' }}</td></tr>
            <tr><td>Report Date</td><td>: {{ $report->created_at->format('d M Y H:i') }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Customer Information</div>
        @if($report->workOrder && $report->workOrder->customer)
        <table class="info-table">
            <tr><td>Name</td><td>: {{ $report->workOrder->customer->full_name }}</td></tr>
            <tr><td>Phone</td><td>: {{ $report->workOrder->customer->phone ?? '-' }}</td></tr>
            <tr><td>Address</td><td>: {{ $report->workOrder->customer->address ?? '-' }}</td></tr>
        </table>
        @else
        <p style="color:#999;">-</p>
        @endif
    </div>

    <table class="two-col">
        <tr>
            <td>
                <div class="section">
                    <div class="section-title">Findings</div>
                    <div class="content-box">{!! $report->findings ?: '<em style="color:#999;">No findings recorded</em>' !!}</div>
                </div>
            </td>
            <td>
                <div class="section">
                    <div class="section-title">Actions Taken</div>
                    <div class="content-box">{!! $report->actions_taken ?: '<em style="color:#999;">No actions recorded</em>' !!}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="section">
        <div class="section-title">Spare Parts Used</div>
        @php $spareparts = $report->spareparts_used; @endphp
        @if($spareparts && is_array($spareparts) && count($spareparts) > 0)
        <table class="spareparts-table">
            <thead>
                <tr><th>#</th><th>Item</th><th>Qty</th></tr>
            </thead>
            <tbody>
                @foreach($spareparts as $i => $sp)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $sp['name'] ?? 'Item #'.$sp['id'] }}</td>
                    <td>{{ $sp['qty'] ?? 1 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="color:#999;font-size:11px;">No spare parts used.</p>
        @endif
    </div>

    @if($report->customer_notes)
    <div class="section">
        <div class="section-title">Customer Notes</div>
        <div class="content-box">{{ $report->customer_notes }}</div>
    </div>
    @endif

    <table class="signature-area" width="100%">
        <tr>
            <td align="center">
                <div class="signature-box">Technician</div>
            </td>
            <td align="center">
                <div class="signature-box">Customer</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        DQIN AC Service & Installation &middot; Generated on {{ now()->format('d M Y H:i') }}
    </div>
</body>
</html>
