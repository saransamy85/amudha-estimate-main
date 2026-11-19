<aside class="sidebar">
    {{-- Brand Header --}}
    <div class="sidebar-brand d-flex align-items-center justify-content-between">
        <a href="{{ route('dashboard1') }}" class="d-flex align-items-center gap-2 text-decoration-none brand-link">
            <img src="{{ asset('images/logo.png') }}" alt="Amudha Decors Logo" class="brand-logo-img">
            <div class="brand-text-wrap">
                <span class="brand-title">Amudha</span>
                <span class="brand-title-accent">Decors</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill brand-badge">CRM</span>
            </div>
        </a>

        <button class="btn btn-sm sidebar-toggle-btn" id="sidebarToggle" type="button" title="Toggle Sidebar">
            <i class="bi bi-chevron-left toggle-icon"></i>
        </button>
    </div>

    {{-- Main Navigation Menu --}}
    <nav class="sidebar-menu">

        {{-- Section 1: Main Navigation --}}
        <div class="sidebar-section-title">Main Navigation</div>

        {{-- Dashboard --}}
        <a href="{{ route('dashboard1') }}" class="menu-item {{ request()->routeIs('dashboard1') ? 'active' : '' }}" title="Dashboard">
            <div class="menu-icon-box">
                <i class="bi bi-speedometer2"></i>
            </div>
            <span class="menu-text">Dashboard</span>
            @if(isset($escount) && $escount > 0)
                <span class="menu-badge badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">{{ $escount }}</span>
            @endif
        </a>

        {{-- Enquiries --}}
        <a href="{{ route('adminlead') }}" class="menu-item {{ request()->routeIs('adminlead') ? 'active' : '' }}" title="Enquiries">
            <div class="menu-icon-box">
                <i class="bi bi-bullseye"></i>
            </div>
            <span class="menu-text">Enquiry</span>
            @if(isset($lc) && $lc > 0)
                <span class="menu-badge badge bg-info-subtle text-info border border-info-subtle rounded-pill">{{ $lc }}</span>
            @endif
        </a>

        {{-- Reports --}}
        <a href="{{ route('adminreport') }}" class="menu-item {{ request()->routeIs('adminreport', 'reports.custom') ? 'active' : '' }}" title="Analytics & Reports">
            <div class="menu-icon-box">
                <i class="bi bi-bar-chart-line-fill"></i>
            </div>
            <span class="menu-text">Reports</span>
        </a>

        {{-- Customers --}}
        <a href="{{ route('admincustomer') }}" class="menu-item {{ request()->routeIs('admincustomer') ? 'active' : '' }}" title="Customers">
            <div class="menu-icon-box">
                <i class="bi bi-people-fill"></i>
            </div>
            <span class="menu-text">Customers</span>
            @if(isset($cuscount) && $cuscount > 0)
                <span class="menu-badge badge bg-success-subtle text-success border border-success-subtle rounded-pill">{{ $cuscount }}</span>
            @endif
        </a>

        {{-- Customer 360 Search --}}
        <a href="{{ route('customersearch') }}" class="menu-item {{ request()->routeIs('customersearch') ? 'active' : '' }}" title="Customer 360° Search">
            <div class="menu-icon-box">
                <i class="bi bi-search-heart"></i>
            </div>
            <span class="menu-text">Customer Search</span>
        </a>

        {{-- RCS Messaging --}}
        <a href="{{ route('rcsmsg') }}" class="menu-item {{ request()->routeIs('rcsmsg') ? 'active' : '' }}" title="RCS Campaigns">
            <div class="menu-icon-box text-success">
                <i class="bi bi-chat-left-dots-fill"></i>
            </div>
            <span class="menu-text">RCS Campaigns</span>
        </a>

        {{-- Section 2: Operations & Procurement --}}
        <div class="sidebar-section-title">Operations</div>

        @php
            $isPurchaseActive = request()->routeIs('purchasedashboard', 'admin.po_orders', 'vendors', 'purchase.*');
        @endphp

        {{-- Purchase Orders Dropdown --}}
        <div class="menu-dropdown-wrap">
            <a class="menu-item sidebar-dropdown-toggle {{ $isPurchaseActive ? 'active' : 'collapsed' }}" 
               data-bs-toggle="collapse" 
               href="#sidebarPurchaseMenu" 
               role="button" 
               aria-expanded="{{ $isPurchaseActive ? 'true' : 'false' }}" 
               aria-controls="sidebarPurchaseMenu"
               title="Purchase Orders">
                <div class="menu-icon-box text-warning">
                    <i class="bi bi-cart-check-fill"></i>
                </div>
                <span class="menu-text">Purchase Orders</span>
                <i class="bi bi-chevron-down chevron-icon ms-auto small"></i>
            </a>

            <div class="collapse {{ $isPurchaseActive ? 'show' : '' }}" id="sidebarPurchaseMenu">
                <div class="submenu-container">
                    <a href="{{ route('purchasedashboard') }}" class="submenu-item {{ request()->routeIs('purchasedashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer submenu-icon"></i>
                        <span>PO Dashboard</span>
                    </a>
                    <a href="{{ route('admin.po_orders') }}" class="submenu-item {{ request()->routeIs('admin.po_orders') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text submenu-icon"></i>
                        <span>Orders List</span>
                    </a>
                    <a href="{{ route('vendors') }}" class="submenu-item {{ request()->routeIs('vendors', 'vendor.*') ? 'active' : '' }}">
                        <i class="bi bi-building submenu-icon"></i>
                        <span>Vendors & Suppliers</span>
                    </a>
                </div>
            </div>
        </div>

        @php
            $isReceptionActive = request()->routeIs('receptiondashboard', 'receptionmaterialitems', 'receptionsites');
        @endphp

        {{-- Reception & Logistics Dropdown --}}
        <div class="menu-dropdown-wrap">
            <a class="menu-item sidebar-dropdown-toggle {{ $isReceptionActive ? 'active' : 'collapsed' }}" 
               data-bs-toggle="collapse" 
               href="#sidebarReceptionMenu" 
               role="button" 
               aria-expanded="{{ $isReceptionActive ? 'true' : 'false' }}" 
               aria-controls="sidebarReceptionMenu"
               title="Reception & Logistics">
                <div class="menu-icon-box text-info">
                    <i class="bi bi-door-open-fill"></i>
                </div>
                <span class="menu-text">Receptionist</span>
                <i class="bi bi-chevron-down chevron-icon ms-auto small"></i>
            </a>

            <div class="collapse {{ $isReceptionActive ? 'show' : '' }}" id="sidebarReceptionMenu">
                <div class="submenu-container">
                    <a href="{{ route('receptiondashboard') }}" class="submenu-item {{ request()->routeIs('receptiondashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer submenu-icon"></i>
                        <span>Reception Dashboard</span>
                    </a>
                    <a href="{{ route('receptionmaterialitems') }}" class="submenu-item {{ request()->routeIs('receptionmaterialitems') ? 'active' : '' }}">
                        <i class="bi bi-box-arrow-right submenu-icon"></i>
                        <span>Material Out</span>
                    </a>
                    <a href="{{ route('receptionsites') }}" class="submenu-item {{ request()->routeIs('receptionsites') ? 'active' : '' }}">
                        <i class="bi bi-geo-alt submenu-icon"></i>
                        <span>Sites Directory</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Section 3: Administration --}}
        <div class="sidebar-section-title">Administration</div>

        {{-- Staff Attendance --}}
        <a href="{{ route('accounts.attendance') }}" class="menu-item {{ request()->routeIs('accounts.attendance*') ? 'active' : '' }}" title="Staff Attendance">
            <div class="menu-icon-box text-success">
                <i class="bi bi-calendar2-check-fill"></i>
            </div>
            <span class="menu-text">Staff Attendance</span>
        </a>

        {{-- Users --}}
        <a href="{{ route('usermanagement') }}" class="menu-item {{ request()->routeIs('usermanagement') ? 'active' : '' }}" title="User Management">
            <div class="menu-icon-box">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <span class="menu-text">Users</span>
        </a>

        {{-- Settings --}}
        <a href="#" class="menu-item" title="Settings">
            <div class="menu-icon-box">
                <i class="bi bi-sliders"></i>
            </div>
            <span class="menu-text">Settings</span>
        </a>

    </nav>

    {{-- Online Team Footer Card --}}
    @if(isset($onlineUsers))
        <div class="sidebar-footer">
            <div class="online-users-card">
                <div class="d-flex align-items-center justify-content-between mb-2 online-card-header">
                    <div class="d-flex align-items-center gap-2">
                        <span class="online-radar-pulse"></span>
                        <span class="small fw-bold text-dark online-title">Online Team</span>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill online-count">
                        {{ count($onlineUsers) }}
                    </span>
                </div>

                <div class="online-users-list">
                    @forelse($onlineUsers->take(4) as $user)
                        @php
                            $userInitial = strtoupper(substr($user->name ?? $user->username ?? 'U', 0, 1));
                        @endphp
                        <div class="online-user-item d-flex align-items-center gap-2 py-1" title="{{ $user->name ?? $user->username }}">
                            <div class="online-user-avatar">
                                {{ $userInitial }}
                                <span class="online-user-dot"></span>
                            </div>
                            <span class="small text-secondary text-truncate online-user-name">
                                {{ $user->name ?? $user->username }}
                            </span>
                        </div>
                    @empty
                        <small class="text-muted fst-italic">No active staff</small>
                    @endforelse

                    @if(count($onlineUsers) > 4)
                        <div class="small text-muted text-center pt-1 online-more">
                            +{{ count($onlineUsers) - 4 }} more active
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</aside>
