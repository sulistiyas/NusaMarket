<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk - NusaMarket</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-container" x-data="{
        email: '{{ old('email') }}',
        password: '',
        showPassword: false,
        activeDemo: null,
        fillDemo(demoEmail, demoPass, role) {
            this.email = demoEmail;
            this.password = demoPass;
            this.activeDemo = role;
        }
    }">
        <div class="auth-card">
            <div class="auth-header">
                <a href="{{ url('/') }}" class="auth-logo">
                    <span class="auth-logo-icon">
                        <i class="fas fa-store"></i>
                    </span>
                    <span>NusaMarket</span>
                </a>
                <h1 class="auth-title">Selamat Datang Kembali</h1>
                <p class="auth-subtitle">Masuk ke akun NusaMarket Anda untuk melanjutkan</p>
            </div>

            @include('components.alert')

            <!-- Quick Fill Demo Accounts Panel -->
            <div class="demo-panel">
                <div class="demo-panel-title">
                    <span><i class="fas fa-bolt"></i> Akun Uji Coba (Demo)</span>
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="demo-buttons-grid">
                    <button type="button" 
                            class="demo-btn demo-btn-admin" 
                            :class="{ 'active': activeDemo === 'admin' }"
                            @click="fillDemo('admin@nusamarket.com', 'password', 'admin')">
                        <i class="fas fa-user-gear"></i>
                        <span>Admin</span>
                    </button>

                    <button type="button" 
                            class="demo-btn demo-btn-seller" 
                            :class="{ 'active': activeDemo === 'seller' }"
                            @click="fillDemo('seller@nusamarket.com', 'password', 'seller')">
                        <i class="fas fa-store"></i>
                        <span>Penjual</span>
                    </button>

                    <button type="button" 
                            class="demo-btn demo-btn-buyer" 
                            :class="{ 'active': activeDemo === 'buyer' }"
                            @click="fillDemo('buyer@nusamarket.com', 'password', 'buyer')">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Pembeli</span>
                    </button>
                </div>
            </div>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email <span class="required">*</span></label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="form-control" 
                            placeholder="contoh@email.com" 
                            x-model="email"
                            required 
                            autofocus
                        >
                    </div>
                    @error('email')
                        <span class="form-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi <span class="required">*</span></label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            :type="showPassword ? 'text' : 'password'" 
                            name="password" 
                            id="password" 
                            class="form-control" 
                            placeholder="••••••••" 
                            x-model="password"
                            required
                        >
                        <button type="button" 
                                class="password-toggle-btn" 
                                @click="showPassword = !showPassword"
                                :title="showPassword ? 'Sembunyikan Kata Sandi' : 'Tampilkan Kata Sandi'"
                                tabindex="-1">
                            <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="form-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-auth-submit">
                    <i class="fas fa-right-to-bracket"></i>
                    <span>Masuk Sekarang</span>
                </button>
            </form>

            <div class="auth-footer">
                <p>Belum memiliki akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
            </div>
        </div>
    </div>
</body>
</html>
