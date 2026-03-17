<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: Arial, sans-serif;
    background: #fdfdfd;
    padding: 20px;
    font-size: 14px;
}
.a4-page {
    width: 210mm;
    min-height: 297mm;
    background: white;
    margin: 0 auto 20px auto;
    padding: 20mm;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    position: relative;
    page-break-after: always;
}
@media print {
    body { padding: 0; background: none; }
    .a4-page { margin: 0; padding: 20mm; box-shadow: none; border: none; }
    .no-print { display: none; }
}

/* Header Sections */
.header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
.header h1 { font-size: 22px; text-transform: uppercase; margin-bottom: 15px; }
.header p { margin-bottom: 5px; color: #555; font-size: 14px; }

/* Content sections */
.section { margin-bottom: 25px; }
.section h2 { font-size: 18px; margin-bottom: 10px; color: #8B0000; border-bottom: 1px dotted #8B0000; padding-bottom: 5px; }
.info-row { display: flex; margin-bottom: 8px; border-bottom: 1px solid #f0f0f0; padding-bottom: 4px; }
.info-label { font-weight: bold; width: 200px; color: #333; }
.info-value { flex: 1; color: #000; }

/* Footers */
.footer-logos {
    position: absolute;
    bottom: 20mm;
    left: 20mm;
    right: 20mm;
    text-align: center;
    border-top: 1px solid #eee;
    padding-top: 10px;
    display: flex;
    justify-content: center;
    gap: 20px;
    align-items: center;
}
.footer-logos img {
    max-height: 60px;
    max-width: 120px;
    object-fit: contain;
}
</style>

<div class="a4-page">
    <div class="header">
        <h1>Fiche d'Inscription - Projet</h1>
        <p>Projet: {{ $project->name }}</p>
        <p>Date d'inscription: {{ $agreement->created_at->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <h2>IDEE DE PROJET</h2>
        <div class="info-row" style="display:block;">
            <div class="info-value" style="margin-top: 10px; font-style: italic; background:#f9f9f9; padding: 10px; border: 1px dashed #ccc;">
                {{ $agreement->project_idea ?? 'Aucune idÃ©e de projet renseignÃ©e.' }}
            </div>
        </div>
    </div>

    <div class="section">
        <h2>INFORMATIONS PERSONNELLES</h2>
        <div class="info-row">
            <span class="info-label">Nom et PrÃ©nom :</span>
            <span class="info-value">{{ strtoupper($candidat->nom) }} {{ ucfirst($candidat->prenom) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">CIN :</span>
            <span class="info-value">{{ $candidat->cin }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">TÃ©lÃ©phone :</span>
            <span class="info-value">{{ $candidat->phone }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email :</span>
            <span class="info-value">{{ $candidat->email }}</span>
        </div>
    </div>

    <div class="section">
        <h2>COMMENT NOUS AVEZ-VOUS CONNU ?</h2>
        <div class="info-row" style="display:block;">
            <div class="info-value" style="margin-top: 10px; background:#f9f9f9; padding: 10px; border: 1px dotted #ccc;">
                {{ $agreement->how_knew ?? 'Non spÃ©cifiÃ©.' }}
            </div>
        </div>
    </div>

    <div class="footer-logos">
        @if($project->logo1)
            <img src="{{ asset('uploads/' . $project->logo1) }}" alt="Logo 1">
        @endif
        @if($project->logo2)
            <img src="{{ asset('uploads/' . $project->logo2) }}" alt="Logo 2">
        @endif
        @if($project->logo3)
            <img src="{{ asset('uploads/' . $project->logo3) }}" alt="Logo 3">
        @endif
    </div>
</div>
