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
}
.header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 15px; }
.header h1 { font-size: 20px; text-transform: uppercase; margin-bottom: 10px; }
.header p { margin-bottom: 5px; color: #555; }

.section { margin-bottom: 20px; }
.section h2 { font-size: 16px; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 15px; }
.score-row { display: flex; justify-content: space-between; padding: 10px; background: #fafafa; border: 1px solid #eee; margin-bottom: 10px; }
.score-row span { font-weight: bold; }
.total-row { background: #333; color: white; margin-top: 20px; }

</style>

<div class="a4-page">
    <div class="header">
        <h1>Grille d'Évaluation</h1>
        <p>Projet: {{ $project->name }}</p>
        <p>Candidat: {{ $candidat->nom }} {{ $candidat->prenom }}</p>
        <p>Date: {{ $evaluation->created_at->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <h2>Scores détaillés</h2>
        
        <div class="score-row">
            <div>Motivation</div>
            <span>{{ $evaluation->motivation_score ?? 0 }} / 100</span>
        </div>
        
        <div class="score-row">
            <div>Profil et Compétences</div>
            <span>{{ $evaluation->profile_score ?? 0 }} / 100</span>
        </div>
        
        <div class="score-row">
            <div>Viabilité du Projet</div>
            <span>{{ $evaluation->viability_score ?? 0 }} / 100</span>
        </div>

        <div class="score-row total-row">
            <div>Score Total</div>
            <span>{{ $evaluation->total_score ?? 0 }} / 300</span>
        </div>
    </div>
</div>
