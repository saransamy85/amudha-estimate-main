<aside class="sidebar">
    {{-- Brand Header --}}
    <div class="sidebar-brand d-flex align-items-center justify-content-between">
        <a href="{{ route('purchasedashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none brand-link">
            <img src="{{ asset('images/logo.png') }}" alt="Amudha Decors Logo" class="brand-logo-img">
            <div class="brand-text-wrap">
                <span class="brand-title">Amudha</span>
                <span class="brand-title-accent">Decors</span>
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill brand-badge">PO</span>
            </div>
        </a>

        <button class="btn btn-sm sidebar-toggle-btn" id="sidebarToggle" type="button" title="Toggle Sidebar">
            <i class="bi bi-chevron-left toggle-icon"></i>
        </button>
    </div>

    {{-- Main Navigation Menu --}}
    <nav class="sidebar-menu">
        <div class="sidebar-section-title">Procurement</div>

        <a href="{{ route('purchasedashboard') }}" class="menu-item {{ request()->routeIs('purchasedashboard') ? 'active' : '' }}" title="Dashboard">
            <div class="menu-icon-box">
                <i class="bi bi-speedometer2"></i>
            </div>
            <span class="menu-text">Dashboard</span>
        </a>

        <a href="{{ route('purchase.index') }}" class="menu-item {{ request()->routeIs('purchase.index', 'purchase.view', 'purchase.edit') ? 'active' : '' }}" title="Purchase Orders">
            <div class="menu-icon-box text-success">
                <i class="bi bi-receipt"></i>
            </div>
            <span class="menu-text">Purchase Orders</span>
        </a>

        <a href="{{ route('vendors') }}" class="menu-item {{ request()->routeIs('vendors', 'vendor.*') ? 'active' : '' }}" title="Vendors">
            <div class="menu-icon-box text-primary">
                <i class="bi bi-building"></i>
            </div>
            <span class="menu-text">Vendors</span>
        </a>

        <div class="sidebar-section-title">Reports & System</div>

        <a href="{{ route('purchase.reports') }}" class="menu-item {{ request()->routeIs('purchase.reports') ? 'active' : '' }}" title="Reports">
            <div class="menu-icon-box">
                <i class="bi bi-bar-chart-line-fill"></i>
            </div>
            <span class="menu-text">Reports</span>
        </a>

        <a href="{{ route('logout') }}" class="menu-item text-danger" title="Logout">
            <div class="menu-icon-box text-danger">
                <i class="bi bi-box-arrow-right"></i>
            </div>
            <span class="menu-text">Logout</span>
        </a>
    </nav>
</aside>
