<header class="main-header">
    <div class="flex items-center gap-4">
        <button class="sidebar-toggle" @click="sidebarOpen = !sidebarOpen" aria-label="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <a href="{{ url('/') }}" class="header-brand">
            <div class="header-brand-icon">
                <i class="fas fa-store"></i>
            </div>
            <span>NusaMarket</span>
        </a>
    </div>

    <div class="header-nav">
        @auth
            <div class="user-dropdown" x-data="{ open: false }" @click="open = !open" @click.outside="open = false">
                <div class="user-avatar-wrapper">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="user-online-dot"></div>
                </div>
                
                <div class="user-details">
                    <span class="user-name">{{ auth()->user()->name ?? 'Pengguna' }}</span>
                    @if(auth()->user()->role ?? false)
                        <span class="user-role-badge">{{ strtoupper(auth()->user()->role) }}</span>
                    @endif
                </div>

                <i class="fas fa-chevron-down user-dropdown-icon"></i>

                {{-- Modern User Dropdown Menu --}}
                <div x-show="open" x-transition class="user-menu-card" style="display: none;">
                    <div class="user-menu-header">
                        <div class="user-menu-name">{{ auth()->user()->name ?? 'Pengguna' }}</div>
                        <div class="user-menu-email">{{ auth()->user()->email ?? 'user@nusamarket.com' }}</div>
                    </div>
                    <div class="user-menu-divider"></div>
                    <div class="user-menu-body">
                        <a href="{{ route('dashboard') }}" class="user-menu-link">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                        <form action="{{ route('auth.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="user-menu-link text-danger">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Keluar / Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-ghost" style="color: #ffffff;">
                <i class="fas fa-sign-in-alt"></i> Masuk
            </a>
            <a href="{{ route('register') }}" class="btn btn-ocean btn-sm btn-pill">
                <i class="fas fa-user-plus"></i> Daftar
            </a>
        @endauth
    </div>
</header>
