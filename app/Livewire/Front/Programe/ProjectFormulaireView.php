<?php

namespace App\Livewire\Front\Programe;

use Livewire\Component;
use App\Models\ProjectsList;
use App\Models\DynamicForm;
use App\Models\DynamicFormSubmission;
use App\Models\DynamicFormAnswer;
use App\Models\DynamicFormTableAnswer;
use App\Models\CandidatFormulaireOrder;
use App\Models\CandidatProjectAgreement;
use App\Models\ProjectsSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Services\ProjectEligibilityService;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class ProjectFormulaireView extends Component
{
    use WithFileUploads;

    public $projectId;
    public $formulaireSlug;
    public $order;
    public $project;
    public $formulaire;
    public $currentStep = 1;
    public $showIntroduction = true;
    public $isReadOnly = false;

    // Answers stored by field id (matching DynamicFormWizard pattern)
    public array $answers = [];

    // Table data stored by table_key => [row_index => [column_key => value]]
    public array $tableData = [];

    // Track dynamic row counts per table
    public array $tableRowCounts = [];

    public ?int $submissionId = null;
    public ?DynamicFormSubmission $existingSubmission = null;
    protected ProjectEligibilityService $eligibilityService;

    public function boot(ProjectEligibilityService $eligibilityService): void
    {
        $this->eligibilityService = $eligibilityService;
    }

    public function mount($projectId, $formulaireSlug, $order)
    {
        $this->projectId = $projectId;
        $this->formulaireSlug = $formulaireSlug;
        $this->order = $order;

        return $this->loadData();
    }

    public function loadData()
    {
        $this->project = ProjectsList::with(['formulaires' => function ($query) {
            $query->where('programe_formulaire.status', 'active')
                ->orderBy('programe_formulaire.order');
        }])->findOrFail($this->projectId);

        $candidat = Auth::guard('candidat')->user();
        if ($candidat) {
            $check = $this->eligibilityService->evaluate($candidat, $this->project);
            if (!$check['eligible']) {
                session()->flash('error', 'Vous ne pouvez pas remplir ce projet: ' . implode(' ', $check['reasons']));
                return redirect()->route('user.projects.list');
            }
        }

        // Frontend is behind candidat middleware, so always use candidat guard
        $candidatId = Auth::guard('candidat')->id();

        $hasAgreement = CandidatProjectAgreement::where('candidat_id', $candidatId)
            ->where('project_id', $this->projectId)
            ->exists();

        if (!$hasAgreement) {
            return redirect()->route('user.project.conditions', $this->projectId);
        }

        $customOrders = CandidatFormulaireOrder::where('candidat_id', $candidatId)
            ->where('programe_id', $this->projectId)
            ->get()
            ->keyBy('formulaire_id');

        $submissionMap = DynamicFormSubmission::where('programe_id', $this->projectId)
            ->where('candidat_id', $candidatId)
            ->whereIn('dynamic_form_id', $this->project->formulaires->pluck('id'))
            ->get()
            ->keyBy('dynamic_form_id');

        $orderedForms = $this->project->formulaires
            ->map(function ($form) use ($customOrders, $submissionMap) {
                $submission = $submissionMap->get($form->id);

                return [
                    'id' => $form->id,
                    'slug' => $form->slug,
                    'title' => $form->title,
                    'is_required' => (bool) $form->pivot->is_required,
                    'unlock_on_status' => $form->pivot->unlock_on_status ?? 'approved',
                    'order' => $customOrders->has($form->id)
                        ? (int) $customOrders->get($form->id)->order
                        : (int) $form->pivot->order,
                    'global_order' => (int) $form->pivot->order,
                    'is_submitted' => (bool) ($submission?->is_submitted ?? false),
                    'submission_status' => $submission?->status,
                    'workflow_stages' => is_array($submission?->workflow_stages) ? $submission->workflow_stages : [],
                ];
            })
            ->sortBy([['order', 'asc'], ['global_order', 'asc'], ['id', 'asc']])
            ->values();

        $blockingTitle = null;
        $orderedForms = $orderedForms->map(function ($form) use (&$blockingTitle) {
            $form['can_start'] = $blockingTitle === null;
            $form['lock_reason'] = $form['can_start']
                ? null
                : 'Vous devez attendre la validation du formulaire précédent: ' . $blockingTitle;

            if ($form['is_required'] && !$this->meetsUnlockStatus($form['workflow_stages'] ?? []) && $blockingTitle === null) {
                $blockingTitle = $form['title'];
            }

            return $form;
        })->values();

        $target = $orderedForms->firstWhere('slug', $this->formulaireSlug);
        if (!$target) {
            abort(404);
        }

        $targetSubmission = $submissionMap->get($target['id']);
        if (!$target['can_start'] && !($targetSubmission && $targetSubmission->isSubmitted())) {
            session()->flash('error', $target['lock_reason'] ?: 'Vous devez compléter le formulaire précédent.');
            return redirect()->route('user.project.detail', $this->projectId);
        }

        $this->order = $target['order'];

        // Load existing submission
        $existing = $targetSubmission;

        $this->formulaire = DynamicForm::with(['steps.fields', 'steps.tables.columns', 'steps.tables.fixedRows'])
            ->findOrFail($target['id']);
        $this->showIntroduction = (bool) $this->formulaire->has_introduction;

        if ($existing && $existing->isSubmitted()) {
            $this->existingSubmission = $existing;
            $this->submissionId = $existing->id;
            $this->isReadOnly = true;
            $this->showIntroduction = false;
            $this->currentStep = 1;
            $this->loadExistingData();
        } elseif ($existing && $existing->isDraft()) {
            $this->existingSubmission = $existing;
            $this->submissionId = $existing->id;
            $this->currentStep = $existing->current_step ?? 1;
            $this->showIntroduction = false;
            $this->loadExistingData();
        }

        // Initialize table row counts and data
        foreach ($this->formulaire->steps as $formStep) {
            foreach ($formStep->tables as $table) {
                if ($table->has_dynamic_rows && !isset($this->tableRowCounts[$table->table_key])) {
                    $this->tableRowCounts[$table->table_key] = $table->min_rows;
                }
                if (!$table->has_dynamic_rows && $table->fixedRows->isNotEmpty()) {
                    foreach ($table->fixedRows as $ri => $row) {
                        foreach ($table->columns as $col) {
                            if (!isset($this->tableData[$table->table_key][$ri][$col->column_key])) {
                                $this->tableData[$table->table_key][$ri][$col->column_key] = '';
                            }
                        }
                    }
                } elseif ($table->has_dynamic_rows) {
                    $count = $this->tableRowCounts[$table->table_key] ?? $table->min_rows;
                    for ($r = 0; $r < $count; $r++) {
                        foreach ($table->columns as $col) {
                            if (!isset($this->tableData[$table->table_key][$r][$col->column_key])) {
                                $this->tableData[$table->table_key][$r][$col->column_key] = '';
                            }
                        }
                    }
                }
            }
        }
    }

    protected function meetsUnlockStatus(array $workflowStages): bool
    {
        return (bool) ($workflowStages['next_form_allowed'] ?? false);
    }

    protected function loadExistingData()
    {
        $submission = DynamicFormSubmission::with(['answers', 'tableAnswers'])->find($this->submissionId);
        if (!$submission) return;

        foreach ($submission->answers as $answer) {
            if ($answer->dynamic_form_field_id) {
                $this->answers[$answer->dynamic_form_field_id] = $answer->value;
            }
        }

        foreach ($submission->tableAnswers as $ta) {
            $this->tableData[$ta->table_key][$ta->row_index][$ta->column_key] = $ta->value;
        }

        foreach ($this->formulaire->steps as $formStep) {
            foreach ($formStep->tables as $table) {
                if ($table->has_dynamic_rows) {
                    $maxRow = 0;
                    if (isset($this->tableData[$table->table_key])) {
                        $maxRow = max(array_keys($this->tableData[$table->table_key])) + 1;
                    }
                    $this->tableRowCounts[$table->table_key] = max($maxRow, $table->min_rows);
                }

                // Restore _radio selection from saved column values
                $radioColKeys = $table->columns->where('input_type', 'radio')->pluck('column_key');
                if ($radioColKeys->isNotEmpty() && isset($this->tableData[$table->table_key])) {
                    foreach ($this->tableData[$table->table_key] as $ri => &$rowData) {
                        foreach ($radioColKeys as $colKey) {
                            if (!empty($rowData[$colKey])) {
                                $rowData['_radio'] = $colKey;
                                break;
                            }
                        }
                    }
                    unset($rowData);
                }
            }
        }
    }

    public function skipIntroduction()
    {
        $this->showIntroduction = false;
    }

    public function nextStep()
    {
        if ($this->showIntroduction) {
            $this->showIntroduction = false;
            return;
        }

        if ($this->isReadOnly) {
            if ($this->currentStep < $this->formulaire->steps->count()) {
                $this->currentStep++;
                $this->dispatch('scroll-to-top');
            }
            return;
        }

        $this->validateCurrentStep();
        $this->saveAsDraft();

        if ($this->currentStep < $this->formulaire->steps->count()) {
            $this->currentStep++;
            $this->dispatch('scroll-to-top');
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->dispatch('scroll-to-top');
        } elseif ($this->formulaire->has_introduction && !$this->showIntroduction) {
            $this->showIntroduction = true;
        }
    }

    public function addTableRow(string $tableKey)
    {
        if ($this->isReadOnly) return;

        foreach ($this->formulaire->steps as $formStep) {
            foreach ($formStep->tables as $table) {
                if ($table->table_key === $tableKey) {
                    $count = $this->tableRowCounts[$tableKey] ?? $table->min_rows;
                    if ($count < $table->max_rows) {
                        $newIndex = $count;
                        foreach ($table->columns as $col) {
                            $this->tableData[$tableKey][$newIndex][$col->column_key] = '';
                        }
                        $this->tableRowCounts[$tableKey] = $count + 1;
                    }
                    return;
                }
            }
        }
    }

    public function removeTableRow(string $tableKey, int $rowIndex)
    {
        if ($this->isReadOnly) return;

        foreach ($this->formulaire->steps as $formStep) {
            foreach ($formStep->tables as $table) {
                if ($table->table_key === $tableKey) {
                    $count = $this->tableRowCounts[$tableKey] ?? $table->min_rows;
                    if ($count > $table->min_rows) {
                        unset($this->tableData[$tableKey][$rowIndex]);
                        $this->tableData[$tableKey] = array_values($this->tableData[$tableKey]);
                        $this->tableRowCounts[$tableKey] = $count - 1;
                    }
                    return;
                }
            }
        }
    }

    public function getTableTotal(string $tableKey, string $columnKey): float
    {
        $total = 0;
        if (isset($this->tableData[$tableKey])) {
            foreach ($this->tableData[$tableKey] as $row) {
                $total += (float)($row[$columnKey] ?? 0);
            }
        }
        return $total;
    }

    protected function validateCurrentStep()
    {
        $currentStepData = $this->formulaire->steps->firstWhere('step_number', $this->currentStep);
        if (!$currentStepData) return;

        $rules = [];
        foreach ($currentStepData->fields as $field) {
            if ($field->is_required && !in_array($field->type, ['heading', 'paragraph', 'file'])) {
                $rules['answers.' . $field->id] = 'required';
            }
        }

        if (!empty($rules)) {
            $this->validate($rules, [
                'required' => 'This field is required.',
            ]);
        }

        $this->validateFileAnswersForFields($currentStepData->fields);
    }

    protected function validateFileAnswersForFields($fields): void
    {
        $allowedExtensions = ['pdf', 'xls', 'xlsx', 'csv', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'webp'];
        $maxFileSizeBytes = 10 * 1024 * 1024; // 10 MB
        $errors = [];

        foreach ($fields as $field) {
            if (($field->type ?? null) !== 'file') {
                continue;
            }

            $value = $this->answers[$field->id] ?? null;
            $uploaded = [];

            if ($value instanceof TemporaryUploadedFile) {
                $uploaded = [$value];
            } elseif (is_array($value)) {
                foreach ($value as $item) {
                    if ($item instanceof TemporaryUploadedFile) {
                        $uploaded[] = $item;
                    }
                }
            }

            $hasExistingPath = is_string($value) && trim($value) !== '';
            if (is_array($value) && !$hasExistingPath) {
                foreach ($value as $item) {
                    if (is_string($item) && trim($item) !== '') {
                        $hasExistingPath = true;
                        break;
                    }
                }
            }

            $hasAnyValue = $hasExistingPath || !empty($uploaded);
            $attribute = 'answers.' . $field->id;

            if ($field->is_required && !$hasAnyValue) {
                $errors[$attribute] = 'This field is required.';
                continue;
            }

            if (empty($uploaded)) {
                continue;
            }

            if (!$field->allow_multiple_files && count($uploaded) > 1) {
                $errors[$attribute] = 'Only one file is allowed for this field.';
                continue;
            }

            foreach ($uploaded as $file) {
                $ext = strtolower($file->getClientOriginalExtension() ?: '');
                if (!in_array($ext, $allowedExtensions, true)) {
                    $errors[$attribute] = 'Allowed file types: pdf, excel, document, and image files only.';
                    break;
                }

                if (($file->getSize() ?? 0) > $maxFileSizeBytes) {
                    $errors[$attribute] = 'Each file must be 10 MB or less.';
                    break;
                }
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    public function saveAsDraft()
    {
        if ($this->isReadOnly) return;
        if (!Auth::guard('candidat')->check()) return;

        DB::beginTransaction();
        try {
            $candidatId = Auth::guard('candidat')->id();

            $ProjectsSubmission = ProjectsSubmission::updateOrCreate(
                [
                    'candidat_id' => $candidatId,
                    'programe_id' => $this->projectId,
                ],
                [
                    'last_activity' => now(),
                ]
            );

            $submission = DynamicFormSubmission::updateOrCreate(
                [
                    'dynamic_form_id' => $this->formulaire->id,
                    'programe_id' => $this->projectId,
                    'candidat_id' => $candidatId,
                ],
                [
                    'project_submission_id' => $ProjectsSubmission->id,
                    'current_step' => $this->currentStep,
                    'is_submitted' => false,
                ]
            );

            $this->submissionId = $submission->id;

            $fieldsById = $this->formulaire->steps
                ->flatMap(fn($s) => $s->fields)
                ->keyBy('id');

            // Save field answers by dynamic_form_field_id.
            foreach ($this->answers as $fieldId => $value) {
                $fieldId = (int) $fieldId;
                $field = $fieldsById->get($fieldId);
                if (!$field) {
                    continue;
                }

                $answerValue = $value;
                if ($field->type === 'file') {
                    $answerValue = $this->prepareFileAnswerValue(
                        $submission->id,
                        $fieldId,
                        $field->label,
                        $value,
                        $candidatId,
                        (int) $this->formulaire->id,
                        (bool) ($field->allow_multiple_files ?? false)
                    );
                }

                DynamicFormAnswer::updateOrCreate(
                    [
                        'dynamic_form_submission_id' => $submission->id,
                        'dynamic_form_field_id' => $fieldId,
                    ],
                    [
                        'field_key' => $field->field_key,
                        'value' => $answerValue,
                    ]
                );
            }

            // Save table answers
            DynamicFormTableAnswer::where('dynamic_form_submission_id', $submission->id)->delete();
            foreach ($this->tableData as $tableKey => $rows) {
                $tableId = null;
                $radioColKeys = collect();
                foreach ($this->formulaire->steps as $s) {
                    foreach ($s->tables as $t) {
                        if ($t->table_key === $tableKey) {
                            $tableId = $t->id;
                            $radioColKeys = $t->columns->where('input_type', 'radio')->pluck('column_key');
                            break 2;
                        }
                    }
                }

                foreach ($rows as $rowIndex => $rowData) {
                    $selectedRadio = $rowData['_radio'] ?? null;

                    foreach ($rowData as $colKey => $val) {
                        // Skip the virtual _radio key and individual radio column entries
                        if ($colKey === '_radio') continue;
                        if ($radioColKeys->contains($colKey)) continue;

                        if ($val !== '' && $val !== null) {
                            DynamicFormTableAnswer::create([
                                'dynamic_form_submission_id' => $submission->id,
                                'dynamic_form_table_id' => $tableId,
                                'table_key' => $tableKey,
                                'row_index' => $rowIndex,
                                'column_key' => $colKey,
                                'value' => $val,
                            ]);
                        }
                    }

                    // Save only the selected radio column
                    if ($selectedRadio && $radioColKeys->contains($selectedRadio)) {
                        DynamicFormTableAnswer::create([
                            'dynamic_form_submission_id' => $submission->id,
                            'dynamic_form_table_id' => $tableId,
                            'table_key' => $tableKey,
                            'row_index' => $rowIndex,
                            'column_key' => $selectedRadio,
                            'value' => '1',
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error saving: ' . $e->getMessage());
        }
    }

    protected function prepareFileAnswerValue(
        int $submissionId,
        int $fieldId,
        string $fieldLabel,
        mixed $value,
        int $candidatId,
        int $formId,
        bool $allowMultiple
    ): ?string {
        $uploaded = [];

        if ($value instanceof TemporaryUploadedFile) {
            $uploaded = [$value];
        } elseif (is_array($value)) {
            foreach ($value as $item) {
                if ($item instanceof TemporaryUploadedFile) {
                    $uploaded[] = $item;
                }
            }
        }

        if (!empty($uploaded)) {
            $candidat = Auth::guard('candidat')->user();
            $candidatSlug = Str::slug(trim(($candidat->nom ?? '') . ' ' . ($candidat->prenom ?? '')) ?: 'candidat-' . $candidatId);
            $fieldSlug = Str::slug($fieldLabel ?: ('field-' . $fieldId));
            $dir = 'dynamic-forms/' . $formId . '/candidat-' . $candidatId . '/submission-' . $submissionId;
            $paths = [];

            foreach ($uploaded as $index => $file) {
                $ext = strtolower($file->getClientOriginalExtension() ?: 'bin');
                $name = $candidatSlug . '_' . $fieldSlug . '_' . now()->format('Ymd_His_u') . '_' . ($index + 1) . '.' . $ext;
                $paths[] = $file->storeAs($dir, $name, 'uploads');
            }

            if (!$allowMultiple) {
                return $paths[0] ?? null;
            }

            return json_encode($paths, JSON_UNESCAPED_SLASHES);
        }

        $existing = DynamicFormAnswer::query()
            ->where('dynamic_form_submission_id', $submissionId)
            ->where('dynamic_form_field_id', $fieldId)
            ->value('value');

        if (is_string($value) && trim($value) !== '') {
            return $value;
        }

        return $existing;
    }

    public function submit()
    {
        if ($this->isReadOnly) return;

        if (!Auth::guard('candidat')->check()) {
            session()->flash('error', 'You must be logged in to submit.');
            return redirect()->route('user.login');
        }

        // Validate all steps
        $rules = [];
        foreach ($this->formulaire->steps as $formStep) {
            foreach ($formStep->fields as $field) {
                if ($field->is_required && !in_array($field->type, ['heading', 'paragraph', 'file'])) {
                    $rules['answers.' . $field->id] = 'required';
                }
            }
        }

        if (!empty($rules)) {
            $this->validate($rules, [
                'required' => 'This field is required.',
            ]);
        }

        $allFields = $this->formulaire->steps->flatMap(fn($step) => $step->fields);
        $this->validateFileAnswersForFields($allFields);

        $this->saveAsDraft();

        $submission = DynamicFormSubmission::find($this->submissionId);
        if ($submission) {
            $submission->update([
                'status' => 'submitted',
                'is_submitted' => true,
                'submitted_at' => now(),
            ]);

            ProjectsSubmission::where('id', $submission->project_submission_id)->update([
                'last_activity' => now(),
            ]);

            ProjectsSubmission::syncFinishedStatusFor(
                (int) $submission->candidat_id,
                (int) $submission->programe_id
            );
        }

        session()->flash('message', 'Formulaire submitted successfully!');
        return redirect()->route('user.project.detail', $this->projectId);
    }

    public function saveProgress()
    {
        $this->saveAsDraft();
        session()->flash('message', 'Progress saved successfully!');
    }

    public function render()
    {
        $currentStepData = null;
        $totalSteps = $this->formulaire->steps->count();
        $formTitle = str_starts_with(app()->getLocale(), 'ar') && filled($this->formulaire->title_ar)
            ? $this->formulaire->title_ar
            : $this->formulaire->title;

        if (!$this->showIntroduction) {
            $currentStepData = $this->formulaire->steps->firstWhere('step_number', $this->currentStep);
        }

        $projectId = request()->integer('project_id');
        $project = null;

        if ($this->existingSubmission?->programe) {
            $project = $this->existingSubmission->programe;
        } elseif ($projectId > 0) {
            $project = ProjectsList::find($projectId);
        }

        return view('livewire.front.programe.project-formulaire-view', [
            'currentStepData' => $currentStepData,
            'totalSteps' => $totalSteps,
            'project' => $project,
            'form' => $this->formulaire,
        ])->layout('layouts.app', ['title' => $this->project->project_name . ' - ' . $formTitle]);
    }
}
