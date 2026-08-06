# Plan Pengerjaan: Redesain UI/UX Halaman Login & Quick Fill Akun Demo

Dokumen ini berisi rencana pengerjaan modernisasi halaman Login dan penambahan tombol Pengisi Akun Demo (Quick Fill Demo Account) pada NusaMarket.

---

## 🎯 Tujuan & Requirement
1. **Modernisasi UI/UX Halaman Login**:
   - Mempercantik visual dengan skema warna *Blue Ocean Theme* (`--primary: #1e6fd9`, `--primary-deeper: #0b3a75`, `--ocean: #0e7490`).
   - Efek visual modern (card glassmorphism/elevated shadow, visual hero/branding badge, input focus glow, toggle show/hide password).
   - Mematuhi aturan **Paten NusaMarket**: Tidak ada inline `<style>` & `<script>` di Blade file.

2. **Fitur Akun Demo (Quick Fill Demo)**:
   - Menyediakan tombol quick-fill untuk 3 Role: **Admin**, **Seller (Penjual)**, dan **Buyer (Pembeli)**.
   - Menggunakan Alpine.js reactive state (`x-data`, `x-model`, `x-on:click`) tanpa tag `<script>` inline.
   - Kredensial demo:
     - Admin: `admin@nusamarket.com` / `password`
     - Seller: `seller@nusamarket.com` / `password`
     - Buyer: `buyer@nusamarket.com` / `password`

---

## 🛠️ Perubahan File

1. `public/css/auth.css` (CSS Partial Auth)
   - Styling container login responsif dengan background modern gradient/patterns.
   - Styling badge role demo account (Admin, Seller, Buyer) dengan hover effect & micro-animations.
   - Styling password input wrapper dengan button toggle eye icon.
   - Highlighting focus ring & smooth transitions.

2. `resources/views/auth/login.blade.php` (Blade View Login)
   - Refactor layout form dengan Alpine `x-data="{ email: '...', password: '', showPassword: false, fillDemo(e, p) { this.email = e; this.password = p; } }"`.
   - Tambahkan section quick fill akun demo di atas/bawah form dengan icon & badge role.
   - Tambahkan toggle show/hide password interaktif.

---

## 🧪 Verifikasi & Pengujian
- Menjalankan Vite build (`npx vite build` / dev mode check).
- Pengujian interaktif mengisi form lewat tombol Admin, Seller, dan Buyer.
- Pengujian validasi form login dengan submit.
