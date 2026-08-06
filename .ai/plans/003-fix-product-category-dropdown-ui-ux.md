# Rencana Pengerjaan 003: Perbaikan Dropdown Kategori Produk & Enhancement UI/UX Form Produk

## 🎯 Tujuan
1. Memperbaiki dropdown kategori produk yang tidak menampilkan opsi kategori pada form Tambah/Edit Produk.
2. Memperbaiki konflik CSS antara styling `<select>` bawaan dengan komponen Select2.
3. Meningkatkan estetika UI/UX Form Produk (Tambah & Edit) sesuai standar Blue Ocean Theme & Clean Architecture (tanpa inline `<style>` atau `<script>`).

---

## 🔍 Analisis Root Cause
1. **Konflik CSS `!important` pada `form.css`**:
   - Class `select.form-control, select.select2, select` memaksakan `height: 44px !important`, `width: 100% !important`, dan `appearance: none !important`.
   - Hal ini menimpa class internal `.select2-hidden-accessible` milik Select2 yang seharusnya menyembunyikan `<select>` asli (dengan `height: 1px`, `width: 1px`).
   - Dampaknya, elemen `<select>` asli tetap muncul dan bertumpuk/tutup-menutupi elemen `.select2-container` sehingga data kategori seolah-olah hilang / tidak bisa dipilih dengan benar.

2. **Pengayaan Fitur Templating Select2 (Icon Kategori)**:
   - Data kategori di database memiliki atribut `icon` (misal: `fa-laptop`, `fa-shirt`, `fa-utensils`, `fa-car`).
   - `select2.init.js` perlu mendukung pencetakan icon kategori ini (`data-icon`) secara otomatis agar UX pemilihan kategori jauh lebih interaktif dan visual.

3. **Peningkatan UI/UX Form Produk**:
   - Struktur kartu (card) perlu dibagi menjadi seksi yang rapi: Informasi Utama, Harga & Stok, Media & Gambar, Deskripsi.
   - Penambahan addon grup input untuk unit mata uang (`Rp`), berat (`gram`), dan stok (`unit`).
   - Live image preview menggunakan Alpine.js (`x-data`) tanpa menyisipkan tag `<script>` inline.

---

## 🛠️ Langkah-Langkah Implementasi

1. **`public/css/form.css`**:
   - Hapus `select.select2` dari aturan `!important` native select.
   - Tambahkan styling modern untuk Select2 dropdown, icon, hover state, active option, serta addon input group.

2. **`resources/js/select2.init.js` & `public/js/select2.init.js`**:
   - Perbarui `initSelect2()` dengan `templateResult` dan `templateSelection` untuk merender icon FontAwesome jika `data-icon` tersedia pada tag `<option>`.
   - Pastikan inisialisasi aman berjalan di `DOMContentLoaded`, `load`, dan terekspos di `window.initSelect2`.

3. **`resources/views/pages/products/create.blade.php` & `edit.blade.php`**:
   - Tambahkan atribut `data-icon="{{ $category->icon }}"` pada loop `<option>`.
   - Terapkan grup input dengan addon visual (`Rp`, `gram`, `Unit`).
   - Tambahkan preview gambar langsung menggunakan Alpine.js state (`x-data`).
   - Rapikan layout tombol action (Batal & Simpan/Perbarui).

4. **Verifikasi & Build**:
   - Jalankan `npm run build` untuk mengompilasi aset JS & CSS.
   - Uji coba form di browser.

---

## ✅ Status Selesai (Completed)
- [x] Perbaikan `resources/js/select2.init.js` & `public/js/select2.init.js` (Return string HTML pada templateResult).
- [x] Perbaikan CSS `form.css` & `app.css` untuk styling Select2 dropdown.
- [x] Penambahan icon FontAwesome pada dropdown kategori di `create.blade.php` & `edit.blade.php`.
- [x] Aset telah di-build dengan `npm run build`.

