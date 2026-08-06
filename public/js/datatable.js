// =============================================
// datatable.js — Alpine.js Datatable Component
// NusaMarket Blue Ocean Theme
// =============================================

document.addEventListener('alpine:init', () => {
    Alpine.data('datatable', (config) => ({
        url: config.url || '',
        columns: config.columns || [],
        perPage: config.perPage || 10,
        search: '',
        sortBy: config.sortBy || 'id',
        sortOrder: config.sortOrder || 'desc',
        currentPage: 1,
        totalPages: 1,
        totalItems: 0,
        rows: [],
        loading: false,

        async fetchData(resetPage = false) {
            if (!this.url) return;
            if (resetPage) {
                this.currentPage = 1;
            }

            this.loading = true;
            try {
                const response = await axios.get(this.url, {
                    params: {
                        page: this.currentPage,
                        per_page: this.perPage,
                        search: this.search,
                        sort_by: this.sortBy,
                        sort_order: this.sortOrder
                    }
                });

                if (response.data && response.data.success) {
                    this.rows = response.data.data || [];
                    if (response.data.meta) {
                        this.currentPage = response.data.meta.current_page || 1;
                        this.totalPages = Math.max(1, response.data.meta.last_page || 1);
                        this.totalItems = response.data.meta.total || 0;
                    }
                }
            } catch (error) {
                console.error('Error fetching datatable data:', error);
                if (window.Toast) {
                    window.Toast.error('Gagal mengambil data tabel.');
                }
            } finally {
                this.loading = false;
            }
        },

        resetAndFetch() {
            this.fetchData(true);
        },

        sort(column) {
            if (this.sortBy === column) {
                this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortBy = column;
                this.sortOrder = 'asc';
            }
            this.fetchData(true);
        },

        sortIcon(column) {
            if (this.sortBy !== column) return '↕';
            return this.sortOrder === 'asc' ? '↑' : '↓';
        },

        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.fetchData();
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.fetchData();
            }
        },

        gotoPage(page) {
            if (page >= 1 && page <= this.totalPages && page !== this.currentPage) {
                this.currentPage = page;
                this.fetchData();
            }
        },

        firstItem() {
            if (this.totalItems === 0) return 0;
            return (this.currentPage - 1) * this.perPage + 1;
        },

        lastItem() {
            if (this.totalItems === 0) return 0;
            return Math.min(this.currentPage * this.perPage, this.totalItems);
        },

        pageNumbers() {
            const pages = [];
            const start = Math.max(1, this.currentPage - 2);
            const end = Math.min(this.totalPages, this.currentPage + 2);
            
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            return pages;
        }
    }));
});
