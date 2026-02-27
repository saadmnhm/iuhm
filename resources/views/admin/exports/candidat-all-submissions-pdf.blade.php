<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Toutes les soumissions – {{ $candidat->nom }} {{ $candidat->prenom }}</title>
    <style>
        @page { margin: 15mm; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0; padding: 0;
        }
        /* ── Cover header ── */
        .cover-header {
            border-bottom: 3px solid #4f46e5;
            margin-bottom: 20px;
            padding-bottom: 14px;
        }
        .cover-title { font-size: 22px; font-weight: bold; color: #4f46e5; margin: 0 0 4px 0; }
        .cover-sub   { font-size: 12px; color: #555; margin: 0; }
        /* ── Candidat box ── */
        .candidat-box {
            background: #f0f4ff;
            border: 2px solid #c7d2fe;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 20px;
        }
        .candidat-title { font-size: 15px; font-weight: bold; color: #1e1b4b; margin: 0 0 8px 0; }
        .meta-table { width: 100%; border-collapse: collapse; }
        .meta-label { width: 22%; font-size: 10px; color: #6366f1; font-weight: bold; vertical-align: top; padding: 2px 0; }
        .meta-value { width: 28%; font-size: 11px; color: #111; vertical-align: top; padding: 2px 8px 2px 0; }
        /* ── Submission separator ── */
        .submission-section { page-break-inside: avoid; margin-bottom: 24px; }
        .submission-header {
            background: #4f46e5;
            color: #fff;
            padding: 8px 14px;
            border-radius: 6px 6px 0 0;
        }
        .submission-header-table { width: 100%; border-collapse: collapse; }
        .sub-title { font-size: 13px; font-weight: bold; }
        .sub-right { text-align: right; font-size: 11px; }
        /* ── Badges ── */
        .badge { display:inline-block; padding:2px 8px; border-radius:10px; font-size:10px; font-weight:bold; }
        .badge-submitted  { background:#dbeafe; color:#1d4ed8; }
        .badge-in_review  { background:#ede9fe; color:#6d28d9; }
        .badge-approved   { background:#d1fae5; color:#065f46; }
        .badge-rejected   { background:#fee2e2; color:#991b1b; }
        .badge-draft      { background:#fef3c7; color:#92400e; }
        .badge-default    { background:#f3f4f6; color:#374151; }
        /* ── Meta row under submission header ── */
        .sub-meta {
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            border-top: none;
            padding: 6px 14px;
            font-size: 10px;
            color: #4b5563;
        }
        /* ── Step section ── */
        .step-title {
            font-size: 12px;
            font-weight: bold;
            color: #fff;
            background: #6366f1;
            padding: 5px 12px;
            margin-top: 10px;
        }
        .section-border {
            border: 1px solid #c7d2fe;
            border-top: none;
            border-radius: 0 0 4px 4px;
            margin-bottom: 8px;
        }
        /* ── Fields ── */
        .fields-table { width: 100%; border-collapse: collapse; }
        .field-row    { border-bottom: 1px solid #e5e7eb; }
        .field-row:last-child { border-bottom: none; }
        .field-label  {
            width: 38%; padding: 6px 10px 6px 12px;
            font-size: 10px; font-weight: bold; color: #4b5563;
            background: #f9fafb; vertical-align: top;
        }
        .field-value  {
            width: 62%; padding: 6px 10px;
            font-size: 11px; color: #111827; vertical-align: top;
            word-break: break-word;
        }
        .no-answer { color: #9ca3af; font-style: italic; }
        /* ── Data tables ── */
        .data-table { width: 100%; border-collapse: collapse; margin: 4px 0 6px 0; font-size: 10px; }
        .data-table th { background:#6366f1; color:#fff; padding:4px 8px; text-align:left; }
        .data-table td { padding:4px 8px; border-bottom:1px solid #e5e7eb; }
        .data-table tr:nth-child(even) td { background:#f9fafb; }
        /* ── Page break ── */
        .page-break { page-break-after: always; }
        /* ── Footer ── */
        .page-footer {
            position: fixed; bottom: 8mm; left: 0; right: 0;
            text-align: center; font-size: 9px; color: #9ca3af;
            border-top: 1px solid #e5e7eb; padding-top: 4px;
        }
        /* ── TOC ── */
        .toc-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .toc-table td { padding: 5px 8px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
        .toc-table tr:nth-child(even) td { background: #f9fafb; }
        .toc-num { width: 8%; font-weight: bold; color: #6366f1; }
        .toc-form { width: 55%; }
        .toc-status { width: 20%; text-align: center; }
        .toc-date { width: 17%; color: #6b7280; font-size: 10px; }
    </style>
</head>
<body>

    <div class="page-footer">
        Généré le {{ now()->format('d/m/Y à H:i') }} — IUHM &nbsp;|&nbsp; {{ $candidat->nom }} {{ $candidat->prenom }}
    </div>

    {{-- ══ COVER ══ --}}
    <div class="cover-header">
        <p class="cover-title">Dossier de soumissions</p>
        <p class="cover-sub">Rapport complet des formulaires soumis</p>
    </div>

    <div class="candidat-box">
        <p class="candidat-title">{{ $candidat->nom }} {{ $candidat->prenom }}</p>
        <table class="meta-table">
            <tr>
                <td class="meta-label">Email</td>
                <td class="meta-value">{{ $candidat->email ?? '—' }}</td>
                <td class="meta-label">Téléphone</td>
                <td class="meta-value">{{ $candidat->phone ?? '—' }}</td>
            </tr>
            <tr>
                <td class="meta-label">Adresse</td>
                <td class="meta-value">{{ $candidat->address ?? '—' }}</td>
                <td class="meta-label">Formulaires</td>
                <td class="meta-value">{{ $submissions->count() }} soumission(s)</td>
            </tr>
        </table>
    </div>

    {{-- ══ TABLE OF CONTENTS ══ --}}
    @if($submissions->count() > 1)
    <p style="font-size:12px;font-weight:bold;color:#4f46e5;margin:0 0 6px 0;">Sommaire</p>
    <table class="toc-table">
        <thead>
            <tr style="background:#4f46e5;color:#fff;">
                <td class="toc-num">#</td>
                <td class="toc-form" style="font-weight:bold;">Formulaire</td>
                <td class="toc-status" style="font-weight:bold;">Statut</td>
                <td class="toc-date" style="font-weight:bold;">Soumis le</td>
            </tr>
        </thead>
        <tbody>
            @foreach($submissions as $i => $sub)
            @php
                $statusLabels = [
                    'draft'     => ['Brouillon',  'badge-draft'],
                    'submitted' => ['Soumis',     'badge-submitted'],
                    'in_review' => ['En révision','badge-in_review'],
                    'approved'  => ['Approuvé',   'badge-approved'],
                    'rejected'  => ['Rejeté',     'badge-rejected'],
                ];
                [$stxt, $sclass] = $statusLabels[$sub->status ?? ''] ?? ['Inconnu','badge-default'];
            @endphp
            <tr>
                <td class="toc-num">{{ $i + 1 }}</td>
                <td class="toc-form">{{ $sub->form->title ?? 'Formulaire #'.$sub->id }}</td>
                <td class="toc-status"><span class="badge {{ $sclass }}">{{ $stxt }}</span></td>
                <td class="toc-date">{{ $sub->submitted_at ? \Carbon\Carbon::parse($sub->submitted_at)->format('d/m/Y') : '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- ══ EACH SUBMISSION ══ --}}
    @foreach($submissions as $i => $submission)
    @php
        $statusLabels = [
            'draft'     => ['Brouillon',  'badge-draft'],
            'submitted' => ['Soumis',     'badge-submitted'],
            'in_review' => ['En révision','badge-in_review'],
            'approved'  => ['Approuvé',   'badge-approved'],
            'rejected'  => ['Rejeté',     'badge-rejected'],
        ];
        [$statusText, $statusClass] = $statusLabels[$submission->status ?? ''] ?? ['Inconnu','badge-default'];
        $answers = $submission->answers_keyed ?? $submission->answers->keyBy('field_key');
    @endphp

    @if($i > 0)
    <div class="page-break"></div>
    @endif

    <div class="submission-section">
        {{-- Submission header bar --}}
        <div class="submission-header">
            <table class="submission-header-table">
                <tr>
                    <td class="sub-title">{{ $i + 1 }}. {{ $submission->form->title ?? 'Formulaire' }}</td>
                    <td class="sub-right"><span class="badge {{ $statusClass }}">{{ $statusText }}</span></td>
                </tr>
            </table>
        </div>

        {{-- Submission meta --}}
        <div class="sub-meta">
            Soumission #{{ $submission->id }}
            &nbsp;|&nbsp; Soumis le : {{ $submission->submitted_at ? \Carbon\Carbon::parse($submission->submitted_at)->format('d/m/Y H:i') : '—' }}
            @if($submission->reviewer)
            &nbsp;|&nbsp; Révisé par : {{ $submission->reviewer->name }}
            @endif
            @if($submission->review_notes)
            &nbsp;|&nbsp; <em>{{ $submission->review_notes }}</em>
            @endif
        </div>

        {{-- Steps & Answers --}}
        @foreach($submission->form->steps->sortBy('order') as $step)
        <div>
            <div class="step-title">{{ $step->title }}</div>
            <div class="section-border">
                {{-- Fields --}}
                @if($step->fields->isNotEmpty())
                <table class="fields-table">
                    @foreach($step->fields->sortBy('order') as $field)
                    @php $answer = $answers->get($field->field_key); @endphp
                    <tr class="field-row">
                        <td class="field-label">{{ $field->label }}</td>
                        <td class="field-value">
                            @if($answer && $answer->value !== null && $answer->value !== '')
                                @php
                                    $val = $answer->value;
                                    $decoded = is_string($val) ? json_decode($val, true) : null;
                                @endphp
                                @if(is_array($decoded))
                                    {{ implode(', ', $decoded) }}
                                @else
                                    {{ $val }}
                                @endif
                            @else
                                <span class="no-answer">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </table>
                @endif

                {{-- Table fields --}}
                @foreach($step->tables as $table)
                @php
                    $tableAnswers = $submission->tableAnswers
                        ->where('dynamic_form_table_id', $table->id)
                        ->groupBy('row_index')
                        ->sortKeys();
                @endphp
                @if($tableAnswers->isNotEmpty())
                <div style="padding:6px 12px;">
                    <p style="font-size:11px;font-weight:bold;color:#4b5563;margin:4px 0 4px 0;">{{ $table->title ?? 'Tableau' }}</p>
                    <table class="data-table">
                        <thead>
                            <tr>
                                @foreach($table->columns->sortBy('sort_order') as $col)
                                <th>{{ $col->header }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tableAnswers as $rowIndex => $rowAnswers)
                            <tr>
                                @foreach($table->columns->sortBy('sort_order') as $col)
                                @php $cell = $rowAnswers->firstWhere('column_key', $col->column_key); @endphp
                                <td>{{ $cell?->value ?? '—' }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    @endforeach

</body>
</html>
