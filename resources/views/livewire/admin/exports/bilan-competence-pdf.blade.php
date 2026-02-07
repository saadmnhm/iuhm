<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bilan de Compétences #{{ $bilanCompetence->id }}</title>
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
            border-bottom: 3px solid #eab308;
            margin-bottom: 20px;
            padding-bottom: 15px;
        }
        .main-title {
            font-size: 22px;
            font-weight: bold;
            color: #eab308;
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
            background-color: #eab308; 
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
        <div class="main-title">Bilan de Compétences</div>
        <div class="project-id">
            ID: #{{ $bilanCompetence->id }} | 
            Date: {{ $bilanCompetence->created_at->format('d/m/Y') }} |
            Statut: {{ ucfirst($bilanCompetence->status) }}
        </div>
    </div>

    <!-- Candidat Info -->
    @if($bilanCompetence->candidat)
    <div class="candidat-info">
        <div class="label">Candidat</div>
        <div class="value">{{ $bilanCompetence->candidat->nom }} {{ $bilanCompetence->candidat->prenom }}</div>
        <div class="value">{{ $bilanCompetence->candidat->email }}</div>
    </div>
    @endif

    <!-- Section 1: Qualités et Défauts -->
    <div class="section">
        <div class="section-title">Qualités et Défauts</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Qualités et Défauts:</div>
                <div class="value">
                    @if(is_array($bilanCompetence->qualites_defauts))
                        @foreach($bilanCompetence->qualites_defauts as $item)
                            @if(isset($item['qualite'])) ✓ {{ $item['qualite'] }} @endif
                            @if(isset($item['defaut'])) ✗ {{ $item['defaut'] }} @endif
                        @endforeach
                    @else
                        {{ is_string($bilanCompetence->qualites_defauts) ? $bilanCompetence->qualites_defauts : 'N/A' }}
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="label">Contribution:</div>
                <div class="value">{{ $bilanCompetence->qualites_contribution ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Freins:</div>
                <div class="value">{{ $bilanCompetence->defauts_freins ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 2: Formation -->
    <div class="section">
        <div class="section-title">Formation</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Niveau d'études:</div>
                <div class="value">{{ $bilanCompetence->niveau_etude ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Diplômes obtenus:</div>
                <div class="value">{{ $bilanCompetence->diplomes_obtenus ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Compétences formation:</div>
                <div class="value">{{ is_array($bilanCompetence->competences_formation) ? implode(', ', array_filter($bilanCompetence->competences_formation, fn($i) => is_string($i))) : ($bilanCompetence->competences_formation ?? 'N/A') }}</div>
            </div>
            <div class="info-row">
                <div class="label">Besoins formations:</div>
                <div class="value">{{ $bilanCompetence->besoin_formations ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 3: Environnement Professionnel -->
    <div class="section">
        <div class="section-title">Environnement Professionnel</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Type d'environnement:</div>
                <div class="value">{{ is_array($bilanCompetence->environnement_professionnel) ? implode(', ', $bilanCompetence->environnement_professionnel) : ($bilanCompetence->environnement_professionnel ?? 'N/A') }}</div>
            </div>
            <div class="info-row">
                <div class="label">Secteurs activité:</div>
                <div class="value">{{ is_array($bilanCompetence->secteurs_activite) ? implode(', ', $bilanCompetence->secteurs_activite) : ($bilanCompetence->secteurs_activite ?? 'N/A') }}</div>
            </div>
            <div class="info-row">
                <div class="label">Fonctions envisagées:</div>
                <div class="value">{{ is_array($bilanCompetence->fonctions_envisagees) ? implode(', ', $bilanCompetence->fonctions_envisagees) : ($bilanCompetence->fonctions_envisagees ?? 'N/A') }}</div>
            </div>
        </div>
    </div>

    <!-- Section 4: Expérience Professionnelle -->
    <div class="section">
        <div class="section-title">Expérience Professionnelle</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Société:</div>
                <div class="value">{{ $bilanCompetence->exp_societe ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Secteur:</div>
                <div class="value">{{ $bilanCompetence->exp_secteur ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Durée:</div>
                <div class="value">{{ $bilanCompetence->exp_duree ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Compétences:</div>
                <div class="value">{{ $bilanCompetence->exp_competences ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Section 5: Objectifs et Aspirations -->
    <div class="section">
        <div class="section-title">Objectifs et Aspirations</div>
        <div class="section-content">
            <div class="info-row">
                <div class="label">Contraintes acceptées:</div>
                <div class="value">{{ is_array($bilanCompetence->contraintes_acceptees) ? implode(', ', $bilanCompetence->contraintes_acceptees) : ($bilanCompetence->contraintes_acceptees ?? 'N/A') }}</div>
            </div>
            <div class="info-row">
                <div class="label">Exigences:</div>
                <div class="value">{{ is_array($bilanCompetence->exigences) ? implode(', ', $bilanCompetence->exigences) : ($bilanCompetence->exigences ?? 'N/A') }}</div>
            </div>
            <div class="info-row">
                <div class="label">Réflexions personnelles:</div>
                <div class="value">{{ $bilanCompetence->reflexions_personnelles ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
</body>
</html>
            <div class="info-row">
                <div class="label">Objectifs à court terme:</div>
                <div class="value">{{ $bilanCompetence->objectifs_court_terme ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Objectifs à long terme:</div>
                <div class="value">{{ $bilanCompetence->objectifs_long_terme ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Motivations:</div>
                <div class="value">{{ $bilanCompetence->motivations ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
</body>
</html>
