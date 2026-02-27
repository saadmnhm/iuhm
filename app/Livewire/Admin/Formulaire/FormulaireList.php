<?php

namespace App\Livewire\Admin\Formulaire;

use App\Models\DynamicForm;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class FormulaireList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleActive($formId)
    {
        $form = DynamicForm::findOrFail($formId);
        $form->update(['is_active' => !$form->is_active]);

        $status = $form->is_active ? 'activated' : 'deactivated';
        AdminActivityLog::log(
            'formulaire_toggled',
            "Formulaire {$status}: {$form->title}",
            DynamicForm::class,
            $form->id
        );

        $this->dispatch('alert', type: 'success', title: 'Succès', message: 'Statut du formulaire mis à jour.');
    }

    public function deleteForm($formId)
    {
        $form = DynamicForm::findOrFail($formId);
        $form->delete();

        AdminActivityLog::log(
            'formulaire_deleted',
            "Deleted formulaire: {$form->title}",
            DynamicForm::class,
            $form->id
        );

        $this->dispatch('alert', type: 'success', title: 'Supprimé', message: 'Formulaire supprimé avec succès.');
    }

    public function duplicateForm($formId)
    {
        $original = DynamicForm::with(['steps.fields', 'steps.tables.columns', 'steps.tables.fixedRows'])->findOrFail($formId);

        $newForm = $original->replicate();
        $newForm->title = $original->title . ' (Copie)';
        $newForm->slug = $original->slug . '-copy-' . time();
        $newForm->is_active = false;
        $newForm->save();

        foreach ($original->steps as $step) {
            $newStep = $step->replicate();
            $newStep->dynamic_form_id = $newForm->id;
            $newStep->save();

            foreach ($step->fields as $field) {
                $newField = $field->replicate();
                $newField->dynamic_form_step_id = $newStep->id;
                $newField->save();
            }

            foreach ($step->tables as $table) {
                $newTable = $table->replicate();
                $newTable->dynamic_form_step_id = $newStep->id;
                $newTable->save();

                foreach ($table->columns as $col) {
                    $newCol = $col->replicate();
                    $newCol->dynamic_form_table_id = $newTable->id;
                    $newCol->save();
                }

                foreach ($table->fixedRows as $row) {
                    $newRow = $row->replicate();
                    $newRow->dynamic_form_table_id = $newTable->id;
                    $newRow->save();
                }
            }
        }

        AdminActivityLog::log(
            'formulaire_duplicated',
            "Duplicated formulaire: {$original->title} → {$newForm->title}",
            DynamicForm::class,
            $newForm->id
        );

        $this->dispatch('alert', type: 'success', title: 'Dupliqué', message: 'Formulaire dupliqué avec succès.');
    }

    public function updateOrder($formId, $direction)
    {
        $form = DynamicForm::findOrFail($formId);
        $form->update(['sort_order' => $form->sort_order + ($direction === 'up' ? -1 : 1)]);
    }

    public function render()
    {
        $forms = DynamicForm::withCount(['steps', 'submissions'])
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->filterStatus !== '', fn($q) => $q->where('is_active', $this->filterStatus === 'active'))
            ->orderBy('sort_order')
            ->paginate(10);

        return view('livewire.admin.formulaire.formulaire-list', [
            'forms' => $forms,
        ])->layout('layouts.admin', ['header' => 'Gestion des Formulaires']);
    }
}
