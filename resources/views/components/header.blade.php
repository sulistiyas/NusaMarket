<header class="main-header">
    <div class="flex items-center gap-4">
        <button class="sidebar-toggle" @click="sidebarOpen = !sidebarOpen" aria-label="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <a href="{{ url('/') }}" class="header-brand">
            <i class="fas fa-store"></i>
            <span>NusaMarket</span>
        </a>
    </div>

    <div class="header-nav">
        @auth
            <div class="user-dropdown" x-data="{ open: false }" @click="open = !open" @click.outside="open = false">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <span class="user-name">{{ auth()->user()->name ?? 'Pengguna' }}</span>
                <i class="fas fa-chevron-down" style="font-size: 0.8rem; color: #ffffff;"></i>

                <div x-show="open" x-transition class="card" style="position: absolute; right: 0; top: 110%; width: 200px; display: none; z-index: 1010;">
                    <div class="card-body" style="padding: 10px;">
                        <a href="{{ route('dashboard') }}" class="btn btn-ghost" style="width: 100%; justify-content: flex-start;">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <form action="{{ route('auth.logout') }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" style="width: 100%; justify-content: flex-start;">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-ghost" style="color: #ffffff;">
                <i class="fas fa-sign-in-alt"></i> Masuk
            </a>
            <a href="{{ route('register') }}" class="btn btn-ocean btn-sm">
                <i class="fas fa-user-plus"></i> Daftar
            </a>
        @endauth
    </div>
</header>
