<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Candidat;
use App\Models\DynamicFormSubmission;
use App\Models\ProjectsSubmission;
use App\Models\ProjectsList;
use App\Models\SupportTicket;
use Livewire\Component;
use Livewire\WithFileUploads;

class Dashboard extends Component
{
    use WithFileUploads;

    public $projectId;

    // ── Ticket creation modal (admin opens tickets to devs) ──
    public $showCreateTicketModal = false;
    public $ticketSubject = '';
    public $ticketMessage = '';
    public $ticketCategory = 'technical';
    public $ticketPriority = 'medium';
    public $ticketImage = null;

    protected $rules = [
        'ticketSubject'   => 'required|string|min:5|max:255',
        'ticketMessage'   => 'required|string|min:10|max:2000',
        'ticketImage'     => 'nullable|image|max:10240',
        'ticketCategory'  => 'required|in:general,technical,account,form,bug,other',
        'ticketPriority'  => 'required|in:low,medium,high,urgent',
    ];

    public function openCreateTicketModal()
    {
        $this->reset(['ticketSubject', 'ticketMessage', 'ticketCategory', 'ticketPriority', 'ticketImage']);
        $this->ticketCategory = 'technical';
        $this->ticketPriority = 'medium';
        $this->showCreateTicketModal = true;
    }

    public function closeCreateTicketModal()
    {
        $this->showCreateTicketModal = false;
        $this->reset(['ticketSubject', 'ticketMessage', 'ticketCategory', 'ticketPriority', 'ticketImage']);
    }

    public function createTicket()
    {
        $this->validate();

        $assignedTo = User::whereIn('role', ['admin', 'super_admin'])->orderBy('id')->first()?->id;

        $imagePath = null;
        if ($this->ticketImage) {
            $imagePath = $this->ticketImage->store('support_tickets', 'uploads');
        }

        SupportTicket::create([
            'candidat_id' => null,
            'assigned_to' => $assignedTo,
            'subject'     => $this->ticketSubject,
            'message'     => $this->ticketMessage,
            'image'       => $imagePath,
            'category'    => $this->ticketCategory,
            'priority'    => $this->ticketPriority,
            'status'      => 'open',
        ]);

        $this->closeCreateTicketModal();
        session()->flash('success', 'Ticket créé avec succès ! Il a été assigné à l\'équipe de développement.');
    }

    public function render()
    {

        $matricule_null = Candidat::whereNull('matricule')->count();

        $latestSubmission = DynamicFormSubmission::latest()->first();
        $this->projectId = $latestSubmission?->programe_id;

        $allSubmissions = DynamicFormSubmission::with(['candidat', 'programe'])->latest()->get();
        $programe_list = ProjectsList::all();

        $ProjectsSubmissions = ProjectsSubmission::latest('updated_at')->get();


        
        // Dynamic address distribution (top 6)
        $addressDistribution = Candidat::whereNotNull('selected_prefecture')
            ->selectRaw('selected_prefecture as selected_prefecture , COUNT(*) as count')
            ->groupBy('selected_prefecture')
            ->orderByDesc('count')
            // ->limit(6)
            ->pluck('count', 'selected_prefecture')
            ->toArray();

        // Submission status counts
        $submissionStats = [
            'submitted' => DynamicFormSubmission::where('status', 'submitted')->count(),
            'in_review' => DynamicFormSubmission::where('status', 'in_review')->count(),
            'approved'  => DynamicFormSubmission::where('status', 'approved')->count(),
            'rejected'  => DynamicFormSubmission::where('status', 'rejected')->count(),
        ];

        $statistics = [
            'total_users' => User::count(),
            'total_candidats' => Candidat::count(),
            'total_submissions' => ProjectsSubmission::count(),
            'recent_projects' => $allSubmissions->take(20),
            'total_projects' => $programe_list->count(),
            'total_candidats' => Candidat::count(),
            'male_count' => Candidat::where('gender', 'homme')->count(),
            'female_count' => Candidat::where('gender', 'femme')->count(),
            'address_labels' => array_keys($addressDistribution),
            'address_values' => array_values($addressDistribution),
            'submission_stats' => $submissionStats,
        ];


        
        $monthlyData = [];
        foreach ($allSubmissions as $submission) {
            $month = $submission->created_at->month;
            $monthlyData[$month] = ($monthlyData[$month] ?? 0) + 1;
        }

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyData[$i] ?? 0;
        }
            
        $menu = [
            ['label' => 'Admin Console', 'icon' => 'ri-dashboard-line', 'route' =>  route('admin.users.index')],
            ['label' => 'Gestion des Projets', 'icon' => 'ri-network-line', 'route' => route('admin.programe')],
            ['label' => 'Gestion des Submissions', 'icon' => 'ri-briefcase-line', 'route' => route('admin.all.submissions')],
            ['label' => 'Media Management', 'icon' => 'ri-image-2-line', 'route' => route('admin.media.index')],
        ];

        // ── Activité Récente (dynamic) ──
        $recentActivities = collect();

        // New form submissions
        DynamicFormSubmission::with(['candidat', 'programe'])
            ->latest()
            ->take(5)
            ->get()
            ->each(function ($sub) use (&$recentActivities) {
                $recentActivities->push([
                    'title'   => 'Nouvelle soumission de formulaire',
                    'subtitle'=> $sub->candidat ? $sub->candidat->nom . ' ' . $sub->candidat->prenom : 'Candidat inconnu',
                    'time'    => $sub->created_at?->diffForHumans(),
                    'icon'    => 'ri-file-list-3-line',
                    'color'   => 'text-blue-600',
                    'url'     => route('admin.all.submissions'),
                ]);
            });

        // New admin users
        User::whereIn('role', ['admin', 'super_admin'])
            ->latest()
            ->take(3)
            ->get()
            ->each(function ($user) use (&$recentActivities) {
                $recentActivities->push([
                    'title'   => 'Nouvel administrateur créé',
                    'subtitle'=> $user->nom . ' ' . $user->prenom,
                    'time'    => $user->created_at?->diffForHumans(),
                    'icon'    => 'ri-admin-line',
                    'color'   => 'text-green-600',
                    'url'     => route('admin.users.index'),
                ]);
            });

        // New candidats
        Candidat::latest()
            ->take(3)
            ->get()
            ->each(function ($c) use (&$recentActivities) {
                $recentActivities->push([
                    'title'   => 'Nouveau candidat inscrit',
                    'subtitle'=> $c->nom . ' ' . $c->prenom,
                    'time'    => $c->created_at?->diffForHumans(),
                    'icon'    => 'ri-user-add-line',
                    'color'   => 'text-purple-600',
                    'url'     => route('admin.users.index'),
                ]);
            });

        // New support tickets
        SupportTicket::latest()
            ->take(3)
            ->get()
            ->each(function ($t) use (&$recentActivities) {
                $recentActivities->push([
                    'title'   => 'Nouveau ticket support',
                    'subtitle'=> $t->subject,
                    'time'    => $t->created_at?->diffForHumans(),
                    'icon'    => 'ri-chat-3-line',
                    'color'   => 'text-orange-600',
                    'url'     => route('admin.support.tickets'),
                ]);
            });

        $recentActivities = $recentActivities
            ->sortByDesc(fn ($a) => $a['time'] instanceof \Carbon\Carbon ? $a['time']->timestamp : 0)
            ->take(8)
            ->map(function ($a) {
                $a['time'] = $a['time'] instanceof \Carbon\Carbon ? $a['time']->diffForHumans() : $a['time'];
                return $a;
            })
            ->values()
            ->all();

        return view('livewire.admin.dashboard', [
            'statistics'           => $statistics,
            'chartData'            => $chartData,
            'userSubmissions'      => $ProjectsSubmissions,
            'matricule_null'       => $matricule_null,
            'admins'               => User::whereIn('role', ['admin', 'super_admin'])->orderBy('nom')->get(['id', 'nom']),
            'menu'                 => $menu,
            'recentActivities'     => $recentActivities,
            'showCreateTicketModal'=> $this->showCreateTicketModal,
            'ticketSubject'        => $this->ticketSubject,
            'ticketMessage'        => $this->ticketMessage,
            'ticketCategory'       => $this->ticketCategory,
            'ticketPriority'       => $this->ticketPriority,
        ])->layout('layouts.admin', ['header' => 'Dashboard']);
    }
    
}
