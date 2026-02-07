<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Évaluation d'Idée #{{ $evaluationIdee->id }}</title>
    <style>
        @page { margin: 15mm; }
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 11px;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .header-container {
            border-bottom: 3px solid #9333ea;
            margin-bottom: 20px;
            padding-bottom: 15px;
        }
        .main-title {
            font-size: 22px;
            font-weight: bold;
            color: #9333ea;
            margin: 0 0 5px 0;
            text-align: center;
        }
        .project-id {
            font-size: 12px;
            color: #666;
            margin: 5px 0;
            text-align: center;
        }
        .section { 
            margin-bottom: 20px; 
            page-break-inside: avoid; 
        }
        .section-title { 
            background-color: #9333ea; 
            color: white;
            padding: 10px 15px; 
            font-weight: bold; 
            font-size: 13px;
            margin-bottom: 10px;
        }
        .section-content {
            padding: 10px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }
        .info-row {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            color: #555;
            margin-bottom: 3px;
        }
        .value {
            color: #333;
            margin-left: 10px;
        }
        .candidat-info {
            background-color: #f3f4f6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header-container">
        <div class="main-title">Évaluation d'Idée</div>
        <div class="project-id">
            ID: #{{ $evaluationIdee->id }} | 
            Date: {{ $evaluationIdee->created_at->format('d/m/Y') }} |
            Statut: {{ ucfirst($evaluationIdee->status) }}
        </div>
    </div>

    <!-- Candidat Info -->
    @if($evaluationIdee->candidat)
    <div class="candidat-info">
        <div class="label">Candidat</div>
        <div class="value">{{ $evaluationIdee->candidat->nom }} {{ $evaluationIdee->candidat->prenom }}</div>
        <div class="value">{{ $evaluationIdee->candidat->email }}</div>
    </div>
    @endif

    <!-- Section 1: Idée du Projet -->
    <div class="section">
        <div class="section-title">Idée du Projet</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Idée du projet:</div>
                <div class="value">{{ $evaluationIdee->idee_projet ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Résumé de l'idée:</div>
                <div class="value">{{ $evaluationIdee->resume_idee ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Activité:</div>
                <div class="value">{{ $evaluationIdee->activite ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Lieu d'implantation:</div>
                <div class="value">{{ $evaluationIdee->lieu_implantation ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 2: Besoin du Projet -->
    <div class="section">
        <div class="section-title">Besoin du Projet</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Besoin identifié:</div>
                <div class="value">{{ $evaluationIdee->besoin_projet ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Origine de l'idée:</div>
                <div class="value">{{ $evaluationIdee->origine_idee ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Expérience nécessaire:</div>
                <div class="value">{{ $evaluationIdee->experience_necessaire ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 3: Produits/Services -->
    <div class="section">
        <div class="section-title">Produits/Services</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Produits/Services offerts:</div>
                <div class="value">{{ $evaluationIdee->produits_services ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Description:</div>
                <div class="value">{{ $evaluationIdee->description_produits ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Utilité:</div>
                <div class="value">{{ $evaluationIdee->utilite_produits ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 4: Clients Cibles -->
    <div class="section">
        <div class="section-title">Clients Cibles</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Clients identifiés:</div>
                <div class="value">{{ $evaluationIdee->clients_identifies ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Besoins des clients:</div>
                <div class="value">{{ $evaluationIdee->besoins_clients ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Nombre de clients:</div>
                <div class="value">{{ $evaluationIdee->nombre_clients ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 5: Proposition de Valeur -->
    <div class="section">
        <div class="section-title">Proposition de Valeur</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Valeur ajoutée:</div>
                <div class="value">{{ $evaluationIdee->valeur_ajoutee ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Différenciation:</div>
                <div class="value">{{ $evaluationIdee->differenciation ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Avantages concurrentiels:</div>
                <div class="value">{{ $evaluationIdee->avantages_concurrentiels ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
</body>
</html>
