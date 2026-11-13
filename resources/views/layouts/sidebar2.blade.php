<aside class="sidebar">
    {{-- Brand Header --}}
    <div class="sidebar-brand d-flex align-items-center justify-content-between">
        <a href="{{ route('receptiondashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none brand-link">
            <img src="{{ asset('images/logo.png') }}" alt="Amudha Decors Logo" class="brand-logo-img">
            <div class="brand-text-wrap">
                <span class="brand-title">Amudha</span>
                <span class="brand-title-accent">Decors</span>
                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill brand-badge">REC</span>
            </div>
        </a>

        <button class="btn btn-sm sidebar-toggle-btn" id="sidebarToggle" type="button" title="Toggle Sidebar">
            <i class="bi bi-chevron-left toggle-icon"></i>
        </button>
    </div>

    {{-- Main Navigation Menu --}}
    <nav class="sidebar-menu">
        <div class="sidebar-section-title">Front Desk & Materials</div>

        <a href="{{ route('receptiondashboard') }}" class="menu-item {{ request()->routeIs('receptiondashboard') ? 'active' : '' }}" title="Dashboard">
            <div class="menu-icon-box">
                <i class="bi bi-speedometer2"></i>
            </div>
            <span class="menu-text">Dashboard</span>
        </a>

        <a href="{{ route('materialreturn.history') }}" class="menu-item {{ request()->routeIs('materialreturn.history', 'materialreturn.view') ? 'active' : '' }}" title="Materials-In">
            <div class="menu-icon-box text-success">
                <i class="bi bi-box-arrow-in-left"></i>
            </div>
            <span class="menu-text">Materials-In</span>
        </a>

        <a href="{{ route('receptionmaterialitems') }}" class="menu-item {{ request()->routeIs('receptionmaterialitems') ? 'active' : '' }}" title="Materials-Out">
            <div class="menu-icon-box text-danger">
                <i class="bi bi-box-arrow-right"></i>
            </div>
            <span class="menu-text">Materials-Out</span>
        </a>

        <a href="{{ route('materialreturn') }}" class="menu-item {{ request()->routeIs('materialreturn') ? 'active' : '' }}" title="Returns">
            <div class="menu-icon-box text-warning">
                <i class="bi bi-arrow-return-left"></i>
            </div>
            <span class="menu-text">Returns</span>
        </a>

        <a href="{{ route('receptionsites') }}" class="menu-item {{ request()->routeIs('receptionsites', 'sitetimeline') ? 'active' : '' }}" title="Sites Directory">
            <div class="menu-icon-box text-primary">
                <i class="bi bi-geo-alt-fill"></i>
            </div>
            <span class="menu-text">Sites</span>
        </a>

        <a href="{{ route('reception.reports') }}" class="menu-item {{ request()->routeIs('reception.reports') ? 'active' : '' }}" title="Reports">
            <div class="menu-icon-box text-secondary">
                <i class="bi bi-bar-chart-line-fill"></i>
            </div>
            <span class="menu-text">Reports</span>
        </a>

        <div class="sidebar-section-title">System</div>

        <a href="{{ route('logout') }}" class="menu-item text-danger" title="Logout">
            <div class="menu-icon-box text-danger">
                <i class="bi bi-box-arrow-right"></i>
            </div>
            <span class="menu-text">Logout</span>
        </a>
    </nav>
</aside>
