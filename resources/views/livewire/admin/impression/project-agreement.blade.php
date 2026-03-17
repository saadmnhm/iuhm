<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: Arial, sans-serif;
    background: #fdfdfd;
    padding: 20px;
    font-size: 14px;
    line-height: 1.6;
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
.header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 15px; }
.header h1 { font-size: 20px; text-transform: uppercase; margin-bottom: 10px; }
.header p { margin-bottom: 5px; color: #555; font-size: 14px; }

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
        <h1>Engagement d'adhÃ©sion aux conditions du Projet</h1>
        <p>Projet: {{ $project->name }}</p>
        <p>Le: {{ $agreement->created_at->format('d/m/Y') }}</p>
    </div>

    <div class="content" style="margin-bottom: 20px;">
        <p>Je soussignÃ©(e) :</p>
        <ul style="list-style:none; padding-left: 20px; margin-top:10px;">
            <li><strong>Nom et PrÃ©nom :</strong> {{ $candidat->nom }} {{ $candidat->prenom }}</li>
            <li><strong>CIN :</strong> {{ $candidat->cin }}</li>
        </ul>
        
        <p style="margin-top:20px;">
            Je reconnais avoir pris connaissance des conditions de participation au projet "{{ $project->name }}".
            Je m'engage Ã  respecter les termes Ã©tablis et Ã  fournir toutes les informations nÃ©cessaires Ã  la rÃ©alisation de ce projet.
        </p>

        <p style="margin-top:20px;">
            <strong>Comment m'avez-vous connu :</strong> <br>
            {{ $agreement->how_knew ?? 'Non rÃ©pondu' }}
        </p>
    </div>

    <div class="signature" style="text-align: right; margin-top:50px; padding-right: 50px;">
        <p><strong>Signature du candidat :</strong></p>
        <div style="height: 60px; width: 150px; border-bottom: 1px dashed #000; margin-left: auto; margin-top: 20px;"></div>
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
