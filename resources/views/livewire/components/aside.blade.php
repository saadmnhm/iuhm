<aside class="sidebar d-flex flex-column">
    <div class="logo">
       <a href="{{ route('form.dashboard') }}"> <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="Logo"></a>
    </div>

    <nav class="py-3 flex-grow-1">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('form.dashboard') }}" class="nav-link {{ request()->routeIs('form.dashboard') ? 'active' : '' }}">
                    <i class="ri-home-4-line fs-5"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="ri-folder-open-line fs-5"></i>
                    <span>My Projects</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('form.business_plan') }}" class="nav-link {{ request()->routeIs('form.business_plan') ? 'active' : '' }}">
                    <i class="ri-bar-chart-box-line fs-5"></i>
                    <span>Business Plan</span>
                </a>
            </li>
            
        </ul>
    </nav>
</aside>