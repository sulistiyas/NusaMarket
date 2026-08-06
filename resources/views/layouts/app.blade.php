<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'NusaMarket')) - NusaMarket</title>

    {{-- Font & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Assets: @vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- CSS per-halaman --}}
    @stack('styles')
</head>
<body x-data="{ sidebarOpen: false }">

    {{-- Overlay saat sidebar mobile terbuka --}}
    <div
        class="sidebar-overlay"
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition.opacity
        style="display: none;"
    ></div>

    @include('components.header')

    <div class="layout-wrapper">
        <aside class="sidebar" :class="{ 'open': sidebarOpen }">
            @include('components.sidebar')
        </aside>

        <main class="main-content">
            @include('components.breadcrumb')
            @include('components.alert')

            @yield('content')
        </main>
    </div>

    @include('components.footer')

    {{-- JS per-halaman --}}
    @stack('scripts')

</body>
</html>
