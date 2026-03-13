@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div @if($isArabic) dir="rtl" @endif>
<aside class="sidebar d-flex flex-column" style="overflow-x: hidden; height: 100%;">
    <div class="logo">
        <a href="{{ route('user.dashboard') }}">
            <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="Logo">
        </a>
    </div>

    <nav class="py-3 grow" style="min-height:0;overflow-y:auto;-webkit-overflow-scrolling:touch;">
        <ul class="nav flex-column gap-1">

            {{-- Main Section --}}
            <li class="nav-item mt-1 mb-1">
                <small class="sidebar-section-label">{{ $tr('Navigation principale', 'التنقل الرئيسي') }}</small>
            </li>

            {{-- Dashboard (high priority) --}}
            <li class="nav-item">
                <a href="{{ route('user.dashboard') }}"
                   class="nav-link sidebar-main-link d-flex align-items-center gap-2 {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"
                   style="{{ request()->routeIs('user.dashboard') ? 'background:#6366f115;color:#648454;border-radius:.65rem;font-weight:700;' : '' }}">
                    <i class="ri-home-4-line fs-5" style="{{ request()->routeIs('user.dashboard') ? 'color:#648454;' : '' }}"></i>
                    <span>{{ $tr('Tableau de bord', 'لوحة التحكم') }}</span>
                    @if(request()->routeIs('user.dashboard'))
                        <span class="ms-auto rounded-pill" style="width:6px;height:6px;background:#648454;display:inline-block;"></span>
                    @endif
                </a>
            </li>

            {{-- Projects Hub (highest priority) --}}
            <li class="nav-item" x-data="{ open: window.innerWidth >= 992 }">
                <div class="project-hub">
                    <button @click="open = !open"
                            class="project-toggle w-100 d-flex align-items-center gap-2 text-start"
                            type="button">
                        <i class="ri-folder-open-line fs-5"></i>
                        <span class="grow fw-semibold">{{ $tr('Mes Projets', 'مشاريعي') }}</span>
                        <span class="project-count">{{ count($programe_list ?? []) }}</span>
                        <i :class="open ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line'" style="font-size:1rem;"></i>
                    </button>

                    <div x-show="open" x-collapse class="mt-2">
                        <a href="{{ route('user.projects.list') }}"
                           class="project-item d-flex align-items-center gap-2 {{ request()->routeIs('user.projects.list') ? 'active' : '' }}">
                            <i class="ri-list-check-2"></i>
                            <span>{{ $tr('Tous les projets', 'كل المشاريع') }}</span>
                        </a>

                        <div class="project-list mt-1">
                            @forelse($programe_list ?? [] as $list)
                                <a href="{{ route('user.project.detail', $list->id) }}"
                                   class="project-item d-flex align-items-center  gap-2 {{ request()->routeIs('user.project.detail') && request()->route('id') == $list->id ? 'active' : '' }}">
                                    <i class="ri-arrow-right-s-line"></i>
                                    <span class="text-truncate d-inline-block" style="max-width:100%;">{{ $list->project_name }}</span>
                                </a>
                            @empty
                                <p class="text-muted small px-2 py-2 mb-0">{{ $tr('Aucun projet disponible.', 'لا توجد مشاريع متاحة.') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </li>

            {{-- Secondary Section --}}
            <li class="nav-item mt-2 mb-1">
                <small class="nav-link text-muted text-uppercase fw-bold px-3"
                       style="font-size:.65rem;letter-spacing:1.2px;pointer-events:none;">{{ $tr('Autres menus', 'قوائم أخرى') }}</small>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.blog') }}"
                   class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('user.blog*') ? 'active' : '' }}"
                   style="{{ request()->routeIs('user.blog*') ? 'background:#f59e0b15;color:#b45309;border-radius:.55rem;font-weight:600;' : '' }}">
                    <i class="ri-article-line fs-5" style="{{ request()->routeIs('user.blog*') ? 'color:#f59e0b;' : '' }}"></i>
                    <span>{{ $tr('Blog & Actualités', 'المدونة والأخبار') }}</span>
                    @if(request()->routeIs('user.blog*'))
                        <span class="ms-auto rounded-pill" style="width:6px;height:6px;background:#f59e0b;display:inline-block;"></span>
                    @endif
                </a>
            </li>

            {{-- Section label: Assistance --}}
            <li class="nav-item mt-2 mb-1">
                <small class="nav-link text-muted text-uppercase fw-bold px-3"
                       style="font-size:.65rem;letter-spacing:1.2px;pointer-events:none;">{{ $tr('Assistance', 'المساعدة') }}</small>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.support') }}"
                   class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('user.support') ? 'active' : '' }}"
                   style="{{ request()->routeIs('user.support') ? 'background:#22c55e15;color:#15803d;border-radius:.55rem;font-weight:600;' : '' }}">
                    <i class="ri-customer-service-2-line fs-5" style="{{ request()->routeIs('user.support') ? 'color:#22c55e;' : '' }}"></i>
                    <span>{{ $tr('Support', 'الدعم') }}</span>
                    @if(request()->routeIs('user.support'))
                        <span class="ms-auto rounded-pill" style="width:6px;height:6px;background:#22c55e;display:inline-block;"></span>
                    @endif
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.chat') }}"
                   class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('user.chat') ? 'active' : '' }}"
                   style="{{ request()->routeIs('user.chat') ? 'background:#3b82f615;color:#1d4ed8;border-radius:.55rem;font-weight:600;' : '' }}">
                    <i class="ri-chat-3-line fs-5" style="{{ request()->routeIs('user.chat') ? 'color:#3b82f6;' : '' }}"></i>
                    <span class="grow">{{ $tr('Chat Admin', 'دردشة الإدارة') }}</span>
                    @php
                        $unreadChat = \App\Models\ChatMessage::unreadForCandidat(Auth::guard('candidat')->id());
                    @endphp
                    @if($unreadChat > 0)
                        <span class="badge rounded-pill" style="background:#ef4444;font-size:.65rem;">{{ $unreadChat }}</span>
                    @elseif(request()->routeIs('user.chat'))
                        <span class="ms-auto rounded-pill" style="width:6px;height:6px;background:#3b82f6;display:inline-block;"></span>
                    @endif
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.broadcasts.history') }}"
                   class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('user.broadcasts.history') ? 'active' : '' }}"
                   style="{{ request()->routeIs('user.broadcasts.history') ? 'background:#64845415;color:#648454;border-radius:.55rem;font-weight:600;' : '' }}">
                    <i class="ri-broadcast-line fs-5" style="{{ request()->routeIs('user.broadcasts.history') ? 'color:#648454;' : '' }}"></i>
                    <span>{{ $tr('Historique messages', 'سجل الرسائل') }}</span>
                    @if(request()->routeIs('user.broadcasts.history'))
                        <span class="ms-auto rounded-pill" style="width:6px;height:6px;background:#648454;display:inline-block;"></span>
                    @endif
                </a>
            </li>

        </ul>
    </nav>

</aside>

{{-- Profile complete modal (rendered outside sidebar so it shows on mobile too) --}}
@if($showCompleteProfileModal)
<div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);z-index:2000;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="ri-information-line text-warning me-2"></i>{{ $tr('Complétez votre profil', 'أكمل ملفك الشخصي') }}
                </h5>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="ri-user-settings-line" style="font-size:3.5rem;color:#648454;"></i>
                    <h5 class="mt-3 mb-2">{{ $tr('Votre profil est incomplet', 'ملفك الشخصي غير مكتمل') }}</h5>
                    <p class="text-muted mb-0 small">
                        {{ $tr('Veuillez compléter vos informations pour accéder à toutes les fonctionnalités.', 'يرجى إكمال معلوماتك للوصول إلى جميع الميزات.') }}
                    </p>
                </div>
                <div class="alert alert-info d-flex align-items-start border-0 rounded-3">
                    <i class="ri-lightbulb-line me-2 mt-1"></i>
                    <div>
                        <strong>{{ $tr('Pourquoi compléter votre profil ?', 'لماذا يجب إكمال ملفك الشخصي؟') }}</strong>
                        <ul class="mb-0 mt-2 ps-3 small">
                            <li>{{ $tr('Soumettre et gérer vos projets', 'تقديم وإدارة مشاريعك') }}</li>
                            <li>{{ $tr('Recevoir des notifications importantes', 'تلقي إشعارات مهمة') }}</li>
                            <li>{{ $tr('Améliorer le support et la communication', 'تحسين الدعم والتواصل') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <a href="{{ route('user.settings') }}" class="btn fw-semibold"
                   style="background:#648454;color:white;border-radius:.6rem;">
                    <i class="ri-settings-3-line me-1"></i>{{ $tr('Compléter maintenant', 'أكمل الآن') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endif

</div>
