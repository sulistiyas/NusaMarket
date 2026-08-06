@if (isset($breadcrumbs) && is_array($breadcrumbs))
    <nav class="breadcrumb-container" aria-label="breadcrumb">
        <a href="{{ route('dashboard') }}" class="breadcrumb-item">
            <i class="fas fa-home"></i> Dashboard
        </a>
        @foreach ($breadcrumbs as $name => $link)
            <span class="breadcrumb-separator">/</span>
            @if ($loop->last)
                <span class="breadcrumb-item active">{{ $name }}</span>
            @else
                <a href="{{ $link }}" class="breadcrumb-item">{{ $name }}</a>
            @endif
        @endforeach
    </nav>
@endif
