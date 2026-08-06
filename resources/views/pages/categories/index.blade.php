@extends('layouts.app')

@section('title', 'Kategori Produk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/datatable.css') }}">
@endpush

@section('content')
<div class="flex justify-between items-center mb-4">
    <div>
        <h1>Kategori Produk</h1>
        <p>Kelola kategori produk untuk marketplace NusaMarket.</p>
    </div>
    <button class="btn btn-primary" @click="$dispatch('open-modal-category')">
        <i class="fas fa-plus"></i> Tambah Kategori
    </button>
</div>

<div
    x-data="datatable({
        url: '{{ url('/api/v1/categories') }}',
        columns: ['name', 'slug', 'is_active', 'created_at'],
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
                    placeholder="Cari nama kategori..."
                >
            </div>
            <div class="flex items-center gap-2">
                <label class="form-label form-label-inline">Tampilkan:</label>
                <select class="form-control select-per-page" x-model="perPage" @change="fetchData(true)">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <table class="dt-table">
            <thead>
                <tr>
                    <th @click="sort('name')" class="sortable">
                        Nama Kategori <span x-text="sortIcon('name')"></span>
                    </th>
                    <th @click="sort('slug')" class="sortable">
                        Slug <span x-text="sortIcon('slug')"></span>
                    </th>
                    <th>Deskripsi</th>
                    <th @click="sort('is_active')" class="sortable">
                        Status <span x-text="sortIcon('is_active')"></span>
                    </th>
                    <th @click="sort('created_at')" class="sortable">
                        Tanggal Dibuat <span x-text="sortIcon('created_at')"></span>
                    </th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="row in rows" :key="row.id">
                    <tr>
                        <td>
                            <strong x-text="row.name"></strong>
                        </td>
                        <td x-text="row.slug"></td>
                        <td x-text="row.description || '-'"></td>
                        <td>
                            <template x-if="row.is_active">
                                <span class="badge badge-success">AKTIF</span>
                            </template>
                            <template x-if="!row.is_active">
                                <span class="badge badge-danger">NON-AKTIF</span>
                            </template>
                        </td>
                        <td x-text="row.created_at ? new Date(row.created_at).toLocaleDateString('id-ID') : '-'"></td>
                        <td class="text-right">
                            <form :action="`{{ url('/categories') }}/${row.id}`" method="POST" class="form-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                </template>
                <tr x-show="rows.length === 0 && !loading">
                    <td colspan="6" class="text-center empty-cell">
                        <i class="fas fa-folder-open fa-2x mb-2 empty-icon"></i>
                        Tidak ada data kategori ditemukan.
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="dt-pagination">
            <div>
                Menampilkan <strong x-text="firstItem()"></strong> - <strong x-text="lastItem()"></strong> dari <strong x-text="totalItems"></strong> kategori
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

{{-- Modal Create Category --}}
<div x-data="{ open: false }" @open-modal-category.window="open = true">
    <div x-show="open" x-cloak class="modal-overlay" x-transition.opacity>
        <div class="card modal-card" @click.outside="open = false">
            <div class="card-header">
                <h3 class="card-title">Tambah Kategori Baru</h3>
                <button class="btn btn-ghost btn-sm" @click="open = false"><i class="fas fa-times"></i></button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Kategori <span class="required">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" required placeholder="Contoh: Elektronik">
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control" placeholder="Penjelasan singkat kategori"></textarea>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="button" class="btn btn-secondary" @click="open = false">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
