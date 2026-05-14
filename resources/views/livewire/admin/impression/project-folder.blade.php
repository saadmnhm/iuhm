@php
    $folderAgreement = $agreement ?: (object) [
        'how_knew' => null,
        'project_idea' => null,
    ];

    $hasAgreementPage = isset($agreement) && $agreement;
    $hasEvaluationPage = isset($evaluation) && $evaluation;
    $hasProjectSubmission = isset($ProjectsSubmission) && $ProjectsSubmission;

    $reviewAnswers = (array) ($ProjectsSubmission->formation_review_answers ?? []);
    $reviewFiles = (array) ($ProjectsSubmission->formation_review_files ?? []);

    $hasAvisFormation = $hasProjectSubmission && (
        filled($ProjectsSubmission->formation_review_rating)
        || filled($ProjectsSubmission->formation_review_feedback)
        || !empty($reviewAnswers)
        || !empty($reviewFiles)
    );
@endphp

<style>
    .folder-page {
        width: 210mm;
        height: 297mm;
        min-height: 297mm;
        background: #fff;
        position: relative;
        page-break-after: always;
        break-after: page;
        margin: 0 auto 14px;
        box-sizing: border-box;
    }

    .folder-page:last-of-type {
        page-break-after: auto;
        break-after: auto;
    }

    .folder-page .content,
    .folder-page .page {
        min-height: 100%;
    }

    @media print {
        .paper {
            height: auto !important;
            min-height: 297mm;
        }

        .folder-page {
            margin: 0;
        }
    }
</style>

<div class="folder-page">
    @include('livewire.admin.impression.project-fiche-inscription', [
        'candidat' => $candidat,
        'project' => $project,
        'agreement' => $folderAgreement,
        'association' => $association ?? null,
    ])
</div>

@if($hasAgreementPage)
    <div class="folder-page">
        @include('livewire.admin.impression.project-agreement', [
            'candidat' => $candidat,
            'project' => $project,
            'agreement' => $agreement,
            'association' => $association ?? null,
        ])
    </div>
@endif

@foreach($submissions as $submission)
    @include('livewire.admin.impression.formulaire', [
        'submission' => $submission,
        'project' => $project,
        'association' => $association ?? null,
    ])
@endforeach

@if($hasEvaluationPage)
    @include('livewire.admin.impression.project-evaluation', [
        'candidat' => $candidat,
        'project' => $project,
        'evaluation' => $evaluation,
        'association' => $association ?? null,
    ])
@endif

@if($hasProjectSubmission)
    @include('livewire.admin.impression.project-review', [
        'candidat' => $candidat,
        'project' => $project,
        'submission' => $ProjectsSubmission,
        'association' => $association ?? null,
    ])
@endif

@if($hasAvisFormation)
    <div class="folder-page">
        @include('livewire.admin.impression.avis-formation', [
            'candidat' => $candidat,
            'project' => $project,
            'submission' => $ProjectsSubmission,
            'association' => $association ?? null,
        ])
    </div>
@endif

<style>
    /* Keep project folder pages centered even when included templates define global body styles. */
    @media print {
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        .print-wrap,
        .paper {
        }

        .paper {
            width: 210mm !important;
            margin: 0 auto !important;
        }

        .a4-page,
        .folder-page {
            margin-left: auto !important;
            margin-right: auto !important;
        }
    }
</style>

