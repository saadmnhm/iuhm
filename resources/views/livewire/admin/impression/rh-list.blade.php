<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des Employés</title>
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
        .footer { margin-top: 15px; border-top: 1px solid #e5e7eb; padding-top: 6px; text-align: center; font-size: 8px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LISTE DES EMPLOYÉS</div>
        <div class="subtitle">Date: {{ now()->format('d/m/Y') }} | Total: {{ $employees->count() }} employés actifs</div>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>#</th>
                <th>Matricule</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>CIN</th>
                <th>Poste</th>
                <th>Département</th>
                <th>Contrat</th>
                <th>Date embauche</th>
                <th>Email</th>
                <th>Téléphone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $idx => $emp)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $emp->matricule ?? '-' }}</td>
                <td style="font-weight: bold;">{{ $emp->nom }}</td>
                <td>{{ $emp->prenom }}</td>
                <td>{{ $emp->cin ?? '-' }}</td>
                <td>{{ $emp->poste ?? '-' }}</td>
                <td>{{ $emp->departement ?? '-' }}</td>
                <td>{{ $emp->contrat_type }}</td>
                <td>{{ $emp->date_embauche ? $emp->date_embauche->format('d/m/Y') : '-' }}</td>
                <td style="font-size: 8px;">{{ $emp->email ?? '-' }}</td>
                <td>{{ $emp->phone ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Liste générée le {{ now()->format('d/m/Y à H:i') }}
    </div>
</body>
</html>
