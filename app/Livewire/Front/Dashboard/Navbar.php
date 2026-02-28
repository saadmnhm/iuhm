<?php

namespace App\Livewire\Front\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\DynamicFormSubmission;

class Navbar extends Component
{
    public string $pageTitle  = 'Dashboard';
    public ?string $profile_image = null;
    public array $notifications = [];
    public int $unreadCount   = 0;

    public function mount($pageTitle = null): void
    {
        $candidat             = Auth::guard('candidat')->user();
        $this->pageTitle      = $pageTitle ?? 'Dashboard';
        $this->profile_image  = $candidat?->profile_image;
        $this->loadNotifications($candidat);
    }

    protected function loadNotifications($candidat): void
    {
        if (!$candidat) return;

        $statusIconMap  = [
            'approved'  => ['icon' => 'ri-checkbox-circle-line',  'color' => 'bg-success',  'label' => 'Approuvé'],
            'rejected'  => ['icon' => 'ri-close-circle-line',     'color' => 'bg-danger',   'label' => 'Refusé'],
            'in_review' => ['icon' => 'ri-eye-line',              'color' => 'bg-primary',  'label' => 'En révision'],
            'submitted' => ['icon' => 'ri-send-plane-2-line',     'color' => 'bg-info',     'label' => 'Soumis'],
        ];

        $submissions = DynamicFormSubmission::with(['form', 'programe'])
            ->where('candidat_id', $candidat->id)
            ->whereIn('status', array_keys($statusIconMap))
            ->latest('updated_at')
            ->take(8)
            ->get();

        $this->notifications = $submissions->map(function ($sub) use ($statusIconMap) {
            $meta = $statusIconMap[$sub->status] ?? ['icon' => 'ri-notification-line', 'color' => 'bg-secondary', 'label' => $sub->status];
            return [
                'id'    => $sub->id,
                'title' => $meta['label'] . ' — ' . ($sub->form?->title ?? 'Formulaire'),
                'text'  => $sub->programe?->project_name ?? 'Formulaire indépendant',
                'time'  => $sub->updated_at?->diffForHumans() ?? '',
                'icon'  => $meta['icon'],
                'color' => $meta['color'],
            ];
        })->toArray();

        $this->unreadCount = min(count($this->notifications), 9);
    }

    public function render()
    {
        return view('livewire.front.dashboard.navbar');
    }
}
