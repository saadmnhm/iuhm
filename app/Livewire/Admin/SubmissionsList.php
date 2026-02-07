<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Candidat;

class SubmissionsList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterAddress = '';

    protected $queryString = ['search', 'filterAddress'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterAddress()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Candidat::query()
            ->withCount([
                'businessPlans',
                'etudeMarches',
                'evaluationIdees',
                'bmcs',
                'bilanCompetences'
            ]);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nom', 'like', '%' . $this->search . '%')
                  ->orWhere('prenom', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterAddress) {
            $query->where('address', $this->filterAddress);
        }

        $candidats = $query->latest()->paginate(15);

        return view('livewire.admin.submissions-list', [
            'candidats' => $candidats,
        ])->layout('layouts.admin', ['header' => 'Soumissions des Candidats']);
    }
}
