<?php

namespace App\Livewire\Admin\Candidat;

use App\Models\Role;
use App\Models\Candidat;
use App\Models\ProjectsList;
use App\Models\DynamicFormSubmission;
use App\Models\CandidatFormulaireOrder;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.admin', ['header' => 'Edit Candidat'])]
class EditCandidat extends Component
{
    public $candidatId;
    public $matricule;
    public $nom;
    public $prenom;
    public $email;
    public $phone;
    public $date_naissance;
    public $age;
    public $address;
    public $password;
    public $is_active;
    public $formation_ranking;
    public $ranking_feedback_status = 'pending';
    public $ranking_feedback_note = '';

    // Admin-side formulaire order settings
    public $selected_project_id = null;
    public array $projectOptions = [];
    public array $formOrderItems = [];
    public array $customOrders = [];
    public array $globalOrders = [];
    public array $lockedOrders = [];

    public function mount($id)
    {
        $currentUser = auth()->user();

        if (!(Role::isDevelopmentAccessLocked() && Role::canBypassDevelopmentLock($currentUser->role)) && !$currentUser->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can edit candidats.');
            return redirect()->route('admin.users.index');
        }

        $candidat = Candidat::findOrFail($id);
        $this->candidatId = $candidat->id;
        $this->matricule = $candidat->matricule;
        $this->nom = $candidat->nom;
        $this->prenom = $candidat->prenom;
        $this->email = $candidat->email;
        $this->phone = $candidat->phone;
        $this->date_naissance = $candidat->date_naissance;
        $this->address = $candidat->address;
        $this->age = $candidat->age;
        $this->is_active = $candidat->is_active ?? true;
        $this->formation_ranking = $candidat->formation_ranking;
        $this->ranking_feedback_status = $candidat->ranking_feedback_status ?? 'pending';
        $this->ranking_feedback_note = $candidat->ranking_feedback_note ?? '';

        $this->loadProjectOptions();
        if (!empty($this->projectOptions)) {
            $this->selected_project_id = $this->projectOptions[0]['id'];
            $this->loadProjectFormOrders();
        }
    }

    protected function rules()
    {
        $rules = [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:candidat,email,' . $this->candidatId,
            'phone' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'formation_ranking' => 'nullable|string|max:5000',
            'ranking_feedback_status' => 'required|in:pending,good,not_good',
            'ranking_feedback_note' => 'nullable|string|max:4000',
        ];

        if ($this->password) {
            $rules['password'] = 'min:6';
        }

        return $rules;
    }

    public function updateCandidat()
    {
        $currentUser = auth()->user();

        if (!(Role::isDevelopmentAccessLocked() && Role::canBypassDevelopmentLock($currentUser->role)) && !$currentUser->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can edit candidats.');
            return redirect()->route('admin.candidats.index');
        }

        $this->validate();

        $candidat = Candidat::findOrFail($this->candidatId);

        $data = [
            'matricule' => $this->matricule,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_naissance' => $this->date_naissance,
            'address' => $this->address,
            'age' => $this->age,
            'is_active' => $this->is_active,
            'formation_ranking' => $this->formation_ranking,
            'ranking_feedback_status' => $this->ranking_feedback_status,
            'ranking_feedback_note' => $this->ranking_feedback_note,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $candidat->update($data);

        session()->flash('success', 'Candidat updated successfully!');
        return redirect()->route('admin.candidats.show', $candidat->id);
    }

    public function updatedSelectedProjectId()
    {
        $this->loadProjectFormOrders();
    }

    protected function loadProjectOptions(): void
    {
        $projects = ProjectsList::withCount(['formulaires' => function ($q) {
                $q->where('programe_formulaire.status', 'active');
            }])
            ->where('is_active', true)
            ->having('formulaires_count', '>', 0)
            ->orderBy('sort_order')
            ->get();

        $this->projectOptions = $projects->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->project_name,
            ];
        })->values()->toArray();
    }

    protected function loadProjectFormOrders(): void
    {
        $this->formOrderItems = [];
        $this->customOrders = [];
        $this->globalOrders = [];
        $this->lockedOrders = [];

        if (!$this->selected_project_id) {
            return;
        }

        $project = ProjectsList::with(['formulaires' => function ($q) {
                $q->where('programe_formulaire.status', 'active')
                    ->orderBy('programe_formulaire.order');
            }])
            ->find($this->selected_project_id);

        if (!$project) {
            return;
        }

        $customOrdersMap = CandidatFormulaireOrder::where('candidat_id', $this->candidatId)
            ->where('programe_id', $this->selected_project_id)
            ->get()
            ->keyBy('formulaire_id');

        $submissionMap = DynamicFormSubmission::where('candidat_id', $this->candidatId)
            ->where('programe_id', $this->selected_project_id)
            ->whereIn('dynamic_form_id', $project->formulaires->pluck('id'))
            ->get()
            ->keyBy('dynamic_form_id');

        $this->formOrderItems = $project->formulaires->map(function ($form) use ($customOrdersMap, $submissionMap) {
            $sub = $submissionMap->get($form->id);
            $globalOrder = (int) $form->pivot->order;
            $effectiveOrder = $customOrdersMap->has($form->id)
                ? (int) $customOrdersMap->get($form->id)->order
                : $globalOrder;

            $locked = (bool) (($sub?->is_submitted ?? false) || in_array($sub?->status, ['submitted', 'in_review', 'approved'], true));

            $this->globalOrders[$form->id] = $globalOrder;
            $this->customOrders[$form->id] = $effectiveOrder;
            $this->lockedOrders[$form->id] = $locked;

            return [
                'id' => $form->id,
                'title' => $form->title,
                'global_order' => $globalOrder,
                'effective_order' => $effectiveOrder,
                'status_label' => $sub?->status_label ?? 'Non soumis',
                'locked' => $locked,
                'has_custom_order' => $customOrdersMap->has($form->id),
            ];
        })->values()->toArray();
    }

    public function saveProjectFormOrders(): void
    {
        if (!$this->selected_project_id) {
            session()->flash('error', 'Sélectionnez un projet.');
            return;
        }

        $allowedFormIds = collect($this->formOrderItems)->pluck('id')->map(fn ($id) => (int) $id)->toArray();

        DB::transaction(function () use ($allowedFormIds) {
            foreach ($allowedFormIds as $formId) {
                if (($this->lockedOrders[$formId] ?? false) === true) {
                    continue;
                }

                $order = max(1, (int) ($this->customOrders[$formId] ?? $this->globalOrders[$formId] ?? 1));
                $globalOrder = (int) ($this->globalOrders[$formId] ?? 1);

                if ($order === $globalOrder) {
                    CandidatFormulaireOrder::where('candidat_id', $this->candidatId)
                        ->where('programe_id', $this->selected_project_id)
                        ->where('formulaire_id', $formId)
                        ->delete();
                    continue;
                }

                CandidatFormulaireOrder::updateOrCreate(
                    [
                        'candidat_id' => $this->candidatId,
                        'programe_id' => $this->selected_project_id,
                        'formulaire_id' => $formId,
                    ],
                    [
                        'order' => $order,
                    ]
                );
            }
        });

        $this->loadProjectFormOrders();
        session()->flash('success', 'Ordre des formulaires enregistré (hors formulaires déjà soumis).');
    }

    public function render()
    {
        return view('livewire.admin.candidat.edit-candidat');
    }
}
