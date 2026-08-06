# Plan Pengerjaan: Modern UI/UX Design System Overhaul (NusaMarket)

> **Goal**: Mengubah seluruh desain sistem NusaMarket yang sebelumnya kaku menjadi ultra-modern, elegan, dan interaktif (Glassmorphism, Dynamic Gradients, Soft Depth Shadows, Micro-animations) sesuai dengan konvensi Blue Ocean Theme & instruksi paten di `.ai/instructions.md`.

---

## 🧭 Prinsip Utama Refactoring UI/UX

1. **Blue Ocean Theme Compliance**: Tetap mematuhi palet resmi (`--primary: #1e6fd9`, `--primary-deeper: #0b3a75`, `--ocean: #0e7490`).
2. **Modular Vanilla CSS Partials**: Semua perbaikan ditulis di file partial `public/css/` (`app.css`, `main.css`, `header.css`, `sidebar.css`, `card.css`, `button.css`, `form.css`, `datatable.css`, `badge.css`, `responsive.css`).
3. **Zero Inline Styles/Scripts**: Memastikan seluruh Blade view bersih dari `<style>` & `<script>` inline.
4. **Fluid Responsiveness & Touch Target**: Responsif sempurna di layar 375px – 1440px+ dengan min touch target 44px.

---

## 📋 Detail Perubahan Design Tokens & Modul CSS

### 1. `public/css/app.css` (Design Tokens & Glassmorphism)
- Penambahan token radius modern (`--radius-sm: 6px`, `--radius-md: 10px`, `--radius-lg: 16px`, `--radius-xl: 24px`, `--radius-pill: 9999px`).
- Penambahan token bayangan berlapis (`--shadow-xs`, `--shadow-sm`, `--shadow-md`, `--shadow-lg`, `--shadow-xl`, `--shadow-glow-primary`).
- Token Glassmorphism (`--glass-bg`, `--glass-border`, `--glass-shadow`, `--backdrop-blur`).
- Presets gradien modern & smoothing font global.

### 2. `public/css/main.css` (Layout & Ambient Background)
- Ambient background glow effect dengan radial overlay halus.
- Modernisasi breadcrumb (pill item dengan micro-separator chevron & active highlight).
- Utility spacing & layout grid responsif modern.

### 3. `public/css/header.css` (Glassmorphic Top Navbar)
- Navbar melayang glassmorphic dengan `backdrop-filter: blur(16px)`.
- Brand logo dengan icon gradien menyala & efek hover lift.
- User dropdown menu berbentuk pill halus dengan ring avatar indicator & online dot.
- Mobile toggle button dengan efek hover & micro-transition.

### 4. `public/css/sidebar.css` (Sleek Modern Navigation)
- Sidebar dengan background dark navy-teal gradient & efek glass.
- Active menu item menggunakan rounded pill dengan ambient glow shadow (`box-shadow: 0 4px 14px rgba(30, 111, 217, 0.4)`).
- Micro-interactions: link hover bergeser halus (`translateX(4px)`), icon glow, dan badge pill pemberitahuan.

### 5. `public/css/card.css` (Elevated & Metric Cards)
- Modern rounded cards dengan border subtle & bayangan multi-depth.
- Hover state melayang (`translateY(-4px)` & glow subtle).
- Metric / Stat Card modern: Icon container gradien melingkar/rounded-xl, badge trend persentase interaktif (`+12.5%` green pill), dan tipografi angka yang kontras & tegas.

### 6. `public/css/button.css` (Interactive & Gradient Buttons)
- Button gradien modern dengan multi-layer shadow.
- Micro-animations: Hover lift (`translateY(-2px)` + glow shadow), active click compression (`scale(0.97)`), serta shine overlay animasi.
- Variasi bentuk button: Standard rounded, Pill button, dan Soft Translucent badge buttons.

### 7. `public/css/form.css` (Inputs, Controls & Select2)
- Input field dengan glassmorphic background & border halus.
- Focus ring aura 4px dengan warna primary soft (`rgba(30, 111, 217, 0.2)`).
- Custom Select2 dropdown container dengan sudut rounded modern & active row highlight.

### 8. `public/css/datatable.css` (Modern Table & Toolbar)
- Wrapper tabel card melengkung halus (`16px radius`) dengan overflow shadow.
- Floating search bar dengan icon pencarian interaktif.
- Header tabel uppercase dengan tracking huruf & active sort icon chevron.
- Highlighting baris tabel saat hover dengan background kebiruan soft.

### 9. `public/css/badge.css` (Pill Badges & Status Indicators)
- Soft translucent background (`rgba`) dengan border 1px matching tone.
- Live status dot indicator dengan efek denyut animasi (`ping animation`).

### 10. `public/css/responsive.css` (Breakpoints & Mobile Polish)
- Penyesuaian penuh untuk mobile drawer sidebar, responsive form grid, dan responsive datatable overflow.

---

## 🔍 Verifikasi & Uji Coba

- Memastikan `public/css/app.css` meng-import seluruh partial dengan urutan yang benar.
- Menjalankan testing browser/preview halaman dashboard & halaman lainnya untuk mengonfirmasi keindahan UI/UX baru.
- Memastikan tidak ada breaking layout di mobile (375px), tablet (768px), maupun desktop (1280px+).
