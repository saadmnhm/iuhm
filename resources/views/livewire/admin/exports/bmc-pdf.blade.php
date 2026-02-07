<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Business Model Canvas #{{ $bmc->id }}</title>
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
            border-bottom: 3px solid #6366f1;
            margin-bottom: 20px;
            padding-bottom: 15px;
        }
        .main-title {
            font-size: 22px;
            font-weight: bold;
            color: #6366f1;
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
            background-color: #6366f1; 
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
        <div class="main-title">Business Model Canvas (BMC)</div>
        <div class="project-id">
            ID: #{{ $bmc->id }} | 
            Date: {{ $bmc->created_at->format('d/m/Y') }} |
            Statut: {{ ucfirst($bmc->status) }}
        </div>
    </div>

    <!-- Candidat Info -->
    @if($bmc->candidat)
    <div class="candidat-info">
        <div class="label">Candidat</div>
        <div class="value">{{ $bmc->candidat->nom }} {{ $bmc->candidat->prenom }}</div>
        <div class="value">{{ $bmc->candidat->email }}</div>
    </div>
    @endif

    <!-- Section 1: Partenaires Clés -->
    <div class="section">
        <div class="section-title">Partenaires Clés</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Partenaires clés:</div>
                <div class="value">{{ $bmc->partenaires_cles ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Rôle:</div>
                <div class="value">{{ $bmc->role_partenaires ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 2: Activités Clés -->
    <div class="section">
        <div class="section-title">Activités Clés</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Activités clés:</div>
                <div class="value">{{ $bmc->activites_cles ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Description:</div>
                <div class="value">{{ $bmc->description_activites ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 3: Proposition de Valeur -->
    <div class="section">
        <div class="section-title">Proposition de Valeur</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Proposition de valeur:</div>
                <div class="value">{{ $bmc->proposition_valeur ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Avantages:</div>
                <div class="value">{{ $bmc->avantages_offerts ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 4: Relations Clients -->
    <div class="section">
        <div class="section-title">Relations Clients</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Type de relation:</div>
                <div class="value">{{ $bmc->relations_clients ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Description:</div>
                <div class="value">{{ $bmc->type_relation ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 5: Segments de Clientèle -->
    <div class="section">
        <div class="section-title">Segments de Clientèle</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Segments de clientèle:</div>
                <div class="value">{{ $bmc->segments_clientele ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Caractéristiques:</div>
                <div class="value">{{ $bmc->caracteristiques_segments ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 6: Ressources Clés -->
    <div class="section">
        <div class="section-title">Ressources Clés</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Ressources clés:</div>
                <div class="value">{{ $bmc->ressources_cles ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Type:</div>
                <div class="value">{{ $bmc->type_ressources ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 7: Canaux de Distribution -->
    <div class="section">
        <div class="section-title">Canaux de Distribution</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Canaux:</div>
                <div class="value">{{ $bmc->canaux ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Description:</div>
                <div class="value">{{ $bmc->description_canaux ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 8: Structure des Coûts -->
    <div class="section">
        <div class="section-title">Structure des Coûts</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Coûts principaux:</div>
                <div class="value">{{ $bmc->structure_couts ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Type de coûts:</div>
                <div class="value">{{ $bmc->type_couts ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 9: Flux de Revenus -->
    <div class="section">
        <div class="section-title">Flux de Revenus</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Sources de revenus:</div>
                <div class="value">{{ $bmc->flux_revenus ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Type:</div>
                <div class="value">{{ $bmc->type_revenus ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
</body>
</html>
