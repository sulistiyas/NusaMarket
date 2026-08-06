// =============================================
// page-loading.js — Page Transition Loading Overlay
// NusaMarket Blue Ocean Theme
// Dipanggil oleh halaman server-side (non-Alpine datatable)
// =============================================

(function () {
    const overlay = document.getElementById('pageLoadingOverlay');
    if (!overlay) return;

    function showOverlay() {
        overlay.classList.add('active');
    }

    // Trigger saat semua link navigasi di-klik (filter pill, segmented btn, pagination)
    document.querySelectorAll(
        'a.filter-pill, a.segmented-btn, a.page-link, .dt-pagination-links a'
    ).forEach(function (el) {
        el.addEventListener('click', function (e) {
            // Abaikan jika ada modifier key (open new tab, dll)
            if (e.ctrlKey || e.metaKey || e.shiftKey) return;
            showOverlay();
        });
    });

    // Trigger saat form di-submit (search, sort)
    document.querySelectorAll('form.order-toolbar, form[data-loading="true"]').forEach(function (form) {
        form.addEventListener('submit', function () {
            showOverlay();
        });
    });

    // Trigger saat select sort di-change (tidak butuh submit manual)
    document.querySelectorAll('.sort-select').forEach(function (sel) {
        sel.addEventListener('change', function () {
            showOverlay();
        });
    });

    // Trigger saat input search di-change (onchange="this.form.submit()")
    document.querySelectorAll('input[onchange]').forEach(function (inp) {
        inp.addEventListener('change', function () {
            showOverlay();
        });
    });
})();
