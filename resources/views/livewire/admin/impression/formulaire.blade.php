<style>
/* CSS Reset */
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
.header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 15px; }
.header h1 { font-size: 20px; text-transform: uppercase; margin-bottom: 10px; }
.header p { margin-bottom: 5px; color: #555; font-size: 12px; }

/* Grid layout for 4 columns */
.form-answers-grid {
    overflow: hidden;
    margin-top: 20px;
}

.answer-item {
    float: left;
    width: 23%;
    margin-right: 2%;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
    background-color: #fafafa;
    break-inside: avoid;
    min-height: 80px;
    box-sizing: border-box;
}
.answer-item:nth-child(4n) { margin-right: 0; }
.answer-item:nth-child(4n+1) { clear: left; }

.answer-label { font-size: 11px; color: #666; margin-bottom: 4px; border-bottom: 1px solid #eee; padding-bottom: 4px; font-weight: bold;}
.answer-value { font-size: 13px; color: #111; word-wrap: break-word; }

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
        <h1>Formulaire de soumission</h1>
        <p>Projet: {{ $project->name }}</p>
        <p>Candidat: {{ $submission->candidat->prenom }} {{ $submission->candidat->nom }}</p>
        <p>Submission ID: #{{ $submission->id }} - Date: {{ $submission->created_at->format('d/m/Y') }}</p>
    </div>

    <div class="form-content">
        <h3>{{ $submission->form->title ?? 'Details' }}</h3>
        
        <div class="form-answers-grid">
            @foreach($submission->answers as $answer)
                <div class="answer-item">
                    <div class="answer-label">{{ $answer->field->label ?? 'Question' }}</div>
                    <div class="answer-value">
                        @if(is_array($answer->value) || is_object($answer->value))
                            {{ json_encode($answer->value) }}
                        @else
                            {{ $answer->value }}
                        @endif
                    </div>
                </div>
            @endforeach
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
