<?php

/**
 * Test Script for Form Refactoring
 * 
 * Run: php artisan tinker
 * Then paste this code to test the refactored system
 */

// Test 1: Check if FormSubmissionService works
echo "=== Test 1: FormSubmissionService ===\n";
$service = new \App\Services\FormSubmissionService();
$allSubmissions = $service->getAllSubmissions();
echo "Total submissions across all forms: " . $allSubmissions->count() . "\n";
echo "Form types found: " . $allSubmissions->pluck('form_type')->unique()->implode(', ') . "\n\n";

// Test 2: Check trait methods on models
echo "=== Test 2: Model Trait Methods ===\n";
$models = [
    \App\Models\BusinessPlan::class,
    \App\Models\EtudeMarche::class,
    \App\Models\EvaluationIdee::class,
    \App\Models\Bmc::class,
    \App\Models\BilanCompetence::class,
];

foreach ($models as $model) {
    $instance = $model::first();
    if ($instance) {
        echo class_basename($model) . ":\n";
        echo "  - form_type: " . ($instance->form_type ?? $instance->getFormType()) . "\n";
        echo "  - form_type_label: " . $instance->form_type_label . "\n";
        echo "  - status: " . $instance->status . "\n";
        echo "  - status_label: " . $instance->status_label . "\n";
        echo "  - isDraft(): " . ($instance->isDraft() ? 'Yes' : 'No') . "\n";
        echo "  - isSubmitted(): " . ($instance->isSubmitted() ? 'Yes' : 'No') . "\n";
        echo "  - canBeEdited(): " . ($instance->canBeEdited() ? 'Yes' : 'No') . "\n\n";
    } else {
        echo class_basename($model) . ": No records found\n\n";
    }
}

// Test 3: Check candidat submissions
echo "=== Test 3: Candidat Submissions ===\n";
$candidat = \App\Models\Candidat::first();
if ($candidat) {
    $submissions = $service->getCandidatSubmissions($candidat->id);
    echo "Candidat: " . $candidat->nom . " " . $candidat->prenom . "\n";
    echo "Total submissions: " . $submissions->count() . "\n";
    
    foreach ($submissions as $submission) {
        echo "  - " . $submission->form_type_label . " (#" . $submission->id . "): " . $submission->status_label . "\n";
    }
    
    echo "\nStats:\n";
    $stats = $service->getCandidatStats($candidat->id);
    foreach ($stats as $key => $value) {
        echo "  - " . ucfirst($key) . ": " . $value . "\n";
    }
} else {
    echo "No candidats found\n";
}

echo "\n=== All Tests Complete ===\n";
echo "✅ If you see no errors, the refactoring is working correctly!\n";
