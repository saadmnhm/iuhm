<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Étude de Marché #{{ $etudeMarche->id }}</title>
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
            border-bottom: 3px solid #10b981;
            margin-bottom: 20px;
            padding-bottom: 15px;
        }
        .main-title {
            font-size: 22px;
            font-weight: bold;
            color: #10b981;
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
            background-color: #10b981; 
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
        <div class="main-title">Étude de Marché</div>
        <div class="project-id">
            ID: #{{ $etudeMarche->id }} | 
            Date: {{ $etudeMarche->created_at->format('d/m/Y') }} |
            Statut: {{ ucfirst($etudeMarche->status) }}
        </div>
    </div>

    <!-- Candidat Info -->
    @if($etudeMarche->candidat)
    <div class="candidat-info">
        <div class="label">Candidat</div>
        <div class="value">{{ $etudeMarche->candidat->nom }} {{ $etudeMarche->candidat->prenom }}</div>
        <div class="value">{{ $etudeMarche->candidat->email }}</div>
    </div>
    @endif

    <!-- Section 1: Produit/Service -->
    <div class="section">
        <div class="section-title">Produit/Service</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Produit/Service:</div>
                <div class="value">{{ $etudeMarche->produit_service ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Description de l'offre:</div>
                <div class="value">{{ $etudeMarche->description_offre ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Bénéfices clients:</div>
                <div class="value">{{ $etudeMarche->benefices_clients ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Prix marché:</div>
                <div class="value">{{ $etudeMarche->prix_marche ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Contrôle prix:</div>
                <div class="value">{{ $etudeMarche->controle_prix ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 2: Clientèle -->
    <div class="section">
        <div class="section-title">Clientèle</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Type de clients:</div>
                <div class="value">{{ $etudeMarche->type_clients ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Caractéristiques:</div>
                <div class="value">{{ $etudeMarche->caracteristiques_clientele ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Fréquence de consommation:</div>
                <div class="value">{{ $etudeMarche->frequence_consommation ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Localisation:</div>
                <div class="value">{{ $etudeMarche->localisation_clients ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Exigences principales:</div>
                <div class="value">{{ $etudeMarche->exigences_principales ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 3: Concurrence -->
    <div class="section">
        <div class="section-title">Concurrence</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Concurrents directs:</div>
                <div class="value">{{ $etudeMarche->nombre_concurrents_directs ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Concurrents indirects:</div>
                <div class="value">{{ $etudeMarche->concurrents_indirects ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Taille des concurrents:</div>
                <div class="value">{{ $etudeMarche->taille_concurrents ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Informations concurrents:</div>
                <div class="value">{{ $etudeMarche->informations_concurrents ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Communication:</div>
                <div class="value">{{ $etudeMarche->communication_concurrents ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 4: Fournisseurs -->
    <div class="section">
        <div class="section-title">Fournisseurs</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Nombre de fournisseurs:</div>
                <div class="value">{{ $etudeMarche->nombre_fournisseurs ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Origine:</div>
                <div class="value">{{ $etudeMarche->origine_fournisseurs ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Prix:</div>
                <div class="value">{{ $etudeMarche->prix_fournisseurs ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Délais de livraison:</div>
                <div class="value">{{ $etudeMarche->delais_livraison ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Stabilité du marché:</div>
                <div class="value">{{ $etudeMarche->stabilite_marche ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
</body>
</html>
