<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport Financier</title>
    <style>
        @page { margin: 12mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #333; }
        .header { border-bottom: 3px solid #4f46e5; padding-bottom: 10px; margin-bottom: 15px; }
        .title { font-size: 18px; font-weight: bold; color: #4f46e5; }
        .subtitle { font-size: 10px; color: #666; }
        .summary { margin-bottom: 15px; }
        .summary table { width: 100%; border-collapse: collapse; }
        .summary td { padding: 8px 12px; text-align: center; }
        .summary .box { border: 1px solid #e5e7eb; border-radius: 6px; }
        .summary .label { font-size: 9px; color: #6b7280; text-transform: uppercase; font-weight: bold; }
        .summary .value { font-size: 16px; font-weight: bold; }
        .green { color: #059669; }
        .red { color: #dc2626; }
        .blue { color: #2563eb; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th { background: #4f46e5; color: #fff; padding: 6px 8px; text-align: left; font-size: 9px; text-transform: uppercase; }
        table.data td { padding: 5px 8px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        table.data tr:nth-child(even) { background: #f9fafb; }
        .footer { margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 6px; text-align: center; font-size: 8px; color: #9ca3af; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 8px; font-weight: bold; }
        .badge-rev { background: #d1fae5; color: #065f46; }
        .badge-dep { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">RAPPORT FINANCIER</div>
        <div class="subtitle">Période: {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} – {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}</div>
    </div>

    <div class="summary">
        <table>
            <tr>
                <td><div class="box" style="padding: 10px;"><div class="label">Solde initial</div><div class="value blue">{{ number_format($caisse->solde_initial, 2) }} MAD</div></div></td>
                <td><div class="box" style="padding: 10px;"><div class="label">Total Revenus</div><div class="value green">+{{ number_format($totalRevenue, 2) }} MAD</div></div></td>
                <td><div class="box" style="padding: 10px;"><div class="label">Total Dépenses</div><div class="value red">-{{ number_format($totalDepense, 2) }} MAD</div></div></td>
                <td><div class="box" style="padding: 10px; {{ $solde >= 0 ? 'background:#ecfdf5;' : 'background:#fef2f2;' }}"><div class="label">Solde actuel</div><div class="value {{ $solde >= 0 ? 'green' : 'red' }}">{{ number_format($solde, 2) }} MAD</div></div></td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>Date</th>
                <th>Réf.</th>
                <th>Type</th>
                <th>Libellé</th>
                <th>Catégorie</th>
                <th>Bénéficiaire</th>
                <th style="text-align: right;">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
            <tr>
                <td>{{ $t->date_transaction->format('d/m/Y') }}</td>
                <td style="font-family: monospace; font-size: 8px;">{{ $t->reference }}</td>
                <td><span class="badge {{ $t->type === 'revenue' ? 'badge-rev' : 'badge-dep' }}">{{ $t->type === 'revenue' ? 'REV' : 'DEP' }}</span></td>
                <td>{{ $t->label }}</td>
                <td>{{ $t->category->name ?? '-' }}</td>
                <td>{{ $t->beneficiaire ?? '-' }}</td>
                <td style="text-align: right; font-weight: bold; {{ $t->type === 'revenue' ? 'color:#059669;' : 'color:#dc2626;' }}">
                    {{ $t->type === 'revenue' ? '+' : '-' }}{{ number_format($t->amount, 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; text-align: right;">
        <table style="width: auto; float: right; border-collapse: collapse;">
            <tr><td style="padding: 4px 12px; font-weight: bold;">Total Revenus:</td><td style="padding: 4px 12px; font-weight: bold; color: #059669;">+{{ number_format($totalRevenue, 2) }} MAD</td></tr>
            <tr><td style="padding: 4px 12px; font-weight: bold;">Total Dépenses:</td><td style="padding: 4px 12px; font-weight: bold; color: #dc2626;">-{{ number_format($totalDepense, 2) }} MAD</td></tr>
            <tr style="border-top: 2px solid #333;"><td style="padding: 4px 12px; font-weight: bold; font-size: 12px;">Solde:</td><td style="padding: 4px 12px; font-weight: bold; font-size: 12px; {{ $solde >= 0 ? 'color:#059669;' : 'color:#dc2626;' }}">{{ number_format($solde, 2) }} MAD</td></tr>
        </table>
        <div style="clear: both;"></div>
    </div>

    <div class="footer">
        Rapport généré le {{ now()->format('d/m/Y à H:i') }} | {{ $transactions->count() }} transactions
    </div>
</body>
</html>
