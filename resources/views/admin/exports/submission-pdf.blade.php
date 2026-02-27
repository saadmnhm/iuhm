<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $submission->form->title ?? 'Formulaire' }} – {{ $submission->candidat->nom ?? '' }}</title>
    <style>
        @page { margin: 15mm; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0; padding: 0;
        }
        /* ── Header ── */
        .page-header {
            border-bottom: 3px solid #4f46e5;
            margin-bottom: 18px;
            padding-bottom: 12px;
        }
        .header-table { width: 100%; border-collapse: collapse; }
        .header-left { width: 70%; vertical-align: middle; }
        .header-right { width: 30%; vertical-align: middle; text-align: right; }
        .doc-title { font-size: 18px; font-weight: bold; color: #4f46e5; margin: 0 0 4px 0; }
        .doc-sub   { font-size: 11px; color: #555; }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-submitted  { background:#dbeafe; color:#1d4ed8; }
        .badge-in_review  { background:#ede9fe; color:#6d28d9; }
        .badge-approved   { background:#d1fae5; color:#065f46; }
        .badge-rejected   { background:#fee2e2; color:#991b1b; }
        .badge-draft      { background:#fef3c7; color:#92400e; }
        .badge-default    { background:#f3f4f6; color:#374151; }
        /* ── Candidat info box ── */
        .info-box {
            background: #f8f9ff;
            border: 1px solid #c7d2fe;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }
        .info-row { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .info-label { width: 35%; font-size: 10px; color: #6366f1; font-weight: bold; vertical-align: top; }
        .info-value { font-size: 11px; color: #111; vertical-align: top; }
        /* ── Step section ── */
        .step-title {
            font-size: 13px;
            font-weight: bold;
            color: #fff;
            background: #4f46e5;
            padding: 6px 12px;
            margin: 16px 0 0 0;
            border-radius: 4px 4px 0 0;
        }
        /* ── Field rows ── */
        .fields-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .field-row { border-bottom: 1px solid #e5e7eb; }
        .field-row:last-child { border-bottom: none; }
        .field-label {
            width: 40%;
            padding: 7px 10px 7px 12px;
            font-size: 10px;
            font-weight: bold;
            color: #4b5563;
            background: #f9fafb;
            vertical-align: top;
        }
        .field-value {
            width: 60%;
            padding: 7px 10px;
            font-size: 11px;
            color: #111827;
            vertical-align: top;
            word-break: break-word;
        }
        .no-answer { color: #9ca3af; font-style: italic; }
        /* ── Tables ── */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 6px; font-size: 10px; }
        .data-table th {
            background: #4f46e5;
            color: #fff;
            padding: 5px 8px;
            text-align: left;
            font-size: 10px;
        }
        .data-table td {
            padding: 5px 8px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) td { background: #f9fafb; }
        /* ── Footer ── */
        .page-footer {
            position: fixed;
            bottom: 8mm;
            left: 0; right: 0;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 4px;
        }
        .section-border {
            border: 1px solid #c7d2fe;
            border-top: none;
            border-radius: 0 0 4px 4px;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>

    <div class="page-footer">
        Généré le {{ now()->format('d/m/Y à H:i') }} — IUHM
    </div>

    {{-- ══ PAGE HEADER ══ --}}
    <div class="page-header">
        <table class="header-table">
            <tr>
                <td class="header-left">
                    <p class="doc-title">{{ $submission->form->title ?? 'Formulaire' }}</p>
                    <p class="doc-sub">Soumission #{{ $submission->id }}</p>
                </td>
                <td class="header-right">
                    @php
                        $statusLabels = [
                            'draft'     => ['Brouillon',  'badge-draft'],
                            'submitted' => ['Soumis',     'badge-submitted'],
                            'in_review' => ['En révision','badge-in_review'],
                            'approved'  => ['Approuvé',   'badge-approved'],
                            'rejected'  => ['Rejeté',     'badge-rejected'],
                        ];
                        [$statusText, $statusClass] = $statusLabels[$submission->status ?? ''] ?? ['Inconnu', 'badge-default'];
                    @endphp
                    <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                </td>
            </tr>
        </table>
    </div>

    {{-- ══ CANDIDAT INFO ══ --}}
    <div class="info-box">
        <table class="info-row">
            <tr>
                <td class="info-label">Candidat</td>
                <td class="info-value"><strong>{{ $submission->candidat->nom ?? '—' }} {{ $submission->candidat->prenom ?? '' }}</strong></td>
                <td class="info-label">Email</td>
                <td class="info-value">{{ $submission->candidat->email ?? '—' }}</td>
            </tr>
            <tr>
                <td class="info-label">Tél.</td>
                <td class="info-value">{{ $submission->candidat->phone ?? '—' }}</td>
                <td class="info-label">Programme</td>
                <td class="info-value">{{ $submission->programe->project_name ?? '—' }}</td>
            </tr>
            <tr>
                <td class="info-label">Soumis le</td>
                <td class="info-value">{{ $submission->submitted_at ? \Carbon\Carbon::parse($submission->submitted_at)->format('d/m/Y H:i') : '—' }}</td>
                @if($submission->reviewer)
                <td class="info-label">Révisé par</td>
                <td class="info-value">{{ $submission->reviewer->name }}</td>
                @endif
            </tr>
            @if($submission->review_notes)
            <tr>
                <td class="info-label">Notes</td>
                <td class="info-value" colspan="3" style="font-style:italic;color:#6b7280;">{{ $submission->review_notes }}</td>
            </tr>
            @endif
        </table>
    </div>

    {{-- ══ FORM STEPS & ANSWERS ══ --}}
    @foreach($submission->form->steps->sortBy('order') as $step)
    <div>
        <div class="step-title">{{ $step->title }}</div>
        <div class="section-border">
            {{-- Regular fields --}}
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
                                // Try to detect JSON arrays (checkboxes/multi-select)
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
            <div style="padding:8px 12px;">
                <p style="font-size:11px;font-weight:bold;color:#4b5563;margin:6px 0 4px 0;">{{ $table->title ?? 'Tableau' }}</p>
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

</body>
</html>
