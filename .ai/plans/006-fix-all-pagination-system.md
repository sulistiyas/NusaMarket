# Rencana Perbaikan Seluruh Sistem Pagination di NusaMarket

Dokumen ini berisi rencana perbaikan sistem pagination di NusaMarket (API Datatable, Service Layer, dan Blade Views).

## Root Cause Analysis
1. `ApiResponse::paginated`: `AnonymousResourceCollection` tidak meng-expose `currentPage()`, `lastPage()`, dll secara langsung, melainkan melalui objek `$paginator->resource`. Ini menyebabkan `last_page` selalu 1.
2. `OrderService`: `getUserOrders()` menggunakan `->get()` alih-alih `->paginate()`, dan `orders/index.blade.php` tidak memiliki link pagination.
3. Blade pagination default Laravel merender Tailwind CSS yang tidak kompatibel dengan CSS NusaMarket.
4. `ProductService` & `CategoryService` mengabaikan parameter sorting (`sort_by`, `sort_order`).

## Perubahan yang Akan Dilakukan
1. **`app/Traits/ApiResponse.php`**: Unwrap ResourceCollection untuk mengambil metadata `currentPage`, `lastPage`, `perPage`, `total`.
2. **`app/Providers/AppServiceProvider.php`**: Set default pagination view ke `components.pagination`.
3. **`resources/views/components/pagination.blade.php`**: Buat Blade view pagination Vanilla CSS dengan Blue Ocean Theme.
4. **`app/Services/ProductService.php` & `CategoryService.php`**: Tambahkan penanganan `sort_by` dan `sort_order`.
5. **`app/Services/OrderService.php` & `orders/index.blade.php`**: Ubah `getUserOrders` menggunakan `paginate()` dan tambahkan `{{ $orders->links() }}`.
6. **`public/js/datatable.js`**: Reset `currentPage` ke 1 saat pencarian/filter berubah.
7. **`public/css/datatable.css`**: Tambahkan styling `.pagination` Vanilla CSS.
