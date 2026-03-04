<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche Matériel - {{ $material->reference }}</title>
    <style>
        @page { margin: 15mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }
        .header { border-bottom: 3px solid #4f46e5; padding-bottom: 10px; margin-bottom: 15px; }
        .title { font-size: 18px; font-weight: bold; color: #4f46e5; }
        .subtitle { font-size: 10px; color: #666; }
        .info-box { border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px; margin-bottom: 12px; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 4px 8px; font-size: 11px; vertical-align: top; }
        .info-label { color: #6366f1; font-weight: bold; width: 35%; }
        .section { font-size: 12px; font-weight: bold; color: #4f46e5; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin: 14px 0 8px 0; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th { background: #f3f4f6; padding: 5px 8px; text-align: left; font-size: 9px; text-transform: uppercase; color: #374151; border: 1px solid #e5e7eb; }
        table.data td { padding: 4px 8px; font-size: 10px; border: 1px solid #e5e7eb; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .footer { margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 6px; text-align: center; font-size: 8px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">FICHE MATÉRIEL</div>
        <div class="subtitle">Réf: {{ $material->reference }} | Généré le {{ now()->format('d/m/Y') }}</div>
    </div>

    <div class="info-box">
        <table class="info-table">
            <tr><td class="info-label">Nom</td><td>{{ $material->name }}</td></tr>
            <tr><td class="info-label">Référence</td><td>{{ $material->reference }}</td></tr>
            <tr><td class="info-label">Catégorie</td><td>{{ $material->category->name ?? '-' }}</td></tr>
            <tr><td class="info-label">Quantité</td><td>{{ $material->quantity }}</td></tr>
            <tr><td class="info-label">Prix unitaire</td><td>{{ $material->prix_unitaire ? number_format($material->prix_unitaire, 2) . ' MAD' : '-' }}</td></tr>
            <tr><td class="info-label">Valeur totale</td><td>{{ $material->valeur_totale ? number_format($material->valeur_totale, 2) . ' MAD' : '-' }}</td></tr>
            <tr><td class="info-label">État</td><td>{{ ucfirst($material->etat) }}</td></tr>
            <tr><td class="info-label">Statut</td><td>{{ ucfirst(str_replace('_', ' ', $material->status)) }}</td></tr>
            <tr><td class="info-label">Emplacement</td><td>{{ $material->emplacement ?? '-' }}</td></tr>
            <tr><td class="info-label">Fournisseur</td><td>{{ $material->fournisseur ?? '-' }}</td></tr>
            <tr><td class="info-label">N° de série</td><td>{{ $material->numero_serie ?? '-' }}</td></tr>
            <tr><td class="info-label">Date d'acquisition</td><td>{{ $material->date_acquisition ? $material->date_acquisition->format('d/m/Y') : '-' }}</td></tr>
            <tr><td class="info-label">Fin garantie</td><td>{{ $material->date_garantie ? $material->date_garantie->format('d/m/Y') : '-' }}</td></tr>
        </table>
    </div>

    @if($material->description)
    <div class="section">Description</div>
    <p>{{ $material->description }}</p>
    @endif

    @if($material->movements->count() > 0)
    <div class="section">Historique des mouvements</div>
    <table class="data">
        <thead><tr><th>Date</th><th>Type</th><th>Quantité</th><th>Motif</th></tr></thead>
        <tbody>
            @foreach($material->movements as $mv)
            <tr>
                <td>{{ $mv->created_at->format('d/m/Y') }}</td>
                <td>{{ ucfirst($mv->type) }}</td>
                <td>{{ $mv->quantity }}</td>
                <td>{{ $mv->motif ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($material->maintenances->count() > 0)
    <div class="section">Historique maintenance</div>
    <table class="data">
        <thead><tr><th>Date</th><th>Type</th><th>Description</th><th>Coût</th><th>Statut</th></tr></thead>
        <tbody>
            @foreach($material->maintenances as $m)
            <tr>
                <td>{{ $m->date_maintenance->format('d/m/Y') }}</td>
                <td>{{ $m->type_maintenance ?? '-' }}</td>
                <td>{{ $m->description ?? '-' }}</td>
                <td>{{ $m->cout ? number_format($m->cout, 2) . ' MAD' : '-' }}</td>
                <td>{{ ucfirst($m->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        Fiche générée le {{ now()->format('d/m/Y à H:i') }}
    </div>
</body>
</html>
