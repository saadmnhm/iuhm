<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: Arial, sans-serif;
    padding: 20px;
    font-size: 14px;
}

.a4-page {
    width: 210mm;
    min-height: 297mm;
    background: white;
    margin: 0 auto 20px auto;
    padding: 20mm;
}

.header {
    text-align: center;
    margin-bottom: 20px;
}

h2 {
    font-size: 20px;
    font-weight: bold;
}

.section {
    margin-top: 20px;
}

h3 {
    font-size: 16px;
    margin-bottom: 10px;
}

.form-line {
    display: flex;
    margin-bottom: 12px;
    align-items: center;
}

.form-line label {
    min-width: 230px;
    font-weight: 700;
}

.line-value {
    flex: 1;
    border-bottom: 1px dotted #000;
    min-height: 22px;
    display: flex;
    align-items: center;
    padding: 0 4px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

table th,
table td {
    border: 1px solid black;
    padding: 8px;
    vertical-align: top;
    font-size: 14px;
}

table th {
    background: #f5f5f5;
    text-align: center;
}

.note-cell {
    width: 90px;
    text-align: center;
    font-weight: 700;
}

.total-line {
    margin-top: 12px;
    font-weight: 700;
    font-size: 16px;
    background: #f0f7ff;
    border: 1px solid #c7ddf5;
    padding: 8px 10px;
    border-radius: 8px;
}

.comment-box {
    min-height: 120px;
    border: 1px dotted #000;
    margin-top: 10px;
    padding: 10px;
    white-space: pre-wrap;
    word-break: break-word;
}

@media print {
    body { padding: 0; background: #fff; }
    .a4-page { margin: 0; box-shadow: none; }
}
</style>

@php
    $criteriaNotes = is_array($evaluation->criteria_notes)
        ? $evaluation->criteria_notes
        : (json_decode($evaluation->criteria_notes ?? '[]', true) ?: []);

    $getNote = function (string $key) use ($criteriaNotes): string {
        $value = $criteriaNotes[$key] ?? null;

        if ($value === null || $value === '') {
            return '-';
        }

        return (string) $value;
    };
@endphp

<div class="a4-page">
    <div class="header">
        <h2>Grille d'evaluation - Entretien de positionnement INDH</h2>
    </div>

    <div class="section">
        <h3>• Informations de base</h3>

        <div class="form-line">
            <label>Nom du porteur de projet :</label>
            <span class="line-value">{{ trim(($candidat->nom ?? '') . ' ' . ($candidat->prenom ?? '')) ?: 'N/A' }}</span>
        </div>

        <div class="form-line">
            <label>Projet / Idee :</label>
            <span class="line-value">{{ $project->name ?? $project->project_name ?? 'N/A' }}</span>
        </div>

        <div class="form-line">
            <label>Date de l'entretien :</label>
            <span class="line-value">{{ $evaluation->date_entretien?->format('d/m/Y') ?? $evaluation->created_at?->format('d/m/Y') ?? 'N/A' }}</span>
        </div>

        <div class="form-line">
            <label>Evaluateur :</label>
            <span class="line-value">{{ $evaluation->admin->name ?? 'Administrateur' }}</span>
        </div>
    </div>

    <div class="section">
        <h3>• Criteres d'evaluation</h3>

        <table>
            <thead>
                <tr>
                    <th>Axe / Critere</th>
                    <th>Sous-criteres</th>
                    <th>Poids (%)</th>
                    <th>Note (1-5)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pertinence de l'idee du projet</td>
                    <td>- Alignement avec les priorites du territoire<br>- Innovation<br>- Impact socio-economique</td>
                    <td>10%</td>
                    <td class="note-cell">{{ $getNote('pertinence') }}</td>
                </tr>
                <tr>
                    <td>Experience dans le domaine</td>
                    <td>- Experience professionnelle<br>- Competences techniques</td>
                    <td>20%</td>
                    <td class="note-cell">{{ $getNote('experience') }}</td>
                </tr>
                <tr>
                    <td>Niveau d'etude / Diplome</td>
                    <td>- Niveau academique<br>- Pertinence des etudes</td>
                    <td>10%</td>
                    <td class="note-cell">{{ $getNote('niveau_etude') }}</td>
                </tr>
                <tr>
                    <td>Capacite financiere / Fonds propres</td>
                    <td>- Apport personnel<br>- Fonds de roulement</td>
                    <td>10%</td>
                    <td class="note-cell">{{ $getNote('capacite_financiere') }}</td>
                </tr>
                <tr>
                    <td>Statut d'activite</td>
                    <td>- Projet deja en activite ou non<br>- Anciennete</td>
                    <td>10%</td>
                    <td class="note-cell">{{ $getNote('statut_activite') }}</td>
                </tr>
                <tr>
                    <td>Infrastructure physique (Local)</td>
                    <td>- Possession d'un local<br>- Adequation du lieu</td>
                    <td>10%</td>
                    <td class="note-cell">{{ $getNote('infrastructure') }}</td>
                </tr>
                <tr>
                    <td>Viabilite et faisabilite</td>
                    <td>- Faisabilite technique<br>- Faisabilite commerciale<br>- Plan financier</td>
                    <td>20%</td>
                    <td class="note-cell">{{ $getNote('viabilite_faisabilite') }}</td>
                </tr>
                <tr>
                    <td>Disponibilite et engagement</td>
                    <td>- En travail<br>- Etudiant</td>
                    <td>20%</td>
                    <td class="note-cell">{{ $getNote('disponibilite') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total-line">
            Motivation: {{ $evaluation->motivation_score ?? 0 }} / 100 |
            Profil et competences: {{ $evaluation->profile_score ?? 0 }} / 100 |
            Viabilite: {{ $evaluation->viability_score ?? 0 }} / 100 |
            Note globale: {{ $evaluation->total_score ?? 0 }} / 300
        </div>
    </div>

    <div class="section">
        <h3>• Commentaires de l'evaluateur</h3>
        <div class="comment-box">{{ $evaluation->comment ?? 'Aucun commentaire.' }}</div>
    </div>
</div>
