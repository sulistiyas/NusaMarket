<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Store;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Core Users (Admin, Seller, Buyer)
        $admin = User::create([
            'name' => 'Admin NusaMarket',
            'email' => 'admin@nusamarket.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jakarta Pusat, Indonesia',
        ]);

        $seller = User::create([
            'name' => 'Budi Penjual',
            'email' => 'seller@nusamarket.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'phone' => '081987654321',
            'address' => 'Bandung, Jawa Barat',
        ]);

        $buyer = User::create([
            'name' => 'Siti Pembeli',
            'email' => 'buyer@nusamarket.com',
            'password' => Hash::make('password'),
            'role' => 'buyer',
            'phone' => '085712345678',
            'address' => 'Surabaya, Jawa Timur',
        ]);

        // 2. Multi Stores
        $stores = [];
        $storeNames = [
            'Toko Nusantara Jaya',
            'Lokal Craft & Batik',
            'Gudang Gadget ID',
            'Dapur Rasa Indonesia',
            'Kopi & Camilan Nusantara'
        ];

        foreach ($storeNames as $index => $name) {
            $stores[] = Store::create([
                'user_id' => $seller->id,
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => "Penyedia barang unggulan {$name} resmi bergaransi dan kualitas terbaik.",
                'rating' => 4.80 + ($index * 0.04),
            ]);
        }

        // 3. 10 Categories
        $categoriesData = [
            ['name' => 'Elektronik & Gadget', 'description' => 'Perangkat gadget, smartphone, dan aksesori teknologi', 'icon' => 'fa-laptop'],
            ['name' => 'Pakaian & Fashion', 'description' => 'Busana pria, wanita, anak-anak, dan kain tradisional', 'icon' => 'fa-shirt'],
            ['name' => 'Makanan & Minuman', 'description' => 'Kuliner Nusantara, kopi, dan olahan makanan kering', 'icon' => 'fa-utensils'],
            ['name' => 'Otomotif & Aksesoris', 'description' => 'Perlengkapan berkendara dan suku cadang motor/mobil', 'icon' => 'fa-car'],
            ['name' => 'Kecantikan & Perawatan', 'description' => 'Skincare alami, kosmetik, dan perawatan tubuh', 'icon' => 'fa-spa'],
            ['name' => 'Kesehatan & Herbal', 'description' => 'Suplemen kesehatan, jamu herbal, dan alat medis', 'icon' => 'fa-heart-pulse'],
            ['name' => 'Rumah Tangga & Dekorasi', 'description' => 'Peralatan dapur, dekorasi ruangan, dan kebersihan', 'icon' => 'fa-house'],
            ['name' => 'Kerajinan Tangan & Seni', 'description' => 'Souvenir ukiran, anyaman, keramik, dan karya seni', 'icon' => 'fa-palette'],
            ['name' => 'Buku & Alat Tulis', 'description' => 'Buku pelajaran, novel, perlengkapan kantor, dan ATK', 'icon' => 'fa-book'],
            ['name' => 'Olahraga & Outdoor', 'description' => 'Perlengkapan gym, sepeda, camping, dan sepatu olahraga', 'icon' => 'fa-football'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[] = Category::create($cat);
        }

        // 4. 100 Real Products Generation
        $productTemplates = [
            // Elektronik
            ['name' => 'Headphone Wireless Noise Cancelling X1', 'price' => 1250000, 'cat_idx' => 0],
            ['name' => 'Smartwatch Sport Fit Waterproof 5ATM', 'price' => 750000, 'cat_idx' => 0],
            ['name' => 'Keyboard Mechanical RGB Gaming Switch Red', 'price' => 480000, 'cat_idx' => 0],
            ['name' => 'Mouse Wireless Silent Click Ergonomic', 'price' => 150000, 'cat_idx' => 0],
            ['name' => 'Powerbank Fast Charging 20000mAh PD3.0', 'price' => 320000, 'cat_idx' => 0],
            ['name' => 'Webcam Full HD 1080p with Noise Mic', 'price' => 290000, 'cat_idx' => 0],
            ['name' => 'Speaker Bluetooth Portable Bass Boost', 'price' => 380000, 'cat_idx' => 0],
            ['name' => 'Monitor LED 24 Inch Full HD 75Hz', 'price' => 1450000, 'cat_idx' => 0],
            ['name' => 'USB Hub Type-C 7 in 1 HDMI 4K', 'price' => 240000, 'cat_idx' => 0],
            ['name' => 'Earbuds TWS Bluetooth 5.3 Low Latency', 'price' => 220000, 'cat_idx' => 0],

            // Fashion
            ['name' => 'Kemeja Batik Solo Premium Cotton', 'price' => 275000, 'cat_idx' => 1],
            ['name' => 'Jaket Denim Vintage Classic Unisex', 'price' => 350000, 'cat_idx' => 1],
            ['name' => 'Celana Chino Slim Fit Stretch Casual', 'price' => 195000, 'cat_idx' => 1],
            ['name' => 'Gamis Syar\'i Premium Ceruty Babydoll', 'price' => 285000, 'cat_idx' => 1],
            ['name' => 'Kaos Polos Cotton Combed 30s Pack of 3', 'price' => 135000, 'cat_idx' => 1],
            ['name' => 'Sepatu Sneakers Canvas Low Top White', 'price' => 225000, 'cat_idx' => 1],
            ['name' => 'Topi Snapback Distro Premium Cotton', 'price' => 75000, 'cat_idx' => 1],
            ['name' => 'Tas Ransel Laptop Unisex Backpack 20L', 'price' => 210000, 'cat_idx' => 1],
            ['name' => 'Sandal Pria Casual Leather Strap', 'price' => 145000, 'cat_idx' => 1],
            ['name' => 'Dompet Kulit Asli Bifold Leather Slim', 'price' => 165000, 'cat_idx' => 1],

            // Makanan
            ['name' => 'Kopi Arabika Gayo Single Origin 250g', 'price' => 85000, 'cat_idx' => 2],
            ['name' => 'Sambal Bawang Cumi Asin Khas Surabaya 200g', 'price' => 38000, 'cat_idx' => 2],
            ['name' => 'Keripik Tempe Renyah Original Malang 500g', 'price' => 45000, 'cat_idx' => 2],
            ['name' => 'Rendang Sapi Kemasan Vakum siap Saji 300g', 'price' => 95000, 'cat_idx' => 2],
            ['name' => 'Madu Murni Forest Honey Organik 500ml', 'price' => 120000, 'cat_idx' => 2],
            ['name' => 'Teh Hijau Herbal Organik Premium 100g', 'price' => 55000, 'cat_idx' => 2],
            ['name' => 'Cokelat Batang Dark Chocolate 70% 100g', 'price' => 42000, 'cat_idx' => 2],
            ['name' => 'Kue Biji Ketapang Gurih Crispy 400g', 'price' => 35000, 'cat_idx' => 2],
            ['name' => 'Kopi Robusta Temanggung Roast Bean 500g', 'price' => 78000, 'cat_idx' => 2],
            ['name' => 'Minuman Bandrek Instan Rempah Nusantara Box', 'price' => 32000, 'cat_idx' => 2],

            // Otomotif
            ['name' => 'Helm Full Face SNI Aerodynamic Glossy', 'price' => 420000, 'cat_idx' => 3],
            ['name' => 'Oli Mesin Synthetic 10W-40 1 Liter', 'price' => 95000, 'cat_idx' => 3],
            ['name' => 'Sarung Tangan Motor Touchscreen Anti Selip', 'price' => 65000, 'cat_idx' => 3],
            ['name' => 'Cover Mobil Waterproof Outdoor Sedan/SUV', 'price' => 275000, 'cat_idx' => 3],
            ['name' => 'Pompa Ban Elektrik Portable Digital Auto', 'price' => 290000, 'cat_idx' => 3],
            ['name' => 'Kain Lap Microfiber Detailing 40x40cm 4 Pcs', 'price' => 48000, 'cat_idx' => 3],
            ['name' => 'Holder HP Motor Alumunium Anti Shake', 'price' => 85000, 'cat_idx' => 3],
            ['name' => 'Kaca Mata Sun Glasses Polarized Anti UV', 'price' => 110000, 'cat_idx' => 3],
            ['name' => 'Pembersih Injektor Carbon Cleaner 300ml', 'price' => 52000, 'cat_idx' => 3],
            ['name' => 'Klakson Keong Waterproof 12V Super Loud', 'price' => 135000, 'cat_idx' => 3],

            // Kecantikan
            ['name' => 'Sunscreen SPF 50 PA++++ Light Texture 50ml', 'price' => 78000, 'cat_idx' => 4],
            ['name' => 'Serum Niacinamide 10% Brightening 30ml', 'price' => 92000, 'cat_idx' => 4],
            ['name' => 'Facial Wash Gentle Cleansing Gel 100ml', 'price' => 55000, 'cat_idx' => 4],
            ['name' => 'Moisturizer Gel Hyaluronic Acid 50g', 'price' => 88000, 'cat_idx' => 4],
            ['name' => 'Lip Cream Velvet Matte Longlasting 4g', 'price' => 48000, 'cat_idx' => 4],
            ['name' => 'Micellar Water Hydrating Make Up Remover 250ml', 'price' => 45000, 'cat_idx' => 4],
            ['name' => 'Sheet Mask Aloe Vera Soothing Pack of 10', 'price' => 65000, 'cat_idx' => 4],
            ['name' => 'Body Lotion Olive Oil & Vitamin E 250ml', 'price' => 52000, 'cat_idx' => 4],
            ['name' => 'Shampoo Anti Dandruff Natural Extract 300ml', 'price' => 68000, 'cat_idx' => 4],
            ['name' => 'Parfum Eau De Parfum Fresh Floral 50ml', 'price' => 145000, 'cat_idx' => 4],

            // Kesehatan
            ['name' => 'Minyak Kayu Putih Asli Ambon 210ml', 'price' => 68000, 'cat_idx' => 5],
            ['name' => 'Vitamin C 1000mg Non Acidic 30 Tablet', 'price' => 85000, 'cat_idx' => 5],
            ['name' => 'Jamu Temulawak Organik Serbuk Instan 250g', 'price' => 42000, 'cat_idx' => 5],
            ['name' => 'Masker Medis 3 Ply BFE 99% Box 50 Pcs', 'price' => 35000, 'cat_idx' => 5],
            ['name' => 'Alat Cek Gula Darah Digital Tensimeter Pro', 'price' => 230000, 'cat_idx' => 5],
            ['name' => 'Minyak Habbatussauda Black Seed Oil 100 Kapsul', 'price' => 75000, 'cat_idx' => 5],
            ['name' => 'Thermomoter Digital Infra Red Non Contact', 'price' => 125000, 'cat_idx' => 5],
            ['name' => 'Freshcare Aromatherapy Roll On 10ml Pack 3', 'price' => 42000, 'cat_idx' => 5],
            ['name' => 'Korset Terapi Tulang Belakang Support Belakang', 'price' => 115000, 'cat_idx' => 5],
            ['name' => 'Hand Sanitizer Gel Antiseptik 500ml Pump', 'price' => 38000, 'cat_idx' => 5],

            // Rumah Tangga
            ['name' => 'Blender Portable USB Juicer 6 Pisau Stainless', 'price' => 145000, 'cat_idx' => 6],
            ['name' => 'Panci Frypan Anti Lengket Granite Coating 24cm', 'price' => 175000, 'cat_idx' => 6],
            ['name' => 'Sapu & Pengki Set Modern Minimalis Ergonomis', 'price' => 68000, 'cat_idx' => 6],
            ['name' => 'Lampu LED Bulb Save Energy 12 Watt Warm White', 'price' => 32000, 'cat_idx' => 6],
            ['name' => 'Rak Bumbu Dapur Stainless 3 Susun Minimalis', 'price' => 125000, 'cat_idx' => 6],
            ['name' => 'Pisau Dapur Set Stainless Steel 5 in 1 High Grade', 'price' => 98000, 'cat_idx' => 6],
            ['name' => 'Toples Kaca Kedap Udara Airtight Jar 1 Liter', 'price' => 45000, 'cat_idx' => 6],
            ['name' => 'Sprei Polos Microtex Halus Lembut 160x200', 'price' => 135000, 'cat_idx' => 6],
            ['name' => 'Air Humidifier Diffuser Aromatherapy 300ml', 'price' => 110000, 'cat_idx' => 6],
            ['name' => 'Timbangan Dapur Digital Precision 5kg', 'price' => 65000, 'cat_idx' => 6],

            // Kerajinan
            ['name' => 'Tas Anyaman Pandan Khas Rajapolah Tasik', 'price' => 125000, 'cat_idx' => 7],
            ['name' => 'Patung Ukiran Kayu Jati Asli Jepara 25cm', 'price' => 285000, 'cat_idx' => 7],
            ['name' => 'Cangkir Gerabah Keramik Handmade Kasongan', 'price' => 48000, 'cat_idx' => 7],
            ['name' => 'Wayang Kulit Asli Mini Souvenir Frame', 'price' => 165000, 'cat_idx' => 7],
            ['name' => 'Kain Tenun Ikat Jepara Motif Etnik 200x100cm', 'price' => 230000, 'cat_idx' => 7],
            ['name' => 'Asbak Kayu Mahoni Ukir Tradisional', 'price' => 35000, 'cat_idx' => 7],
            ['name' => 'Tikar Rumbia Alami Eco Friendly 150x200cm', 'price' => 95000, 'cat_idx' => 7],
            ['name' => 'Lampu Hias Anyaman Bambu Minimalis Etnik', 'price' => 145000, 'cat_idx' => 7],
            ['name' => 'Kipas Kain Batik Lipat Souvenir Handcrafted', 'price' => 25000, 'cat_idx' => 7],
            ['name' => 'Kotak Perhiasan Kayu Sonokeling Inlay', 'price' => 185000, 'cat_idx' => 7],

            // Buku
            ['name' => 'Buku Belajar Laravel 13 & Clean Architecture', 'price' => 120000, 'cat_idx' => 8],
            ['name' => 'Novel Sejarah Nusantara & Peradaban Emas', 'price' => 95000, 'cat_idx' => 8],
            ['name' => 'Buku Panduan UMKM Naik Kelas & Go Digital', 'price' => 88000, 'cat_idx' => 8],
            ['name' => 'Notebook Hardcover Dotted A5 Bullet Journal', 'price' => 55000, 'cat_idx' => 8],
            ['name' => 'Pulpen Gel Black 0.5mm Pack of 10 Pcs', 'price' => 35000, 'cat_idx' => 8],
            ['name' => 'Sticky Notes Colorful Tabs Index Memo Set', 'price' => 22000, 'cat_idx' => 8],
            ['name' => 'Buku Resep Masakan Otentik 34 Provinsi', 'price' => 110000, 'cat_idx' => 8],
            ['name' => 'Pensil Warna 24 Warna Classic Edition', 'price' => 48000, 'cat_idx' => 8],
            ['name' => 'Map Dokumen Expanding File 12 Pocket A4', 'price' => 62000, 'cat_idx' => 8],
            ['name' => 'Buku Filosofi Teras Stoikisme Modern', 'price' => 85000, 'cat_idx' => 8],

            // Olahraga
            ['name' => 'Matras Yoga TPE Non Slip Extra Thick 6mm', 'price' => 165000, 'cat_idx' => 9],
            ['name' => 'Resistance Band Fitness Set 5 Level Elastic', 'price' => 75000, 'cat_idx' => 9],
            ['name' => 'Tali Skipping Speed Jump Rope Bearing Pro', 'price' => 45000, 'cat_idx' => 9],
            ['name' => 'Dumbbell Barbel Rubber Coated 5kg Pair', 'price' => 220000, 'cat_idx' => 9],
            ['name' => 'Botol Minum Olahraga Sport Water Bottle 1L', 'price' => 58000, 'cat_idx' => 9],
            ['name' => 'Kacamata Renang Anti Fog UV Protection Set', 'price' => 92000, 'cat_idx' => 9],
            ['name' => 'Raket Badminton Carbon Graphite Lightweight', 'price' => 340000, 'cat_idx' => 9],
            ['name' => 'Bola Sepak Size 5 High Durability PU Leather', 'price' => 185000, 'cat_idx' => 9],
            ['name' => 'Tenda Camping Dome Waterproof 4 Person', 'price' => 450000, 'cat_idx' => 9],
            ['name' => 'Jersey Running Quick Dry Breathable Shirt', 'price' => 98000, 'cat_idx' => 9],
        ];

        $createdProducts = [];
        $pIndex = 0;
        foreach ($productTemplates as $tpl) {
            $pIndex++;
            $store = $stores[$pIndex % count($stores)];
            $category = $categories[$tpl['cat_idx']];

            $product = Product::create([
                'store_id' => $store->id,
                'category_id' => $category->id,
                'name' => $tpl['name'],
                'description' => "Produk berkualitas tinggi {$tpl['name']} dari toko {$store->name}. Terjamin original, bergaransi resmi, dan dikemas dengan aman.",
                'price' => $tpl['price'],
                'stock' => rand(15, 120),
                'weight' => rand(100, 1500),
                'is_active' => true,
            ]);

            $createdProducts[] = $product;
        }

        // 5. Real Order Data Dummy Seeders (Transactions & Order Items)
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        
        for ($i = 1; $i <= 12; $i++) {
            $selectedStore = $stores[$i % count($stores)];
            $selectedStatus = $statuses[$i % count($statuses)];
            
            // Pick 2-3 random products from the created list
            $randomProducts = collect($createdProducts)->where('store_id', $selectedStore->id)->random(min(3, count($createdProducts)));
            
            if ($randomProducts->isEmpty()) {
                $randomProducts = collect($createdProducts)->random(2);
            }

            $subtotal = 0;
            $orderItemsData = [];

            foreach ($randomProducts as $prod) {
                $qty = rand(1, 3);
                $itemSubtotal = $prod->price * $qty;
                $subtotal += $itemSubtotal;

                $orderItemsData[] = [
                    'product_id' => $prod->id,
                    'product_name' => $prod->name,
                    'price' => $prod->price,
                    'quantity' => $qty,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $shippingFee = 15000;
            $totalAmount = $subtotal + $shippingFee;

            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'buyer_id' => $buyer->id,
                'store_id' => $selectedStore->id,
                'total_amount' => $totalAmount,
                'shipping_fee' => $shippingFee,
                'status' => $selectedStatus,
                'payment_status' => 'paid',
                'shipping_address' => [
                    'recipient_name' => 'Siti Pembeli',
                    'phone' => '085712345678',
                    'address' => 'Jl. Gubeng Kertajaya No. 12B',
                    'city' => 'Surabaya',
                    'postal_code' => '60281',
                ],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);

            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }
        }
    }
}
