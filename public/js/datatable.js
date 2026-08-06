// =============================================
// datatable.js — Alpine.js Datatable Component
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

        async fetchData() {
            if (!this.url) return;
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
                    this.rows = response.data.data;
                    if (response.data.meta) {
                        this.currentPage = response.data.meta.current_page;
                        this.totalPages = response.data.meta.last_page;
                        this.totalItems = response.data.meta.total;
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

        sort(column) {
            if (this.sortBy === column) {
                this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortBy = column;
                this.sortOrder = 'asc';
            }
            this.fetchData();
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
        }
    }));
});
