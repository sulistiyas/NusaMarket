@if (session('success'))
    <div data-flash-success="{{ session('success') }}"></div>
@endif

@if (session('error'))
    <div data-flash-error="{{ session('error') }}"></div>
@endif

@if (session('info'))
    <div data-flash-info="{{ session('info') }}"></div>
@endif

@if (session('warning'))
    <div data-flash-warning="{{ session('warning') }}"></div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const successMsg = document.querySelector('[data-flash-success]')?.dataset.flashSuccess;
        const errorMsg = document.querySelector('[data-flash-error]')?.dataset.flashError;
        const infoMsg = document.querySelector('[data-flash-info]')?.dataset.flashInfo;
        const warningMsg = document.querySelector('[data-flash-warning]')?.dataset.flashWarning;

        if (successMsg && window.Toast) window.Toast.success(successMsg);
        if (errorMsg && window.Toast) window.Toast.error(errorMsg);
        if (infoMsg && window.Toast) window.Toast.info(infoMsg);
        if (warningMsg && window.Toast) window.Toast.warning(warningMsg);
    });
</script>
@endpush
