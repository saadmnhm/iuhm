<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche Employé - {{ $employee->nom }} {{ $employee->prenom }}</title>
    <style>
        @page { margin: 15mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }
        .header { border-bottom: 3px solid #4f46e5; padding-bottom: 10px; margin-bottom: 15px; }
        .title { font-size: 18px; font-weight: bold; color: #4f46e5; }
        .subtitle { font-size: 10px; color: #666; }
        .employee-header { background: #f8f9ff; border: 1px solid #c7d2fe; border-radius: 6px; padding: 15px; margin-bottom: 15px; }
        .name { font-size: 20px; font-weight: bold; color: #1e3a5f; }
        .poste { font-size: 12px; color: #6366f1; }
        .info-grid { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .info-grid td { padding: 6px 10px; font-size: 11px; border: 1px solid #e5e7eb; vertical-align: top; }
        .info-label { background: #f3f4f6; font-weight: bold; color: #374151; width: 30%; }
        .section { font-size: 13px; font-weight: bold; color: #4f46e5; margin: 15px 0 8px 0; padding-bottom: 4px; border-bottom: 1px solid #e5e7eb; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 6px; text-align: center; font-size: 8px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">FICHE EMPLOYÉ</div>
        <div class="subtitle">Matricule: {{ $employee->matricule ?? 'N/A' }} | Généré le {{ now()->format('d/m/Y') }}</div>
    </div>

    <div class="employee-header">
        <div class="name">{{ strtoupper($employee->nom) }} {{ $employee->prenom }}</div>
        <div class="poste">{{ $employee->poste ?? 'Non spécifié' }}</div>
        @php
            $statusBadge = $employee->status === 'active' ? 'badge-active' : 'badge-inactive';
            $statusLabel = ['active' => 'Actif', 'inactive' => 'Inactif', 'en_conge' => 'En congé', 'quitte' => 'Quitté'][$employee->status] ?? $employee->status;
        @endphp
        <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
    </div>

    <div class="section">Informations personnelles</div>
    <table class="info-grid">
        <tr><td class="info-label">Nom complet</td><td>{{ $employee->nom }} {{ $employee->prenom }}</td></tr>
        <tr><td class="info-label">CIN</td><td>{{ $employee->cin ?? '-' }}</td></tr>
        <tr><td class="info-label">Genre</td><td>{{ $employee->gender ? ucfirst($employee->gender) : '-' }}</td></tr>
        <tr><td class="info-label">Date de naissance</td><td>{{ $employee->date_naissance ? $employee->date_naissance->format('d/m/Y') : '-' }}</td></tr>
        <tr><td class="info-label">Email</td><td>{{ $employee->email ?? '-' }}</td></tr>
        <tr><td class="info-label">Téléphone</td><td>{{ $employee->phone ?? '-' }}</td></tr>
        <tr><td class="info-label">Adresse</td><td>{{ $employee->address ?? '-' }}</td></tr>
    </table>

    <div class="section">Informations professionnelles</div>
    <table class="info-grid">
        <tr><td class="info-label">Matricule</td><td>{{ $employee->matricule ?? '-' }}</td></tr>
        <tr><td class="info-label">Poste</td><td>{{ $employee->poste ?? '-' }}</td></tr>
        <tr><td class="info-label">Département</td><td>{{ $employee->departement ?? '-' }}</td></tr>
        <tr><td class="info-label">Type de contrat</td><td>{{ $employee->contrat_type }}</td></tr>
        <tr><td class="info-label">Date d'embauche</td><td>{{ $employee->date_embauche ? $employee->date_embauche->format('d/m/Y') : '-' }}</td></tr>
        <tr><td class="info-label">Fin de contrat</td><td>{{ $employee->date_fin_contrat ? $employee->date_fin_contrat->format('d/m/Y') : '-' }}</td></tr>
        <tr><td class="info-label">Salaire</td><td>{{ $employee->salaire ? number_format($employee->salaire, 2) . ' MAD' : '-' }}</td></tr>
    </table>

    @if($employee->notes)
    <div class="section">Notes</div>
    <p>{{ $employee->notes }}</p>
    @endif

    <div class="footer">
        Fiche employé générée le {{ now()->format('d/m/Y à H:i') }}
    </div>
</body>
</html>
