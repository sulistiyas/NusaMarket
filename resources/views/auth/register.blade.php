<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun Baru - NusaMarket</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-container">
        <div class="auth-card" style="max-width: 520px;">
            <div class="auth-header">
                <a href="{{ url('/') }}" class="auth-logo">
                    <i class="fas fa-store"></i> NusaMarket
                </a>
                <h1 class="auth-title">Daftar Akun</h1>
                <p class="auth-subtitle">Bergabung dengan ekosistem marketplace Indonesia</p>
            </div>

            @include('components.alert')

            <form action="{{ route('register.post') }}" method="POST" class="form-grid">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        class="form-control" 
                        placeholder="Nama lengkap Anda" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus
                    >
                    @error('name')
                        <span class="form-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email <span class="required">*</span></label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        class="form-control" 
                        placeholder="contoh@email.com" 
                        value="{{ old('email') }}" 
                        required
                    >
                    @error('email')
                        <span class="form-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-grid cols-2">
                    <div class="form-group">
                        <label for="password" class="form-label">Kata Sandi <span class="required">*</span></label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="form-control" 
                            placeholder="Minimal 8 karakter" 
                            required
                        >
                        @error('password')
                            <span class="form-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Sandi <span class="required">*</span></label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            class="form-control" 
                            placeholder="Ulangi kata sandi" 
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="role" class="form-label">Tipe Akun <span class="required">*</span></label>
                    <select name="role" id="role" class="form-control" required>
                        <option value="buyer" {{ old('role') === 'buyer' ? 'selected' : '' }}>Pembeli (Buyer)</option>
                        <option value="seller" {{ old('role') === 'seller' ? 'selected' : '' }}>Penjual (Seller - Buka Toko)</option>
                    </select>
                    @error('role')
                        <span class="form-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-lg mt-2">
                    <i class="fas fa-user-plus"></i> Buat Akun Sekarang
                </button>
            </form>

            <div class="auth-footer">
                <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
            </div>
        </div>
    </div>
</body>
</html>
