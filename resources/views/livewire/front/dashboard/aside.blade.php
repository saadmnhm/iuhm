<div>
<aside class="sidebar d-flex flex-column" style="overflow-x: hidden; height: 100%;">
    <div class="logo">
        <a href="{{ route('user.dashboard') }}">
            <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="Logo">
        </a>
    </div>

    <nav class="py-3 flex-grow- h">
        <ul class="nav flex-column gap-1">

            {{-- Dashboard --}}
            <li class="nav-item">
                <a href="{{ route('user.dashboard') }}"
                   class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"
                   style="{{ request()->routeIs('user.dashboard') ? 'background:#6366f115;color:#648454;border-radius:.55rem;font-weight:600;' : '' }}">
                    <i class="ri-home-4-line fs-5" style="{{ request()->routeIs('user.dashboard') ? 'color:#648454;' : '' }}"></i>
                    <span>Tableau de bord</span>
                    @if(request()->routeIs('user.dashboard'))
                        <span class="ms-auto rounded-pill" style="width:6px;height:6px;background:#648454;display:inline-block;"></span>
                    @endif
                </a>
            </li>

            {{-- Section label --}}
            <li class="nav-item mt-2 mb-1">
                <small class="nav-link text-muted text-uppercase fw-bold px-3"
                       style="font-size:.65rem;letter-spacing:1.2px;pointer-events:none;">Programmes</small>
            </li>

            {{-- Projets collapsible --}}
            <li class="nav-item" x-data="{ open: {{ request()->routeIs('user.project.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="nav-link w-100 d-flex align-items-center gap-2 text-start"
                        style="background:none;border:none;">
                    <i class="ri-folder-open-line fs-5" ></i>
                    <span class="flex-grow-1">Projets</span>
                    <i :class="open ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line'"
                       style="font-size:1rem;transition:transform .2s;"></i>
                </button>

                <div x-show="open" x-collapse class="ps-3 mt-1">
                    @foreach($programe_list ?? [] as $list)
                    <a href="{{ route('user.project.detail', $list->id) }}"
                       class="nav-link d-flex align-items-center gap-2 py-2 {{ request()->routeIs('user.project.detail') && request()->route('id') == $list->id ? 'active' : '' }}"
                       style="font-size:.85rem;border-radius:.45rem;
                              {{ request()->routeIs('user.project.detail') && request()->route('id') == $list->id ? 'background:#6366f110;color:#648454;font-weight:600;' : '' }}">
                        <i class="ri-arrow-right-s-line" style="font-size:.9rem;{{ request()->routeIs('user.project.detail') && request()->route('id') == $list->id ? 'color:#648454;' : 'color:#94a3b8;' }}"></i>
                        <span class="text-truncate">{{ $list->project_name }}</span>
                    </a>
                    @endforeach
                </div>
            </li>

            {{-- Section label: Ressources --}}
            <li class="nav-item mt-2 mb-1">
                <small class="nav-link text-muted text-uppercase fw-bold px-3"
                       style="font-size:.65rem;letter-spacing:1.2px;pointer-events:none;">Ressources</small>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.blog') }}"
                   class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('user.blog*') ? 'active' : '' }}"
                   style="{{ request()->routeIs('user.blog*') ? 'background:#f59e0b15;color:#b45309;border-radius:.55rem;font-weight:600;' : '' }}">
                    <i class="ri-article-line fs-5" style="{{ request()->routeIs('user.blog*') ? 'color:#f59e0b;' : '' }}"></i>
                    <span>Blog & Actualités</span>
                    @if(request()->routeIs('user.blog*'))
                        <span class="ms-auto rounded-pill" style="width:6px;height:6px;background:#f59e0b;display:inline-block;"></span>
                    @endif
                </a>
            </li>

            {{-- Section label: Assistance --}}
            <li class="nav-item mt-2 mb-1">
                <small class="nav-link text-muted text-uppercase fw-bold px-3"
                       style="font-size:.65rem;letter-spacing:1.2px;pointer-events:none;">Assistance</small>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.support') }}"
                   class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('user.support') ? 'active' : '' }}"
                   style="{{ request()->routeIs('user.support') ? 'background:#22c55e15;color:#15803d;border-radius:.55rem;font-weight:600;' : '' }}">
                    <i class="ri-customer-service-2-line fs-5" style="{{ request()->routeIs('user.support') ? 'color:#22c55e;' : '' }}"></i>
                    <span>Support</span>
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
                    <span class="flex-grow-1">Chat Admin</span>
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
                    <span>Historique messages</span>
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
                    <i class="ri-information-line text-warning me-2"></i>Complete Your Profile
                </h5>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="ri-user-settings-line" style="font-size:3.5rem;color:#648454;"></i>
                    <h5 class="mt-3 mb-2">Your profile is incomplete</h5>
                    <p class="text-muted mb-0 small">
                        Please complete your profile information to access all features.
                    </p>
                </div>
                <div class="alert alert-info d-flex align-items-start border-0 rounded-3">
                    <i class="ri-lightbulb-line me-2 mt-1"></i>
                    <div>
                        <strong>Why complete your profile?</strong>
                        <ul class="mb-0 mt-2 ps-3 small">
                            <li>Submit and manage projects</li>
                            <li>Receive important notifications</li>
                            <li>Better support and communication</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <a href="{{ route('user.settings') }}" class="btn fw-semibold"
                   style="background:#648454;color:white;border-radius:.6rem;">
                    <i class="ri-settings-3-line me-1"></i>Complete Profile Now
                </a>
            </div>
        </div>
    </div>
</div>
@endif

</div>
