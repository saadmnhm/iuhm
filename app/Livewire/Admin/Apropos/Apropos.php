<?php

namespace App\Livewire\Admin\Apropos;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Apropos as AproposModel; // Ajustez selon le nom exact de votre modèle
use Illuminate\Support\Facades\Storage;

class Apropos extends Component
{
    use WithFileUploads;

    // Propriétés du formulaire
    public $title;
    public $excerpt;
    public $content;
    public $image;     // Conserve le chemin de l'image existante
    public $newImage;  // Reçoit le fichier téléversé temporaire

    // États de l'interface
    public $isEditing = false;
    protected $aproposRecord;

    public function mount()
    {
        $this->loadData();
    }

    /**
     * Charge les données de la page À Propos depuis la base de données
     */
    public function loadData()
    {
        // On récupère le premier enregistrement ou on crée une instance vide si inexistant
        $this->aproposRecord = AproposModel::first() ?: new AproposModel();

        $this->title   = $this->aproposRecord->title ?? "À Propos de nous";
        $this->excerpt = $this->aproposRecord->excerpt ?? "";
        $this->content = $this->aproposRecord->content ?? "";
        $this->image   = $this->aproposRecord->image ?? null;
        $this->newImage = null;
    }

    /**
     * Active le mode édition
     */
    public function toggleEdit()
    {
        $this->isEditing = true;
    }

    /**
     * Annule les modifications et repasse en mode affichage
     */
    public function cancelEdit()
    {
        $this->loadData();
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    /**
     * Enregistre les modifications en base de données
     */
    public function save()
    {
        // Validation des champs
        $rules = [
            'title'   => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:250',
            'content' => 'required|string',
            'newImage'=> 'nullable|image|max:2048', // Max 2Mo
        ];

        $this->validate($rules);

        $record = AproposModel::first() ?: new AproposModel();

// Gestion de l'image de couverture
if ($this->newImage) {
    // Generate unique name
    $filename = uniqid('apropos_') . '.' . $this->newImage->getClientOriginalExtension();
    $destinationPath = public_path('assets');

    // Ensure public/assets directory exists
    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }

    try {
        // Attempt moving file directly to public/assets
        $this->newImage->move($destinationPath, $filename);
        $record->image = 'assets/' . $filename;
    } catch (\Exception $e) {
        // Fallback: copy file if cross-disk move restrictions apply
        $tmp = $this->newImage->getRealPath();
        if ($tmp && file_exists($tmp)) {
            copy($tmp, $destinationPath . '/' . $filename);
            $record->image = 'assets/' . $filename;
        } else {
            // Last resort: standard store directly to the local folder structure
            $storedPath = $this->newImage->storeAs('assets', $filename, 'real_public'); 
            // Note: If you choose to use storeAs, ensure your config/filesystems.php 
            // has a disk pointing to public_path(), otherwise the direct `move` or `copy` above handles it natively.
            $record->image = 'assets/' . $filename;
        }
    }
}

        // Sauvegarde des textes
        $record->title   = $this->title;
        $record->excerpt = $this->excerpt;
        $record->content = $this->content;
        $record->save();

        // Rafraîchir les propriétés locales et fermer l'éditeur
        $this->loadData();
        $this->isEditing = false;

        session()->flash('success', 'La page À Propos a été mise à jour avec succès.');
    }

    public function render()
    {
        return view('livewire.admin.apropos.apropos')->layout('layouts.admin', [
            'header' => 'A Propos'
        ]);
    }
}