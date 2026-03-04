<?php

namespace App\Livewire\Admin\Material;

use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\MaterialAttachment;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithFileUploads;

class MaterialEdit extends Component
{
    use WithFileUploads;

    public $materialId;
    public $category_id, $name, $description, $quantity, $quantity_min;
    public $prix_unitaire, $emplacement, $etat, $status;
    public $fournisseur, $date_acquisition, $date_garantie, $numero_serie, $notes;
    public $newPhotos = [];
    public $existingPhotos = [];

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
            'newPhotos.*' => 'nullable|file|max:10240',
        ];
    }

    public function mount($id)
    {
        $m = Material::with('attachments')->findOrFail($id);
        $this->materialId = $m->id;
        $this->category_id = $m->category_id ?? '';
        $this->name = $m->name;
        $this->description = $m->description;
        $this->quantity = $m->quantity;
        $this->quantity_min = $m->quantity_min;
        $this->prix_unitaire = $m->prix_unitaire;
        $this->emplacement = $m->emplacement;
        $this->etat = $m->etat;
        $this->status = $m->status;
        $this->fournisseur = $m->fournisseur;
        $this->date_acquisition = $m->date_acquisition?->format('Y-m-d');
        $this->date_garantie = $m->date_garantie?->format('Y-m-d');
        $this->numero_serie = $m->numero_serie;
        $this->notes = $m->notes;
        $this->existingPhotos = $m->attachments->toArray();
    }

    public function removePhoto($attachmentId)
    {
        $att = MaterialAttachment::find($attachmentId);
        if ($att) {
            \Storage::disk('public')->delete($att->file_path);
            $att->delete();
            $this->existingPhotos = array_filter($this->existingPhotos, fn($a) => $a['id'] != $attachmentId);
        }
    }

    public function save()
    {
        $this->validate();

        $m = Material::findOrFail($this->materialId);
        $valeurTotale = $this->prix_unitaire ? $this->prix_unitaire * $this->quantity : null;

        $m->update([
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
        ]);

        if ($this->newPhotos) {
            foreach ($this->newPhotos as $photo) {
                $path = $photo->store('materials/' . $m->id, 'public');
                MaterialAttachment::create([
                    'material_id' => $m->id,
                    'file_path' => $path,
                    'file_name' => $photo->getClientOriginalName(),
                    'file_type' => 'photo',
                    'mime_type' => $photo->getMimeType(),
                    'is_primary' => false,
                ]);
            }
        }

        AdminActivityLog::log('material_updated', "Updated material: {$m->name}", Material::class, $m->id);
        session()->flash('success', 'Matériel mis à jour!');
        return redirect()->route('admin.material.index');
    }

    public function render()
    {
        $categories = MaterialCategory::orderBy('name')->get();
        return view('livewire.admin.material.material-edit', compact('categories'))
            ->layout('layouts.admin', ['header' => 'Modifier Matériel']);
    }
}
