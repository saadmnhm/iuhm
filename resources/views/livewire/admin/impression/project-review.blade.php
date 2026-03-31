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

.review-box {
    background: #f9f9f9;
    padding: 15px;
    border: 1px dashed #ccc;
    margin-top: 10px;
}
.rating-p {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 15px;
}
</style>

<div class="a4-page">
    <div class="header">
        <h1>Avis de Formation</h1>
        <p>Projet: {{ $project->name }}</p>
        <p>Candidat: {{ $candidat->nom }} {{ $candidat->prenom }}</p>
    </div>

    <div class="section">
        <h2>Evaluation du candidat</h2>

        <p class="rating-p">Note attribuee : {{ $submission->formation_review_rating ?? 'Non specifiee' }} / 5</p>

        <h3>Commentaire / Feedback :</h3>
        <div class="review-box">
            {{ $submission->formation_review_feedback ?? 'Aucun commentaire.' }}
        </div>
    </div>
</div>
