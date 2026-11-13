<aside class="sidebar">
    {{-- Brand Header --}}
    <div class="sidebar-brand d-flex align-items-center justify-content-between">
        <a href="{{ route('salesdashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none brand-link">
            <img src="{{ asset('images/logo.png') }}" alt="Amudha Decors Logo" class="brand-logo-img">
            <div class="brand-text-wrap">
                <span class="brand-title">Amudha</span>
                <span class="brand-title-accent">Decors</span>
                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill brand-badge">SALES</span>
            </div>
        </a>

        <button class="btn btn-sm sidebar-toggle-btn" id="sidebarToggle" type="button" title="Toggle Sidebar">
            <i class="bi bi-chevron-left toggle-icon"></i>
        </button>
    </div>

    {{-- Main Navigation Menu --}}
    <nav class="sidebar-menu">
        <div class="sidebar-section-title">Sales & Pipeline</div>

        <a href="{{ route('salesdashboard') }}" class="menu-item {{ request()->routeIs('salesdashboard') ? 'active' : '' }}" title="Dashboard">
            <div class="menu-icon-box">
                <i class="bi bi-speedometer2"></i>
            </div>
            <span class="menu-text">Dashboard</span>
            @if(isset($escount) && $escount > 0)
                <span class="menu-badge badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">{{ $escount }}</span>
            @endif
        </a>

        <a href="{{ route('leaddash') }}" class="menu-item {{ request()->routeIs('leaddash') ? 'active' : '' }}" title="Leads">
            <div class="menu-icon-box text-info">
                <i class="bi bi-bullseye"></i>
            </div>
            <span class="menu-text">Leads</span>
            @if(isset($lc) && $lc > 0)
                <span class="menu-badge badge bg-info-subtle text-info border border-info-subtle rounded-pill">{{ $lc }}</span>
            @endif
        </a>

        <a href="{{ route('salescustomer') }}" class="menu-item {{ request()->routeIs('salescustomer') ? 'active' : '' }}" title="Customers">
            <div class="menu-icon-box text-success">
                <i class="bi bi-people-fill"></i>
            </div>
            <span class="menu-text">Customers</span>
            @if(isset($cuscount) && $cuscount > 0)
                <span class="menu-badge badge bg-success-subtle text-success border border-success-subtle rounded-pill">{{ $cuscount }}</span>
            @endif
        </a>

        <a href="{{ route('salesgreetings') }}" class="menu-item {{ request()->routeIs('salesgreetings') ? 'active' : '' }}" title="Greetings">
            <div class="menu-icon-box text-warning">
                <i class="bi bi-envelope-heart-fill"></i>
            </div>
            <span class="menu-text">Greetings</span>
        </a>

        <div class="sidebar-section-title">System</div>

        <a href="{{ route('logout') }}" class="menu-item text-danger" title="Logout">
            <div class="menu-icon-box text-danger">
                <i class="bi bi-box-arrow-right"></i>
            </div>
            <span class="menu-text">Logout</span>
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

