<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        $admin = User::create([
            'name' => 'Admin NusaMarket',
            'email' => 'admin@nusamarket.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jakarta Pusat, Indonesia',
        ]);

        // 2. Seller User & Store
        $seller = User::create([
            'name' => 'Budi Penjual',
            'email' => 'seller@nusamarket.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'phone' => '081987654321',
            'address' => 'Bandung, Jawa Barat',
        ]);

        $store = Store::create([
            'user_id' => $seller->id,
            'name' => 'Toko Nusantara Jaya',
            'slug' => 'toko-nusantara-jaya',
            'description' => 'Penyedia barang berkualitas khas Nusantara dan elektronik terpercaya.',
            'rating' => 4.95,
        ]);

        // 3. Buyer User
        $buyer = User::create([
            'name' => 'Siti Pembeli',
            'email' => 'buyer@nusamarket.com',
            'password' => Hash::make('password'),
            'role' => 'buyer',
            'phone' => '085712345678',
            'address' => 'Surabaya, Jawa Timur',
        ]);

        // 4. Categories
        $cat1 = Category::create(['name' => 'Elektronik', 'description' => 'Perangkat gadget dan teknologi terbaru', 'icon' => 'fa-laptop']);
        $cat2 = Category::create(['name' => 'Pakaian & Fashion', 'description' => 'Busana modern dan pakaian batik Nusantara', 'icon' => 'fa-shirt']);
        $cat3 = Category::create(['name' => 'Makanan & Minuman', 'description' => 'Kuliner olahan lokal dan camilan sehat', 'icon' => 'fa-utensils']);
        $cat4 = Category::create(['name' => 'Otomotif & Aksesoris', 'description' => 'Perlengkapan berkendara dan komponen motor/mobil', 'icon' => 'fa-car']);

        // 5. Products
        Product::create([
            'store_id' => $store->id,
            'category_id' => $cat1->id,
            'name' => 'Wireless Noise Cancelling Headphone X1',
            'description' => 'Headphone premium suara jernih dengan fitur aktif noise cancellation dan baterai tahan 30 jam.',
            'price' => 1250000,
            'stock' => 25,
            'weight' => 350,
            'images' => ['https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80'],
        ]);

        Product::create([
            'store_id' => $store->id,
            'category_id' => $cat2->id,
            'name' => 'Kemeja Batik Solo Premium Cotton',
            'description' => 'Kemeja batik pria lengan panjang motif tradisional dengan bahan katun halus dan nyaman.',
            'price' => 275000,
            'stock' => 50,
            'weight' => 250,
            'images' => ['https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=600&q=80'],
        ]);

        Product::create([
            'store_id' => $store->id,
            'category_id' => $cat3->id,
            'name' => 'Kopi Arabika Gayo Single Origin 250g',
            'description' => 'Biji kopi sangrai arabika Gayo asli dengan rasa fruity aroma wangi yang khas.',
            'price' => 85000,
            'stock' => 100,
            'weight' => 250,
            'images' => ['https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=600&q=80'],
        ]);
    }
}
