<aside class="sidebar">
    {{-- Brand Header --}}
    <div class="sidebar-brand d-flex align-items-center justify-content-between">
        <a href="{{ route('accountshome') }}" class="d-flex align-items-center gap-2 text-decoration-none brand-link">
            <img src="{{ asset('images/logo.png') }}" alt="Amudha Decors Logo" class="brand-logo-img">
            <div class="brand-text-wrap">
                <span class="brand-title">Amudha</span>
                <span class="brand-title-accent">Decors</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill brand-badge">ACC</span>
            </div>
        </a>

        <button class="btn btn-sm sidebar-toggle-btn" id="sidebarToggle" type="button" title="Toggle Sidebar">
            <i class="bi bi-chevron-left toggle-icon"></i>
        </button>
    </div>

    {{-- Main Navigation Menu --}}
    <nav class="sidebar-menu">
        <div class="sidebar-section-title">Finance & Accounts</div>

        <a href="{{ route('accountshome') }}" class="menu-item {{ request()->routeIs('accountshome') ? 'active' : '' }}" title="Dashboard">
            <div class="menu-icon-box">
                <i class="bi bi-speedometer2"></i>
            </div>
            <span class="menu-text">Dashboard</span>
        </a>

        <a href="{{ route('customersearch') }}" class="menu-item {{ request()->routeIs('customersearch') ? 'active' : '' }}" title="Customer 360° Search">
            <div class="menu-icon-box text-primary">
                <i class="bi bi-search-heart"></i>
            </div>
            <span class="menu-text">Customer Search</span>
        </a>

        <div class="sidebar-section-title">Billing & Transactions</div>

        <a href="#" class="menu-item" title="Purchase Orders">
            <div class="menu-icon-box text-warning">
                <i class="bi bi-receipt"></i>
            </div>
            <span class="menu-text">Purchase Orders</span>
        </a>

        <a href="#" class="menu-item" title="Bills">
            <div class="menu-icon-box text-info">
                <i class="bi bi-receipt-cutoff"></i>
            </div>
            <span class="menu-text">Bills</span>
        </a>

        <a href="#" class="menu-item" title="Payments">
            <div class="menu-icon-box text-success">
                <i class="bi bi-cash-stack"></i>
            </div>
            <span class="menu-text">Payments</span>
        </a>

        <a href="#" class="menu-item" title="Expenses">
            <div class="menu-icon-box text-warning">
                <i class="bi bi-wallet2"></i>
            </div>
            <span class="menu-text">Expenses</span>
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
