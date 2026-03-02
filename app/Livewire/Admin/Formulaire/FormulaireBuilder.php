<?php

namespace App\Livewire\Admin\Formulaire;

use App\Models\DynamicForm;
use App\Models\DynamicFormStep;
use App\Models\DynamicFormField;
use App\Models\DynamicFormTable;
use App\Models\DynamicFormTableColumn;
use App\Models\DynamicFormTableRow;
use Illuminate\Support\Str;
use Livewire\Component;

class FormulaireBuilder extends Component
{
    // Form properties
    public ?int $formId = null;
    public string $title = '';
    public string $title_ar = '';
    public string $introduction = '';
    public string $introduction_ar = '';
    public string $slug = '';
    public string $icon = 'ri-file-list-3-line';
    public string $color = '#2f5496';
    public string $bg_color = '#ffffff';
    public bool $is_active = true;
    public bool $has_steps = true;
    
    // Introduction page
    public bool $has_introduction = false;
    public string $introduction_title = '';
    public string $introduction_title_ar = '';
    public string $introduction_content = '';
    public string $introduction_content_ar = '';
    public bool $showIntroductionModal = false;

    // Builder state
    public string $activeTab = 'settings'; // settings, steps, preview
    public ?int $activeStepId = null;
    public ?int $editingFieldId = null;
    public ?int $editingTableId = null;

    // Step editing
    public string $stepTitle = '';
    public string $stepTitleAr = '';
    public string $stepDescription = '';

    public function selectIcon($iconClass)
    {
        $this->icon = $iconClass;
    }

    // Field editing modal
    public bool $showFieldModal = false;
    public array $fieldForm = [
        'id' => null,
        'label' => '',
        'label_ar' => '',
        'field_key' => '',
        'type' => 'text',
        'placeholder' => '',
        'help_text' => '',
        'options' => [],
        'is_required' => false,
        'is_full_width' => true,
        'sort_order' => 0,
    ];
    public string $newOption = '';

    // Table editing modal
    public bool $showTableModal = false;
    public array $tableForm = [
        'id' => null,
        'title' => '',
        'title_ar' => '',
        'table_key' => '',
        'has_dynamic_rows' => false,
        'has_total_row' => false,
        'min_rows' => 1,
        'max_rows' => 20,
        'sort_order' => 0,
    ];

    // Column editing
    public bool $showColumnModal = false;
    public array $columnForm = [
        'id' => null,
        'header' => '',
        'header_ar' => '',
        'column_key' => '',
        'input_type' => 'text',
        'options' => [],
        'is_totaled' => false,
        'width' => '',
        'sort_order' => 0,
    ];
    public string $newColOption = '';

    // Fixed row editing
    public bool $showRowModal = false;
    public array $rowForm = [
        'id' => null,
        'label' => '',
        'label_ar' => '',
        'sort_order' => 0,
    ];

    // Icon list
    public array $availableIcons = [
        'ri-file-list-3-line', 'ri-file-text-line', 'ri-survey-line', 'ri-clipboard-line',
        'ri-book-open-line', 'ri-draft-line', 'ri-article-line', 'ri-newspaper-line',
        'ri-task-line', 'ri-todo-line', 'ri-list-check-2', 'ri-list-ordered',
        'ri-pie-chart-line', 'ri-bar-chart-line', 'ri-line-chart-line', 'ri-funds-line',
        'ri-briefcase-line', 'ri-building-line', 'ri-store-line', 'ri-shopping-bag-line',
        'ri-user-line', 'ri-team-line', 'ri-group-line', 'ri-contacts-line',
        'ri-lightbulb-line', 'ri-brain-line', 'ri-graduation-cap-line', 'ri-medal-line',
        'ri-heart-line', 'ri-shield-check-line', 'ri-settings-line', 'ri-tools-line',
        'ri-money-dollar-circle-line', 'ri-bank-line', 'ri-wallet-line', 'ri-coin-line',
        'ri-calendar-line', 'ri-time-line', 'ri-map-pin-line', 'ri-earth-line',
    ];

    protected $listeners = ['refreshBuilder' => '$refresh'];

    public function mount($id = null)
    {
        if ($id) {
            $this->formId = $id;
            $this->loadForm();
        }
    }

    protected function loadForm()
    {
        $form = DynamicForm::with(['steps.fields', 'steps.tables.columns', 'steps.tables.fixedRows'])
            ->findOrFail($this->formId);

        $this->title = $form->title;
        $this->title_ar = $form->title_ar ?? '';
        $this->introduction = $form->introduction ?? '';
        $this->introduction_ar = $form->introduction_ar ?? '';
        $this->slug = $form->slug;
        $this->icon = $form->icon;
        $this->color = $form->color;
        $this->bg_color = $form->bg_color;
        $this->is_active = $form->is_active;
        $this->has_steps = $form->has_steps;
        
        // Load introduction page data
        $this->has_introduction = $form->has_introduction ?? false;
        $this->introduction_title = $form->introduction_title ?? '';
        $this->introduction_title_ar = $form->introduction_title_ar ?? '';
        $this->introduction_content = $form->introduction_content ?? '';
        $this->introduction_content_ar = $form->introduction_content_ar ?? '';

        if ($form->steps->isNotEmpty() && !$this->activeStepId) {
            $this->activeStepId = $form->steps->first()->id;
        }
    }

    // ============ FORM SETTINGS ============

    public function saveSettings()
    {
        // Auto-generate slug from title if empty (safety net for debounce timing)
        if (empty(trim($this->slug)) && !empty(trim($this->title))) {
            $this->slug = Str::slug($this->title);
        }

        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:dynamic_forms,slug,' . ($this->formId ?? 'NULL'),
            'icon' => 'required|string',
            'color' => 'required|string',
            'bg_color' => 'required|string',
        ]);

        $data = [
            'title' => $this->title,
            'title_ar' => $this->title_ar ?: null,
            'introduction' => $this->introduction ?: null,
            'introduction_ar' => $this->introduction_ar ?: null,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'color' => $this->color,
            'bg_color' => $this->bg_color,
            'is_active' => $this->is_active,
            'has_steps' => $this->has_steps,
            'has_introduction' => $this->has_introduction,
            'introduction_title' => $this->introduction_title ?: null,
            'introduction_title_ar' => $this->introduction_title_ar ?: null,
            'introduction_content' => $this->introduction_content ?: null,
            'introduction_content_ar' => $this->introduction_content_ar ?: null,
        ];

        if ($this->formId) {
            DynamicForm::findOrFail($this->formId)->update($data);
            $this->dispatch('alert', type: 'success', title: 'Sauvegardé', message: 'Paramètres du formulaire sauvegardés.');
        } else {
            $form = DynamicForm::create($data);
            $this->formId = $form->id;

            // Create default first step
            $step = DynamicFormStep::create([
                'dynamic_form_id' => $this->formId,
                'title' => 'Étape 1',
                'step_number' => 1,
                'sort_order' => 1,
            ]);
            $this->activeStepId = $step->id;
            
            $this->dispatch('alert', type: 'success', title: 'Créé', message: 'Formulaire créé avec succès!');
            
            // Redirect to edit page
            return redirect()->route('admin.formulaires.edit', $this->formId);
        }
    }
    
    // ============ INTRODUCTION PAGE ============
    
    public function openIntroductionModal()
    {
        $this->showIntroductionModal = true;
    }
    
    public function closeIntroductionModal()
    {
        $this->showIntroductionModal = false;
    }
    
    public function saveIntroductionPage()
    {
        $this->validate([
            'introduction_title' => 'required|string|max:500',
            'introduction_content' => 'required|string',
        ]);
        
        if ($this->formId) {
            DynamicForm::findOrFail($this->formId)->update([
                'has_introduction' => true,
                'introduction_title' => $this->introduction_title,
                'introduction_title_ar' => $this->introduction_title_ar ?: null,
                'introduction_content' => $this->introduction_content,
                'introduction_content_ar' => $this->introduction_content_ar ?: null,
            ]);
            
            $this->has_introduction = true;
            $this->closeIntroductionModal();
            $this->dispatch('alert', type: 'success', title: 'Sauvegardé', message: 'Page d\'introduction créée avec succès!');
        } else {
            $this->dispatch('alert', type: 'error', title: 'Erreur', message: 'Veuillez d\'abord sauvegarder le formulaire.');
        }
    }
    
    public function deleteIntroductionPage()
    {
        if ($this->formId) {
            DynamicForm::findOrFail($this->formId)->update([
                'has_introduction' => false,
                'introduction_title' => null,
                'introduction_title_ar' => null,
                'introduction_content' => null,
                'introduction_content_ar' => null,
            ]);
            
            $this->has_introduction = false;
            $this->introduction_title = '';
            $this->introduction_title_ar = '';
            $this->introduction_content = '';
            $this->introduction_content_ar = '';
            
            $this->dispatch('alert', type: 'success', title: 'Supprimé', message: 'Page d\'introduction supprimée.');
        }
    }

    public function updatedTitle()
    {
        $this->slug = Str::slug($this->title);
    }

    // ============ STEP MANAGEMENT ============

    public function addStep()
    {
        if (!$this->formId) {
            $this->dispatch('alert', type: 'error', title: 'Erreur', message: 'Veuillez d\'abord sauvegarder les paramètres du formulaire.');
            return;
        }

        $maxStep = DynamicFormStep::where('dynamic_form_id', $this->formId)->max('step_number') ?? 0;

        $step = DynamicFormStep::create([
            'dynamic_form_id' => $this->formId,
            'title' => 'Étape ' . ($maxStep + 1),
            'step_number' => $maxStep + 1,
            'sort_order' => $maxStep + 1,
        ]);

        $this->activeStepId = $step->id;
        $this->dispatch('alert', type: 'success', title: 'Ajouté', message: 'Nouvelle étape ajoutée.');
    }

    public function selectStep($stepId)
    {
        $this->activeStepId = $stepId;
        $this->editingFieldId = null;
        $this->editingTableId = null;
    }

    public function updateStep($stepId)
    {
        $this->validate([
            'stepTitle' => 'required|string|max:255',
        ]);

        DynamicFormStep::findOrFail($stepId)->update([
            'title' => $this->stepTitle,
            'title_ar' => $this->stepTitleAr ?: null,
            'description' => $this->stepDescription ?: null,
        ]);

        $this->dispatch('alert', type: 'success', title: 'Mis à jour', message: 'Étape mise à jour.');
    }

    public function editStep($stepId)
    {
        $step = DynamicFormStep::findOrFail($stepId);
        $this->stepTitle = $step->title;
        $this->stepTitleAr = $step->title_ar ?? '';
        $this->stepDescription = $step->description ?? '';
        $this->activeStepId = $stepId;
    }

    public function deleteStep($stepId)
    {
        $step = DynamicFormStep::findOrFail($stepId);
        $form = DynamicForm::findOrFail($this->formId);

        if ($form->steps()->count() <= 1) {
            $this->dispatch('alert', type: 'error', title: 'Erreur', message: 'Le formulaire doit avoir au moins une étape.');
            return;
        }

        $step->delete();

        // Re-number remaining steps
        $remaining = DynamicFormStep::where('dynamic_form_id', $this->formId)->orderBy('step_number')->get();
        foreach ($remaining as $i => $s) {
            $s->update(['step_number' => $i + 1, 'sort_order' => $i + 1]);
        }

        $this->activeStepId = $remaining->first()?->id;
        $this->dispatch('alert', type: 'success', title: 'Supprimé', message: 'Étape supprimée.');
    }

    public function moveStep($stepId, $direction)
    {
        $steps = DynamicFormStep::where('dynamic_form_id', $this->formId)
            ->orderBy('step_number')->get();

        $index = $steps->search(fn($s) => $s->id === $stepId);

        if ($direction === 'up' && $index > 0) {
            $steps[$index]->update(['step_number' => $steps[$index]->step_number - 1]);
            $steps[$index - 1]->update(['step_number' => $steps[$index - 1]->step_number + 1]);
        } elseif ($direction === 'down' && $index < $steps->count() - 1) {
            $steps[$index]->update(['step_number' => $steps[$index]->step_number + 1]);
            $steps[$index + 1]->update(['step_number' => $steps[$index + 1]->step_number - 1]);
        }
    }

    // ============ FIELD MANAGEMENT ============

    public function openFieldModal($stepId, $fieldId = null)
    {
        $this->activeStepId = $stepId;

        if ($fieldId) {
            $field = DynamicFormField::findOrFail($fieldId);
            $this->fieldForm = [
                'id' => $field->id,
                'label' => $field->label,
                'label_ar' => $field->label_ar ?? '',
                'field_key' => $field->field_key,
                'type' => $field->type,
                'placeholder' => $field->placeholder ?? '',
                'help_text' => $field->help_text ?? '',
                'options' => $field->options ?? [],
                'is_required' => $field->is_required,
                'is_full_width' => $field->is_full_width,
                'sort_order' => $field->sort_order,
            ];
        } else {
            $maxSort = DynamicFormField::where('dynamic_form_step_id', $stepId)->max('sort_order') ?? 0;
            $this->fieldForm = [
                'id' => null,
                'label' => '',
                'label_ar' => '',
                'field_key' => '',
                'type' => 'text',
                'placeholder' => '',
                'help_text' => '',
                'options' => [],
                'is_required' => false,
                'is_full_width' => true,
                'sort_order' => $maxSort + 1,
            ];
        }

        $this->newOption = '';
        $this->showFieldModal = true;
    }

    public function updatedFieldFormLabel()
    {
        if (!$this->fieldForm['id']) {
            $this->fieldForm['field_key'] = Str::slug($this->fieldForm['label'], '_');
        }
    }

    public function addFieldOption()
    {
        if ($this->newOption) {
            $this->fieldForm['options'][] = $this->newOption;
            $this->newOption = '';
        }
    }

    public function removeFieldOption($index)
    {
        unset($this->fieldForm['options'][$index]);
        $this->fieldForm['options'] = array_values($this->fieldForm['options']);
    }

    public function saveField()
    {
        $this->validate([
            'fieldForm.label' => 'required|string|max:255',
            'fieldForm.field_key' => 'required|string|max:255',
            'fieldForm.type' => 'required|in:text,textarea,number,email,date,select,radio,checkbox,file,heading,paragraph',
        ]);

        $data = [
            'dynamic_form_step_id' => $this->activeStepId,
            'label' => $this->fieldForm['label'],
            'label_ar' => $this->fieldForm['label_ar'] ?: null,
            'field_key' => $this->fieldForm['field_key'],
            'type' => $this->fieldForm['type'],
            'placeholder' => $this->fieldForm['placeholder'] ?: null,
            'help_text' => $this->fieldForm['help_text'] ?: null,
            'options' => !empty($this->fieldForm['options']) ? $this->fieldForm['options'] : null,
            'is_required' => $this->fieldForm['is_required'],
            'is_full_width' => $this->fieldForm['is_full_width'],
            'sort_order' => $this->fieldForm['sort_order'],
        ];

        if ($this->fieldForm['id']) {
            DynamicFormField::findOrFail($this->fieldForm['id'])->update($data);
        } else {
            DynamicFormField::create($data);
        }

        $this->showFieldModal = false;
        $this->dispatch('alert', type: 'success', title: 'Sauvegardé', message: 'Question sauvegardée.');
    }

    public function deleteField($fieldId)
    {
        DynamicFormField::findOrFail($fieldId)->delete();
        $this->dispatch('alert', type: 'success', title: 'Supprimé', message: 'Question supprimée.');
    }

    public function moveField($fieldId, $direction)
    {
        $field = DynamicFormField::findOrFail($fieldId);
        $fields = DynamicFormField::where('dynamic_form_step_id', $field->dynamic_form_step_id)
            ->orderBy('sort_order')->get();

        $index = $fields->search(fn($f) => $f->id === $fieldId);

        if ($direction === 'up' && $index > 0) {
            $currentSort = $fields[$index]->sort_order;
            $fields[$index]->update(['sort_order' => $fields[$index - 1]->sort_order]);
            $fields[$index - 1]->update(['sort_order' => $currentSort]);
        } elseif ($direction === 'down' && $index < $fields->count() - 1) {
            $currentSort = $fields[$index]->sort_order;
            $fields[$index]->update(['sort_order' => $fields[$index + 1]->sort_order]);
            $fields[$index + 1]->update(['sort_order' => $currentSort]);
        }
    }

    // ============ TABLE MANAGEMENT ============

    public function openTableModal($stepId, $tableId = null)
    {
        $this->activeStepId = $stepId;

        if ($tableId) {
            $table = DynamicFormTable::findOrFail($tableId);
            $this->tableForm = [
                'id' => $table->id,
                'title' => $table->title,
                'title_ar' => $table->title_ar ?? '',
                'table_key' => $table->table_key,
                'has_dynamic_rows' => $table->has_dynamic_rows,
                'has_total_row' => $table->has_total_row,
                'min_rows' => $table->min_rows,
                'max_rows' => $table->max_rows,
                'sort_order' => $table->sort_order,
            ];
        } else {
            $maxSort = DynamicFormTable::where('dynamic_form_step_id', $stepId)->max('sort_order') ?? 0;
            $fieldMaxSort = DynamicFormField::where('dynamic_form_step_id', $stepId)->max('sort_order') ?? 0;
            $this->tableForm = [
                'id' => null,
                'title' => '',
                'title_ar' => '',
                'table_key' => '',
                'has_dynamic_rows' => false,
                'has_total_row' => false,
                'min_rows' => 1,
                'max_rows' => 20,
                'sort_order' => max($maxSort, $fieldMaxSort) + 1,
            ];
        }

        $this->showTableModal = true;
    }

    public function updatedTableFormTitle()
    {
        if (!$this->tableForm['id']) {
            $this->tableForm['table_key'] = Str::slug($this->tableForm['title'], '_');
        }
    }

    public function saveTable()
    {
        $this->validate([
            'tableForm.title' => 'required|string|max:255',
            'tableForm.table_key' => 'required|string|max:255',
        ]);

        $data = [
            'dynamic_form_step_id' => $this->activeStepId,
            'title' => $this->tableForm['title'],
            'title_ar' => $this->tableForm['title_ar'] ?: null,
            'table_key' => $this->tableForm['table_key'],
            'has_dynamic_rows' => $this->tableForm['has_dynamic_rows'],
            'has_total_row' => $this->tableForm['has_total_row'],
            'min_rows' => $this->tableForm['min_rows'],
            'max_rows' => $this->tableForm['max_rows'],
            'sort_order' => $this->tableForm['sort_order'],
        ];

        if ($this->tableForm['id']) {
            DynamicFormTable::findOrFail($this->tableForm['id'])->update($data);
        } else {
            $table = DynamicFormTable::create($data);
            $this->editingTableId = $table->id;
        }

        $this->showTableModal = false;
        $this->dispatch('alert', type: 'success', title: 'Sauvegardé', message: 'Tableau sauvegardé.');
    }

    public function deleteTable($tableId)
    {
        DynamicFormTable::findOrFail($tableId)->delete();
        $this->dispatch('alert', type: 'success', title: 'Supprimé', message: 'Tableau supprimé.');
    }

    // ============ COLUMN MANAGEMENT ============

    public function openColumnModal($tableId, $columnId = null)
    {
        $this->editingTableId = $tableId;

        if ($columnId) {
            $col = DynamicFormTableColumn::findOrFail($columnId);
            $this->columnForm = [
                'id' => $col->id,
                'header' => $col->header,
                'header_ar' => $col->header_ar ?? '',
                'column_key' => $col->column_key,
                'input_type' => $col->input_type,
                'options' => $col->options ?? [],
                'is_totaled' => $col->is_totaled,
                'width' => $col->width ?? '',
                'sort_order' => $col->sort_order,
            ];
        } else {
            $maxSort = DynamicFormTableColumn::where('dynamic_form_table_id', $tableId)->max('sort_order') ?? 0;
            $this->columnForm = [
                'id' => null,
                'header' => '',
                'header_ar' => '',
                'column_key' => '',
                'input_type' => 'text',
                'options' => [],
                'is_totaled' => false,
                'width' => '',
                'sort_order' => $maxSort + 1,
            ];
        }

        $this->newColOption = '';
        $this->showColumnModal = true;
    }

    public function updatedColumnFormHeader()
    {
        if (!$this->columnForm['id']) {
            $this->columnForm['column_key'] = Str::slug($this->columnForm['header'], '_');
        }
    }

    public function addColOption()
    {
        if ($this->newColOption) {
            $this->columnForm['options'][] = $this->newColOption;
            $this->newColOption = '';
        }
    }

    public function removeColOption($index)
    {
        unset($this->columnForm['options'][$index]);
        $this->columnForm['options'] = array_values($this->columnForm['options']);
    }

    public function saveColumn()
    {
        $this->validate([
            'columnForm.header' => 'required|string|max:255',
            'columnForm.column_key' => 'required|string|max:255',
            'columnForm.input_type' => 'required|in:text,number,checkbox,select,readonly,label',
        ]);

        $data = [
            'dynamic_form_table_id' => $this->editingTableId,
            'header' => $this->columnForm['header'],
            'header_ar' => $this->columnForm['header_ar'] ?: null,
            'column_key' => $this->columnForm['column_key'],
            'input_type' => $this->columnForm['input_type'],
            'options' => !empty($this->columnForm['options']) ? $this->columnForm['options'] : null,
            'is_totaled' => $this->columnForm['is_totaled'],
            'width' => $this->columnForm['width'] ?: null,
            'sort_order' => $this->columnForm['sort_order'],
        ];

        if ($this->columnForm['id']) {
            DynamicFormTableColumn::findOrFail($this->columnForm['id'])->update($data);
        } else {
            DynamicFormTableColumn::create($data);
        }

        $this->showColumnModal = false;
        $this->dispatch('alert', type: 'success', title: 'Sauvegardé', message: 'Colonne sauvegardée.');
    }

    public function deleteColumn($columnId)
    {
        DynamicFormTableColumn::findOrFail($columnId)->delete();
        $this->dispatch('alert', type: 'success', title: 'Supprimé', message: 'Colonne supprimée.');
    }

    // ============ FIXED ROW MANAGEMENT ============

    public function openRowModal($tableId, $rowId = null)
    {
        $this->editingTableId = $tableId;

        if ($rowId) {
            $row = DynamicFormTableRow::findOrFail($rowId);
            $this->rowForm = [
                'id' => $row->id,
                'label' => $row->label,
                'label_ar' => $row->label_ar ?? '',
                'sort_order' => $row->sort_order,
            ];
        } else {
            $maxSort = DynamicFormTableRow::where('dynamic_form_table_id', $tableId)->max('sort_order') ?? 0;
            $this->rowForm = [
                'id' => null,
                'label' => '',
                'label_ar' => '',
                'sort_order' => $maxSort + 1,
            ];
        }

        $this->showRowModal = true;
    }

    public function saveRow()
    {
        $this->validate([
            'rowForm.label' => 'required|string|max:255',
        ]);

        $data = [
            'dynamic_form_table_id' => $this->editingTableId,
            'label' => $this->rowForm['label'],
            'label_ar' => $this->rowForm['label_ar'] ?: null,
            'sort_order' => $this->rowForm['sort_order'],
        ];

        if ($this->rowForm['id']) {
            DynamicFormTableRow::findOrFail($this->rowForm['id'])->update($data);
        } else {
            DynamicFormTableRow::create($data);
        }

        $this->showRowModal = false;
        $this->dispatch('alert', type: 'success', title: 'Sauvegardé', message: 'Ligne sauvegardée.');
    }

    public function deleteRow($rowId)
    {
        DynamicFormTableRow::findOrFail($rowId)->delete();
        $this->dispatch('alert', type: 'success', title: 'Supprimé', message: 'Ligne supprimée.');
    }

    // ============ RENDER ============

    public function render()
    {
        $form = null;
        $steps = collect();
        $activeStep = null;

        if ($this->formId) {
            $form = DynamicForm::with(['steps.fields', 'steps.tables.columns', 'steps.tables.fixedRows'])
                ->find($this->formId);
            
            if ($form) {
                $steps = $form->steps;

                if ($this->activeStepId) {
                    $activeStep = $steps->firstWhere('id', $this->activeStepId);
                }
            }
        }

        return view('livewire.admin.formulaire.formulaire-builder-new', [
            'form' => $form,
            'steps' => $steps,
            'activeStep' => $activeStep,
        ])->layout('layouts.admin');
    }
}
