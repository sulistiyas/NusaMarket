# Implementation Plan: Phase 7 s/d 9 NusaMarket

Rencana pengerjaan penuh dari Fase 7 hingga Fase 9 untuk menyelesaikan aplikasi **NusaMarket** secara 100% sempurna sesuai dengan panduan di [.ai/instructions.md](file:///c:/laragon/www/NusaMarket/.ai/instructions.md) dan master plan di [.ai/plans/000-master-execution-plan.md](file:///c:/laragon/www/NusaMarket/.ai/plans/000-master-execution-plan.md).

---

## 📌 Status Terakhir & Langkah yang Sudah Dilakukan
- ✅ **Commit & Push**: Perbaikan issue modal auto-open kategori & pembersihan inline styles telah di-commit dan di-push ke branch `main`.

---

## 🎯 Target Pengerjaan

### **Phase 7: Katalog Marketplace, Keranjang & Checkout (Buyer)**
1. **Service Classes**:
   - `app/Services/CartService.php`: Logika tambah item, hapus item, update kuantitas, kalkulasi total keranjang.
   - `app/Services/OrderService.php`: Logika pembuatan pesanan (checkout), transaksi database (DB transaction), kalkulasi ongkir/pajak.
2. **Form Requests & API Resources**:
   - `AddToCartRequest.php`, `UpdateCartRequest.php`, `CheckoutRequest.php`.
   - `CartResource.php`, `OrderResource.php`.
3. **Controllers (Web & API)**:
   - Web: `CatalogController.php`, `CartController.php`, `CheckoutController.php`.
   - API: `CartController.php`, `CheckoutController.php`, `OrderController.php`.
4. **Views & Partial CSS**:
   - `resources/views/pages/marketplace/index.blade.php`: Catalog marketplace interaktif (search, filter kategori, sorting, grid card produk).
   - `resources/views/pages/marketplace/show.blade.php`: Detail produk buyer dengan tombol Tambah ke Keranjang.
   - `resources/views/pages/cart/index.blade.php`: Halaman keranjang belanja dengan kalkulasi live via Alpine.js & Axios.
   - `resources/views/pages/checkout/index.blade.php`: Form checkout, alamat pengiriman, opsi pembayaran & ringkasan belanja.
   - `public/css/marketplace.css`, `public/css/cart.css`: Styling modern Blue Ocean Theme tanpa inline style/script.

---

### **Phase 8: Manajemen Pesanan & Dashboard Analytics**
1. **Controllers & Views**:
   - `App\Http\Controllers\Web\OrderController.php`: Halaman riwayat/daftar pesanan buyer & seller.
   - `resources/views/pages/orders/index.blade.php`: List pesanan dengan filter status, detail modal/page.
   - `resources/views/pages/orders/show.blade.php`: Detail pesanan (status log, item pesanan, alamat, total).
   - Enhancement pada `resources/views/pages/dashboard.blade.php`: Matriks bisnis (total revenue, total orders, total products, total categories) & tabel transaksi terbaru.
2. **API Endpoints**:
   - Status update pesanan (misal: `pending` -> `processing` -> `completed` / `cancelled`) dengan SweetAlert2 konfirmasi.

---

### **Phase 9: Pengujian & Verifikasi 100%**
1. **Automated Testing & Build**:
   - Menjalankan `php artisan test` untuk memastikan seluruh fitur backend & API berjalan tanpa error.
   - Menjalankan `npx vite build` untuk memastikan kompilasi CSS & JS berjalan bersih.
2. **Quality & Guideline Audit**:
   - Verifikasi tidak ada `<style>` inline atau `<script>` inline di Blade views (kecuali `x-*` Alpine.js).
   - Verifikasi responsivitas pada 375px (mobile), 768px (tablet), dan 1280px (desktop).

---

## 📁 File yang Akan Dibuat / Dimodifikasi

#### [NEW] [CartService.php](file:///c:/laragon/www/NusaMarket/app/Services/CartService.php)
#### [NEW] [OrderService.php](file:///c:/laragon/www/NusaMarket/app/Services/OrderService.php)
#### [NEW] [CatalogController.php](file:///c:/laragon/www/NusaMarket/app/Http/Controllers/Web/CatalogController.php)
#### [NEW] [CartController.php](file:///c:/laragon/www/NusaMarket/app/Http/Controllers/Web/CartController.php)
#### [NEW] [CheckoutController.php](file:///c:/laragon/www/NusaMarket/app/Http/Controllers/Web/CheckoutController.php)
#### [NEW] [OrderController.php](file:///c:/laragon/www/NusaMarket/app/Http/Controllers/Web/OrderController.php)
#### [NEW] [Api/CartController.php](file:///c:/laragon/www/NusaMarket/app/Http/Controllers/Api/CartController.php)
#### [NEW] [Api/OrderController.php](file:///c:/laragon/www/NusaMarket/app/Http/Controllers/Api/OrderController.php)
#### [NEW] [CartResource.php](file:///c:/laragon/www/NusaMarket/app/Http/Resources/CartResource.php)
#### [NEW] [OrderResource.php](file:///c:/laragon/www/NusaMarket/app/Http/Resources/OrderResource.php)
#### [NEW] [marketplace.css](file:///c:/laragon/www/NusaMarket/public/css/marketplace.css)
#### [NEW] [cart.css](file:///c:/laragon/www/NusaMarket/public/css/cart.css)
#### [NEW] [marketplace/index.blade.php](file:///c:/laragon/www/NusaMarket/resources/views/pages/marketplace/index.blade.php)
#### [NEW] [marketplace/show.blade.php](file:///c:/laragon/www/NusaMarket/resources/views/pages/marketplace/show.blade.php)
#### [NEW] [cart/index.blade.php](file:///c:/laragon/www/NusaMarket/resources/views/pages/cart/index.blade.php)
#### [NEW] [checkout/index.blade.php](file:///c:/laragon/www/NusaMarket/resources/views/pages/checkout/index.blade.php)
#### [NEW] [orders/index.blade.php](file:///c:/laragon/www/NusaMarket/resources/views/pages/orders/index.blade.php)
#### [NEW] [orders/show.blade.php](file:///c:/laragon/www/NusaMarket/resources/views/pages/orders/show.blade.php)
#### [MODIFY] [routes/web.php](file:///c:/laragon/www/NusaMarket/routes/web.php)
#### [MODIFY] [routes/api.php](file:///c:/laragon/www/NusaMarket/routes/api.php)
#### [MODIFY] [public/css/app.css](file:///c:/laragon/www/NusaMarket/public/css/app.css)
#### [MODIFY] [resources/views/pages/dashboard.blade.php](file:///c:/laragon/www/NusaMarket/resources/views/pages/dashboard.blade.php)

---

## 🧪 Verification Plan

### Automated Tests
- `php artisan test`
- `npx vite build`

### Manual Verification
- Testing alur buyer: Buka katalog `/marketplace` -> lihat detail produk -> tambah ke keranjang `/cart` -> update jumlah item -> checkout `/checkout` -> buat pesanan.
- Testing alur order: Cek halaman pesanan `/orders` -> update status pesanan.
- Cek responsivitas layout di layar mobile & desktop.
