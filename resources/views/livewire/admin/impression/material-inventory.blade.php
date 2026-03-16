<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventaire Matériel</title>
    <style>
        @page { margin: 10mm; size: landscape; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #333; }
        .header { border-bottom: 3px solid #4f46e5; padding-bottom: 8px; margin-bottom: 12px; }
        .title { font-size: 16px; font-weight: bold; color: #4f46e5; }
        .subtitle { font-size: 9px; color: #666; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th { background: #4f46e5; color: #fff; padding: 5px 6px; text-align: left; font-size: 8px; text-transform: uppercase; }
        table.data td { padding: 4px 6px; border-bottom: 1px solid #e5e7eb; font-size: 9px; }
        table.data tr:nth-child(even) { background: #f9fafb; }
        .total-row { font-weight: bold; background: #f3f4f6 !important; border-top: 2px solid #333; }
        .footer { margin-top: 15px; border-top: 1px solid #e5e7eb; padding-top: 6px; text-align: center; font-size: 8px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">INVENTAIRE MATÉRIEL</div>
        <div class="subtitle">Date: {{ now()->format('d/m/Y') }} | Total articles: {{ $materials->count() }} | Valeur totale: {{ number_format($totalValue, 2) }} MAD</div>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>Réf.</th>
                <th>Désignation</th>
                <th>Catégorie</th>
                <th>Qté</th>
                <th>Prix unit.</th>
                <th>Valeur</th>
                <th>État</th>
                <th>Statut</th>
                <th>Emplacement</th>
                <th>Fournisseur</th>
                <th>N° Série</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materials as $m)
            <tr>
                <td style="font-family: monospace;">{{ $m->reference }}</td>
                <td>{{ $m->name }}</td>
                <td>{{ $m->category->name ?? '-' }}</td>
                <td style="text-align: center;">{{ $m->quantity }}</td>
                <td style="text-align: right;">{{ $m->prix_unitaire ? number_format($m->prix_unitaire, 2) : '-' }}</td>
                <td style="text-align: right;">{{ $m->valeur_totale ? number_format($m->valeur_totale, 2) : '-' }}</td>
                <td>{{ ucfirst($m->etat) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $m->status)) }}</td>
                <td>{{ $m->emplacement ?? '-' }}</td>
                <td>{{ $m->fournisseur ?? '-' }}</td>
                <td style="font-size: 8px;">{{ $m->numero_serie ?? '-' }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3">TOTAL</td>
                <td style="text-align: center;">{{ $materials->sum('quantity') }}</td>
                <td></td>
                <td style="text-align: right;">{{ number_format($totalValue, 2) }} MAD</td>
                <td colspan="5"></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Inventaire généré le {{ now()->format('d/m/Y à H:i') }}
    </div>
</body>
</html>
