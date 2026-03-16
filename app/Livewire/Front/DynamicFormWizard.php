<?php

namespace App\Livewire\Front;

use App\Models\DynamicForm;
use App\Models\DynamicFormSubmission;
use App\Models\DynamicFormAnswer;
use App\Models\DynamicFormTableAnswer;
use App\Models\ProjectSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DynamicFormWizard extends Component
{
    public string $slug;
    public int $step = 1;
    public ?int $submissionId = null;
    public bool $isReadOnly = false;

    // All field answers stored as field_id => value
    public array $answers = [];

    // All table data stored as table_key => [row_index => [column_key => value]]
    public array $tableData = [];

    // Track dynamic row counts per table
    public array $tableRowCounts = [];

    public ?DynamicFormSubmission $existingSubmission = null;

    public function mount(string $slug)
    {
        $this->slug = $slug;
        $candidatId = Auth::guard('candidat')->id();

        $form = DynamicForm::where('slug', $slug)->where('is_active', true)
            ->with(['steps.fields', 'steps.tables.columns', 'steps.tables.fixedRows'])
            ->firstOrFail();

        // Check for existing submitted form
        $existing = DynamicFormSubmission::where('dynamic_form_id', $form->id)
            ->where('candidat_id', $candidatId)
            ->first();

        if ($existing && $existing->isSubmitted()) {
            $this->existingSubmission = $existing;
            $this->submissionId = $existing->id;
            $this->isReadOnly = true;
            $this->step = 1;
            $this->loadExistingData($form);
        } elseif ($existing && $existing->isDraft()) {
            $this->existingSubmission = $existing;
            $this->submissionId = $existing->id;
            $this->step = $existing->current_step;
            $this->loadExistingData($form);
        }

        // Initialize default table row counts for dynamic tables
        foreach ($form->steps as $formStep) {
            foreach ($formStep->tables as $table) {
                if ($table->has_dynamic_rows && !isset($this->tableRowCounts[$table->table_key])) {
                    $this->tableRowCounts[$table->table_key] = $table->min_rows;
                }
                // Initialize fixed row table data
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

    protected function loadExistingData(DynamicForm $form)
    {
        $submission = DynamicFormSubmission::with(['answers', 'tableAnswers'])->find($this->submissionId);
        if (!$submission) return;

        // Load field answers keyed by stable field id to avoid key collisions across steps.
        foreach ($submission->answers as $answer) {
            if ($answer->dynamic_form_field_id) {
                $this->answers[$answer->dynamic_form_field_id] = $answer->value;
            }
        }

        // Load table answers
        foreach ($submission->tableAnswers as $ta) {
            $this->tableData[$ta->table_key][$ta->row_index][$ta->column_key] = $ta->value;
        }

        // Determine row counts for dynamic tables
        foreach ($form->steps as $formStep) {
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

    public function addTableRow(string $tableKey)
    {
        if ($this->isReadOnly) return;

        $form = $this->getForm();
        foreach ($form->steps as $formStep) {
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

        $form = $this->getForm();
        foreach ($form->steps as $formStep) {
            foreach ($formStep->tables as $table) {
                if ($table->table_key === $tableKey) {
                    $count = $this->tableRowCounts[$tableKey] ?? $table->min_rows;
                    if ($count > $table->min_rows) {
                        unset($this->tableData[$tableKey][$rowIndex]);
                        // Re-index
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

    public function next()
    {
        if ($this->isReadOnly) {
            $form = $this->getForm();
            if ($this->step < $form->steps->count()) {
                $this->step++;
                $this->dispatch('scroll-to-top');
            }
            return;
        }

        $this->validateCurrentStep();
        $this->saveAsDraft();

        $form = $this->getForm();
        if ($this->step < $form->steps->count()) {
            $this->step++;
            $this->dispatch('scroll-to-top');
        }
    }

    public function back()
    {
        if ($this->step > 1) {
            $this->step--;
            $this->dispatch('scroll-to-top');
        }
    }

    protected function validateCurrentStep()
    {
        $form = $this->getForm();
        $currentStep = $form->steps->firstWhere('step_number', $this->step);
        if (!$currentStep) return;

        $rules = [];
        foreach ($currentStep->fields as $field) {
            if ($field->is_required && !in_array($field->type, ['heading', 'paragraph'])) {
                $rules['answers.' . $field->id] = 'required';
            }
        }

        if (!empty($rules)) {
            $this->validate($rules, [
                'required' => 'Ce champ est obligatoire.',
            ]);
        }
    }

    public function saveAsDraft()
    {
        if ($this->isReadOnly) return;

        $form = $this->getForm();
        $candidatId = Auth::guard('candidat')->id();

        DB::beginTransaction();
        try {
            $projectSubmissionId = null;
            $projectId = request()->integer('project_id');

            if ($projectId > 0) {
                $projectSubmission = ProjectSubmission::updateOrCreate(
                    [
                        'candidat_id' => $candidatId,
                        'programe_id' => $projectId,
                    ],
                    [
                        'last_activity' => now(),
                    ]
                );

                $projectSubmissionId = $projectSubmission->id;
            }

            // Create or update submission
            $submission = DynamicFormSubmission::updateOrCreate(
                [
                    'dynamic_form_id' => $form->id,
                    'candidat_id' => $candidatId,
                ],
                [
                    'programe_id' => $projectId > 0 ? $projectId : null,
                    'project_submission_id' => $projectSubmissionId,
                    'current_step' => $this->step,
                    'is_submitted' => false,
                ]
            );

            $this->submissionId = $submission->id;

            // Build a fast lookup map for the current form fields.
            $fieldsById = $form->steps
                ->flatMap(fn($s) => $s->fields)
                ->keyBy('id');

            // Save field answers keyed by dynamic_form_field_id.
            foreach ($this->answers as $fieldId => $value) {
                $fieldId = (int) $fieldId;
                $field = $fieldsById->get($fieldId);
                if (!$field) {
                    continue;
                }

                DynamicFormAnswer::updateOrCreate(
                    [
                        'dynamic_form_submission_id' => $submission->id,
                        'dynamic_form_field_id' => $fieldId,
                    ],
                    [
                        'field_key' => $field->field_key,
                        'value' => $value,
                    ]
                );
            }

            // Save table answers
            DynamicFormTableAnswer::where('dynamic_form_submission_id', $submission->id)->delete();
            foreach ($this->tableData as $tableKey => $rows) {
                $tableId = null;
                $radioColKeys = collect();
                foreach ($form->steps as $s) {
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
                        if ($colKey === '_radio') {
                            continue;
                        }
                        if ($radioColKeys->contains($colKey)) {
                            continue;
                        }

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

                    // Save only the selected radio column as 1.
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
            session()->flash('success', 'Brouillon sauvegardé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erreur lors de la sauvegarde: ' . $e->getMessage());
        }
    }

    public function submit()
    {
        if ($this->isReadOnly) return;

        // Validate all steps
        $form = $this->getForm();
        $rules = [];
        foreach ($form->steps as $formStep) {
            foreach ($formStep->fields as $field) {
                if ($field->is_required && !in_array($field->type, ['heading', 'paragraph'])) {
                    $rules['answers.' . $field->id] = 'required';
                }
            }
        }

        if (!empty($rules)) {
            $this->validate($rules, [
                'required' => 'Ce champ est obligatoire.',
            ]);
        }

        $this->saveAsDraft();

        $submission = DynamicFormSubmission::find($this->submissionId);
        if ($submission) {
            $submission->update([
                'is_submitted' => true,
                'submitted_at' => now(),
            ]);
        }

        $this->isReadOnly = true;
        $this->existingSubmission = $submission->fresh();
        session()->flash('success', 'Formulaire soumis avec succès!');
    }

    protected function getForm(): DynamicForm
    {
        return DynamicForm::where('slug', $this->slug)
            ->with(['steps.fields', 'steps.tables.columns', 'steps.tables.fixedRows'])
            ->firstOrFail();
    }

    public function render()
    {
        $form = $this->getForm();
        $currentStep = $form->steps->firstWhere('step_number', $this->step);
        $layoutTitle = str_starts_with(app()->getLocale(), 'ar') && filled($form->title_ar)
            ? $form->title_ar
            : $form->title;

        return view('livewire.front.dynamic_form.wizard', [
            'form' => $form,
            'currentStep' => $currentStep,
            'totalSteps' => $form->steps->count(),
        ])->layout('layouts.app', ['title' => $layoutTitle]);
    }
}
