<div class="max-w-7xl mx-auto bg-[#f8f9fc] min-h-screen font-sans">
    
    {{-- Header & Actions --}}
    <div class="flex items-center justify-between mb-8">
       
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 flex items-center gap-3">
            <i class="ri-checkbox-circle-line text-xl"></i> <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 flex items-center gap-3">
            <i class="ri-error-warning-line text-xl"></i> <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative">
        
        {{-- Zone de Contenu Principal (Colonnes 1 & 2) --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- Carte Profil Candidat --}}
            <div class="bg-white rounded-[1.5rem] shadow-sm p-8 flex flex-col sm:flex-row items-start gap-8 border border-gray-100">
                <div class="w-24 h-24 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400 text-4xl font-bold shadow-inner flex-shrink-0 relative overflow-hidden">
                    @if($candidat->profile_image)
                        <img src="{{ asset('uploads/'.$candidat->profile_image) }}" alt="{{ $candidat->nom }}" class="w-full h-full object-cover">
                    @else
                        <i class="ri-user-line absolute opacity-20 text-6xl"></i>
                        <span class="z-10">{{ strtoupper(substr($candidat->prenom, 0, 1) . substr($candidat->nom, 0, 1)) }}</span>
                    @endif
                </div>
                
                <div class="flex-1 w-full">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
                        <div>
                            <span class="inline-block text-[10px] font-bold tracking-wider px-3 py-1 rounded-full bg-green-100 text-green-600 mb-4 uppercase">
                                Candidat Actif
                            </span>
                            <h2 class="text-3xl font-extrabold text-[#0a1128]">{{ $candidat->prenom }} {{ $candidat->nom }}</h2>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" wire:click="openReviewModal" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-50 border border-gray-200 rounded-full hover:bg-gray-100 transition-colors">
                                <i class="ri-user-star-line"></i> Assigner à un admin
                            </button>
                            <a href="{{ route('admin.candidats.edit', $candidat->id) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-50 border border-gray-200 rounded-full hover:bg-gray-100 transition-colors">
                                <i class="ri-edit-2-line"></i> Edit info
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6 text-sm">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center flex-shrink-0 text-gray-500"><i class="ri-mail-line text-lg"></i></div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold tracking-wider uppercase mb-1">Email</p>
                                <p class="text-[#0a1128] font-semibold">{{ $candidat->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center flex-shrink-0 text-gray-500"><i class="ri-phone-line text-lg"></i></div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold tracking-wider uppercase mb-1">Téléphone</p>
                                <p class="text-[#0a1128] font-semibold">{{ $candidat->phone ?? '+212 -- --- ----' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center flex-shrink-0 text-gray-500"><i class="ri-map-pin-line text-lg"></i></div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold tracking-wider uppercase mb-1">Quartier</p>
                                <p class="text-[#0a1128] font-semibold">{{ $candidat->address ?? 'Non renseigné' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center flex-shrink-0 text-gray-500"><i class="ri-calendar-line text-lg"></i></div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold tracking-wider uppercase mb-1">Soumis le</p>
                                <p class="text-[#0a1128] font-semibold">{{ $candidat->created_at ? $candidat->created_at->format('d F Y') : 'Date inconnue' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Liste des Formulaires du Projet --}}
            <div class="mt-8">
                <h3 class="text-2xl font-bold text-[#0a1128] mb-6">Formulaires du Projet</h3>
                
                <div class="space-y-6">
                    @forelse($statistics['form_attached'] ?? [] as $sub)
                        @if($sub['is_active'] === 'active' )
                            {{-- Alpine x-data pour gérer le commentaire obligatoire de ce formulaire spécifiquement --}}
<div
    x-data="{
        comment: @js($sub['review_notes'] ?? ''),
        isLoading: false,
        hasSubmission: @js((bool) $sub['submission_id']),
        editing: false
    }"
    class="bg-white rounded-2xl shadow-sm border {{ $sub['actual_status'] == 'rejected' ? 'border-red-200' : 'border-gray-100' }} p-6 transition-all duration-200 hover:shadow-md relative overflow-hidden"
>
                                
                                {{-- Ligne du haut : Infos & Actions --}}
                                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                                    {{-- Titre & Icone --}}
                                    <div class="flex items-center gap-4 flex-1">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm" style="background-color: {{ $sub['color'] ?? '#e0f2fe' }}40; color: {{ $sub['color'] ?? '#0369a1' }}">
                                            <i class="{{ $sub['icon'] ?? 'ri-file-list-3-line' }} text-2xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-[15px] font-bold text-[#0a1128] leading-tight">{{ $sub['title'] }}</h4>
                                        </div>
                                    </div>

                                    {{-- Actions & Statut --}}
                                    @if($sub['status_label'] === 'Brouillon')
                                        <span class="px-4 py-2 text-xs font-bold rounded-full bg-gray-50 text-gray-600 border border-gray-200">Brouillon</span>
                                    @else
                                    <div class="flex  items-center gap-3">
                                        {{-- Badge de Statut --}}
                                        @if($sub['actual_status'] === 'in_review')
                                            <button type="button" wire:click="openWorkflowModal({{ $sub['submission_id'] }})" class="px-4 py-2 text-xs font-bold rounded-full bg-yellow-50 text-yellow-700 border border-yellow-300 hover:bg-yellow-100 transition-colors">
                                                En révision
                                            </button>
                                        @elseif($sub['actual_status'] === 'approved')
                                            <button type="button" wire:click="openWorkflowModal({{ $sub['submission_id'] }})" class="px-4 py-2 text-xs font-bold rounded-full bg-green-50 text-green-700 border border-green-300 hover:bg-green-100 transition-colors">
                                                Approuvé
                                            </button>
                                        @elseif($sub['actual_status'] === 'rejected')
                                            <button type="button" wire:click="openWorkflowModal({{ $sub['submission_id'] }})" class="px-4 py-2 text-xs font-bold rounded-full bg-red-50 text-red-700 border border-red-300 hover:bg-red-100 transition-colors">
                                                Rejeté
                                            </button>
                                        @else
                                            <button type="button" wire:click="openWorkflowModal({{ $sub['submission_id'] }})" class="px-4 py-2 text-xs font-bold rounded-full bg-gray-50 text-gray-600 border border-gray-200 hover:bg-gray-100 transition-colors">
                                                {{ $sub['status_label'] }}
                                            </button>
                                        @endif

                                        @if($sub['submission_id'])
                                            <a href="{{ route('admin.formulaires.submission.detail', ['id' => $sub['submission_id']]) }}" wire:navigate class="px-4 py-2 text-xs font-bold text-gray-700 bg-gray-50 border border-gray-200 hover:bg-gray-100 rounded-full transition-colors flex items-center gap-2">
                                                <i class="ri-eye-line"></i> Ouvrir
                                            </a>
                                        @endif
                                    </div>
                                    @endif
                                </div>

                                {{-- Zone de Commentaire Obligatoire (Affichée seulement s'il y a une soumission à juger) --}}
                                @if($sub['submission_id'] && $sub['status_label'] !== 'Brouillon')
                                    <div class="mt-6">
                                        <!-- <div class="flex items-center justify-between mb-2">
                                            <label class="text-[11px] font-bold text-red-500 tracking-wider uppercase flex items-center gap-2">
                                                Commentaire (Obligatoire)
                                                <i class="ri-asterisk text-xs"></i>
                                            </label>
                                            <div class="flex items-center gap-2">
                                                <span x-show="comment.trim().length < 3" class="px-2 py-0.5 text-[9px] font-bold text-red-700 bg-red-100 rounded-sm">REQUIS</span>
                                                <button x-show="!editing" @click="editing = true" class="px-2 py-1 text-[11px] bg-gray-50 border border-gray-200 rounded-sm text-gray-700">Modifier</button>
                                                <button x-show="editing" @click="$wire.set('workflowSubmissionId', {{ $sub['submission_id'] }}); $wire.set('workflowComment', comment); $wire.saveComment(); editing = false"
                                                    x-bind:disabled="comment.trim().length < 3"
                                                    x-bind:class="comment.trim().length < 3 ? 'opacity-40 cursor-not-allowed bg-gray-200 text-gray-500' : 'bg-blue-600 hover:bg-blue-700 text-white'"
                                                    class="px-3 py-1 text-[11px] rounded-sm transition-all">
                                                    Sauvegarder
                                                </button>
                                            </div>
                                        </div>
                                        <textarea 
                                            x-model="comment" 
                                            rows="2" 
                                            :readonly="!editing"
                                            x-bind:class="(!editing ? 'bg-gray-50/60 border-gray-100' : (comment.trim().length < 3 ? 'border-red-200 bg-red-50/30 focus:border-red-400 focus:ring-red-100' : 'border-gray-200 bg-gray-50 focus:border-blue-400 focus:ring-blue-100'))"
                                            class="w-full text-sm rounded-xl px-4 py-3 placeholder:text-gray-400 outline-none transition-all border  resize-none" 
                                            placeholder="Rédigez vos observations ou justifications ici pour activer les boutons..."></textarea> -->

                                        @if(!empty($sub['history']))
                                            <div class="mt-4 space-y-3 border-t border-gray-200 pt-4">
                                                <h5 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Historique des commentaires</h5>
                                                @foreach($sub['history'] as $historyItem)
                                                    <div class="rounded-2xl bg-gray-50 border border-gray-200 p-3">
                                                        <div class="flex items-center justify-between gap-3 text-[11px] text-gray-500 mb-2">
                                                            <span>{{ $historyItem['by'] }}</span>
                                                            <span>{{ $historyItem['at'] }}</span>
                                                        </div>
                                                        <p class="text-sm text-gray-700">{{ $historyItem['notes'] ?? 'Pas de commentaire.' }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif
                    @empty
                        <div class="p-12 text-center bg-white rounded-2xl border border-gray-200 border-dashed">
                            <i class="ri-folder-open-line text-4xl text-gray-300 mb-3 block"></i>
                            <p class="text-gray-500 font-medium">Aucun formulaire n'est attaché à ce projet pour le moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>

          
        </div>

        {{-- Barre Latérale Droite (Statut de la Revue) --}}
        <div class="lg:col-span-1">
            <div class="sticky top-8">
                <div class="bg-[#0a1128] rounded-[1.5rem] shadow-xl p-8 relative overflow-hidden">
                    {{-- Formes décoratives en fond --}}
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-5 rounded-full blur-2xl"></div>
                    <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-blue-500 opacity-10 rounded-full blur-2xl"></div>
                    
                    <h3 class="text-xl font-bold text-white mb-4 relative z-10">Statut de la Revue</h3>
                    <p class="text-sm text-gray-400 mb-8 leading-relaxed relative z-10">
                        L'examen de cette soumission est en cours. Veuillez valider les formulaires ci-contre.
                    </p>

                    {{-- Calcul dynamique de la progression --}}
                    @php
                        $attachedForms = collect($statistics['form_attached'] ?? []);
                        $totalForms = $attachedForms->where('is_active', 'active')->count();
                        $completedForms = $attachedForms->where('is_active', 'active')->whereIn('actual_status', ['approved'])->count();
                        $progressPercentage = $totalForms > 0 ? round(($completedForms / $totalForms) * 100) : 0;
                    @endphp

                    <div class="space-y-3 relative z-10">
                        <div class="flex items-center justify-between font-bold">
                            <span class="text-[10px] tracking-wider text-gray-400 uppercase">Progression</span>
                            <span class="text-2xl text-white">{{ $progressPercentage }}%</span>
                        </div>
                        <div class="w-full h-2.5 rounded-full bg-gray-800/80 overflow-hidden backdrop-blur-sm">
                            <div class="h-full rounded-full bg-green-400 shadow-[0_0_10px_rgba(7ade80,0.5)] transition-all duration-1000 ease-in-out" style="width: {{ $progressPercentage }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    @if($showReviewModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" wire:click.self="$set('showReviewModal', false)">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4">
            <form wire:submit.prevent="submitReview">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800">Affecter la révision</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assigné à</label>
                        <select wire:model.live="reviewerId" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm outline-none focus:border-blue-500">
                            <option value="">Sélectionner un administrateur</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin['id'] }}">{{ $admin['name'] }}</option>
                            @endforeach
                        </select>
                        @error('reviewerId')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea wire:model="reviewNotes" rows="3" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm outline-none focus:border-blue-500" placeholder="Ajoutez des notes..."></textarea>
                    </div>
        
                    @if(!empty($workflowHistory))
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Historique des commentaires</h4>
                            <div class="space-y-3 max-h-56 overflow-y-auto pr-2">
                                @foreach($workflowHistory as $history)
                                    <div class="rounded-2xl bg-white border border-gray-200 p-3">
                                        <div class="flex items-center justify-between gap-3 mb-2 text-xs text-gray-500">
                                            <span>{{ $history['by'] }}</span>
                                            <span>{{ $history['at'] }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $history['notes'] ?? 'Pas de commentaire.' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                    <button type="button" wire:click="$set('showReviewModal', false)" class="px-5 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition">Annuler</button>
                    <button type="submit" wire:loading.attr="disabled" class="px-5 py-2.5 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
    @endif
    
    @if($showWorkflowModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" wire:click.self="$set('showWorkflowModal', false)">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4">
                <form wire:submit.prevent="saveWorkflowProgress">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">Changer le statut du formulaire</h3>
                        <p class="text-sm text-gray-500 mt-1">Un commentaire est requis pour chaque mise à jour de statut.</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                            <select wire:model.live="workflowStatus" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm outline-none focus:border-blue-500">
                                <option value="draft">Brouillon</option>
                                <option value="submitted">Soumis</option>
                                <option value="in_review">En révision</option>
                                <option value="approved">Approuvé</option>
                                <option value="rejected">Rejeté</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Commentaire</label>
                            <textarea wire:model.live="workflowComment" rows="4" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm outline-none focus:border-blue-500" placeholder="Expliquez la raison du changement de statut..."></textarea>
                            @error('workflowComment')
                                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                        <button type="button" wire:click="$set('showWorkflowModal', false)" class="px-5 py-2.5 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition">Annuler</button>
                        <button type="submit" wire:loading.attr="disabled" class="px-5 py-2.5 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

