<?php

namespace App\Livewire\Admin\Material;

use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\MaterialAttachment;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithFileUploads;

class MaterialCreate extends Component
{
    use WithFileUploads;

    public $category_id = '';
    public $name = '';
    public $description = '';
    public $quantity = 1;
    public $quantity_min = 0;
    public $prix_unitaire = '';
    public $emplacement = '';
    public $etat = 'bon';
    public $status = 'disponible';
    public $fournisseur = '';
    public $date_acquisition = '';
    public $date_garantie = '';
    public $numero_serie = '';
    public $notes = '';
    public $photos = [];

    protected function rules()
    {
        return [
            'category_id' => 'nullable|exists:material_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'quantity' => 'required|integer|min:0',
            'quantity_min' => 'nullable|integer|min:0',
            'prix_unitaire' => 'nullable|numeric|min:0',
            'emplacement' => 'nullable|string|max:255',
            'etat' => 'required|in:neuf,bon,usage,defectueux,hors_service',
            'status' => 'required|in:disponible,en_utilisation,en_maintenance,retire',
            'fournisseur' => 'nullable|string|max:255',
            'date_acquisition' => 'nullable|date',
            'date_garantie' => 'nullable|date',
            'numero_serie' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:2000',
            'photos.*' => 'nullable|file|max:10240',
        ];
    }

    public function save()
    {
        $this->validate();

        $valeurTotale = $this->prix_unitaire ? $this->prix_unitaire * $this->quantity : null;

        $material = Material::create([
            'reference' => Material::generateReference(),
            'category_id' => $this->category_id ?: null,
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'quantity_min' => $this->quantity_min ?? 0,
            'prix_unitaire' => $this->prix_unitaire ?: null,
            'valeur_totale' => $valeurTotale,
            'emplacement' => $this->emplacement,
            'etat' => $this->etat,
            'status' => $this->status,
            'fournisseur' => $this->fournisseur,
            'date_acquisition' => $this->date_acquisition ?: null,
            'date_garantie' => $this->date_garantie ?: null,
            'numero_serie' => $this->numero_serie,
            'notes' => $this->notes,
            'created_by' => auth()->id(),
        ]);

        if ($this->photos) {
            foreach ($this->photos as $idx => $photo) {
                $path = $photo->store('materials/' . $material->id, 'public');
                MaterialAttachment::create([
                    'material_id' => $material->id,
                    'file_path' => $path,
                    'file_name' => $photo->getClientOriginalName(),
                    'file_type' => 'photo',
                    'mime_type' => $photo->getMimeType(),
                    'is_primary' => $idx === 0,
                ]);
            }
        }

        AdminActivityLog::log('material_created', "Created material: {$material->name}", Material::class, $material->id);
        session()->flash('success', 'Matériel ajouté avec succès!');
        return redirect()->route('admin.material.index');
    }

    public function render()
    {
        $categories = MaterialCategory::orderBy('name')->get();
        return view('livewire.admin.material.material-create', compact('categories'))
            ->layout('layouts.admin', ['header' => 'Nouveau Matériel']);
    }
}
