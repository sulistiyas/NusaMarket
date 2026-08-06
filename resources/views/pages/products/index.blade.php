@extends('layouts.app')

@section('title', 'Manajemen Produk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/datatable.css') }}">
@endpush

@section('content')
<div class="flex justify-between items-center mb-4">
    <div>
        <h1>Manajemen Produk</h1>
        <p>Kelola katalog produk toko dan inventaris NusaMarket.</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Produk Baru
    </a>
</div>

<div
    x-data="datatable({
        url: '{{ url('/api/v1/products') }}',
        columns: ['name', 'price', 'stock', 'created_at'],
        perPage: 10
    })"
    x-init="fetchData()"
>
    <div class="dt-responsive">
        {{-- Search & Filter Toolbar --}}
        <div class="dt-toolbar">
            <div class="dt-search">
                <i class="fas fa-search"></i>
                <input 
                    type="text" 
                    x-model="search" 
                    @input.debounce.400ms="fetchData(true)" 
                    placeholder="Cari nama produk..."
                >
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <label class="form-label" style="margin: 0;">Tampilkan:</label>
                    <select class="form-control" style="width: auto; min-height: 38px;" x-model="perPage" @change="fetchData(true)">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <table class="dt-table">
            <thead>
                <tr>
                    <th @click="sort('name')" class="sortable">
                        Produk <span x-text="sortIcon('name')"></span>
                    </th>
                    <th>Kategori</th>
                    <th @click="sort('price')" class="sortable">
                        Harga <span x-text="sortIcon('price')"></span>
                    </th>
                    <th @click="sort('stock')" class="sortable">
                        Stok <span x-text="sortIcon('stock')"></span>
                    </th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="row in rows" :key="row.id">
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <template x-if="row.images && row.images.length > 0">
                                    <img :src="row.images[0]" style="width: 40px; height: 40px; border-radius: 6px; object-fit: cover;">
                                </template>
                                <template x-if="!row.images || row.images.length === 0">
                                    <div style="width: 40px; height: 40px; border-radius: 6px; background: var(--bg-light); display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                                        <i class="fas fa-box"></i>
                                    </div>
                                </template>
                                <div>
                                    <a :href="`{{ url('/products') }}/${row.id}`" style="font-weight: 600;" x-text="row.name"></a>
                                    <div style="font-size: var(--text-xs); color: var(--text-muted);" x-text="row.store_name"></div>
                                </div>
                            </div>
                        </td>
                        <td x-text="row.category ? row.category.name : '-'"></td>
                        <td>
                            <strong style="color: var(--primary-deeper);" x-text="row.formatted_price"></strong>
                        </td>
                        <td>
                            <span class="badge" :class="row.stock > 10 ? 'badge-primary' : (row.stock > 0 ? 'badge-warning' : 'badge-danger')" x-text="`${row.stock} unit`"></span>
                        </td>
                        <td>
                            <template x-if="row.is_active">
                                <span class="badge badge-success">AKTIF</span>
                            </template>
                            <template x-if="!row.is_active">
                                <span class="badge badge-danger">NON-AKTIF</span>
                            </template>
                        </td>
                        <td class="text-right">
                            <div class="flex justify-end gap-2">
                                <a :href="`{{ url('/products') }}/${row.id}/edit`" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form :action="`{{ url('/products') }}/${row.id}`" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                </template>
                <tr x-show="rows.length === 0 && !loading">
                    <td colspan="6" class="text-center" style="padding: 30px; color: var(--text-muted);">
                        <i class="fas fa-box-open fa-2x mb-2" style="display: block;"></i>
                        Belum ada produk yang ditemukan.
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="dt-pagination">
            <div>
                Menampilkan <strong x-text="firstItem()"></strong> - <strong x-text="lastItem()"></strong> dari <strong x-text="totalItems"></strong> produk
            </div>
            <div class="dt-pagination-buttons">
                <button class="btn-page" @click="prevPage()" :disabled="currentPage === 1">
                    <i class="fas fa-chevron-left"></i> Prev
                </button>
                <template x-for="p in pageNumbers()" :key="p">
                    <button class="btn-page" :class="{ 'active': p === currentPage }" @click="gotoPage(p)" x-text="p"></button>
                </template>
                <button class="btn-page" @click="nextPage()" :disabled="currentPage === totalPages">
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
