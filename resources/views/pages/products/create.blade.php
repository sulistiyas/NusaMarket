@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="mb-4">
    <h1>Tambah Produk Baru</h1>
    <p>Isi formulir berikut untuk menambahkan produk baru ke marketplace.</p>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-box"></i> Informasi Produk</h2>
        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <form action="{{ route('products.store') }}" method="POST" class="card-body form-grid">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">Nama Produk <span class="required">*</span></label>
            <input 
                type="text" 
                name="name" 
                id="name" 
                class="form-control" 
                placeholder="Contoh: Kemeja Batik Solo Cotton" 
                value="{{ old('name') }}" 
                required
            >
            @error('name')
                <span class="form-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-grid cols-2">
            <div class="form-group">
                <label for="category_id" class="form-label">Kategori <span class="required">*</span></label>
                <select name="category_id" id="category_id" class="select2" data-placeholder="Pilih Kategori" required>
                    <option value=""></option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="price" class="form-label">Harga (Rp) <span class="required">*</span></label>
                <input 
                    type="number" 
                    name="price" 
                    id="price" 
                    class="form-control" 
                    placeholder="Contoh: 150000" 
                    value="{{ old('price') }}" 
                    min="0" 
                    required
                >
                @error('price')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-grid cols-2">
            <div class="form-group">
                <label for="stock" class="form-label">Jumlah Stok <span class="required">*</span></label>
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
                @error('stock')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="weight" class="form-label">Berat Produk (gram) <span class="required">*</span></label>
                <input 
                    type="number" 
                    name="weight" 
                    id="weight" 
                    class="form-control" 
                    placeholder="500" 
                    value="{{ old('weight', 250) }}" 
                    min="1" 
                    required
                >
                @error('weight')
                    <span class="form-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="image_url" class="form-label">URL Gambar Utama Produk</label>
            <input 
                type="url" 
                name="images[]" 
                id="image_url" 
                class="form-control" 
                placeholder="https://images.unsplash.com/photo-..." 
                value="{{ old('images.0') }}"
            >
            <span style="font-size: var(--text-xs); color: var(--text-muted); margin-top: 4px;">Tempel URL gambar langsung (Direct Image Link).</span>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Deskripsi Lengkap Produk</label>
            <textarea name="description" id="description" class="form-control" placeholder="Jelaskan spesifikasi, bahan, dan keunggulan produk Anda...">{{ old('description') }}</textarea>
        </div>

        <div class="card-footer text-right" style="margin: 0 -20px -20px -20px;">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Produk
            </button>
        </div>
    </form>
</div>
@endsection
