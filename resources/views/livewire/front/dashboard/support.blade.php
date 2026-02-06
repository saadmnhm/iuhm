<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Support - Mes Tickets</h4>
        <button wire:click="openCreateModal" class="btn btn-primary btn-sm">
            <i class="ri-add-line"></i> Nouveau Ticket
        </button>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tickets List -->
    <div class="row">
        @forelse($tickets as $ticket)
        <div class="col-12 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $ticket->subject }}</h6>
                            <p class="text-muted small mb-2">{{ Str::limit($ticket->message, 150) }}</p>
                            <div class="d-flex gap-2 align-items-center">
                                <span class="badge bg-{{ $ticket->status === 'open' ? 'warning' : ($ticket->status === 'resolved' ? 'success' : ($ticket->status === 'in_progress' ? 'primary' : 'secondary')) }}">
                                    {{ $ticket->status_label }}
                                </span>
                                <span class="badge bg-{{ $ticket->priority === 'urgent' ? 'danger' : ($ticket->priority === 'high' ? 'warning' : 'info') }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                                <small class="text-muted">{{ $ticket->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                    
                    @if($ticket->admin_response)
                    <div class="mt-3 p-3 bg-light rounded">
                        <small class="fw-bold text-primary d-block mb-1">
                            <i class="ri-customer-service-line"></i> Réponse de l'administration
                        </small>
                        <p class="small mb-0">{{ $ticket->admin_response }}</p>
                        @if($ticket->responded_at)
                        <small class="text-muted">{{ $ticket->responded_at->diffForHumans() }}</small>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="ri-customer-service-2-line" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Aucun ticket de support. Créez-en un si vous avez besoin d'aide!</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $tickets->links() }}
    </div>

    <!-- Create Ticket Modal -->
    @if($showCreateModal)
    <div class="modal show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouveau Ticket de Support</h5>
                    <button type="button" class="btn-close" wire:click="$set('showCreateModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Sujet *</label>
                        <input type="text" wire:model="subject" class="form-control @error('subject') is-invalid @enderror" 
                            placeholder="Décrivez brièvement votre problème">
                        @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Catégorie</label>
                            <select wire:model="category" class="form-select">
                                <option value="general">Général</option>
                                <option value="technical">Technique</option>
                                <option value="account">Mon Compte</option>
                                <option value="form">Formulaire</option>
                                <option value="other">Autre</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Priorité</label>
                            <select wire:model="priority" class="form-select">
                                <option value="low">Basse</option>
                                <option value="medium">Moyenne</option>
                                <option value="high">Haute</option>
                                <option value="urgent">Urgente</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Message *</label>
                        <textarea wire:model="message" class="form-control @error('message') is-invalid @enderror" 
                            rows="5" placeholder="Décrivez votre problème en détail..."></textarea>
                        @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showCreateModal', false)">Annuler</button>
                    <button type="button" class="btn btn-primary" wire:click="createTicket">
                        <i class="ri-send-plane-line"></i> Envoyer
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
