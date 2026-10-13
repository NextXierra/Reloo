/**
 * Reloo Marketplace - Native GrapesJS E-Commerce Blocks Plugin
 * Drag & Drop components for E-Commerce landing pages & homepage.
 */
(function() {
    'use strict';

    function relooGjsPlugin(editor) {
        const bm = editor.BlockManager;

        // Custom canvas styles to guarantee proper styling inside GrapesJS iframe
        const canvasCss = `
            .rlo-badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 600; }
            .rlo-btn { display: inline-flex; align-items: center; justify-content: center; padding: 10px 20px; border-radius: 10px; font-weight: 700; text-decoration: none; transition: all 0.2s; cursor: pointer; }
            .rlo-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; }
            .rlo-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08); }
        `;
        editor.addComponents(`<style>${canvasCss}</style>`);

        // 1. Hero Split 2-Kolom
        bm.add('reloo-hero-split', {
            category: 'Hero & Banner',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="32" height="26" viewBox="0 0 40 34" fill="none" stroke="currentColor" style="margin: 0 auto 4px; display:block;">
                        <rect x="2" y="3" width="36" height="28" rx="3" stroke-width="2"/>
                        <rect x="6" y="8" width="12" height="4" rx="1" fill="currentColor"/>
                        <rect x="6" y="15" width="14" height="2" rx="1" fill="currentColor" opacity="0.6"/>
                        <rect x="23" y="8" width="11" height="18" rx="2" stroke-width="1.5" stroke-dasharray="2 2"/>
                    </svg>
                    <span style="font-size:11px; font-weight:600;">Hero 2-Kolom</span>
                </div>
            `,
            content: `
                <section class="py-12 px-6 max-w-7xl mx-auto my-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white p-8 md:p-14 rounded-3xl shadow-xl">
                        <div>
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 mb-4">
                                Koleksi Eksklusif 2026
                            </span>
                            <h1 class="text-3xl md:text-5xl font-black tracking-tight leading-tight mb-4">
                                Temukan Barang Impian & <span class="text-indigo-400">Preloved Terkurasi</span>
                            </h1>
                            <p class="text-slate-300 text-base md:text-lg mb-8 leading-relaxed">
                                Marketplace terpercaya untuk berbelanja produk baru dan secondhand berkualitas. Sneakers, jam tangan, tas, dan gadget bergaransi keaslian.
                            </p>
                            <div class="flex flex-wrap gap-4">
                                <a href="/catalog" class="px-6 py-3.5 rounded-xl font-bold bg-indigo-600 hover:bg-indigo-500 text-white transition shadow-lg shadow-indigo-600/30">
                                    Jelajahi Katalog
                                </a>
                                <a href="/catalog?condition=like_new" class="px-6 py-3.5 rounded-xl font-bold bg-white/10 hover:bg-white/20 text-white backdrop-blur transition border border-white/20">
                                    Koleksi Like New
                                </a>
                            </div>
                        </div>
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80" alt="Hero Banner" class="w-full h-80 md:h-96 object-cover rounded-2xl shadow-2xl border border-white/10">
                        </div>
                    </div>
                </section>
            `
        });

        // 2. Trust Badges
        bm.add('reloo-trust-badges', {
            category: 'Fitur & Keunggulan',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="32" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 4px; display:block;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span style="font-size:11px; font-weight:600;">Trust Badges</span>
                </div>
            `,
            content: `
                <section class="max-w-7xl mx-auto px-6 py-6 my-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="flex items-center gap-4 p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs uppercase flex-shrink-0">
                                CEK
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">Kurasi Fisik Ketat</h4>
                                <p class="text-xs text-slate-500">Kondisi barang 100% riil</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs uppercase flex-shrink-0">
                                QRIS
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">Pembayaran Aman</h4>
                                <p class="text-xs text-slate-500">Midtrans QRIS & Rek Bank</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs uppercase flex-shrink-0">
                                LOG
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">Pengiriman Cepat</h4>
                                <p class="text-xs text-slate-500">JNE, SiCepat & J&T Express</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
                            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xs uppercase flex-shrink-0">
                                ORI
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">Jaminan Original</h4>
                                <p class="text-xs text-slate-500">Garansi uang kembali 100%</p>
                            </div>
                        </div>
                    </div>
                </section>
            `
        });

        // 3. Flash Sale Banner Box
        bm.add('reloo-flash-sale', {
            category: 'Promo & Diskon',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="32" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 4px; display:block;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span style="font-size:11px; font-weight:600;">Flash Sale Box</span>
                </div>
            `,
            content: `
                <section class="max-w-7xl mx-auto px-6 py-8 my-6">
                    <div class="rounded-3xl bg-gradient-to-r from-rose-600 via-red-600 to-amber-600 p-8 md:p-12 text-white shadow-xl">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/20 mb-3">
                                    Penawaran Terbatas
                                </span>
                                <h2 class="text-2xl md:text-4xl font-black mb-2">
                                    Flash Sale Akhir Pekan Hemat s/d 50%
                                </h2>
                                <p class="text-rose-100 text-sm md:text-base">
                                    Stok preloved langka dengan harga terbaik. Jangan sampai kehabisan!
                                </p>
                            </div>
                            <div class="flex items-center gap-4">
                                <a href="/catalog?condition=like_new" class="px-8 py-4 rounded-xl font-black bg-white text-rose-600 hover:bg-rose-50 transition shadow-lg shadow-black/10">
                                    Beli Sekarang &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            `
        });

        // 4. Category Grid
        bm.add('reloo-category-grid', {
            category: 'E-Commerce',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="32" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 4px; display:block;">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    <span style="font-size:11px; font-weight:600;">Kategori Grid</span>
                </div>
            `,
            content: `
                <section class="max-w-7xl mx-auto px-6 py-10 my-4">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900">Jelajahi Berdasarkan Kategori</h2>
                            <p class="text-sm text-slate-500">Pilihan produk terkurasi sesuai kebutuhan Anda</p>
                        </div>
                        <a href="/catalog" class="text-indigo-600 font-bold text-sm hover:underline">Semua &rarr;</a>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <a href="/catalog?category=sepatu" class="group relative rounded-2xl overflow-hidden aspect-square bg-slate-100 flex flex-col justify-end p-4 border border-slate-200">
                            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=400&q=80" alt="Sepatu" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 to-transparent"></div>
                            <span class="relative z-10 font-bold text-white text-base">Sepatu</span>
                        </a>
                        <a href="/catalog?category=tas" class="group relative rounded-2xl overflow-hidden aspect-square bg-slate-100 flex flex-col justify-end p-4 border border-slate-200">
                            <img src="https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=400&q=80" alt="Tas" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 to-transparent"></div>
                            <span class="relative z-10 font-bold text-white text-base">Tas</span>
                        </a>
                        <a href="/catalog?category=jam-tangan" class="group relative rounded-2xl overflow-hidden aspect-square bg-slate-100 flex flex-col justify-end p-4 border border-slate-200">
                            <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=400&q=80" alt="Jam Tangan" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 to-transparent"></div>
                            <span class="relative z-10 font-bold text-white text-base">Jam Tangan</span>
                        </a>
                        <a href="/catalog?category=elektronik" class="group relative rounded-2xl overflow-hidden aspect-square bg-slate-100 flex flex-col justify-end p-4 border border-slate-200">
                            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=400&q=80" alt="Elektronik" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 to-transparent"></div>
                            <span class="relative z-10 font-bold text-white text-base">Elektronik</span>
                        </a>
                        <a href="/catalog?category=pakaian" class="group relative rounded-2xl overflow-hidden aspect-square bg-slate-100 flex flex-col justify-end p-4 border border-slate-200">
                            <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=400&q=80" alt="Pakaian" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 to-transparent"></div>
                            <span class="relative z-10 font-bold text-white text-base">Pakaian</span>
                        </a>
                    </div>
                </section>
            `
        });

        // 5. Featured Product Showcase
        bm.add('reloo-product-showcase', {
            category: 'E-Commerce',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="32" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 4px; display:block;">
                        <circle cx="9" cy="21" r="1"/>
                        <circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                    <span style="font-size:11px; font-weight:600;">Produk Unggulan</span>
                </div>
            `,
            content: `
                <section class="max-w-7xl mx-auto px-6 py-10 my-4">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900">Produk Unggulan Terkini</h2>
                            <p class="text-sm text-slate-500">Koleksi terfavorit yang siap langsung di-checkout</p>
                        </div>
                        <a href="/catalog" class="text-indigo-600 font-bold text-sm hover:underline">Lihat Semua Katalog &rarr;</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Product Card 1 -->
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
                            <div class="relative aspect-square bg-slate-100">
                                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80" alt="Nike Air Max" class="w-full h-full object-cover">
                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                    Like New
                                </span>
                            </div>
                            <div class="p-5 flex flex-col flex-grow">
                                <span class="text-xs text-slate-400 font-medium uppercase mb-1">Sepatu</span>
                                <h3 class="font-bold text-slate-900 text-sm mb-2 line-clamp-1">Nike Air Max 270 Black White</h3>
                                <div class="mt-auto">
                                    <div class="text-lg font-black text-indigo-600">Rp 1.250.000</div>
                                    <div class="text-xs text-slate-400 line-through">Rp 2.200.000</div>
                                    <a href="/catalog/nike-air-max-270-black-white" class="mt-4 w-full block text-center py-2.5 rounded-xl font-bold bg-slate-900 hover:bg-indigo-600 text-white transition text-xs">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Product Card 2 -->
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
                            <div class="relative aspect-square bg-slate-100">
                                <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80" alt="Seiko 5 Sports" class="w-full h-full object-cover">
                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                    Like New
                                </span>
                            </div>
                            <div class="p-5 flex flex-col flex-grow">
                                <span class="text-xs text-slate-400 font-medium uppercase mb-1">Jam Tangan</span>
                                <h3 class="font-bold text-slate-900 text-sm mb-2 line-clamp-1">Seiko 5 Sports Automatic SRPD55K1</h3>
                                <div class="mt-auto">
                                    <div class="text-lg font-black text-indigo-600">Rp 2.850.000</div>
                                    <div class="text-xs text-slate-400 line-through">Rp 4.100.000</div>
                                    <a href="/catalog/seiko-5-sports-automatic-srpd55k1" class="mt-4 w-full block text-center py-2.5 rounded-xl font-bold bg-slate-900 hover:bg-indigo-600 text-white transition text-xs">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Product Card 3 -->
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
                            <div class="relative aspect-square bg-slate-100">
                                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80" alt="Sony WH-1000XM4" class="w-full h-full object-cover">
                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                    Like New
                                </span>
                            </div>
                            <div class="p-5 flex flex-col flex-grow">
                                <span class="text-xs text-slate-400 font-medium uppercase mb-1">Elektronik</span>
                                <h3 class="font-bold text-slate-900 text-sm mb-2 line-clamp-1">Sony WH-1000XM4 Wireless Headphone</h3>
                                <div class="mt-auto">
                                    <div class="text-lg font-black text-indigo-600">Rp 2.950.000</div>
                                    <div class="text-xs text-slate-400 line-through">Rp 4.500.000</div>
                                    <a href="/catalog/sony-wh-1000xm4-noise-canceling-headphones" class="mt-4 w-full block text-center py-2.5 rounded-xl font-bold bg-slate-900 hover:bg-indigo-600 text-white transition text-xs">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Product Card 4 -->
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
                            <div class="relative aspect-square bg-slate-100">
                                <img src="https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=600&q=80" alt="Fjallraven Kanken" class="w-full h-full object-cover">
                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                    Good
                                </span>
                            </div>
                            <div class="p-5 flex flex-col flex-grow">
                                <span class="text-xs text-slate-400 font-medium uppercase mb-1">Tas</span>
                                <h3 class="font-bold text-slate-900 text-sm mb-2 line-clamp-1">Fjallraven Kanken Backpack Ochre</h3>
                                <div class="mt-auto">
                                    <div class="text-lg font-black text-indigo-600">Rp 680.000</div>
                                    <div class="text-xs text-slate-400 line-through">Rp 1.350.000</div>
                                    <a href="/catalog/fjallraven-kanken-classic-backpack-ochre" class="mt-4 w-full block text-center py-2.5 rounded-xl font-bold bg-slate-900 hover:bg-indigo-600 text-white transition text-xs">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            `
        });

        // 6. Testimonial
        bm.add('reloo-testimonials', {
            category: 'Konten & Review',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="32" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 4px; display:block;">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <span style="font-size:11px; font-weight:600;">Testimonial</span>
                </div>
            `,
            content: `
                <section class="max-w-7xl mx-auto px-6 py-12 my-6 bg-slate-50 rounded-3xl border border-slate-200">
                    <div class="text-center max-w-xl mx-auto mb-10">
                        <h2 class="text-2xl font-black text-slate-900 mb-2">Kata Mereka Tentang Reloo</h2>
                        <p class="text-sm text-slate-500">Ulasan jujur dari ribuan pembeli yang puas bertransaksi</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                            <div class="font-bold text-amber-500 mb-2 text-xs">Rating: 5/5</div>
                            <p class="text-slate-600 text-sm mb-4 leading-relaxed">
                                "Beli sneakers Nike Air Max kondisi Like New, pas datang beneran seperti baru! Dus aman, packing tebal, pengiriman kilat via JNE."
                            </p>
                            <div class="font-bold text-slate-900 text-xs">Dimas Pratama</div>
                            <div class="text-[10px] text-slate-400">Jakarta Selatan</div>
                        </div>
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                            <div class="font-bold text-amber-500 mb-2 text-xs">Rating: 5/5</div>
                            <p class="text-slate-600 text-sm mb-4 leading-relaxed">
                                "Suka banget sama transparansi foto & deskripsi kondisinya. Jam tangan Seiko-nya akurat dan mulus banget. Pasti bakal repeat order!"
                            </p>
                            <div class="font-bold text-slate-900 text-xs">Rina Kusuma</div>
                            <div class="text-[10px] text-slate-400">Surabaya</div>
                        </div>
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                            <div class="font-bold text-amber-500 mb-2 text-xs">Rating: 5/5</div>
                            <p class="text-slate-600 text-sm mb-4 leading-relaxed">
                                "Proses checkout gampang banget, bisa transfer manual dan admin cepat approve buktinya. Seller & marketplace sangat recommended."
                            </p>
                            <div class="font-bold text-slate-900 text-xs">Andi Wicaksono</div>
                            <div class="text-[10px] text-slate-400">Bandung</div>
                        </div>
                    </div>
                </section>
            `
        });

        // 7. FAQ Accordion
        bm.add('reloo-faq', {
            category: 'Konten & Review',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="32" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 4px; display:block;">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    <span style="font-size:11px; font-weight:600;">FAQ Accordion</span>
                </div>
            `,
            content: `
                <section class="max-w-4xl mx-auto px-6 py-12 my-6">
                    <div class="text-center mb-10">
                        <h2 class="text-2xl font-black text-slate-900 mb-2">Pertanyaan yang Sering Diajukan</h2>
                        <p class="text-sm text-slate-500">Informasi seputar cara bertransaksi, keaslian produk, dan pengiriman</p>
                    </div>
                    <div class="space-y-4">
                        <details class="group bg-white rounded-2xl border border-slate-200 p-5 cursor-pointer">
                            <summary class="font-bold text-slate-900 flex justify-between items-center text-sm">
                                <span>Bagaimana jaminan keaslian produk di Reloo?</span>
                                <span class="transition group-open:rotate-180">▼</span>
                            </summary>
                            <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                                Seluruh barang yang tampil di etalase melalui proses kurasi fisik ketat. Kami memberikan garansi uang kembali 100% apabila terbukti tidak original.
                            </p>
                        </details>
                        <details class="group bg-white rounded-2xl border border-slate-200 p-5 cursor-pointer">
                            <summary class="font-bold text-slate-900 flex justify-between items-center text-sm">
                                <span>Metode pembayaran apa saja yang didukung?</span>
                                <span class="transition group-open:rotate-180">▼</span>
                            </summary>
                            <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                                Kami mendukung pembayaran otomatis via Midtrans (QRIS, GoPay, ShopeePay, Virtual Account BCA/Mandiri) serta Transfer Bank Manual ke rekening resmi toko.
                            </p>
                        </details>
                        <details class="group bg-white rounded-2xl border border-slate-200 p-5 cursor-pointer">
                            <summary class="font-bold text-slate-900 flex justify-between items-center text-sm">
                                <span>Berapa lama estimasi pengiriman pesanan?</span>
                                <span class="transition group-open:rotate-180">▼</span>
                            </summary>
                            <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                                Pesanan diproses maksimal 1x24 jam kerja setelah pembayaran terkonfirmasi. Estimasi pengiriman reguler adalah 1-3 hari kerja untuk Jabodetabek dan 2-5 hari untuk luar pulau.
                            </p>
                        </details>
                    </div>
                </section>
            `
        });
    }

    // Expose plugin to window
    window.relooGjsPlugin = relooGjsPlugin;
})();
