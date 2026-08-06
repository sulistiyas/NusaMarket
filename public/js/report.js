/**
 * report.js — Modul Laporan NusaMarket
 * Chart.js integration & Alpine.js component
 * Data diambil lewat AJAX dari API endpoints
 */

// ============================================================
// Inisialisasi Charts
// ============================================================
document.addEventListener('DOMContentLoaded', function () {
    const scriptTag  = document.currentScript || document.querySelector('script[data-start-date]');
    const startDate  = scriptTag?.dataset.startDate || '';
    const endDate    = scriptTag?.dataset.endDate   || '';
    const year       = parseInt(scriptTag?.dataset.year  || new Date().getFullYear(), 10);

    // Chart instances — disimpan agar bisa di-destroy & rebuild saat filter berubah
    const charts = {};

    // Warna Blue Ocean palette
    const palette = {
        blue:   'rgba(30, 111, 217, 0.85)',
        ocean:  'rgba(14, 116, 144, 0.85)',
        purple: 'rgba(124, 58, 237, 0.85)',
        orange: 'rgba(217, 119, 6, 0.85)',
        green:  'rgba(22, 163, 74, 0.85)',
        red:    'rgba(220, 38, 38, 0.85)',
        blueLight:   'rgba(30, 111, 217, 0.15)',
    };

    const statusColors = [palette.orange, palette.blue, palette.green, palette.red];

    // ── Helper: fetch JSON dari API (session cookie auth) ──
    async function apiFetch(url) {
        const res = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept':           'application/json',
            },
            credentials: 'same-origin',
        });
        if (!res.ok) throw new Error('API error ' + res.status);
        const json = await res.json();
        return json.data;
    }

    // ── Destroy chart jika sudah ada ──
    function destroyChart(id) {
        if (charts[id]) {
            charts[id].destroy();
            delete charts[id];
        }
    }

    // ============================================================
    // 1. Revenue Line Chart
    // ============================================================
    async function loadRevenueChart(selectedYear) {
        try {
            const data = await apiFetch(`/api/v1/reports/chart/revenue?year=${selectedYear}`);
            destroyChart('revenue');

            const ctx = document.getElementById('chart-revenue');
            if (!ctx) return;

            charts['revenue'] = new Chart(ctx, {
                type: 'line',
                data: {
                    labels:   data.labels,
                    datasets: [{
                        label:           'Revenue (Rp)',
                        data:            data.revenue,
                        borderColor:     palette.blue,
                        backgroundColor: blueGradient(ctx),
                        borderWidth:     2.5,
                        fill:            true,
                        tension:         0.4,
                        pointRadius:     4,
                        pointBackgroundColor: palette.blue,
                        pointHoverRadius:     6,
                    }],
                },
                options: {
                    responsive:          true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => 'Rp ' + Number(ctx.parsed.y).toLocaleString('id-ID'),
                            },
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: v => 'Rp ' + Number(v).toLocaleString('id-ID'),
                                font: { size: 11 },
                            },
                            grid: { color: 'rgba(0,0,0,0.05)' },
                        },
                        x: {
                            ticks: { font: { size: 11 } },
                            grid: { display: false },
                        },
                    },
                },
            });
        } catch (e) {
            console.error('Revenue chart error:', e);
        }
    }

    // ============================================================
    // 2. Status Donut Chart
    // ============================================================
    async function loadStatusChart() {
        try {
            const data = await apiFetch(`/api/v1/reports/chart/status?start_date=${startDate}&end_date=${endDate}`);
            destroyChart('status');

            const ctx = document.getElementById('chart-status');
            if (!ctx) return;

            charts['status'] = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels:   data.labels,
                    datasets: [{
                        data:            data.data,
                        backgroundColor: statusColors,
                        borderColor:     '#fff',
                        borderWidth:     3,
                        hoverOffset:     8,
                    }],
                },
                options: {
                    responsive:          true,
                    maintainAspectRatio: true,
                    cutout:              '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels:   { padding: 16, font: { size: 12 }, usePointStyle: true },
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.label}: ${ctx.parsed} pesanan`,
                            },
                        },
                    },
                },
            });
        } catch (e) {
            console.error('Status chart error:', e);
        }
    }

    // ============================================================
    // 3. Top Products Horizontal Bar Chart
    // ============================================================
    async function loadTopProductsChart() {
        try {
            const data = await apiFetch(`/api/v1/reports/chart/top-products?start_date=${startDate}&end_date=${endDate}`);
            destroyChart('topProducts');

            const ctx = document.getElementById('chart-top-products');
            if (!ctx) return;

            charts['topProducts'] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels:   data.labels,
                    datasets: [{
                        label:           'Unit Terjual',
                        data:            data.qty,
                        backgroundColor: [palette.blue, palette.ocean, palette.purple, palette.orange, palette.green],
                        borderRadius:    6,
                        borderSkipped:   false,
                    }],
                },
                options: {
                    indexAxis:           'y',
                    responsive:          true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.parsed.x} unit`,
                            },
                        },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { font: { size: 11 } },
                            grid: { color: 'rgba(0,0,0,0.05)' },
                        },
                        y: {
                            ticks: {
                                font: { size: 11 },
                                callback: function(val) {
                                    const label = this.getLabelForValue(val);
                                    return label.length > 18 ? label.substring(0, 16) + '…' : label;
                                },
                            },
                            grid: { display: false },
                        },
                    },
                },
            });
        } catch (e) {
            console.error('Top products chart error:', e);
        }
    }

    // ============================================================
    // 4. User Growth Bar Chart
    // ============================================================
    async function loadUserGrowthChart(selectedYear) {
        try {
            const data = await apiFetch(`/api/v1/reports/chart/user-growth?year=${selectedYear}`);
            destroyChart('userGrowth');

            const ctx = document.getElementById('chart-user-growth');
            if (!ctx) return;

            charts['userGrowth'] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels:   data.labels,
                    datasets: [{
                        label:           'Pengguna Baru',
                        data:            data.counts,
                        backgroundColor: palette.ocean,
                        borderRadius:    6,
                        borderSkipped:   false,
                        hoverBackgroundColor: palette.blue,
                    }],
                },
                options: {
                    responsive:          true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.parsed.y} pengguna baru`,
                            },
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { font: { size: 11 }, stepSize: 1 },
                            grid: { color: 'rgba(0,0,0,0.05)' },
                        },
                        x: {
                            ticks: { font: { size: 11 } },
                            grid: { display: false },
                        },
                    },
                },
            });
        } catch (e) {
            console.error('User growth chart error:', e);
        }
    }

    // ── Blue gradient helper ──
    function blueGradient(ctx) {
        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(30, 111, 217, 0.3)');
        gradient.addColorStop(1, 'rgba(30, 111, 217, 0.01)');
        return gradient;
    }

    // ============================================================
    // Load semua chart pertama kali
    // ============================================================
    loadRevenueChart(year);
    loadStatusChart();
    loadTopProductsChart();
    loadUserGrowthChart(year);

    // ── Year selector listeners ──
    document.getElementById('year-revenue')?.addEventListener('change', function () {
        loadRevenueChart(parseInt(this.value, 10));
        document.getElementById('filter-year').value = this.value;
    });

    document.getElementById('year-users')?.addEventListener('change', function () {
        loadUserGrowthChart(parseInt(this.value, 10));
    });

    // ── Update export links saat tanggal berubah ──
    function updateExportLinks() {
        const sd = document.getElementById('start_date')?.value || startDate;
        const ed = document.getElementById('end_date')?.value   || endDate;

        const pdfLink   = document.getElementById('btn-export-pdf');
        const excelLink = document.getElementById('btn-export-excel');

        if (pdfLink) {
            const base = pdfLink.href.split('?')[0];
            pdfLink.href = `${base}?start_date=${sd}&end_date=${ed}`;
        }
        if (excelLink) {
            const base = excelLink.href.split('?')[0];
            excelLink.href = `${base}?start_date=${sd}&end_date=${ed}`;
        }
    }

    document.getElementById('start_date')?.addEventListener('change', updateExportLinks);
    document.getElementById('end_date')?.addEventListener('change', updateExportLinks);
});

// ============================================================
// Alpine.js Component — Period Summary Table
// ============================================================
function reportTable({ startDate, endDate, groupBy }) {
    return {
        startDate,
        endDate,
        groupBy,
        rows:    [],
        loading: false,

        async fetchData() {
            this.loading = true;
            this.rows    = [];
            try {
                const url  = `/api/v1/reports/table/summary?start_date=${this.startDate}&end_date=${this.endDate}&group_by=${this.groupBy}`;
                const res  = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    credentials: 'same-origin',
                });
                const json = await res.json();
                this.rows = json.data || [];
            } catch (e) {
                console.error('Period table error:', e);
                this.rows = [];
            } finally {
                this.loading = false;
            }
        },
    };
}
