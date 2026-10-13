<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Auth\Models\UserAddress;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductImage;
use App\Modules\Cms\Models\CmsPage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Default Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@reloo.id'],
            [
                'name' => 'Admin Reloo',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'user@reloo.id'],
            [
                'name' => 'Budi Santoso',
                'phone' => '089876543210',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'email_verified_at' => now(),
            ]
        );

        // Seed Customer Default Address
        UserAddress::firstOrCreate(
            ['user_id' => $customer->id, 'recipient_name' => 'Budi Santoso'],
            [
                'phone_number' => '089876543210',
                'address_line' => 'Jl. Sudirman Kav. 52-53, Senayan',
                'province_name' => 'DKI Jakarta',
                'city_name' => 'Jakarta Selatan',
                'district_name' => 'Kebayoran Baru',
                'postal_code' => '12190',
                'is_default' => true,
            ]
        );

        // 2. Seed Categories
        $categoriesData = [
            [
                'name' => 'Sepatu',
                'slug' => 'sepatu',
                'description' => 'Sneakers, boots, dan sepatu formal pilihan berkualitas baru & preloved terkurasi.',
                'image_path' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Tas',
                'slug' => 'tas',
                'description' => 'Backpack, tote bag, sling bag, dan tas kulit branded bergaransi original.',
                'image_path' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Jam Tangan',
                'slug' => 'jam-tangan',
                'description' => 'Jam tangan mewah, analog klasik, dan smartwatch koleksi terbaik.',
                'image_path' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Elektronik',
                'slug' => 'elektronik',
                'description' => 'Gadget, audio, kamera retro, dan perangkat elektronik fungsional teruji.',
                'image_path' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Pakaian',
                'slug' => 'pakaian',
                'description' => 'Jaket kulit, denim vintage, hoodie, dan outfit santai kurasi eksklusif.',
                'image_path' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=600&q=80',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $catData) {
            $categories[$catData['slug']] = Category::firstOrCreate(
                ['slug' => $catData['slug']],
                $catData
            );
        }

        // 3. Seed Products
        $productsData = [
            [
                'category_slug' => 'sepatu',
                'name' => 'Nike Air Max 270 Black White',
                'slug' => 'nike-air-max-270-black-white',
                'sku' => 'RLO-SH-001',
                'condition' => 'like_new',
                'price' => 1250000,
                'original_price' => 2200000,
                'weight_grams' => 1100,
                'stock' => 3,
                'description' => "Sepatu sneakers Nike Air Max 270 Black White original preloved.\n\nKondisi 9.5/10 (Seperti Baru). Outsole masih tebal, insole original empuk, no minus sobek, siap pakai langsung gaya.",
                'images' => [
                    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1608231387042-66d1773070a5?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'sepatu',
                'name' => 'Adidas Ultraboost Light Core Black',
                'slug' => 'adidas-ultraboost-light-core-black',
                'sku' => 'RLO-SH-002',
                'condition' => 'new',
                'price' => 2400000,
                'original_price' => 3100000,
                'weight_grams' => 950,
                'stock' => 5,
                'description' => "Brand New In Box (BNIB) Adidas Ultraboost Light.\n\nKondisi 10/10 (Baru). Sepatu lari premium dengan bantalan Boost ternyaman, lengkap tag dan struk pembelian resmi.",
                'images' => [
                    'https://images.unsplash.com/photo-1584735935682-2f2b69dff9d2?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'tas',
                'name' => 'Fjallraven Kanken Classic Backpack Ochre',
                'slug' => 'fjallraven-kanken-classic-backpack-ochre',
                'sku' => 'RLO-BG-001',
                'condition' => 'good',
                'price' => 680000,
                'original_price' => 1350000,
                'weight_grams' => 600,
                'stock' => 2,
                'description' => "Tas punggung Fjallraven Kanken warna Ochre Yellow original.\n\nKondisi 8.5/10 (Bagus). Bahan Vinylon F awet tahan air, retsleting YKK mulus, ada pemakaian wajar di bagian bawah, tanpa robek.",
                'images' => [
                    'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'tas',
                'name' => 'Coach Vintage Leather Duffle Bag',
                'slug' => 'coach-vintage-leather-duffle-bag',
                'sku' => 'RLO-BG-002',
                'condition' => 'fair',
                'price' => 1850000,
                'original_price' => 5500000,
                'weight_grams' => 1400,
                'stock' => 1,
                'description' => "Tas kulit asli Coach Vintage Duffle koleksi antik.\n\nKondisi 7.5/10 (Cukup). Kulit tebal berpatina alami vintage yang estetik, tali selempang kokoh, hardware kuningan sedikit pudar alami.",
                'images' => [
                    'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'jam-tangan',
                'name' => 'Seiko 5 Sports Automatic SRPD55K1',
                'slug' => 'seiko-5-sports-automatic-srpd55k1',
                'sku' => 'RLO-WT-001',
                'condition' => 'like_new',
                'price' => 2850000,
                'original_price' => 4100000,
                'weight_grams' => 450,
                'stock' => 2,
                'description' => "Jam tangan mekanik otomatis Seiko 5 Sports dial hitam klasik.\n\nKondisi 9.5/10 (Seperti Baru). Kaliber 4R36 power reserve 41 jam, bezel rotating lancar, kaca hardlex mulus tanpa baret, fullset box dan kartu garansi.",
                'images' => [
                    'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'jam-tangan',
                'name' => 'Apple Watch Series 8 GPS 45mm Midnight',
                'slug' => 'apple-watch-series-8-gps-45mm-midnight',
                'sku' => 'RLO-WT-002',
                'condition' => 'good',
                'price' => 4300000,
                'original_price' => 6999000,
                'weight_grams' => 350,
                'stock' => 3,
                'description' => "Smartwatch Apple Watch Series 8 Aluminium Midnight.\n\nKondisi 9/10 (Bagus). Battery Health 94%, fungsi sensor ECG dan detak jantung 100% normal, kelengkapan kabel charger magnetik original.",
                'images' => [
                    'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'elektronik',
                'name' => 'Sony WH-1000XM4 Noise Canceling Headphones',
                'slug' => 'sony-wh-1000xm4-noise-canceling-headphones',
                'sku' => 'RLO-EL-001',
                'condition' => 'like_new',
                'price' => 2950000,
                'original_price' => 4500000,
                'weight_grams' => 800,
                'stock' => 4,
                'description' => "Headphone nirkabel terbaik kelas dunia Sony WH-1000XM4 warna Hitam.\n\nKondisi 9.5/10 (Seperti Baru). ANC kedap suara luar biasa, baterai tahan 30 jam, earpad empuk bebas ngelupas, lengkap hardcase dan kabel.",
                'images' => [
                    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'pakaian',
                'name' => 'Levi\'s Vintage Denim Trucker Jacket Type III',
                'slug' => 'levis-vintage-denim-trucker-jacket-type-iii',
                'sku' => 'RLO-CL-001',
                'condition' => 'good',
                'price' => 750000,
                'original_price' => 1599000,
                'weight_grams' => 900,
                'stock' => 1,
                'description' => "Jaket jeans denim Levi's original Type III Trucker Jacket warna Medium Indigo.\n\nKondisi 8.8/10 (Bagus). Bahan rigid denim tebal, fading alami keren khas jaket vintage, kancing kuningan utuh semua.",
                'images' => [
                    'https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=800&q=80',
                ],
            ],
        ];

        foreach ($productsData as $item) {
            $category = $categories[$item['category_slug']] ?? null;
            if (!$category) {
                continue;
            }

            $product = Product::firstOrCreate(
                ['sku' => $item['sku']],
                [
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'slug' => $item['slug'],
                    'condition' => $item['condition'],
                    'price' => $item['price'],
                    'original_price' => $item['original_price'],
                    'weight_grams' => $item['weight_grams'],
                    'stock' => $item['stock'],
                    'description' => $item['description'],
                    'is_active' => true,
                ]
            );

            // Add images
            foreach ($item['images'] as $idx => $imgUrl) {
                ProductImage::firstOrCreate(
                    ['product_id' => $product->id, 'image_path' => $imgUrl],
                    [
                        'is_primary' => ($idx === 0),
                        'order_index' => $idx,
                    ]
                );
            }
        }

        // 4. Seed Default CmsPage (Homepage)
        $homeHtml = <<<'HTML'
<section class="py-12 px-4 max-w-7xl mx-auto">
    <!-- Hero Banner -->
    <div class="relative rounded-3xl overflow-hidden bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-8 md:p-16 shadow-2xl mb-12">
        <div class="max-w-2xl relative z-10">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/30 text-indigo-300 border border-indigo-400/30 mb-4">
                Terkurasi & 100% Bergaransi Original
            </span>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight leading-tight mb-4">
                Temukan Barang Pilihan & <span class="text-indigo-400">Preloved Berkualitas</span>
            </h1>
            <p class="text-slate-300 text-base md:text-lg mb-8 leading-relaxed">
                Platform marketplace terpercaya untuk transaksi aman produk baru dan secondhand idaman. Mulai dari sneakers langka hingga jam tangan mewah.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="/catalog" class="px-6 py-3.5 rounded-xl font-bold bg-indigo-600 hover:bg-indigo-500 text-white transition shadow-lg shadow-indigo-600/30 inline-flex items-center gap-2">
                    Jelajahi Katalog
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="/catalog?condition=like_new" class="px-6 py-3.5 rounded-xl font-bold bg-white/10 hover:bg-white/20 text-white backdrop-blur transition border border-white/20">
                    Koleksi Like New
                </a>
            </div>
        </div>
        <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Trust Badges -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-16">
        <div class="flex items-center gap-4 p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-sm">Kurasi Fisik Ketat</h4>
                <p class="text-xs text-slate-500">Kondisi riil terverifikasi</p>
            </div>
        </div>
        <div class="flex items-center gap-4 p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-sm">Pembayaran Aman</h4>
                <p class="text-xs text-slate-500">Midtrans & Manual Transfer</p>
            </div>
        </div>
        <div class="flex items-center gap-4 p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-sm">Pengiriman Cepat</h4>
                <p class="text-xs text-slate-500">JNE, SiCepat, J&T Express</p>
            </div>
        </div>
        <div class="flex items-center gap-4 p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-sm">Harga Kompetitif</h4>
                <p class="text-xs text-slate-500">Hemat hingga 60% harga baru</p>
            </div>
        </div>
    </div>

    <!-- Category Grid -->
    <div class="mb-16">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Kategori Pilihan</h2>
                <p class="text-slate-500 text-sm">Pilih kategori favorit Anda</p>
            </div>
            <a href="/catalog" class="text-indigo-600 font-semibold text-sm hover:underline">Semua Kategori &rarr;</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <a href="/catalog?category=sepatu" class="group relative rounded-2xl overflow-hidden aspect-square border border-slate-200 bg-slate-100 flex flex-col justify-end p-4 hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=400&q=80" alt="Sepatu" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                <span class="relative z-10 font-bold text-white text-base">Sepatu</span>
            </a>
            <a href="/catalog?category=tas" class="group relative rounded-2xl overflow-hidden aspect-square border border-slate-200 bg-slate-100 flex flex-col justify-end p-4 hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=400&q=80" alt="Tas" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                <span class="relative z-10 font-bold text-white text-base">Tas</span>
            </a>
            <a href="/catalog?category=jam-tangan" class="group relative rounded-2xl overflow-hidden aspect-square border border-slate-200 bg-slate-100 flex flex-col justify-end p-4 hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=400&q=80" alt="Jam Tangan" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                <span class="relative z-10 font-bold text-white text-base">Jam Tangan</span>
            </a>
            <a href="/catalog?category=elektronik" class="group relative rounded-2xl overflow-hidden aspect-square border border-slate-200 bg-slate-100 flex flex-col justify-end p-4 hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=400&q=80" alt="Elektronik" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                <span class="relative z-10 font-bold text-white text-base">Elektronik</span>
            </a>
            <a href="/catalog?category=pakaian" class="group relative rounded-2xl overflow-hidden aspect-square border border-slate-200 bg-slate-100 flex flex-col justify-end p-4 hover:shadow-lg transition">
                <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=400&q=80" alt="Pakaian" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                <span class="relative z-10 font-bold text-white text-base">Pakaian</span>
            </a>
        </div>
    </div>
</section>
HTML;

        $homeCss = <<<'CSS'
.hero-banner {
    transition: transform 0.2s ease-in-out;
}
CSS;

        CmsPage::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Beranda Utama Reloo',
                'slug' => 'home',
                'gjs_data' => [
                    'components' => [],
                    'styles' => [],
                ],
                'html_content' => $homeHtml,
                'css_content' => $homeCss,
                'is_homepage' => true,
                'is_active' => true,
            ]
        );
    }
}
