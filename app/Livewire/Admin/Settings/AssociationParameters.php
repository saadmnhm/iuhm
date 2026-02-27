<?php

namespace App\Livewire\Admin\Settings;

use App\Models\AssociationParameter;
use App\Models\AdminActivityLog;
use Livewire\Component;

class AssociationParameters extends Component
{
    public string $activeCategory = 'general';
    public array $formData = [];
    public bool $saved = false;

    public function mount(): void
    {
        $this->loadCategoryData();
    }

    public function switchCategory(string $category): void
    {
        $this->activeCategory = $category;
        $this->loadCategoryData();
        $this->saved = false;
    }

    protected function loadCategoryData(): void
    {
        $this->formData = [];
        $params = AssociationParameter::where('category', $this->activeCategory)
            ->orderBy('sort_order')->get();

        foreach ($params as $param) {
            $this->formData[$param->key] = $param->value ?? '';
        }
    }

    public function save(): void
    {
        $params = AssociationParameter::where('category', $this->activeCategory)->get();
        $changed = [];

        foreach ($params as $param) {
            $newValue = $this->formData[$param->key] ?? null;
            if ($param->value !== $newValue) {
                $changed[] = $param->label;
                $param->update([
                    'value' => $newValue,
                    'updated_by' => auth()->id(),
                ]);
            }
        }

        if (count($changed) > 0) {
            AdminActivityLog::log(
                'association_param_updated',
                'Updated association parameters (' . $this->activeCategory . '): ' . implode(', ', $changed),
                AssociationParameter::class
            );
        }

        $this->saved = true;
        session()->flash('success', 'Paramètres sauvegardés avec succès!');
    }

    public function render()
    {
        $categories = AssociationParameter::CATEGORIES;
        $params = AssociationParameter::where('category', $this->activeCategory)
            ->orderBy('sort_order')->get();

        return view('livewire.admin.settings.association-parameters', compact('categories', 'params'))
            ->layout('layouts.admin', ['header' => 'Paramètres Association']);
    }
}
