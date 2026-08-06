<ul class="sidebar-menu">
    <li class="sidebar-heading">Utama</li>
    <li>
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <li class="sidebar-heading">Marketplace</li>
    <li>
        <a href="{{ route('marketplace.index') }}" class="sidebar-link {{ request()->routeIs('marketplace.*') ? 'active' : '' }}">
            <i class="fas fa-shopping-bag"></i>
            <span>Katalog Produk</span>
        </a>
    </li>
    <li>
        <a href="{{ route('cart.index') }}" class="sidebar-link {{ request()->routeIs('cart.*') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i>
            <span>Keranjang</span>
        </a>
    </li>

    <li class="sidebar-heading">Manajemen (Seller/Admin)</li>
    <li>
        <a href="{{ route('categories.index') }}" class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i>
            <span>Kategori Produk</span>
        </a>
    </li>
    <li>
        <a href="{{ route('products.index') }}" class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="fas fa-box-open"></i>
            <span>Manajemen Produk</span>
        </a>
    </li>
    <li>
        <a href="{{ route('orders.index') }}" class="sidebar-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <i class="fas fa-receipt"></i>
            <span>Pesanan Masuk</span>
        </a>
    </li>
</ul>
