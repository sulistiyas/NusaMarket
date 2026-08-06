@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="mb-6">
    <h1>Tambah Produk Baru</h1>
    <p>Isi formulir berikut untuk menambahkan produk baru ke katalog marketplace.</p>
</div>

<div class="card card-container-max">
    <div class="card-header">
        <h2 class="card-title">
            <i class="fas fa-plus-circle"></i> Formulir Tambah Produk
        </h2>
        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm btn-pill">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('products.store') }}" method="POST" class="card-body" x-data="{ imageUrl: '{{ old('images.0') }}' }">
        @csrf

        {{-- Seksi 1: Informasi Utama --}}
        <div class="form-section-title">
            <i class="fas fa-info-circle"></i> Informasi Utama Produk
        </div>

        <div class="form-group">
            <label for="name" class="form-label">Nama Produk <span class="required">*</span></label>
            <input 
                type="text" 
                name="name" 
                id="name" 
                class="form-control" 
                placeholder="Contoh: Kemeja Batik Solo Cotton Premium" 
                value="{{ old('name') }}" 
                required
            >
            @error('name')
                <span class="form-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="category_id" class="form-label">Kategori Produk <span class="required">*</span></label>
            <select name="category_id" id="category_id" class="form-control select2" data-placeholder="-- Pilih Kategori Produk --" required>
                <option value=""></option>
                @foreach($categories as $category)
                    <option 
                        value="{{ $category->id }}" 
                        data-icon="{{ $category->icon ?? 'fa-folder' }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <span class="form-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-section-divider"></div>

        {{-- Seksi 2: Harga, Stok & Spesifikasi --}}
        <div class="form-section-title">
            <i class="fas fa-tags"></i> Harga & Inventaris
        </div>

        <div class="form-grid cols-3">
            <div class="form-group">
                <label for="price" class="form-label">Harga Jual <span class="required">*</span></label>
                <div class="input-group">
                    <span class="input-group-addon">Rp</span>
                    <input 
                        type="number" 
                        name="price" 
                        id="price" 
                        class="form-control" 
                        placeholder="150000" 
                        value="{{ old('price') }}" 
                        min="0" 
                        required
                    >
                </div>
                @error('price')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="stock" class="form-label">Jumlah Stok <span class="required">*</span></label>
                <div class="input-group">
                    <input 
                        type="number" 
                        name="stock" 
                        id="stock" 
                        class="form-control" 
                        placeholder="10" 
                        value="{{ old('stock', 10) }}" 
                        min="0" 
                        required
                    >
                    <span class="input-group-addon">Unit</span>
                </div>
                @error('stock')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="weight" class="form-label">Berat Produk <span class="required">*</span></label>
                <div class="input-group">
                    <input 
                        type="number" 
                        name="weight" 
                        id="weight" 
                        class="form-control" 
                        placeholder="250" 
                        value="{{ old('weight', 250) }}" 
                        min="1" 
                        required
                    >
                    <span class="input-group-addon">Gram</span>
                </div>
                @error('weight')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-section-divider"></div>

        {{-- Seksi 3: Media & Deskripsi --}}
        <div class="form-section-title">
            <i class="fas fa-image"></i> Media & Deskripsi
        </div>

        <div class="form-group">
            <label for="image_url" class="form-label">URL Gambar Utama Produk</label>
            <input 
                type="url" 
                name="images[]" 
                id="image_url" 
                class="form-control" 
                placeholder="https://images.unsplash.com/photo-..." 
                x-model="imageUrl"
                value="{{ old('images.0') }}"
            >
            <span class="form-feedback text-muted">Tempel URL gambar langsung (Direct Image Link).</span>

            <div class="image-preview-card" x-show="imageUrl && imageUrl.trim() !== ''">
                <template x-if="imageUrl && imageUrl.trim() !== ''">
                    <img :src="imageUrl" class="image-preview-thumb" alt="Preview Gambar" x-on:error="$el.src = 'https://placehold.co/100x100?text=Gambar+Error'">
                </template>
                <div class="image-preview-info">
                    <span class="filename" x-text="imageUrl"></span>
                    <span class="filesize"><i class="fas fa-check-circle" style="color: var(--success)"></i> Live Link Preview</span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Deskripsi Lengkap Produk</label>
            <textarea 
                name="description" 
                id="description" 
                class="form-control" 
                placeholder="Jelaskan spesifikasi, keunggulan, bahan, dan informasi penting produk Anda..."
            >{{ old('description') }}</textarea>
        </div>

        <div class="card-footer">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Produk
            </button>
        </div>
    </form>
</div>
@endsection
