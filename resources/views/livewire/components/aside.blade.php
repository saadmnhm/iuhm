<aside class="sidebar">
    <div class="logo">
        <i class="ri-dashboard-3-line me-2"></i>
        <span>Dashboard</span>
    </div>

    <nav class="py-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('form.dashboard') }}" class="nav-link {{ request()->routeIs('form.dashboard') ? 'active' : '' }}">
                    <i class="ri-home-4-line fs-5"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('form.dashboard') }}" class="nav-link">
                    <i class="ri-dashboard-line fs-5"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/" class="nav-link">
                    <i class="ri-file-add-line fs-5"></i>
                    <span>New Project</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="ri-folder-open-line fs-5"></i>
                    <span>My Projects</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="ri-bar-chart-box-line fs-5"></i>
                    <span>Analytics</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="ri-settings-3-line fs-5"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="user-section mt-auto">
        <div class="d-flex align-items-center gap-3">
            <div class="user-avatar">
                <i class="ri-user-line"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-semibold">{{ Auth::user()->name }}</div>
                <small class="text-white-50">{{ Auth::user()->email }}</small>
            </div>
        </div>
        <div class="mt-3">
            <form action="{{ route('form.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-light w-100">
                    <i class="ri-logout-box-line me-2"></i>Sign Out
                </button>
            </form>
        </div>
    </div>
</aside>