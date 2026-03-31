<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

:root {
    --iuhm-navy: #0f3d66;
    --iuhm-sky: #2a7fb8;
    --iuhm-soft: #edf5fb;
    --ink: #1f2937;
    --muted: #5b6473;
    --line: #dbe7f1;
    print-color-adjust: exact;
    -webkit-print-color-adjust: exact;
}

.a4-page {
    width: 210mm;
    min-height: 297mm;
    background: #ffffff;
    margin: 0 auto 20px auto;
    padding: 8mm 4mm 12mm 4mm;
    box-shadow: 0 8px 24px rgba(15, 61, 102, 0.12);
    position: relative;
    page-break-after: auto;
}

.a4-page:not(:last-child) {
    page-break-after: always;
}

.page-bg {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.sheet-content {
    position: relative;
    z-index: 1;
}

.sheet-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--iuhm-sky);
}

.brand-main {
    display: flex;
    align-items: center;
    gap: 10px;
}

.brand-main img {
    width: 54px;
    height: 54px;
    object-fit: contain;
    border: 1px solid var(--line);
    border-radius: 10px;
    background: #ffffff;
    padding: 6px;
}

.sheet-title {
    font-size: 19px;
    font-weight: 800;
    color: var(--iuhm-navy);
    line-height: 1.1;
}

.sheet-subtitle {
    font-size: 11px;
    color: var(--muted);
    margin-top: 2px;
}

.partner-logos {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    flex-wrap: wrap;
    max-width: 48%;
}

.partner-logos img {
    max-height: 36px;
    max-width: 95px;
    object-fit: contain;
    border: 1px solid var(--line);
    border-radius: 6px;
    padding: 4px;
    background: #ffffff;
}

.compact-meta {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 8px;
    margin-top: 10px;
}

.meta-card {
    border: 1px solid var(--line);
    background: var(--iuhm-soft);
    border-radius: 8px;
    padding: 8px;
    min-height: 48px;
}

.meta-label {
    display: block;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.4px;
    color: var(--muted);
    text-transform: uppercase;
    margin-bottom: 2px;
}

.meta-value {
    display: block;
    font-size: 12px;
    color: var(--ink);
    font-weight: 800;
    line-height: 1.25;
}

.page-part {
    margin-top: 10px;
    border: 1px solid var(--line);
    border-radius: 10px;
    padding: 8px;
    background: #ffffff;
}

.part-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px dashed var(--line);
    padding-bottom: 6px;
    margin-bottom: 6px;
}

.part-tag {
    font-size: 10px;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    color: var(--iuhm-navy);
    font-weight: 800;
}

.part-title {
    font-size: 13px;
    color: var(--ink);
    font-weight: 800;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 6px;
}

.detail-item {
    border: 1px solid #e4edf5;
    border-radius: 8px;
    padding: 7px;
    background: #fbfdff;
}

.detail-item span {
    display: block;
    font-size: 10px;
    font-weight: 700;
    color: var(--muted);
    margin-bottom: 2px;
}

.detail-item strong {
    display: block;
    font-size: 12px;
    font-weight: 800;
    color: var(--ink);
    line-height: 1.25;
}

.answers-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.answer-card {
    width: calc(25% - 4.5px);
    border: 1px solid #e4edf5;
    border-radius: 8px;
    padding: 7px;
    background: #fbfdff;
    min-height: 62px;
    break-inside: avoid;
}

.answer-label {
    font-size: 10px;
    color: var(--muted);
    font-weight: 700;
    line-height: 1.3;
    margin-bottom: 4px;
}

.answer-value {
    font-size: 11px;
    color: var(--ink);
    line-height: 1.35;
    white-space: pre-wrap;
    word-break: break-word;
}

.answer-empty {
    font-size: 12px;
    color: #6b7280;
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 8px;
    padding: 8px;
}

.file-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.file-item {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    border: 1px solid #dbe7f1;
    border-left: 4px solid var(--iuhm-sky);
    border-radius: 8px;
    padding: 8px;
    background: #f8fbfe;
}

.footer-note {
    margin-top: 8px;
    font-size: 10px;
    text-align: right;
    color: #6b7280;
}

@media (max-width: 960px) {
    .a4-page {
        width: 100%;
        min-height: auto;
    }

    .sheet-header {
        flex-direction: column;
    }

    .partner-logos {
        max-width: 100%;
        justify-content: flex-start;
    }

    .compact-meta {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .answer-card {
        width: calc(50% - 3px);
    }

}

@media print {
    @page {
        size: A4;
        margin: 0;
    }
    .sheet-header{
        display: flex;
    }

    body {
        padding: 0 !important;
        background: none !important;
    }

    .paper {
        height: auto !important;
        min-height: 297mm;
    }

    .a4-page {
        margin: 0;
        box-shadow: none;
        padding: 8mm 4mm 12mm 4mm;
    }

    .answer-card {
        width: 23.7%;
    }

    .file-preview-wrap {
        min-height: 255mm;
    }

    .file-preview-frame {
        height: 250mm;
    }
}
</style>

@php
    $answers = $submission->answers ?? collect();

    $partnerLogos = collect([
        $project->logo1 ?? null,
        $project->logo2 ?? null,
        $project->logo3 ?? null,
    ])->filter(fn ($logo) => filled($logo))->values();

    $textAnswers = $answers->filter(fn ($answer) => optional($answer->field)->type !== 'file');
    $fileAnswers = $answers->filter(fn ($answer) => optional($answer->field)->type === 'file');

    $renderAnswerValue = function ($value): string {
        if (is_null($value)) {
            return 'N/A';
        }

        if (is_bool($value)) {
            return $value ? 'Oui' : 'Non';
        }

        if (is_array($value)) {
            $flat = collect($value)
                ->map(fn ($item) => is_scalar($item) ? (string) $item : json_encode($item, JSON_UNESCAPED_UNICODE))
                ->filter(fn ($item) => filled($item) && $item !== 'null')
                ->values()
                ->all();

            return empty($flat) ? 'N/A' : implode(', ', $flat);
        }

        if (is_object($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE) ?: 'N/A';
        }

        $stringValue = trim((string) $value);
        if ($stringValue === '') {
            return 'N/A';
        }

        $decoded = json_decode($stringValue, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $flat = collect($decoded)
                ->map(fn ($item) => is_scalar($item) ? (string) $item : json_encode($item, JSON_UNESCAPED_UNICODE))
                ->filter(fn ($item) => filled($item) && $item !== 'null')
                ->values()
                ->all();

            return empty($flat) ? 'N/A' : implode(', ', $flat);
        }

        return $stringValue;
    };

    $extractFilePaths = function ($rawValue): array {
        if (is_array($rawValue)) {
            return collect($rawValue)
                ->filter(fn ($path) => is_string($path) && trim($path) !== '')
                ->map(fn ($path) => trim($path))
                ->values()
                ->all();
        }

        $raw = trim((string) $rawValue);
        if ($raw === '') {
            return [];
        }

        $decoded = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return collect($decoded)
                ->filter(fn ($path) => is_string($path) && trim($path) !== '')
                ->map(fn ($path) => trim($path))
                ->values()
                ->all();
        }

        return [$raw];
    };

    $fileDocuments = collect();
    foreach ($fileAnswers as $fileAnswer) {
        foreach ($extractFilePaths($fileAnswer->value) as $path) {
            $cleanPath = ltrim(str_starts_with($path, 'uploads/') ? substr($path, 8) : $path, '/');

            if ($cleanPath === '') {
                continue;
            }

            $extension = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));

            $fileDocuments->push([
                'label' => $fileAnswer->field->label ?? 'Fichier uploade',
                'path' => $cleanPath,
                'name' => basename($cleanPath),
                'ext' => $extension,
                'url' => route('uploads.show', ['path' => $cleanPath]),
                'is_image' => in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'], true),
                'is_pdf' => $extension === 'pdf',
            ]);
        }
    }

    $uploadedFilesCount = $fileDocuments->count();
@endphp

<div class="a4-page">
    <div class="page-bg"></div>

    <div class="sheet-content">
        <div class="sheet-header">
            <div class="brand-main">
                <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="IUHM logo">
                <div>
                    <h1 class="sheet-title">Formulaire de soumission</h1>
                    <p class="sheet-subtitle">Mise en page compacte en 4 parties pour economiser le papier</p>
                </div>
            </div>

          
        </div>

        <div class="compact-meta">
            <div class="meta-card">
                <span class="meta-label">Projet</span>
                <span class="meta-value">{{ $project->name ?? 'N/A' }}</span>
            </div>
            <div class="meta-card">
                <span class="meta-label">Candidat</span>
                <span class="meta-value">{{ trim(($submission->candidat->prenom ?? '') . ' ' . ($submission->candidat->nom ?? '')) ?: 'N/A' }}</span>
            </div>
            <div class="meta-card">
                <span class="meta-label">Submission ID</span>
                <span class="meta-value">#{{ $submission->id }}</span>
            </div>
            <div class="meta-card">
                <span class="meta-label">Date</span>
                <span class="meta-value">{{ $submission->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</span>
            </div>
        </div>

    

        <section class="page-part">
            <div class="part-head">
                <span class="part-tag">Partie 1</span>
                <h2 class="part-title">Informations candidat et projet</h2>
            </div>
            <div class="detail-grid">
                <div class="detail-item">
                    <span>Nom complet</span>
                    <strong>{{ trim(($submission->candidat->prenom ?? '') . ' ' . ($submission->candidat->nom ?? '')) ?: 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                    <span>Email</span>
                    <strong>{{ $submission->candidat->email ?? 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                    <span>Programme</span>
                    <strong>{{ $project->name ?? 'N/A' }}</strong>
                </div>
                <div class="detail-item">
                    <span>Date de creation</span>
                    <strong>{{ $submission->created_at?->format('d/m/Y') ?? 'N/A' }}</strong>
                </div>
            </div>
        </section>

        <section class="page-part">
            <div class="part-head">
                <span class="part-tag">Partie 2</span>
                <h2 class="part-title">Reponses</h2>
            </div>

            @if($textAnswers->isEmpty())
                <p class="answer-empty">Aucune reponse textuelle disponible.</p>
            @else
                <div class="answers-grid">
                    @foreach($textAnswers as $answer)
                        <article class="answer-card">
                            <p class="answer-label">{{ $answer->field->label ?? 'Question' }}</p>
                            <p class="answer-value">{{ $renderAnswerValue($answer->value) }}</p>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>


        <p class="footer-note">Genere le {{ now()->format('d/m/Y H:i') }}</p>

        
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




