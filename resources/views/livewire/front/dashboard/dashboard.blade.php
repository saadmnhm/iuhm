<div x-data="{ searchOpen: false }" style="min-height:100vh; background:#f8fafc;">

    {{--  WELCOME HEADER  --}}
    <div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h1 class="fw-bold mb-1" style="font-size:1.7rem;color:#1e293b;">
                Bonjour, {{ $candidat->prenom }} {{ $candidat->nom }} 
            </h1>
            <p class="text-muted mb-0" style="font-size:.85rem;">
                <i class="ri-calendar-line me-1"></i>{{ now()->translatedFormat('l d F Y') }}
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('user.settings') }}"
               class="btn btn-sm fw-semibold d-flex align-items-center gap-2"
               style="background:#fff;border:1px solid #e2e8f0;color:#475569;border-radius:.6rem;padding:.4rem .9rem;">
                <i class="ri-user-settings-line" style="color:#6366f1;"></i>
                <span class="d-none d-sm-inline">Mon profil</span>
            </a>
            <a href="{{ route('user.support') }}"
               class="btn btn-sm fw-semibold d-flex align-items-center gap-2"
               style="background:#6366f1;color:white;border-radius:.6rem;padding:.4rem .9rem;border:none;">
                <i class="ri-customer-service-2-line"></i>
                <span class="d-none d-sm-inline">Support</span>
            </a>
        </div>
    </div>

    {{--  STAT CARDS  --}}
    <div class="row g-3 mb-4">
        @php
        $statCards = [
            ['label'=>'Total',      'value'=>$stats['total'],     'icon'=>'ri-folder-3-line',        'color'=>'#6366f1','bg'=>'#eef2ff','filter'=>null,       'hint'=>null],
            ['label'=>'Brouillons', 'value'=>$stats['drafts'],    'icon'=>'ri-draft-line',            'color'=>'#f59e0b','bg'=>'#fffbeb','filter'=>'draft',    'hint'=>'Filtrer'],
            ['label'=>'Soumis',     'value'=>$stats['submitted'], 'icon'=>'ri-send-plane-2-line',     'color'=>'#3b82f6','bg'=>'#eff6ff','filter'=>'submitted','hint'=>'Filtrer'],
            ['label'=>'Approuvés',  'value'=>$stats['approved'],  'icon'=>'ri-checkbox-circle-line',  'color'=>'#22c55e','bg'=>'#f0fdf4','filter'=>'approved', 'hint'=>'Filtrer'],
        ];
        @endphp

        @foreach($statCards as $card)
        <div class="col-6 col-lg-3">
            @if($card['filter'])
            <div wire:click="setFilter('{{ $card['filter'] }}')"
                 class="card border-0 rounded-3 h-100"
                 style="background:{{ $card['bg'] }};
                        border:2px solid {{ $activeFilter===$card['filter'] ? $card['color'] : 'transparent' }} !important;
                        cursor:pointer;transition:all .2s;
                        box-shadow:{{ $activeFilter===$card['filter'] ? '0 0 0 3px '.$card['color'].'25' : '0 1px 3px rgba(0,0,0,.07)' }};">
            @else
            <div class="card border-0 rounded-3 h-100"
                 style="background:{{ $card['bg'] }};border:2px solid transparent !important;box-shadow:0 1px 3px rgba(0,0,0,.07);">
            @endif
                <div class="card-body p-3">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="mb-1 fw-semibold" style="font-size:.75rem;color:{{ $card['color'] }};text-transform:uppercase;letter-spacing:.5px;">{{ $card['label'] }}</p>
                            <h2 class="fw-bold mb-0" style="font-size:2rem;color:{{ $card['color'] }};">{{ $card['value'] }}</h2>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:44px;height:44px;background:{{ $card['color'] }}20;flex-shrink:0;">
                            <i class="{{ $card['icon'] }}" style="font-size:1.3rem;color:{{ $card['color'] }};"></i>
                        </div>
                    </div>
                    @if($card['hint'])
                    <p class="mb-0 mt-2" style="font-size:.68rem;color:{{ $card['color'] }};opacity:.75;">
                        <i class="ri-filter-3-line me-1"></i>{{ $activeFilter===$card['filter'] ? 'Actif  cliquer pour retirer' : $card['hint'] }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{--  ACTIVE FILTER CHIPS + SEARCH  --}}
    @if($activeFilter || $searchQuery)
    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
        <span class="text-muted" style="font-size:.78rem;">Filtres actifs :</span>
        @if($activeFilter)
        <span class="badge d-flex align-items-center gap-1 rounded-pill"
              style="background:#6366f115;color:#6366f1;border:1px solid #6366f130;font-size:.73rem;padding:.3rem .7rem;font-weight:500;">
            <i class="ri-filter-3-line"></i>{{ ucfirst($activeFilter) }}
            <button wire:click="setFilter('{{ $activeFilter }}')"
                    style="background:none;border:none;padding:0;line-height:1;color:#6366f1;font-size:.7rem;cursor:pointer;">
                <i class="ri-close-line"></i>
            </button>
        </span>
        @endif
        @if($searchQuery)
        <span class="badge d-flex align-items-center gap-1 rounded-pill"
              style="background:#f59e0b15;color:#f59e0b;border:1px solid #f59e0b30;font-size:.73rem;padding:.3rem .7rem;font-weight:500;">
            <i class="ri-search-line"></i>{{ $searchQuery }}
            <button wire:click="$set('searchQuery','')"
                    style="background:none;border:none;padding:0;line-height:1;color:#f59e0b;font-size:.7rem;cursor:pointer;">
                <i class="ri-close-line"></i>
            </button>
        </span>
        @endif
        <button wire:click="clearFilters"
                style="background:none;border:none;font-size:.73rem;color:#ef4444;cursor:pointer;padding:0;">
            <i class="ri-close-circle-line me-1"></i>Effacer tout
        </button>
    </div>
    @endif

    {{--  MES PROGRAMMES  --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2" style="color:#1e293b;">
            <span class="rounded-2 d-inline-flex align-items-center justify-content-center"
                  style="width:30px;height:30px;background:#6366f115;">
                <i class="ri-layout-grid-line" style="color:#6366f1;font-size:1rem;"></i>
            </span>
            Mes Programmes
            <span class="badge rounded-pill ms-1"
                  style="background:#6366f115;color:#6366f1;font-size:.72rem;font-weight:600;">{{ $programe_list->count() }}</span>
        </h5>
    </div>

    <div class="row g-3 mb-5">
        @forelse($programe_list as $prog_list)
            @php
                $eligible = ($candidat->age >= $prog_list->min_age && $candidat->age <= $prog_list->max_age)
                         || in_array($candidat->adresse, explode(',', $prog_list->allowed_address_id ?? ''));
            @endphp
            @if($eligible)
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card border-0 rounded-3 h-100 programme-card"
                     style="border-left:4px solid #6366f1 !important;box-shadow:0 1px 4px rgba(0,0,0,.07);transition:all .2s;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:46px;height:46px;background:#6366f115;">
                                <i class="{{ $prog_list->icon ?? 'ri-folder-line' }}" style="font-size:1.4rem;color:#6366f1;"></i>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <h6 class="fw-bold mb-1 text-truncate" style="font-size:.9rem;color:#1e293b;">{{ $prog_list->project_name }}</h6>
                                <span class="badge rounded-pill"
                                      style="background:#22c55e15;color:#15803d;font-size:.68rem;padding:.25rem .55rem;">
                                    <i class="ri-checkbox-circle-line me-1"></i>Éligible
                                </span>
                            </div>
                        </div>
                        <p class="mb-3" style="font-size:.75rem;color:#94a3b8;">
                            <i class="ri-time-line me-1"></i>Mis à jour : {{ $prog_list->updated_at->format('d/m/Y') }}
                        </p>
                        <a href="{{ route('user.project.detail', $prog_list->id) }}"
                           class="btn btn-sm w-100 fw-semibold d-flex align-items-center justify-content-center gap-2"
                           style="background:#6366f1;color:white;border-radius:.55rem;border:none;font-size:.82rem;padding:.5rem;">
                            <i class="ri-arrow-right-circle-line"></i>Accéder au programme
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card border-0 rounded-3 h-100"
                     style="background:#f8fafc;border:1px dashed #cbd5e1 !important;opacity:.7;">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;background:#e2e8f050;">
                            <i class="{{ $prog_list->icon ?? 'ri-folder-line' }}" style="font-size:1.4rem;color:#94a3b8;"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold text-secondary" style="font-size:.88rem;">{{ $prog_list->project_name }}</h6>
                            <span class="badge rounded-pill"
                                  style="background:#e2e8f0;color:#64748b;font-size:.68rem;padding:.25rem .55rem;">
                                <i class="ri-lock-line me-1"></i>Inéligible
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @empty
        <div class="col-12">
            <div class="text-center py-5 rounded-3" style="background:#f1f5f9;">
                <i class="ri-inbox-line" style="font-size:2.5rem;color:#94a3b8;"></i>
                <p class="mt-2 text-muted small">Aucun programme disponible pour le moment.</p>
            </div>
        </div>
        @endforelse
    </div>

    {{--  MES SOUMISSIONS  --}}
    @if(count($dynamicSubmissions) > 0)
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2" style="color:#1e293b;">
            <span class="rounded-2 d-inline-flex align-items-center justify-content-center"
                  style="width:30px;height:30px;background:#f59e0b15;">
                <i class="ri-file-list-3-line" style="color:#f59e0b;font-size:1rem;"></i>
            </span>
            Mes Soumissions
            <span class="badge rounded-pill ms-1"
                  style="background:#f59e0b15;color:#b45309;font-size:.72rem;font-weight:600;">{{ count($dynamicSubmissions) }}</span>
        </h5>
        
    </div>

    @if(count($filteredSubmissions) === 0)
    <div class="text-center py-5 mb-4 rounded-3" style="background:#f8fafc;border:1px dashed #e2e8f0;">
        <i class="ri-filter-off-line" style="font-size:2.5rem;color:#94a3b8;"></i>
        <p class="mt-2 text-muted small mb-2">Aucune soumission pour ce filtre ou cette recherche.</p>
        <button wire:click="clearFilters"
                class="btn btn-sm fw-semibold"
                style="background:#6366f115;color:#6366f1;border-radius:.5rem;font-size:.78rem;border:none;">
            <i class="ri-refresh-line me-1"></i>Réinitialiser les filtres
        </button>
    </div>
    @else
    <div class="row g-3 mb-5">
        @foreach($filteredSubmissions as $sub)
        @php
        $sc = match($sub['status']) {
            'draft'     => ['bg'=>'#fffbeb','border'=>'#f59e0b','bbg'=>'#fef3c7','btxt'=>'#92400e'],
            'submitted' => ['bg'=>'#eff6ff','border'=>'#3b82f6','bbg'=>'#dbeafe','btxt'=>'#1e40af'],
            'in_review' => ['bg'=>'#f0f9ff','border'=>'#0ea5e9','bbg'=>'#e0f2fe','btxt'=>'#0c4a6e'],
            'approved'  => ['bg'=>'#f0fdf4','border'=>'#22c55e','bbg'=>'#dcfce7','btxt'=>'#14532d'],
            'rejected'  => ['bg'=>'#fff1f2','border'=>'#ef4444','bbg'=>'#fee2e2','btxt'=>'#7f1d1d'],
            default     => ['bg'=>'#f8fafc','border'=>'#94a3b8','bbg'=>'#e2e8f0','btxt'=>'#475569'],
        };
        $statusIcons = ['draft'=>'ri-draft-line','submitted'=>'ri-send-plane-2-line','in_review'=>'ri-eye-line','approved'=>'ri-checkbox-circle-line','rejected'=>'ri-close-circle-line'];
        @endphp
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 rounded-3 h-100 submission-card"
                 style="background:{{ $sc['bg'] }};border-left:4px solid {{ $sc['border'] }} !important;
                        box-shadow:0 1px 4px rgba(0,0,0,.06);transition:all .2s;">
                <div class="card-body p-3">
                    {{-- Header row --}}
                    <div class="d-flex align-items-start gap-2 mb-2">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:40px;height:40px;background:{{ $sub['form_color'] }}15;">
                            <i class="{{ $sub['form_icon'] }}" style="font-size:1.1rem;color:{{ $sub['form_color'] }};"></i>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <h6 class="fw-bold mb-0 text-truncate" style="font-size:.86rem;color:#1e293b;">{{ $sub['form_title'] }}</h6>
                            @if($sub['programe_name'])
                            <p class="mb-0 text-truncate" style="font-size:.72rem;color:#6b7280;">
                                <i class="ri-folder-line me-1"></i>{{ $sub['programe_name'] }}
                            </p>
                            @endif
                        </div>
                        <span class="badge rounded-pill flex-shrink-0 d-flex align-items-center gap-1"
                              style="background:{{ $sc['bbg'] }};color:{{ $sc['btxt'] }};font-size:.68rem;padding:.28rem .6rem;font-weight:600;">
                            <i class="{{ $statusIcons[$sub['status']] ?? 'ri-information-line' }}"></i>
                            {{ $sub['status_label'] }}
                        </span>
                    </div>

                    {{-- Progress bar for drafts --}}
                    @if($sub['status'] === 'draft' && $sub['total_steps'] > 0)
                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1" style="font-size:.7rem;color:#6b7280;">
                            <span>Progression</span>
                            <span>{{ $sub['current_step'] }}/{{ $sub['total_steps'] }}</span>
                        </div>
                        <div class="rounded-pill overflow-hidden" style="height:5px;background:#e5e7eb;">
                            <div class="rounded-pill"
                                 style="height:5px;width:{{ $sub['total_steps']>0 ? round(($sub['current_step']/$sub['total_steps'])*100) : 0 }}%;background:#f59e0b;transition:width .3s;"></div>
                        </div>
                    </div>
                    @endif

                    {{-- Timestamp --}}
                    <p class="mb-2" style="font-size:.7rem;color:#9ca3af;">
                        <i class="ri-time-line me-1"></i>
                        {{ $sub['submitted_at'] ? 'Soumis le '.$sub['submitted_at'] : 'Modifié le '.$sub['updated_at'] }}
                    </p>

                    {{-- Action button --}}
                    <button wire:click="resumeForm({{ $sub['id'] }})"
                            class="btn btn-sm w-100 fw-semibold d-flex align-items-center justify-content-center gap-2"
                            style="background:{{ $sc['border'] }}15;color:{{ $sc['border'] }};
                                   border:1px solid {{ $sc['border'] }}35;border-radius:.5rem;font-size:.78rem;padding:.4rem;">
                        @if($sub['status'] === 'draft')
                            <i class="ri-edit-2-line"></i>Continuer le brouillon
                        @elseif($sub['status'] === 'rejected')
                            <i class="ri-refresh-line"></i>Voir les détails
                        @else
                            <i class="ri-eye-line"></i>Voir les détails
                        @endif
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
    @endif

    {{--  BOTTOM ROW: PROFILE + QUICK ACTIONS  --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-5">
            <div class="card border-0 rounded-3 h-100"
                 style="background:linear-gradient(135deg,#6366f1 0%,#8b5cf6 100%);overflow:hidden;position:relative;">
                <div style="position:absolute;top:-20px;right:-20px;width:120px;height:120px;background:rgba(255,255,255,.06);border-radius:50%;"></div>
                <div style="position:absolute;bottom:-30px;left:-10px;width:90px;height:90px;background:rgba(255,255,255,.04);border-radius:50%;"></div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:50px;height:50px;background:rgba(255,255,255,.2);">
                            <i class="ri-user-3-line" style="font-size:1.4rem;color:white;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-white" style="font-size:.95rem;">{{ $candidat->prenom }} {{ $candidat->nom }}</h6>
                            <small style="color:rgba(255,255,255,.7);">Candidat</small>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-2 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color:rgba(255,255,255,.65);font-size:.78rem;">Email</span>
                            <span class="text-white fw-semibold" style="font-size:.78rem;">{{ $candidat->email }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color:rgba(255,255,255,.65);font-size:.78rem;">Membre depuis</span>
                            <span class="text-white fw-semibold" style="font-size:.78rem;">{{ $candidat->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color:rgba(255,255,255,.65);font-size:.78rem;">Soumissions</span>
                            <span class="text-white fw-semibold" style="font-size:.78rem;">{{ $stats['total'] }}</span>
                        </div>
                    </div>
                    <a href="{{ route('user.settings') }}"
                       class="btn btn-sm fw-semibold d-flex align-items-center gap-2"
                       style="background:rgba(255,255,255,.18);color:white;border:1px solid rgba(255,255,255,.3);border-radius:.55rem;font-size:.8rem;width:fit-content;">
                        <i class="ri-edit-line"></i>Modifier le profil
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <div class="card border-0 rounded-3 h-100" style="background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color:#1e293b;">
                        <i class="ri-flashlight-line" style="color:#f59e0b;font-size:1.1rem;"></i>
                        Actions Rapides
                    </h6>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <a href="{{ route('user.support') }}"
                           class="btn btn-sm fw-semibold d-flex align-items-center gap-2"
                           style="background:#f8fafc;border:1px solid #e2e8f0;color:#374151;border-radius:.55rem;padding:.5rem 1rem;font-size:.82rem;">
                            <i class="ri-customer-service-2-line" style="color:#6366f1;font-size:1rem;"></i>Support
                        </a>
                        <a href="{{ route('user.blog') }}"
                           class="btn btn-sm fw-semibold d-flex align-items-center gap-2"
                           style="background:#f8fafc;border:1px solid #e2e8f0;color:#374151;border-radius:.55rem;padding:.5rem 1rem;font-size:.82rem;">
                            <i class="ri-article-line" style="color:#f59e0b;font-size:1rem;"></i>Blog
                        </a>
                        <a href="{{ route('user.settings') }}"
                           class="btn btn-sm fw-semibold d-flex align-items-center gap-2"
                           style="background:#f8fafc;border:1px solid #e2e8f0;color:#374151;border-radius:.55rem;padding:.5rem 1rem;font-size:.82rem;">
                            <i class="ri-settings-3-line" style="color:#22c55e;font-size:1rem;"></i>Paramètres
                        </a>
                    </div>

                    {{-- Mini stats visual --}}
                    <div class="pt-3" style="border-top:1px solid #f1f5f9;">
                        <p class="mb-2 fw-semibold" style="font-size:.75rem;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">Aperçu des statuts</p>
                        <div class="d-flex flex-column gap-2">
                            @php
                            $total = max($stats['total'], 1);
                            $bars = [
                                ['label'=>'Brouillons', 'val'=>$stats['drafts'],   'color'=>'#f59e0b'],
                                ['label'=>'Soumis',     'val'=>$stats['submitted'],'color'=>'#3b82f6'],
                                ['label'=>'Approuvés',  'val'=>$stats['approved'], 'color'=>'#22c55e'],
                            ];
                            @endphp
                            @foreach($bars as $bar)
                            <div class="d-flex align-items-center gap-2">
                                <span style="font-size:.72rem;color:#64748b;width:70px;">{{ $bar['label'] }}</span>
                                <div class="flex-grow-1 rounded-pill overflow-hidden" style="height:7px;background:#f1f5f9;">
                                    <div class="rounded-pill" style="height:7px;width:{{ round(($bar['val']/$total)*100) }}%;background:{{ $bar['color'] }};transition:width .4s;"></div>
                                </div>
                                <span class="fw-bold" style="font-size:.72rem;color:{{ $bar['color'] }};width:18px;text-align:right;">{{ $bar['val'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--  PROFILE COMPLETE MODAL  --}}
    @if($showCompleteProfileModal)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="ri-information-line text-warning me-2"></i>Complétez votre profil
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="ri-user-settings-line" style="font-size:3.5rem;color:#648454;"></i>
                        <h5 class="mt-3 mb-2">Votre profil est incomplet</h5>
                        <p class="text-muted mb-0 small">Veuillez compléter vos informations pour accéder à toutes les fonctionnalités.</p>
                    </div>
                    <div class="alert alert-info d-flex align-items-start border-0 rounded-3">
                        <i class="ri-lightbulb-line me-2 mt-1"></i>
                        <div>
                            <strong>Pourquoi compléter votre profil ?</strong>
                            <ul class="mb-0 mt-2 ps-3 small">
                                <li>Soumettre et gérer vos projets</li>
                                <li>Recevoir des notifications importantes</li>
                                <li>Meilleur support et communication</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn fw-semibold" wire:click="goToSettings"
                            style="background:#648454;color:white;border-radius:.6rem;">
                        <i class="ri-settings-3-line me-1"></i>Compléter le profil
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{--  FAB (mobile quick actions)  --}}
    <div x-data="{ fabOpen: false }" style="position:fixed;right:1.25rem;bottom:1.5rem;z-index:1000;"
         class="d-lg-none">
        <div x-show="fabOpen" x-transition
             class="d-flex flex-column gap-2 mb-2 align-items-end">
            <a href="{{ route('user.settings') }}"
               class="btn btn-sm fw-semibold d-flex align-items-center gap-2 shadow-sm"
               style="background:white;border:1px solid #e2e8f0;color:#374151;border-radius:2rem;padding:.45rem 1rem;font-size:.8rem;">
                <i class="ri-settings-3-line" style="color:#6366f1;"></i>Paramètres
            </a>
            <a href="{{ route('user.support') }}"
               class="btn btn-sm fw-semibold d-flex align-items-center gap-2 shadow-sm"
               style="background:white;border:1px solid #e2e8f0;color:#374151;border-radius:2rem;padding:.45rem 1rem;font-size:.8rem;">
                <i class="ri-customer-service-2-line" style="color:#22c55e;"></i>Support
            </a>
        </div>
        <button @click="fabOpen = !fabOpen"
                class="btn btn-lg rounded-circle shadow d-flex align-items-center justify-content-center"
                style="width:52px;height:52px;background:#6366f1;color:white;border:none;font-size:1.4rem;">
            <i :class="fabOpen ? 'ri-close-line' : 'ri-add-line'"></i>
        </button>
    </div>

</div>
