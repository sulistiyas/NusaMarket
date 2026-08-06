# Panduan & Instruksi Paten AI (NusaMarket)

Dokumen ini berisi instruksi dan panduan terpatenkan yang **WAJIB** selalu diikuti oleh AI Assistant dalam setiap pengerjaan di project **NusaMarket**.

---

## 1. Aturan Utama & Pengerjaan (Core Guidelines)

1. **Kepatuhan pada Plan (`.ai/plans/`)**:
   - Selalu rujuk rencana pengerjaan di folder `.ai/plans/` sebelum memulai tugas.
   - Update atau buat dokumen plan baru jika ada penambahan fitur besar atau perubahan arsitektur.

2. **Standar Penulisan Kode (Laravel Best Practices)**:
   - Gunakan standar dan konvensi Laravel modern.
   - Pertahankan arsitektur yang bersih (Clean Architecture / Skinny Controller).
   - Gunakan `FormRequest` untuk validasi data request.
   - Kelola logic kompleks di Service Class / Action Class atau Model.

3. **Verifikasi & Bebas Error**:
   - Pastikan kode diperiksa/diuji (misal sintaks PHP, perintah `php artisan`, atau test suite) sebelum menyelesaikan task.
   - Jangan pernah menyembunyikan error atau mengabaikan penanganan exception.

4. **Integritas Kode Existing**:
   - Jaga dan pertahankan komentar/docstring yang ada.
   - Hindari efek samping (side-effects) yang merusak fitur lain.

---

## 2. Struktur Direktori `.ai/`

- **`.ai/instructions.md`**: File panduan utama ini (instruksi paten).
- **`.ai/plans/`**: Folder penyimpan dokumen rencana/plan pengerjaan fitur ke depan.

---

## 3. Instruksi Khusus Tambahan (Dapat Ditambahkan User)
*(Tambahkan poin instruksi spesifik Anda di bawah ini agar selalu dipatuhi oleh AI)*

- 

---

## 4. Panduan Teknis Project — Laravel 13

> Bagian di bawah ini adalah panduan teknis detail project (tech stack, struktur folder, konvensi kode, design system, dsb) Panduan ini **wajib** dibaca dan diikuti sebagai pelengkap aturan utama di atas.

### Laravel 13 Project Guidelines

> Panduan ini wajib dibaca dan diikuti oleh AI agent sebelum membuat, memodifikasi, atau me-review kode apapun dalam project ini.

---

## 🧭 Tech Stack

| Layer       | Technology                              |
|-------------|----------------------------------------|
| Framework   | Laravel 13                             |
| Database    | PostgreSQL                             |
| Auth        | Laravel Sanctum (API Token)            |
| CSS         | Vanilla CSS (dipisah per partials)     |
| JS          | Alpine.js (interaktivitas UI)          |
| Dropdown    | Select2                                |
| Alert       | SweetAlert2                            |
| Table       | Alpine.js (sort, pagination, search, filter) |
| Icon        | Heroicons / Font Awesome (CDN)         |
| HTTP Client | Axios (sudah include di app.js)        |

---

## 📁 Struktur Folder Wajib

### Blade Views

```
resources/views/
├── layouts/
│   └── app.blade.php           ← Layout utama satu-satunya
├── components/
│   ├── header.blade.php        ← Navbar / top bar
│   ├── sidebar.blade.php       ← Side navigation
│   ├── footer.blade.php        ← Footer konten
│   ├── alert.blade.php         ← Flash message wrapper (SweetAlert2)
│   └── breadcrumb.blade.php    ← Breadcrumb navigation
├── pages/
│   ├── dashboard.blade.php
│   └── [module]/
│       ├── index.blade.php
│       ├── create.blade.php
│       ├── edit.blade.php
│       └── show.blade.php
└── auth/
    ├── login.blade.php
    └── register.blade.php
```

### CSS — Partial Files

```
public/css/
├── app.css         ← Import semua partial (@import), variabel global, reset
├── main.css        ← Layout umum: body, container, grid, spacing
├── sidebar.css     ← Sidebar styling
├── header.css      ← Header / navbar styling
├── datatable.css   ← Table, pagination, search bar, sort icon
├── form.css        ← Input, label, select, textarea, validation styles
├── card.css        ← Card component styles
├── badge.css       ← Badge, tag, status indicator
├── button.css      ← Semua variasi button
└── auth.css        ← Login/register page specific
```

> ⚠️ **LARANGAN**: Jangan pernah menulis `<style>` inline di dalam file Blade manapun.  
> Semua CSS harus masuk ke file partial yang sesuai di `public/css/`.

### JS — Partial Files

```
public/js/
├── app.js              ← Entry point: import Alpine, Select2, SweetAlert2, Axios
├── datatable.js        ← Alpine.js datatable component (sort, filter, pagination, search)
├── select2.init.js     ← Inisialisasi Select2 global untuk semua .select2 element
├── alert.init.js       ← SweetAlert2 helper functions (success, error, confirm, toast)
└── form.js             ← Helper: form validation feedback, loading state button
```

> ⚠️ **LARANGAN**: Jangan pernah menulis `<script>` inline di dalam file Blade.  
> Pengecualian **satu-satunya**: `x-data`, `x-on`, `x-bind` Alpine.js attribute di HTML element langsung (bukan blok `<script>`).  
> Data yang perlu di-pass ke JS harus lewat `data-*` attribute, bukan blok `<script>` inline.

---

## 🗂️ Layout Utama — `layouts/app.blade.php`

Layout utama **wajib** menggunakan struktur berikut:

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

    {{-- CSS: app.css selalu pertama --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- CSS per-halaman: di-push dari child view --}}
    @stack('styles')
</head>
<body x-data="{}">

    @include('components.header')

    <div class="layout-wrapper">
        @include('components.sidebar')

        <main class="main-content">
            @include('components.breadcrumb')
            @include('components.alert')

            @yield('content')
        </main>
    </div>

    @include('components.footer')

    {{-- JS: app.js selalu terakhir --}}
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- JS per-halaman --}}
    @stack('scripts')

</body>
</html>
```

**Aturan `@stack`:**
- Gunakan `@push('styles')` di child view untuk CSS spesifik halaman (misal: `datatable.css`)
- Gunakan `@push('scripts')` di child view untuk JS spesifik halaman
- **Jangan** include CSS/JS global yang sudah ada di `app.css`/`app.js` lagi di child view

---

## 📦 `app.js` — Struktur Wajib

```javascript
// =============================================
// app.js — Entry Point
// Urutan import TIDAK boleh diubah
// =============================================

// 1. Alpine.js
import Alpine from 'alpinejs'
window.Alpine = Alpine

// 2. Axios
import axios from 'axios'
window.axios = axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.common['X-CSRF-TOKEN'] = document
    .querySelector('meta[name="csrf-token"]')?.getAttribute('content')

// 3. Select2 (jQuery required)
import $ from 'jquery'
window.$ = window.jQuery = $
import 'select2'
import 'select2/dist/css/select2.min.css'

// 4. SweetAlert2
import Swal from 'sweetalert2'
window.Swal = Swal

// 5. Init modules
import './select2.init.js'
import './alert.init.js'

// 6. Start Alpine
Alpine.start()
```

---

## 📦 `app.css` — Struktur Wajib

```css
/* =============================================
   app.css — Root CSS Entry Point
   Semua partial di-import di sini
   ============================================= */

/* 1. CSS Custom Properties — Blue Ocean Theme */
:root {
    /* === PRIMARY BLUE PALETTE === */
    --primary:          #1e6fd9;   /* Biru utama */
    --primary-dark:     #1251a3;   /* Biru gelap */
    --primary-deeper:   #0b3a75;   /* Biru sangat gelap / navy */
    --primary-light:    #4f97f0;   /* Biru terang */
    --primary-pale:     #dbeafe;   /* Biru pucat (background highlight) */

    /* === OCEAN / TEAL ACCENT === */
    --ocean:            #0e7490;   /* Biru laut / teal */
    --ocean-dark:       #075985;   /* Biru laut gelap */
    --ocean-light:      #38bdf8;   /* Biru laut terang / sky */
    --ocean-pale:       #e0f2fe;   /* Biru laut pucat */

    /* === GRADIENT PRESETS === */
    --gradient-primary: linear-gradient(135deg, #1e6fd9 0%, #0b3a75 100%);
    --gradient-ocean:   linear-gradient(135deg, #0e7490 0%, #1e6fd9 100%);
    --gradient-sidebar: linear-gradient(180deg, #0b3a75 0%, #0e7490 100%);
    --gradient-header:  linear-gradient(90deg,  #1251a3 0%, #0e7490 100%);

    /* === SEMANTIC COLORS === */
    --success:          #16a34a;
    --danger:           #dc2626;
    --warning:          #d97706;
    --info:             #0891b2;

    /* === BACKGROUND === */
    --bg-light:         #f0f6ff;   /* Sedikit kebiru-biruan */
    --bg-white:         #ffffff;
    --bg-sidebar:       #0b3a75;   /* Navy untuk sidebar */
    --bg-header:        #1251a3;   /* Biru gelap untuk header */

    /* === TEXT === */
    --text-main:        #0f172a;
    --text-muted:       #475569;
    --text-on-primary:  #ffffff;   /* Teks di atas background biru */
    --text-on-sidebar:  #bfdbfe;   /* Teks menu sidebar (biru pucat) */
    --text-sidebar-active: #ffffff;

    /* === BORDER & SHADOW === */
    --border:           #bfdbfe;   /* Border bernuansa biru */
    --border-dark:      #93c5fd;
    --radius:           8px;
    --radius-lg:        12px;
    --shadow-sm:        0 1px 3px rgba(14, 116, 144, 0.12);
    --shadow-md:        0 4px 12px rgba(14, 116, 144, 0.15);
    --shadow-primary:   0 4px 16px rgba(30, 111, 217, 0.25);

    /* === LAYOUT === */
    --sidebar-width:    260px;
    --sidebar-collapsed: 64px;
    --header-height:    64px;
}

/* 2. Reset */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Inter', sans-serif; background: var(--bg-light); color: var(--text-main); }

/* 3. Import partials */
@import './main.css';
@import './header.css';
@import './sidebar.css';
@import './button.css';
@import './form.css';
@import './card.css';
@import './badge.css';
@import './responsive.css';
```

> CSS per halaman (misal `datatable.css`) **tidak** di-import di `app.css`.  
> Di-push via `@push('styles')` dari masing-masing view yang membutuhkan.

---

## 🎨 Design System — Tema Blue Ocean

### Palet Warna Wajib

| Token | Hex | Penggunaan |
|-------|-----|------------|
| `--primary` | `#1e6fd9` | Button utama, link aktif, aksen |
| `--primary-dark` | `#1251a3` | Hover state, header background |
| `--primary-deeper` | `#0b3a75` | Sidebar background, navy section |
| `--primary-light` | `#4f97f0` | Icon, secondary accent |
| `--ocean` | `#0e7490` | Badge, highlight, border aktif |
| `--ocean-light` | `#38bdf8` | Progress bar, chip, tag |
| `--gradient-sidebar` | navy→teal | Sidebar background gradient |
| `--gradient-header` | blue→teal | Header background gradient |

> ⚠️ **LARANGAN**: Jangan pakai warna plain merah/hijau/kuning/abu sebagai warna utama desain.  
> Semua elemen UI utama (header, sidebar, button, card header) **wajib** menggunakan token dari palet Blue Ocean di atas.

---

## 📱 Responsive Design — Konvensi Wajib

Semua halaman **wajib** 100% responsive: mobile, tablet, dan desktop.  
Gunakan **Mobile-First** approach — styling dasar untuk mobile, kemudian override untuk layar lebih besar.

### Breakpoints Standar

```css
/* responsive.css — Breakpoint definitions */

/* Mobile first: styling default sudah untuk mobile (<640px) */

/* Small — Tablet portrait */
@media (min-width: 640px)  { /* sm  */ }

/* Medium — Tablet landscape */
@media (min-width: 768px)  { /* md  */ }

/* Large — Desktop */
@media (min-width: 1024px) { /* lg  */ }

/* Extra Large — Wide desktop */
@media (min-width: 1280px) { /* xl  */ }
```

### Responsive Layout Rules

```css
/* main.css — Layout responsif */

/* === Sidebar behavior === */
.sidebar {
    width: var(--sidebar-width);
    position: fixed;
    height: 100vh;
    transform: translateX(-100%);         /* Mobile: tersembunyi */
    transition: transform 0.3s ease;
    z-index: 999;
    background: var(--gradient-sidebar);
}

.sidebar.open {
    transform: translateX(0);             /* Mobile: terbuka via toggle */
}

@media (min-width: 1024px) {
    .sidebar {
        transform: translateX(0);         /* Desktop: selalu tampil */
        position: sticky;
        top: 0;
    }
    .main-content {
        margin-left: var(--sidebar-width);
    }
}

/* === Layout wrapper === */
.layout-wrapper {
    display: flex;
    min-height: 100vh;
}

.main-content {
    flex: 1;
    min-width: 0;                         /* Prevent overflow */
    padding: 16px;
    transition: margin-left 0.3s ease;
}

@media (min-width: 768px) {
    .main-content { padding: 24px; }
}

/* === Grid system === */
.grid-cols-1 { display: grid; grid-template-columns: 1fr; gap: 16px; }

@media (min-width: 640px) {
    .grid-cols-sm-2 { grid-template-columns: repeat(2, 1fr); }
}

@media (min-width: 1024px) {
    .grid-cols-lg-3 { grid-template-columns: repeat(3, 1fr); }
    .grid-cols-lg-4 { grid-template-columns: repeat(4, 1fr); }
}
```

### Sidebar Mobile Toggle

Sidebar di mobile dikontrol via Alpine.js. Wajib implementasi ini di `layouts/app.blade.php`:

```html
<body x-data="{ sidebarOpen: false }">

    {{-- Overlay saat sidebar mobile terbuka --}}
    <div
        class="sidebar-overlay"
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition.opacity
    ></div>

    @include('components.header')  {{-- Header punya tombol toggle --}}

    <div class="layout-wrapper">
        {{-- Sidebar menerima state dari parent x-data --}}
        <aside class="sidebar" :class="{ 'open': sidebarOpen }">
            @include('components.sidebar')
        </aside>

        <main class="main-content">
            @include('components.breadcrumb')
            @include('components.alert')
            @yield('content')
        </main>
    </div>
</body>
```

```html
{{-- Di components/header.blade.php: tombol toggle sidebar mobile --}}
<button class="sidebar-toggle" @click="sidebarOpen = !sidebarOpen" aria-label="Toggle Sidebar">
    <i class="fas fa-bars"></i>
</button>
```

### Responsive Table

Semua tabel wajib bisa di-scroll horizontal di mobile:

```html
{{-- Wrap semua tabel dengan .dt-responsive --}}
<div class="dt-responsive">
    <table class="dt-table">
        ...
    </table>
</div>
```

```css
/* datatable.css */
.dt-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.dt-table {
    min-width: 600px;   /* Pastikan tabel tidak terpotong */
    width: 100%;
}
```

### Responsive Form

```css
/* form.css */
.form-grid {
    display: grid;
    grid-template-columns: 1fr;           /* 1 kolom di mobile */
    gap: 16px;
}

@media (min-width: 768px) {
    .form-grid.cols-2 {
        grid-template-columns: repeat(2, 1fr);
    }
    .form-grid.cols-3 {
        grid-template-columns: repeat(3, 1fr);
    }
}
```

### Typography Responsive

```css
/* main.css */
:root {
    --text-xs:   0.75rem;
    --text-sm:   0.875rem;
    --text-base: 1rem;
    --text-lg:   1.125rem;
    --text-xl:   1.25rem;
    --text-2xl:  1.5rem;
    --text-3xl:  1.875rem;
}

h1 { font-size: var(--text-2xl); }
h2 { font-size: var(--text-xl); }
h3 { font-size: var(--text-lg); }

@media (min-width: 768px) {
    h1 { font-size: var(--text-3xl); }
    h2 { font-size: var(--text-2xl); }
}
```

### Tambahan partial CSS wajib

```
public/css/
├── app.css
├── main.css
├── sidebar.css
├── header.css
├── datatable.css
├── form.css
├── card.css
├── badge.css
├── button.css
├── auth.css
└── responsive.css     ← BARU: breakpoints & mobile overrides
```

### Checklist Responsive (tambahan ke checklist commit)

- [ ] Layout tidak overflow di layar 375px (iPhone SE)
- [ ] Sidebar bisa di-toggle di mobile
- [ ] Semua tabel memiliki wrapper `.dt-responsive`
- [ ] Form menggunakan `.form-grid` bukan fixed width
- [ ] Font size cukup besar di mobile (minimal 14px)
- [ ] Touch target (button/link) minimal 44×44px
- [ ] Tidak ada horizontal scroll di halaman utama

---

## 🗃️ Alpine.js Datatable — Konvensi

Gunakan komponen Alpine.js yang sudah dibuat di `datatable.js`.  
**Jangan buat ulang** logic datatable di setiap halaman.

### Cara pakai di Blade:

```html
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/datatable.css') }}">
@endpush

<div
    x-data="datatable({
        url: '{{ route('api.products.index') }}',
        columns: ['name', 'category', 'price', 'status', 'created_at'],
        perPage: 10
    })"
    x-init="fetchData()"
>
    {{-- Search & Filter Bar --}}
    <div class="dt-toolbar">
        <input type="text" x-model="search" @input.debounce.400ms="fetchData()" placeholder="Cari...">
        <select x-model="perPage" @change="fetchData()">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
        </select>
    </div>

    {{-- Table --}}
    <table class="dt-table">
        <thead>
            <tr>
                <th @click="sort('name')" class="sortable">
                    Nama <span x-text="sortIcon('name')"></span>
                </th>
                {{-- kolom lainnya --}}
            </tr>
        </thead>
        <tbody>
            <template x-for="row in rows" :key="row.id">
                <tr>
                    <td x-text="row.name"></td>
                </tr>
            </template>
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="dt-pagination">
        <button @click="prevPage()" :disabled="currentPage === 1">Prev</button>
        <span x-text="`${currentPage} / ${totalPages}`"></span>
        <button @click="nextPage()" :disabled="currentPage === totalPages">Next</button>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/datatable.js') }}"></script>
@endpush
```

**`datatable.js` wajib expose** fungsi: `fetchData()`, `sort(column)`, `sortIcon(column)`, `prevPage()`, `nextPage()`, dan property: `rows`, `search`, `currentPage`, `totalPages`, `perPage`.

---

## 🔽 Select2 — Konvensi

Semua `<select>` yang memerlukan fitur search/ajax **wajib** menggunakan Select2.

### Cara pakai di Blade:

```html
{{-- Tambahkan class 'select2' pada element select --}}
<select
    name="category_id"
    class="select2"
    data-placeholder="Pilih Kategori"
    data-url="{{ route('api.categories.select') }}"  {{-- opsional: untuk AJAX --}}
>
    <option value=""></option>
    @foreach($categories as $category)
        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
            {{ $category->name }}
        </option>
    @endforeach
</select>
```

**Aturan Select2:**
- Semua `.select2` di-init otomatis oleh `select2.init.js` saat DOM ready
- Jika ada `data-url`, otomatis diset ke mode AJAX
- **Jangan** panggil `$('.select2').select2()` manual di halaman manapun

---

## 🔔 SweetAlert2 — Konvensi

Semua alert, konfirmasi, dan toast **wajib** menggunakan SweetAlert2.  
Helper sudah disediakan di `alert.init.js`.

### Fungsi yang tersedia (global via `window`):

```javascript
// Dari alert.init.js — wajib tersedia secara global

// Toast notifikasi (pojok kanan atas)
window.Toast.success('Data berhasil disimpan')
window.Toast.error('Terjadi kesalahan')
window.Toast.warning('Perhatikan data Anda')
window.Toast.info('Informasi tersedia')

// SweetAlert modal
window.Alert.success('Berhasil!', 'Data telah disimpan.')
window.Alert.error('Gagal!', 'Terjadi kesalahan server.')

// Konfirmasi sebelum aksi (delete, dsb)
window.Alert.confirm(
    'Hapus Data?',
    'Data yang dihapus tidak dapat dikembalikan.',
    () => { /* callback jika confirmed */ }
)
```

### Flash Message dari Laravel:

Komponen `components/alert.blade.php` otomatis membaca session flash:

```php
// Di controller:
return redirect()->route('products.index')
    ->with('success', 'Produk berhasil disimpan')

// Atau untuk error:
return redirect()->back()
    ->with('error', 'Terjadi kesalahan')
```

`components/alert.blade.php` membaca session dan menampilkan via SweetAlert2 Toast.

---

## 🌐 REST API — Konvensi

### Response Format Standar

Semua endpoint API **wajib** mengembalikan format berikut:

```json
{
    "success": true,
    "message": "Data berhasil diambil",
    "data": { ... },
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 10,
        "total": 48
    }
}
```

Untuk error:

```json
{
    "success": false,
    "message": "Validasi gagal",
    "errors": {
        "email": ["Email sudah digunakan"],
        "password": ["Password minimal 8 karakter"]
    }
}
```

### Gunakan `ApiResponse` Trait

Buat trait di `app/Traits/ApiResponse.php`:

```php
trait ApiResponse {
    protected function success($data = null, string $message = 'Success', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function paginated($paginator, string $message = 'Success')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $paginator->items(),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ]);
    }

    protected function error(string $message = 'Error', int $code = 400, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }
}
```

### Routing API

```
routes/
├── web.php      ← Route untuk Blade views (authenticated)
├── api.php      ← Route untuk REST API (Sanctum auth)
└── auth.php     ← Route login/logout/register
```

Prefix route API: `api/v1/`

```php
// routes/api.php
Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::apiResource('products', ProductController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('orders', OrderController::class);
    });
});
```

---

## 🗄️ Database — Konvensi PostgreSQL

### Migration

```php
// Selalu gunakan:
$table->id();           // bigIncrements
$table->timestamps();   // created_at, updated_at
$table->softDeletes();  // deleted_at (untuk data penting)

// UUID sebagai public identifier (bukan primary key)
$table->uuid('uuid')->unique()->default(DB::raw('gen_random_uuid()'));

// Untuk relasi:
$table->foreignId('user_id')->constrained()->cascadeOnDelete();
```

**PostgreSQL spesifik:**
- Gunakan `text` bukan `string` untuk field panjang tak terbatas
- Gunakan `jsonb` bukan `json` untuk kolom JSON
- Gunakan `DB::raw('gen_random_uuid()')` untuk UUID default

### Model Conventions

```php
class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'price', 'stock', 'category_id'];

    protected $hidden = ['deleted_at'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'meta' => 'array',     // untuk jsonb column
    ];

    // Selalu definisikan relasi
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
```

---

## 🔧 Controller — Konvensi

### Pisahkan API Controller dan Web Controller

```
app/Http/Controllers/
├── Api/                    ← Controller untuk REST API
│   ├── AuthController.php
│   ├── ProductController.php
│   └── OrderController.php
└── Web/                    ← Controller untuk Blade views
    ├── DashboardController.php
    ├── ProductController.php
    └── OrderController.php
```

### API Controller Template

```php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;

class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(private ProductService $service) {}

    public function index(Request $request)
    {
        $products = $this->service->paginate($request->all());
        return $this->paginated(ProductResource::collection($products));
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->service->create($request->validated());
        return $this->success(new ProductResource($product), 'Produk berhasil dibuat', 201);
    }
}
```

### Wajib Gunakan:

- **Form Request** untuk validasi → `app/Http/Requests/`
- **API Resource** untuk transform response → `app/Http/Resources/`
- **Service Layer** untuk business logic → `app/Services/`
- **Repository Pattern** opsional untuk query complex → `app/Repositories/`

---

## 📋 Naming Conventions

| Item | Convention | Contoh |
|------|-----------|--------|
| Model | PascalCase | `ProductCategory` |
| Controller | PascalCase + Controller | `ProductController` |
| Migration | snake_case, deskriptif | `create_products_table` |
| Route name | dot notation | `products.index` |
| Blade view | snake_case | `product_category.blade.php` |
| CSS class | kebab-case | `.dt-table`, `.btn-primary` |
| JS function | camelCase | `fetchData()`, `sortIcon()` |
| API endpoint | kebab-case plural | `/api/v1/product-categories` |
| DB column | snake_case | `created_at`, `category_id` |

---

## 🚫 Hal yang DILARANG

1. ❌ Menulis `<style>` di file Blade manapun
2. ❌ Menulis `<script>` inline di file Blade (kecuali Alpine.js attribute `x-*`)
3. ❌ Include semua CSS/JS dalam satu file besar
4. ❌ Business logic di dalam Controller (harus di Service)
5. ❌ Query DB langsung di Blade (harus via Controller → Service)
6. ❌ Gunakan `$_GET`, `$_POST` langsung (gunakan `$request`)
7. ❌ Hardcode URL (gunakan `route()` atau `url()` helper)
8. ❌ Gunakan `alert()` / `confirm()` native browser (gunakan SweetAlert2)
9. ❌ Buat dropdown `<select>` tanpa class `select2` (kecuali dropdown HTML native yang memang by design)
10. ❌ Membuat tabel interaktif tanpa menggunakan Alpine.js datatable component
11. ❌ Menggunakan warna hardcode (misal `color: red`, `background: #333`) di luar token CSS yang sudah didefinisikan di `app.css`
12. ❌ Desain yang tidak responsive — setiap komponen wajib ditest di lebar 375px, 768px, dan 1280px
13. ❌ Menggunakan `position: fixed` untuk elemen konten tanpa mempertimbangkan mobile
14. ❌ Membuat tabel tanpa wrapper `.dt-responsive`

---

## ✅ Checklist Sebelum Commit

**Struktur & Kode:**
- [ ] Tidak ada `<style>` inline di Blade
- [ ] Tidak ada `<script>` inline di Blade
- [ ] CSS baru sudah diletakkan di partial yang sesuai
- [ ] Semua select2 sudah menggunakan class `select2`
- [ ] Flash message sudah lewat session `success`/`error`
- [ ] API response menggunakan format standar via `ApiResponse` trait
- [ ] Controller tidak memiliki logic bisnis (sudah dipindah ke Service)
- [ ] Migration menggunakan `softDeletes()` untuk tabel data utama
- [ ] Form Request sudah digunakan untuk validasi di API controller
- [ ] API Resource digunakan untuk transform response

**Desain & Responsive:**
- [ ] Warna menggunakan CSS token dari Blue Ocean palette (bukan hardcode)
- [ ] Sidebar bisa di-toggle di mobile (Alpine.js `sidebarOpen`)
- [ ] Semua tabel memiliki wrapper `.dt-responsive`
- [ ] Form menggunakan `.form-grid` untuk layout kolom
- [ ] Tampilan ditest di mobile (375px), tablet (768px), dan desktop (1280px)
- [ ] Touch target minimal 44×44px untuk semua button interaktif
- [ ] Tidak ada horizontal scroll di halaman utama (selain tabel)
- [ ] Header menggunakan `--gradient-header`, sidebar menggunakan `--gradient-sidebar`
