@extends('layouts.app')

@section('title', 'Edit Produk - ' . $product->name)

@section('content')
<div class="mb-4">
    <h1>Edit Produk</h1>
    <p>Perbarui informasi produk {{ $product->name }}.</p>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-edit"></i> Edit {{ $product->name }}</h2>
        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <form action="{{ route('products.update', $product) }}" method="POST" class="card-body form-grid">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="form-label">Nama Produk <span class="required">*</span></label>
            <input 
                type="text" 
                name="name" 
                id="name" 
                class="form-control" 
                value="{{ old('name', $product->name) }}" 
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
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                    value="{{ old('price', $product->price) }}" 
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
                    value="{{ old('stock', $product->stock) }}" 
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
                    value="{{ old('weight', $product->weight) }}" 
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
                value="{{ old('images.0', $product->images[0] ?? '') }}"
            >
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Deskripsi Lengkap Produk</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="card-footer text-right" style="margin: 0 -20px -20px -20px;">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Perbarui Produk
            </button>
        </div>
    </form>
</div>
@endsection
