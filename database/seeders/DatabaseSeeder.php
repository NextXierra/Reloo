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
<div class="space-y-16 py-8 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        <!-- Hero Campaign Spotlight -->
        <section class="border border-[#EAEAEA] rounded-[6px] bg-white p-6 sm:p-10 md:p-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-7">
                    <h1 class="font-serif text-3xl sm:text-5xl lg:text-6xl text-[#111111] tracking-tight leading-[1.08] mb-6">
                        Pasar Barang Pilihan & Preloved Berkualitas
                    </h1>
                    <p class="text-[#52525B] text-xs sm:text-sm leading-relaxed mb-8 max-w-xl">
                        Belanja sneakers, tas branded, jam tangan, dan gadget berkualitas dengan harga transparan dan transaksi aman.
                    </p>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="/catalog" class="inline-flex items-center justify-center px-5 py-2.5 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                            Jelajahi Katalog Lengkap &rarr;
                        </a>
                        <a href="/catalog?condition=like_new" class="inline-flex items-center justify-center px-5 py-2.5 rounded-[5px] text-xs font-medium bg-transparent text-[#111111] border border-[#EAEAEA] hover:bg-[#F7F6F3] transition-colors whitespace-nowrap">
                            Koleksi Like New
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="rounded-[6px] border border-[#EAEAEA] p-3 bg-[#FBFBFA]">
                        <div class="aspect-[4/5] rounded-[4px] overflow-hidden bg-[#F7F6F3] relative">
                            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80" alt="Nike Air Max 270" class="w-full h-full object-cover">
                            <div class="absolute top-3 left-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#E1F3FE] text-[#17547D] border border-[#C2E4FC]">
                                    Like New
                                </span>
                            </div>
                            <div class="absolute bottom-3 left-3 right-3 p-3 bg-white/95 backdrop-blur-sm border border-[#EAEAEA] rounded-[4px] flex items-center justify-between">
                                <span class="font-medium text-xs text-[#111111]">Nike Air Max 270 Black White</span>
                                <span class="font-mono text-xs font-semibold text-[#111111]">Rp 1.250.000</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Category Quick Navigator Rail -->
        <section>
            <div class="flex items-end justify-between mb-6 pb-3 border-b border-[#EAEAEA]">
                <div>
                    <span class="font-mono text-[10px] text-[#71717A] tracking-wider uppercase block mb-1">KATEGORI PRODUK</span>
                    <h2 class="font-serif text-2xl sm:text-3xl text-[#111111] tracking-tight">Kategori Pilihan</h2>
                </div>
                <a href="/catalog" class="font-mono text-xs text-[#111111] hover:underline">Semua Kategori &rarr;</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                <a href="/catalog?category=sepatu" class="group block p-4 bg-white rounded-[6px] border border-[#EAEAEA] hover:border-[#111111] transition-colors">
                    <div class="aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-3">
                        <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=400&q=80" alt="Sepatu" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <span class="font-medium text-xs text-[#111111] block">Sepatu & Sneakers</span>
                </a>
                <a href="/catalog?category=tas" class="group block p-4 bg-white rounded-[6px] border border-[#EAEAEA] hover:border-[#111111] transition-colors">
                    <div class="aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-3">
                        <img src="https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=400&q=80" alt="Tas" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <span class="font-medium text-xs text-[#111111] block">Tas & Ransel</span>
                </a>
                <a href="/catalog?category=jam-tangan" class="group block p-4 bg-white rounded-[6px] border border-[#EAEAEA] hover:border-[#111111] transition-colors">
                    <div class="aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-3">
                        <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=400&q=80" alt="Jam Tangan" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <span class="font-medium text-xs text-[#111111] block">Jam Tangan</span>
                </a>
                <a href="/catalog?category=elektronik" class="group block p-4 bg-white rounded-[6px] border border-[#EAEAEA] hover:border-[#111111] transition-colors">
                    <div class="aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-3">
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=400&q=80" alt="Elektronik" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <span class="font-medium text-xs text-[#111111] block">Elektronik & Audio</span>
                </a>
                <a href="/catalog?category=pakaian" class="group block p-4 bg-white rounded-[6px] border border-[#EAEAEA] hover:border-[#111111] transition-colors">
                    <div class="aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-3">
                        <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=400&q=80" alt="Pakaian" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <span class="font-medium text-xs text-[#111111] block">Pakaian</span>
                </a>
            </div>
        </section>

        <!-- LIVE PRODUCTS GRID (8 Real E-Commerce Items) -->
        <section>
            <div class="flex items-end justify-between mb-6 pb-3 border-b border-[#EAEAEA]">
                <div>
                    <span class="font-mono text-[10px] text-[#71717A] tracking-wider uppercase block mb-1">PRODUK TERBARU</span>
                    <h2 class="font-serif text-2xl sm:text-3xl text-[#111111] tracking-tight">Katalog Produk Pilihan</h2>
                </div>
                <a href="/catalog" class="font-mono text-xs text-[#111111] hover:underline">Lihat Semua &rarr;</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Product 1: Nike Air Max 270 -->
                <div class="bg-white rounded-[6px] border border-[#EAEAEA] p-4 flex flex-col group hover:border-[#111111] transition-colors">
                    <div class="relative aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-4">
                        <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80" alt="Nike Air Max 270" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#E1F3FE] text-[#17547D] border border-[#C2E4FC]">
                            Like New
                        </span>
                    </div>
                    <span class="text-[10px] font-mono text-[#71717A] uppercase mb-1">Sepatu</span>
                    <h3 class="font-medium text-xs text-[#111111] mb-2 line-clamp-1">
                        <a href="/catalog/nike-air-max-270-black-white" class="hover:underline">Nike Air Max 270 Black White</a>
                    </h3>
                    <div class="mt-auto pt-3 border-t border-[#EAEAEA]">
                        <div class="flex items-baseline justify-between mb-3">
                            <span class="font-mono text-sm font-semibold text-[#111111]">Rp 1.250.000</span>
                            <span class="font-mono text-[10px] text-[#A1A1AA] line-through">Rp 2.200.000</span>
                        </div>
                        <a href="/catalog/nike-air-max-270-black-white" class="w-full inline-flex items-center justify-center py-2 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                            Rincian Barang
                        </a>
                    </div>
                </div>

                <!-- Product 2: Adidas Ultraboost -->
                <div class="bg-white rounded-[6px] border border-[#EAEAEA] p-4 flex flex-col group hover:border-[#111111] transition-colors">
                    <div class="relative aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-4">
                        <img src="https://images.unsplash.com/photo-1587563871167-1ee9c731aefb?auto=format&fit=crop&w=600&q=80" alt="Adidas Ultraboost Light" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#E1F3FE] text-[#17547D] border border-[#C2E4FC]">
                            Like New
                        </span>
                    </div>
                    <span class="text-[10px] font-mono text-[#71717A] uppercase mb-1">Sepatu</span>
                    <h3 class="font-medium text-xs text-[#111111] mb-2 line-clamp-1">
                        <a href="/catalog/adidas-ultraboost-light-core-black" class="hover:underline">Adidas Ultraboost Light Core Black</a>
                    </h3>
                    <div class="mt-auto pt-3 border-t border-[#EAEAEA]">
                        <div class="flex items-baseline justify-between mb-3">
                            <span class="font-mono text-sm font-semibold text-[#111111]">Rp 1.650.000</span>
                            <span class="font-mono text-[10px] text-[#A1A1AA] line-through">Rp 3.100.000</span>
                        </div>
                        <a href="/catalog/adidas-ultraboost-light-core-black" class="w-full inline-flex items-center justify-center py-2 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                            Rincian Barang
                        </a>
                    </div>
                </div>

                <!-- Product 3: Coach Cassie Bag -->
                <div class="bg-white rounded-[6px] border border-[#EAEAEA] p-4 flex flex-col group hover:border-[#111111] transition-colors">
                    <div class="relative aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-4">
                        <img src="https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=600&q=80" alt="Coach Cassie Crossbody" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#E1F3FE] text-[#17547D] border border-[#C2E4FC]">
                            Like New
                        </span>
                    </div>
                    <span class="text-[10px] font-mono text-[#71717A] uppercase mb-1">Tas</span>
                    <h3 class="font-medium text-xs text-[#111111] mb-2 line-clamp-1">
                        <a href="/catalog/coach-cassie-crossbody-bag-black" class="hover:underline">Coach Cassie Crossbody Bag Black</a>
                    </h3>
                    <div class="mt-auto pt-3 border-t border-[#EAEAEA]">
                        <div class="flex items-baseline justify-between mb-3">
                            <span class="font-mono text-sm font-semibold text-[#111111]">Rp 3.200.000</span>
                            <span class="font-mono text-[10px] text-[#A1A1AA] line-through">Rp 6.500.000</span>
                        </div>
                        <a href="/catalog/coach-cassie-crossbody-bag-black" class="w-full inline-flex items-center justify-center py-2 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                            Rincian Barang
                        </a>
                    </div>
                </div>

                <!-- Product 4: Fjallraven Kanken -->
                <div class="bg-white rounded-[6px] border border-[#EAEAEA] p-4 flex flex-col group hover:border-[#111111] transition-colors">
                    <div class="relative aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-4">
                        <img src="https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=600&q=80" alt="Fjallraven Kanken" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#FBF3DB] text-[#785100] border border-[#F5E5B5]">
                            Bagus
                        </span>
                    </div>
                    <span class="text-[10px] font-mono text-[#71717A] uppercase mb-1">Tas</span>
                    <h3 class="font-medium text-xs text-[#111111] mb-2 line-clamp-1">
                        <a href="/catalog/fjallraven-kanken-backpack-ochre" class="hover:underline">Fjallraven Kanken Backpack Ochre</a>
                    </h3>
                    <div class="mt-auto pt-3 border-t border-[#EAEAEA]">
                        <div class="flex items-baseline justify-between mb-3">
                            <span class="font-mono text-sm font-semibold text-[#111111]">Rp 680.000</span>
                            <span class="font-mono text-[10px] text-[#A1A1AA] line-through">Rp 1.350.000</span>
                        </div>
                        <a href="/catalog/fjallraven-kanken-backpack-ochre" class="w-full inline-flex items-center justify-center py-2 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                            Rincian Barang
                        </a>
                    </div>
                </div>

                <!-- Product 5: Seiko 5 Sports -->
                <div class="bg-white rounded-[6px] border border-[#EAEAEA] p-4 flex flex-col group hover:border-[#111111] transition-colors">
                    <div class="relative aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-4">
                        <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80" alt="Seiko 5 Sports" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#E1F3FE] text-[#17547D] border border-[#C2E4FC]">
                            Like New
                        </span>
                    </div>
                    <span class="text-[10px] font-mono text-[#71717A] uppercase mb-1">Jam Tangan</span>
                    <h3 class="font-medium text-xs text-[#111111] mb-2 line-clamp-1">
                        <a href="/catalog/seiko-5-sports-automatic-srpd55k1" class="hover:underline">Seiko 5 Sports Automatic SRPD55K1</a>
                    </h3>
                    <div class="mt-auto pt-3 border-t border-[#EAEAEA]">
                        <div class="flex items-baseline justify-between mb-3">
                            <span class="font-mono text-sm font-semibold text-[#111111]">Rp 2.850.000</span>
                            <span class="font-mono text-[10px] text-[#A1A1AA] line-through">Rp 4.100.000</span>
                        </div>
                        <a href="/catalog/seiko-5-sports-automatic-srpd55k1" class="w-full inline-flex items-center justify-center py-2 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                            Rincian Barang
                        </a>
                    </div>
                </div>

                <!-- Product 6: Casio G-Shock GA-2100 -->
                <div class="bg-white rounded-[6px] border border-[#EAEAEA] p-4 flex flex-col group hover:border-[#111111] transition-colors">
                    <div class="relative aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-4">
                        <img src="https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=600&q=80" alt="Casio G-Shock" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#EDF3EC] text-[#26542A] border border-[#D1E2CF]">
                            Baru
                        </span>
                    </div>
                    <span class="text-[10px] font-mono text-[#71717A] uppercase mb-1">Jam Tangan</span>
                    <h3 class="font-medium text-xs text-[#111111] mb-2 line-clamp-1">
                        <a href="/catalog/casio-g-shock-ga-2100-1a1-all-black" class="hover:underline">Casio G-Shock GA-2100-1A1 All Black</a>
                    </h3>
                    <div class="mt-auto pt-3 border-t border-[#EAEAEA]">
                        <div class="flex items-baseline justify-between mb-3">
                            <span class="font-mono text-sm font-semibold text-[#111111]">Rp 1.150.000</span>
                            <span class="font-mono text-[10px] text-[#A1A1AA] line-through">Rp 1.850.000</span>
                        </div>
                        <a href="/catalog/casio-g-shock-ga-2100-1a1-all-black" class="w-full inline-flex items-center justify-center py-2 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                            Rincian Barang
                        </a>
                    </div>
                </div>

                <!-- Product 7: Sony WH-1000XM4 -->
                <div class="bg-white rounded-[6px] border border-[#EAEAEA] p-4 flex flex-col group hover:border-[#111111] transition-colors">
                    <div class="relative aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-4">
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80" alt="Sony Headphone" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#E1F3FE] text-[#17547D] border border-[#C2E4FC]">
                            Like New
                        </span>
                    </div>
                    <span class="text-[10px] font-mono text-[#71717A] uppercase mb-1">Elektronik</span>
                    <h3 class="font-medium text-xs text-[#111111] mb-2 line-clamp-1">
                        <a href="/catalog/sony-wh-1000xm4-wireless-headphone" class="hover:underline">Sony WH-1000XM4 Wireless Headphone</a>
                    </h3>
                    <div class="mt-auto pt-3 border-t border-[#EAEAEA]">
                        <div class="flex items-baseline justify-between mb-3">
                            <span class="font-mono text-sm font-semibold text-[#111111]">Rp 2.950.000</span>
                            <span class="font-mono text-[10px] text-[#A1A1AA] line-through">Rp 4.500.000</span>
                        </div>
                        <a href="/catalog/sony-wh-1000xm4-wireless-headphone" class="w-full inline-flex items-center justify-center py-2 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                            Rincian Barang
                        </a>
                    </div>
                </div>

                <!-- Product 8: Levi's Type III Trucker -->
                <div class="bg-white rounded-[6px] border border-[#EAEAEA] p-4 flex flex-col group hover:border-[#111111] transition-colors">
                    <div class="relative aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-4">
                        <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=600&q=80" alt="Levis Trucker Jacket" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#FBF3DB] text-[#785100] border border-[#F5E5B5]">
                            Bagus
                        </span>
                    </div>
                    <span class="text-[10px] font-mono text-[#71717A] uppercase mb-1">Pakaian</span>
                    <h3 class="font-medium text-xs text-[#111111] mb-2 line-clamp-1">
                        <a href="/catalog/levis-type-iii-trucker-jacket-indigo" class="hover:underline">Levi's Type III Trucker Jacket Indigo</a>
                    </h3>
                    <div class="mt-auto pt-3 border-t border-[#EAEAEA]">
                        <div class="flex items-baseline justify-between mb-3">
                            <span class="font-mono text-sm font-semibold text-[#111111]">Rp 750.000</span>
                            <span class="font-mono text-[10px] text-[#A1A1AA] line-through">Rp 1.490.000</span>
                        </div>
                        <a href="/catalog/levis-type-iii-trucker-jacket-indigo" class="w-full inline-flex items-center justify-center py-2 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                            Rincian Barang
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
HTML;

        $homeCss = <<<'CSS'
.rlo-hero {
    font-family: inherit;
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
