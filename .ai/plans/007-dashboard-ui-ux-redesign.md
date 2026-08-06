# Rencana Redesain UI/UX Halaman Dashboard NusaMarket

Dokumen rencana kerja redesain halaman Dashboard (4 Stat Cards & Panel Transaksi Terbaru).

## Scope Pekerjaan
1. **4 Stat Cards**:
   - Border-left 4px warna khusus: Blue (Total Penjualan), Purple (Total Pesanan), Navy (Produk Aktif), Green (Pengguna Terdaftar).
   - Label uppercase kecil + ikon badge bulat di kanan atas.
   - Angka besar bold.
   - Mini sparkline (7 CSS bars, 3 bar terakhir disorot aksen card) di kanan bawah.
   - Small badge status di kiri bawah.

2. **Panel Transaksi Terbaru**:
   - Header panel + filter tab (Semua/Pending/Selesai) + tombol "Kelola Produk".
   - Kolom Pembeli: Avatar bulat inisial nama.
   - Kolom Toko: Chip dengan ikon toko.
   - Kolom Status: Warna unik per status (Pending=Amber, Processing=Purple, Completed=Green, Cancelled=Red).
   - Kolom Total: Rata kanan, font monospace, bold.
   - Kolom Aksi: Tombol ikon panah ke detail pesanan.
   - Footer pagination `{{ $recentOrders->links() }}` di bawah tabel.
