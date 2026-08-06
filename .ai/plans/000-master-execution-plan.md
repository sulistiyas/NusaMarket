# Master Plan Pengerjaan NusaMarket (Laravel 13)

Dokumen ini adalah rencana pengerjaan utama untuk membangun aplikasi **NusaMarket** secara utuh dari 0% sampai 100% siap digunakan dengan sempurna.

---

## 🧭 Arsitektur Teknis & Aturan Paten

Sesuai dengan [.ai/instructions.md](file:///c:/laragon/www/NusaMarket/.ai/instructions.md):
- **Framework**: Laravel 13 & PostgreSQL
- **Auth**: Laravel Sanctum (API Token) & Session Web
- **CSS**: Vanilla CSS Blue Ocean Palette Partials (`app.css`, `main.css`, `sidebar.css`, `header.css`, `datatable.css`, `form.css`, `card.css`, `badge.css`, `button.css`, `auth.css`, `responsive.css`)
- **JS**: Alpine.js, Select2, SweetAlert2, Axios
- **Larangan**: 
  - ❌ Tidak ada `<style>` inline di file Blade.
  - ❌ Tidak ada `<script>` inline di file Blade (hanya attribute `x-*` Alpine).
  - ❌ Tidak ada logic bisnis di Controller (harus di Service class).

---

## 📋 Tahapan Pengerjaan Step-by-Step

### Phase 1: Setup Environment & Dependencies
- Install Laravel Sanctum: `composer require laravel/sanctum`
- Install NPM libraries: `alpinejs`, `axios`, `jquery`, `select2`, `sweetalert2`
- Konfigurasi koneksi PostgreSQL di `.env`

### Phase 2: Design System (Blue Ocean Theme) — CSS & JS Partials
- Buat semua partial CSS di `public/css/` dengan token warna `--primary: #1e6fd9`, `--primary-deeper: #0b3a75`, `--ocean: #0e7490`.
- Buat partial JS di `public/js/`:
  - `app.js` (Entry point)
  - `select2.init.js` (Inisialisasi global Select2)
  - `alert.init.js` (SweetAlert2 Toast & Modal helper)
  - `datatable.js` (Komponen Alpine.js datatable reusable)
  - `form.js` (Helper validation & loading button)

### Phase 3: Master Layout & Core Components
- Layout utama `resources/views/layouts/app.blade.php` dengan Alpine state `sidebarOpen` & overlay mobile.
- Komponen `header.blade.php` (dengan toggle button mobile), `sidebar.blade.php`, `footer.blade.php`, `alert.blade.php` (flash message wrapper), `breadcrumb.blade.php`.

### Phase 4: Database Schema, Models & Seeders
- Trait `ApiResponse` di `app/Traits/ApiResponse.php`
- Migrasi tabel: `users`, `categories`, `stores`, `products`, `orders`, `order_items`, `carts`, `cart_items`, `reviews` dengan `$table->uuid()`, `softDeletes()`, dan `jsonb`.
- Models & Seeders untuk data pengujian awal.

### Phase 5: Autentikasi Web & Sanctum API
- Service `AuthService.php` & Form Requests (`LoginRequest`, `RegisterRequest`).
- Controller Web `App\Http\Controllers\Web\AuthController` + Blade views login & register.
- Controller API `App\Http\Controllers\Api\AuthController` + Sanctum Token API endpoints.

### Phase 6: Modul Kategori & Produk (Admin/Seller)
- Service `CategoryService.php` & `ProductService.php`.
- Form Requests & API Resources (`ProductResource`, `CategoryResource`).
- Controllers Web & API.
- Halaman View Blade: Index dengan Datatable Alpine.js, Form Create/Edit dengan Select2 & upload gambar.

### Phase 7: Katalog Marketplace, Keranjang & Checkout (Buyer)
- Service `CartService.php` & `OrderService.php`.
- Halaman katalog utama, detail produk, filter kategori.
- Fitur keranjang (drawer & halaman) dengan kalkulasi interaktif Alpine.js + Axios.
- Halaman checkout & kalkulasi pengiriman.

### Phase 8: Manajemen Pesanan & Dashboard Analytics
- Dashboard dengan ringkasan matriks bisnis & tabel transaksi terbaru.
- Manajemen pesanan untuk seller (update status pesanan dengan SweetAlert2 confirm).

### Phase 9: Pengujian & Verifikasi 100%
- Menjalankan unit & feature test via `php artisan test`.
- Memastikan aset JS/CSS ter-compile via `npx vite build`.
- Memastikan tidak ada script/style inline di seluruh Blade views.
- Test responsivitas di 375px, 768px, 1280px.
