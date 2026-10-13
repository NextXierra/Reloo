/**
 * Reloo Marketplace - Minimalist & Editorial GrapesJS Blocks Plugin
 * Built in accordance with Minimalist-UI and Hallmark design protocols.
 * Zero gradients, zero heavy shadows, zero emojis, pure 1px hairline rules,
 * warm bone & off-black typography with spot desaturated pastels.
 */
(function () {
    'use strict';

    function relooGjsPlugin(editor) {
        const bm = editor.BlockManager;

        /* =========================================================================
         * 1. KATEGORI TATA LETAK (LAYOUT PRIMITIVES)
         * ========================================================================= */

        // 1.1 1 Kolom Penuh (Full Section)
        bm.add('reloo-layout-1col', {
            category: 'Tata Letak',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 28 20" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <rect x="1.5" y="1.5" width="25" height="17" rx="2" stroke-dasharray="2 2"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">1 Kolom</span>
                </div>
            `,
            content: `
                <section class="w-full py-16 px-6">
                    <div class="max-w-6xl mx-auto min-h-[120px] p-8 border border-dashed border-[#D4D4D8] rounded-[6px] flex items-center justify-center text-[#71717A] text-xs font-mono">
                        [1 KOLOM PENUH · SERET ELEMEN KE SINI]
                    </div>
                </section>
            `
        });

        // 1.2 2 Kolom Seimbang (Grid 50% - 50%)
        bm.add('reloo-layout-2col', {
            category: 'Tata Letak',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 28 20" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <rect x="1.5" y="1.5" width="11" height="17" rx="1.5"/>
                        <rect x="15.5" y="1.5" width="11" height="17" rx="1.5"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">2 Kolom</span>
                </div>
            `,
            content: `
                <section class="max-w-6xl mx-auto px-6 py-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        <div class="min-h-[140px] p-6 border border-dashed border-[#D4D4D8] rounded-[6px] flex items-center justify-center text-[#71717A] text-xs font-mono">
                            [KOLOM KIRI 50%]
                        </div>
                        <div class="min-h-[140px] p-6 border border-dashed border-[#D4D4D8] rounded-[6px] flex items-center justify-center text-[#71717A] text-xs font-mono">
                            [KOLOM KANAN 50%]
                        </div>
                    </div>
                </section>
            `
        });

        // 1.3 3 Kolom Seimbang (Grid 33% - 33% - 33%)
        bm.add('reloo-layout-3col', {
            category: 'Tata Letak',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 28 20" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <rect x="1.5" y="1.5" width="7" height="17" rx="1"/>
                        <rect x="10.5" y="1.5" width="7" height="17" rx="1"/>
                        <rect x="19.5" y="1.5" width="7" height="17" rx="1"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">3 Kolom</span>
                </div>
            `,
            content: `
                <section class="max-w-6xl mx-auto px-6 py-12">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="min-h-[130px] p-5 border border-dashed border-[#D4D4D8] rounded-[6px] flex items-center justify-center text-[#71717A] text-xs font-mono">
                            [KOLOM 01]
                        </div>
                        <div class="min-h-[130px] p-5 border border-dashed border-[#D4D4D8] rounded-[6px] flex items-center justify-center text-[#71717A] text-xs font-mono">
                            [KOLOM 02]
                        </div>
                        <div class="min-h-[130px] p-5 border border-dashed border-[#D4D4D8] rounded-[6px] flex items-center justify-center text-[#71717A] text-xs font-mono">
                            [KOLOM 03]
                        </div>
                    </div>
                </section>
            `
        });

        // 1.4 4 Kolom Grid Responsif (Grid 25% x 4)
        bm.add('reloo-layout-4col', {
            category: 'Tata Letak',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 28 20" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <rect x="1.5" y="1.5" width="5" height="17" rx="1"/>
                        <rect x="8" y="1.5" width="5" height="17" rx="1"/>
                        <rect x="14.5" y="1.5" width="5" height="17" rx="1"/>
                        <rect x="21" y="1.5" width="5" height="17" rx="1"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">4 Kolom</span>
                </div>
            `,
            content: `
                <section class="max-w-6xl mx-auto px-6 py-12">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="min-h-[120px] p-4 border border-dashed border-[#D4D4D8] rounded-[6px] flex items-center justify-center text-[#71717A] text-xs font-mono">
                            [KOLOM 01]
                        </div>
                        <div class="min-h-[120px] p-4 border border-dashed border-[#D4D4D8] rounded-[6px] flex items-center justify-center text-[#71717A] text-xs font-mono">
                            [KOLOM 02]
                        </div>
                        <div class="min-h-[120px] p-4 border border-dashed border-[#D4D4D8] rounded-[6px] flex items-center justify-center text-[#71717A] text-xs font-mono">
                            [KOLOM 03]
                        </div>
                        <div class="min-h-[120px] p-4 border border-dashed border-[#D4D4D8] rounded-[6px] flex items-center justify-center text-[#71717A] text-xs font-mono">
                            [KOLOM 04]
                        </div>
                    </div>
                </section>
            `
        });

        // 1.5 Box Kontainer Terpusat
        bm.add('reloo-layout-container', {
            category: 'Tata Letak',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 28 20" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <rect x="4" y="3" width="20" height="14" rx="2"/>
                        <path d="M1 10h3M24 10h3"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">Box Terpusat</span>
                </div>
            `,
            content: `
                <div class="max-w-6xl mx-auto px-6 py-10">
                    <div class="bg-white rounded-[6px] border border-[#EAEAEA] p-8">
                        <h3 class="font-serif text-xl text-[#111111] mb-2">Kontainer Box Terpusat</h3>
                        <p class="text-[#52525B] text-xs leading-relaxed">Gunakan kontainer bergaris 1px ini untuk mengelompokkan konten dengan konsistensi proporsional.</p>
                    </div>
                </div>
            `
        });

        /* =========================================================================
         * 2. KATEGORI ELEMEN DASAR (BASIC ELEMENTS)
         * ========================================================================= */

        // 2.1 Judul Editorial (Instrument Serif H2)
        bm.add('reloo-basic-heading', {
            category: 'Elemen Dasar',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <span style="font-family:Georgia, serif; font-size:16px; font-weight:600; display:block; margin-bottom:2px;">H1</span>
                    <span style="font-size:10px; font-weight:500;">Judul Editorial</span>
                </div>
            `,
            content: `
                <div class="my-6">
                    <h2 class="font-serif text-3xl md:text-4xl text-[#111111] tracking-tight leading-tight">
                        Pilihan Terkurasi Berstandar Tinggi
                    </h2>
                </div>
            `
        });

        // 2.2 Paragraf Teks Deskripsi
        bm.add('reloo-basic-paragraph', {
            category: 'Elemen Dasar',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h10"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">Paragraf Teks</span>
                </div>
            `,
            content: `
                <p class="text-[#52525B] text-sm leading-relaxed my-4 max-w-2xl">
                    Setiap barang yang hadir di Reloo menjalani verifikasi detail sebelum dipajang. Transparansi kondisi, catatan fisik, dan kelengkapan disampaikan secara utuh agar transaksi berlangsung tenang.
                </p>
            `
        });

        // 2.3 Tombol Aksi Utilitarian (Solid Black 5px radius)
        bm.add('reloo-basic-button', {
            category: 'Elemen Dasar',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <rect x="3" y="6" width="18" height="12" rx="2"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">Tombol Aksi</span>
                </div>
            `,
            content: `
                <div class="my-4">
                    <a href="/catalog" class="inline-flex items-center justify-center px-5 py-2.5 rounded-[5px] font-medium bg-[#111111] hover:bg-[#27272A] text-white text-xs transition-colors whitespace-nowrap">
                        Lihat Katalog Lengkap
                    </a>
                </div>
            `
        });

        // 2.4 Gambar Terbingkai Hairline
        bm.add('reloo-basic-image', {
            category: 'Elemen Dasar',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <path d="M21 15l-5-5L5 21"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">Gambar Foto</span>
                </div>
            `,
            content: `
                <div class="my-6">
                    <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=800&q=80" alt="Produk Reloo" class="w-full max-w-xl mx-auto rounded-[6px] border border-[#EAEAEA] object-cover">
                </div>
            `
        });

        // 2.5 Video Embed Frame
        bm.add('reloo-basic-video', {
            category: 'Elemen Dasar',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                        <polygon points="10 8 16 12 10 16 10 8" fill="currentColor"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">Video Embed</span>
                </div>
            `,
            content: `
                <div class="my-8 max-w-3xl mx-auto aspect-video rounded-[6px] overflow-hidden border border-[#EAEAEA]">
                    <iframe class="w-full h-full" src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Video Player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            `
        });

        // 2.6 Kartu Konten Hairline
        bm.add('reloo-basic-card', {
            category: 'Elemen Dasar',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <path d="M3 9h18"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">Kartu Konten</span>
                </div>
            `,
            content: `
                <div class="bg-white rounded-[6px] border border-[#EAEAEA] p-6 my-4">
                    <h4 class="font-serif text-lg text-[#111111] mb-2">Koleksi Pilihan & Preloved</h4>
                    <p class="text-[#52525B] text-xs leading-relaxed mb-4">
                        Temukan pilihan sneakers, tas, dan aksesoris berkualitas dengan harga transparan dan transaksi aman.
                    </p>
                    <a href="/catalog" class="text-[#111111] font-mono text-xs hover:underline inline-flex items-center gap-1">
                        Lihat Semua &rarr;
                    </a>
                </div>
            `
        });

        // 2.7 Garis Pembatas / Divider
        bm.add('reloo-basic-divider', {
            category: 'Elemen Dasar',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <line x1="3" y1="12" x2="21" y2="12"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">Garis 1px</span>
                </div>
            `,
            content: `
                <div class="my-10 max-w-6xl mx-auto px-6">
                    <hr class="border-t border-[#EAEAEA]">
                </div>
            `
        });

        // 2.8 Alert Strip
        bm.add('reloo-basic-alert', {
            category: 'Elemen Dasar',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">Alert Strip</span>
                </div>
            `,
            content: `
                <div class="my-4 max-w-6xl mx-auto px-6">
                    <div class="p-3 rounded-[5px] bg-[#EDF3EC] border border-[#D1E2CF] text-[#26542A] flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="font-mono uppercase font-bold tracking-wider">[PENGUMUMAN]</span>
                            <span>Seluruh barang preloved telah melewati sanitasi UV dan pemeriksaan fisik sebelum dikirim.</span>
                        </div>
                        <a href="/catalog" class="font-mono underline ml-4 flex-shrink-0 text-[#26542A]">Periksa Katalog</a>
                    </div>
                </div>
            `
        });

        // 2.9 Custom HTML Block
        bm.add('reloo-basic-html', {
            category: 'Elemen Dasar',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <polyline points="16 18 22 12 16 6"/>
                        <polyline points="8 6 2 12 8 18"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">Custom HTML</span>
                </div>
            `,
            content: `
                <div class="my-4 p-4 bg-[#121214] text-[#E4E4E7] rounded-[5px] font-mono text-xs border border-[#27272A]">
                    <!-- Masukkan tag HTML atau script khusus Anda di sini -->
                    <p>Edit blok HTML ini secara langsung melalui Trait Manager atau Code Editor.</p>
                </div>
            `
        });

        /* =========================================================================
         * 3. KATEGORI KOMPONEN E-COMMERCE RELOO (MINIMALIST & EDITORIAL)
         * ========================================================================= */

        // 3.1 Hero Asymmetric Diptych (Hallmark H2 Archetype)
        bm.add('reloo-hero-split', {
            category: 'Komponen E-Commerce',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 28 20" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <rect x="1.5" y="1.5" width="25" height="17" rx="2"/>
                        <rect x="3.5" y="4" width="10" height="3" rx="0.5" fill="currentColor"/>
                        <rect x="15" y="4" width="9.5" height="12" rx="1" stroke-dasharray="2 2"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">Hero Diptych</span>
                </div>
            `,
            content: `
                <section class="py-16 md:py-24 px-6 max-w-6xl mx-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                        <div class="lg:col-span-7">

                            <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-[#111111] tracking-tight leading-[1.08] mb-6">
                                Pasar Barang Pilihan & Preloved Berkualitas
                            </h1>
                            <p class="text-[#52525B] text-sm sm:text-base leading-relaxed mb-8 max-w-xl">
                                Platform transaksi terpercaya untuk sneakers, tas kulit, jam tangan, dan gadget berkualitas. Setiap item diverifikasi keasliannya dan diuji fisiknya sebelum dikirim.
                            </p>
                            <div class="flex flex-wrap items-center gap-3">
                                <a href="/catalog" class="inline-flex items-center justify-center px-5 py-2.5 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                                    Jelajahi Katalog
                                </a>
                                <a href="/catalog?condition=like_new" class="inline-flex items-center justify-center px-5 py-2.5 rounded-[5px] text-xs font-medium bg-transparent text-[#111111] border border-[#EAEAEA] hover:bg-[#F7F6F3] transition-colors whitespace-nowrap">
                                    Koleksi Like New
                                </a>
                            </div>
                        </div>
                        <div class="lg:col-span-5">
                            <div class="rounded-[6px] border border-[#EAEAEA] p-3 bg-white">
                                <div class="aspect-[4/5] rounded-[4px] overflow-hidden bg-[#F7F6F3] relative">
                                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80" alt="Hero Featured" class="w-full h-full object-cover">
                                    <div class="absolute bottom-3 left-3 right-3 p-3 bg-white/95 backdrop-blur-sm border border-[#EAEAEA] rounded-[4px] flex items-center justify-between">
                                        <div>
                                            <span class="font-mono text-[10px] uppercase text-[#71717A] block">Grade Terverifikasi</span>
                                            <span class="font-medium text-xs text-[#111111]">Nike Air Max 270</span>
                                        </div>
                                        <span class="font-mono text-xs font-medium text-[#111111]">Rp 1.250.000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            `
        });

        // 3.2 Curated Assurance Strip (Hallmark T4 Archetype)
        bm.add('reloo-trust-badges', {
            category: 'Komponen E-Commerce',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">Jaminan Terkurasi</span>
                </div>
            `,
            content: `
                <section class="max-w-6xl mx-auto px-6 py-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 border border-[#EAEAEA] rounded-[6px] bg-white divide-y sm:divide-y-0 sm:divide-x divide-[#EAEAEA]">
                        <div class="p-6">
                            <span class="font-mono text-xs text-[#71717A] block mb-2">01 / LOGISTIK</span>
                            <h4 class="font-medium text-sm text-[#111111] mb-1">Pengiriman Cepat</h4>
                            <p class="text-xs text-[#52525B] leading-relaxed">Didukung JNE, SiCepat & J&T Express dengan pelacakan resi otomatis.</p>
                        </div>
                        <div class="p-6">
                            <span class="font-mono text-xs text-[#71717A] block mb-2">02 / PEMBAYARAN</span>
                            <h4 class="font-medium text-sm text-[#111111] mb-1">Transaksi Aman</h4>
                            <p class="text-xs text-[#52525B] leading-relaxed">Verifikasi pembayaran instan via QRIS dan transfer rekening bank resmi.</p>
                        </div>
                        <div class="p-6">
                            <span class="font-mono text-xs text-[#71717A] block mb-2">03 / JAMINAN</span>
                            <h4 class="font-medium text-sm text-[#111111] mb-1">Garansi Retur</h4>
                            <p class="text-xs text-[#52525B] leading-relaxed">Jaminan pengembalian dana jika kondisi barang tidak sesuai rincian foto.</p>
                        </div>
                        <div class="p-6">
                            <span class="font-mono text-xs text-[#71717A] block mb-2">04 / BANTUAN</span>
                            <h4 class="font-medium text-sm text-[#111111] mb-1">Layanan CS</h4>
                            <p class="text-xs text-[#52525B] leading-relaxed">Bantuan cepat customer service via WhatsApp resmi setiap hari kerja.</p>
                        </div>
                </section>
            `
        });

        // 3.3 Limited Drop Flash Bar
        bm.add('reloo-flash-sale', {
            category: 'Komponen E-Commerce',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">Drop Terbatas</span>
                </div>
            `,
            content: `
                <section class="max-w-6xl mx-auto px-6 py-8">
                    <div class="rounded-[6px] border border-[#EAEAEA] bg-[#F7F6F3] p-8 md:p-10">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div>
                                <span class="font-mono text-[10px] tracking-wider uppercase text-[#8C2220] px-2 py-0.5 rounded bg-[#FDEBEC] border border-[#F8C8CA] inline-block mb-3">
                                    [DROP TERBATAS]
                                </span>
                                <h2 class="font-serif text-2xl md:text-3xl text-[#111111] tracking-tight mb-2">
                                    Rilisan Khusus Akhir Pekan
                                </h2>
                                <p class="text-xs text-[#52525B] max-w-lg leading-relaxed">
                                    Item langka dengan kondisi prima dan kelengkapan dokumen asli. Penawaran berlaku hingga stok terverifikasi habis.
                                </p>
                            </div>
                            <div class="flex items-center gap-4 flex-shrink-0">
                                <a href="/catalog?condition=like_new" class="inline-flex items-center justify-center px-6 py-3 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                                    Lihat Item Terpilih &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            `
        });

        // 3.4 Bento Category Grid (Hallmark F1 Archetype)
        bm.add('reloo-category-grid', {
            category: 'Komponen E-Commerce',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">Bento Kategori</span>
                </div>
            `,
            content: `
                <section class="max-w-6xl mx-auto px-6 py-12">
                    <div class="flex items-end justify-between mb-8 pb-4 border-b border-[#EAEAEA]">
                        <div>
                            <span class="font-mono text-[10px] text-[#71717A] tracking-wider uppercase block mb-1">KATALOG TERORGANISIR</span>
                            <h2 class="font-serif text-2xl md:text-3xl text-[#111111] tracking-tight">Jelajahi Berdasarkan Kategori</h2>
                        </div>
                        <a href="/catalog" class="font-mono text-xs text-[#111111] hover:underline">Semua Kategori &rarr;</a>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <a href="/catalog?category=sepatu" class="group block p-4 bg-white rounded-[6px] border border-[#EAEAEA] hover:border-[#111111] transition-colors">
                            <div class="aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-3">
                                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=400&q=80" alt="Sepatu" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <span class="font-medium text-xs text-[#111111] block">Sepatu & Sneakers</span>
                            <span class="font-mono text-[10px] text-[#71717A]">Koleksi Terverifikasi</span>
                        </a>
                        <a href="/catalog?category=tas" class="group block p-4 bg-white rounded-[6px] border border-[#EAEAEA] hover:border-[#111111] transition-colors">
                            <div class="aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-3">
                                <img src="https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=400&q=80" alt="Tas" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <span class="font-medium text-xs text-[#111111] block">Tas & Ransel</span>
                            <span class="font-mono text-[10px] text-[#71717A]">Kulit & Kanvas</span>
                        </a>
                        <a href="/catalog?category=jam-tangan" class="group block p-4 bg-white rounded-[6px] border border-[#EAEAEA] hover:border-[#111111] transition-colors">
                            <div class="aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-3">
                                <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=400&q=80" alt="Jam Tangan" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <span class="font-medium text-xs text-[#111111] block">Jam Tangan</span>
                            <span class="font-mono text-[10px] text-[#71717A]">Otomatis & Kuarsa</span>
                        </a>
                        <a href="/catalog?category=elektronik" class="group block p-4 bg-white rounded-[6px] border border-[#EAEAEA] hover:border-[#111111] transition-colors">
                            <div class="aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-3">
                                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=400&q=80" alt="Elektronik" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <span class="font-medium text-xs text-[#111111] block">Elektronik</span>
                            <span class="font-mono text-[10px] text-[#71717A]">Audio & Gadget</span>
                        </a>
                        <a href="/catalog?category=pakaian" class="group block p-4 bg-white rounded-[6px] border border-[#EAEAEA] hover:border-[#111111] transition-colors">
                            <div class="aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-3">
                                <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=400&q=80" alt="Pakaian" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <span class="font-medium text-xs text-[#111111] block">Pakaian</span>
                            <span class="font-mono text-[10px] text-[#71717A]">Jaket & Vintage</span>
                        </a>
                    </div>
                </section>
            `
        });

        // 3.5 Product Showcase Grid (Hallmark F6 Archetype)
        bm.add('reloo-product-showcase', {
            category: 'Komponen E-Commerce',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <circle cx="9" cy="21" r="1"/>
                        <circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">Produk Unggulan</span>
                </div>
            `,
            content: `
                <section class="max-w-6xl mx-auto px-6 py-12">
                    <div class="flex items-end justify-between mb-8 pb-4 border-b border-[#EAEAEA]">
                        <div>
                            <span class="font-mono text-[10px] text-[#71717A] tracking-wider uppercase block mb-1">INSPEKSI FISIK LULUS</span>
                            <h2 class="font-serif text-2xl md:text-3xl text-[#111111] tracking-tight">Koleksi Terverifikasi Siap Kirim</h2>
                        </div>
                        <a href="/catalog" class="font-mono text-xs text-[#111111] hover:underline">Semua Produk &rarr;</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Product Card 1 -->
                        <div class="bg-white rounded-[6px] border border-[#EAEAEA] p-4 flex flex-col group">
                            <div class="relative aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-4">
                                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80" alt="Nike Air Max" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#E1F3FE] text-[#17547D] border border-[#C2E4FC]">
                                    Like New
                                </span>
                            </div>
                            <span class="text-[10px] font-mono text-[#71717A] uppercase mb-1">Sepatu</span>
                            <h3 class="font-medium text-xs text-[#111111] mb-2 line-clamp-1">Nike Air Max 270 Black White</h3>
                            <div class="mt-auto pt-3 border-t border-[#EAEAEA]">
                                <div class="flex items-baseline justify-between mb-3">
                                    <span class="font-mono text-sm font-semibold text-[#111111]">Rp 1.250.000</span>
                                    <span class="font-mono text-[10px] text-[#A1A1AA] line-through">Rp 2.200.000</span>
                                </div>
                                <a href="/catalog" class="w-full inline-flex items-center justify-center py-2 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                                    Rincian Barang
                                </a>
                            </div>
                        </div>

                        <!-- Product Card 2 -->
                        <div class="bg-white rounded-[6px] border border-[#EAEAEA] p-4 flex flex-col group">
                            <div class="relative aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-4">
                                <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80" alt="Seiko 5 Sports" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#E1F3FE] text-[#17547D] border border-[#C2E4FC]">
                                    Like New
                                </span>
                            </div>
                            <span class="text-[10px] font-mono text-[#71717A] uppercase mb-1">Jam Tangan</span>
                            <h3 class="font-medium text-xs text-[#111111] mb-2 line-clamp-1">Seiko 5 Sports Automatic SRPD55</h3>
                            <div class="mt-auto pt-3 border-t border-[#EAEAEA]">
                                <div class="flex items-baseline justify-between mb-3">
                                    <span class="font-mono text-sm font-semibold text-[#111111]">Rp 2.850.000</span>
                                    <span class="font-mono text-[10px] text-[#A1A1AA] line-through">Rp 4.100.000</span>
                                </div>
                                <a href="/catalog" class="w-full inline-flex items-center justify-center py-2 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                                    Rincian Barang
                                </a>
                            </div>
                        </div>

                        <!-- Product Card 3 -->
                        <div class="bg-white rounded-[6px] border border-[#EAEAEA] p-4 flex flex-col group">
                            <div class="relative aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-4">
                                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80" alt="Sony Headphone" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#EDF3EC] text-[#26542A] border border-[#D1E2CF]">
                                    Baru
                                </span>
                            </div>
                            <span class="text-[10px] font-mono text-[#71717A] uppercase mb-1">Elektronik</span>
                            <h3 class="font-medium text-xs text-[#111111] mb-2 line-clamp-1">Sony WH-1000XM4 Wireless</h3>
                            <div class="mt-auto pt-3 border-t border-[#EAEAEA]">
                                <div class="flex items-baseline justify-between mb-3">
                                    <span class="font-mono text-sm font-semibold text-[#111111]">Rp 2.950.000</span>
                                    <span class="font-mono text-[10px] text-[#A1A1AA] line-through">Rp 4.500.000</span>
                                </div>
                                <a href="/catalog" class="w-full inline-flex items-center justify-center py-2 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                                    Rincian Barang
                                </a>
                            </div>
                        </div>

                        <!-- Product Card 4 -->
                        <div class="bg-white rounded-[6px] border border-[#EAEAEA] p-4 flex flex-col group">
                            <div class="relative aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-4">
                                <img src="https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=600&q=80" alt="Fjallraven Backpack" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#FBF3DB] text-[#785100] border border-[#F5E5B5]">
                                    Bagus
                                </span>
                            </div>
                            <span class="text-[10px] font-mono text-[#71717A] uppercase mb-1">Tas</span>
                            <h3 class="font-medium text-xs text-[#111111] mb-2 line-clamp-1">Fjallraven Kanken Backpack Ochre</h3>
                            <div class="mt-auto pt-3 border-t border-[#EAEAEA]">
                                <div class="flex items-baseline justify-between mb-3">
                                    <span class="font-mono text-sm font-semibold text-[#111111]">Rp 680.000</span>
                                    <span class="font-mono text-[10px] text-[#A1A1AA] line-through">Rp 1.350.000</span>
                                </div>
                                <a href="/catalog" class="w-full inline-flex items-center justify-center py-2 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                                    Rincian Barang
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            `
        });

        // 3.6 Editorial Pull-Quote with Marginalia (Hallmark T1 Archetype)
        bm.add('reloo-testimonials', {
            category: 'Komponen E-Commerce',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">Ulasan Editorial</span>
                </div>
            `,
            content: `
                <section class="max-w-6xl mx-auto px-6 py-16">
                    <div class="border-t border-b border-[#EAEAEA] py-12">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="border-l-2 border-[#111111] pl-5">
                                <p class="font-serif text-lg text-[#111111] leading-snug mb-4">
                                    "Kondisi Like New sesuai deskripsi fisik. Dus orisinal rapi, verifikasi serial number akurat."
                                </p>
                                <div class="font-mono text-[11px] text-[#71717A]">
                                    <span class="text-[#111111] font-medium block">Dimas Pratama</span>
                                    Nike Air Max 270 · Pembeli Terverifikasi
                                </div>
                            </div>
                            <div class="border-l-2 border-[#111111] pl-5">
                                <p class="font-serif text-lg text-[#111111] leading-snug mb-4">
                                    "Transparansi catatan goresan mikro sangat jujur. Jam tangan Seiko tiba dengan kartu garansi lengkap."
                                </p>
                                <div class="font-mono text-[11px] text-[#71717A]">
                                    <span class="text-[#111111] font-medium block">Rina Kusuma</span>
                                    Seiko 5 Sports SRPD · Surabaya
                                </div>
                            </div>
                            <div class="border-l-2 border-[#111111] pl-5">
                                <p class="font-serif text-lg text-[#111111] leading-snug mb-4">
                                    "Pembayaran QRIS otomatis terdeteksi tanpa kirim bukti manual. Pengiriman SiCepat tepat estimasi."
                                </p>
                                <div class="font-mono text-[11px] text-[#71717A]">
                                    <span class="text-[#111111] font-medium block">Andi Wicaksono</span>
                                    Sony WH-1000XM4 · Bandung
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            `
        });

        // 3.7 Minimalist Accordion FAQ (Minimalist-UI Spec)
        bm.add('reloo-faq', {
            category: 'Komponen E-Commerce',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">FAQ Accordion</span>
                </div>
            `,
            content: `
                <section class="max-w-4xl mx-auto px-6 py-16">
                    <div class="mb-10 pb-4 border-b border-[#EAEAEA]">
                        <h2 class="font-serif text-2xl md:text-3xl text-[#111111] tracking-tight">Pertanyaan yang Sering Diajukan</h2>
                    </div>
                    <div class="divide-y divide-[#EAEAEA]">
                        <details class="group py-5 cursor-pointer">
                            <summary class="font-medium text-sm text-[#111111] flex justify-between items-center list-none">
                                <span>Bagaimana cara melakukan pemesanan di Reloo?</span>
                                <span class="font-mono text-sm text-[#71717A] group-open:rotate-45 transition-transform">+</span>
                            </summary>
                            <p class="mt-3 text-xs text-[#52525B] leading-relaxed pr-8">
                                Pilih produk yang Anda inginkan, masukkan ke keranjang belanja, lengkapi alamat pengiriman, dan selesaikan pembayaran melalui QRIS atau transfer bank.
                            </p>
                        </details>
                        <details class="group py-5 cursor-pointer">
                            <summary class="font-medium text-sm text-[#111111] flex justify-between items-center list-none">
                                <span>Bagaimana jika kondisi barang tidak sesuai deskripsi?</span>
                                <span class="font-mono text-sm text-[#71717A] group-open:rotate-45 transition-transform">+</span>
                            </summary>
                            <p class="mt-3 text-xs text-[#52525B] leading-relaxed pr-8">
                                Kami menjamin pengembalian dana jika terdapat ketidaksesuaian barang dengan rincian foto dan catatan kondisi pada halaman produk.
                            </p>
                        </details>
                        <details class="group py-5 cursor-pointer">
                            <summary class="font-medium text-sm text-[#111111] flex justify-between items-center list-none">
                                <span>Kapan nomor resi ekspedisi diterbitkan?</span>
                                <span class="font-mono text-sm text-[#71717A] group-open:rotate-45 transition-transform">+</span>
                            </summary>
                            <p class="mt-3 text-xs text-[#52525B] leading-relaxed pr-8">
                                Nomor resi diterbitkan otomatis maksimal 1x24 jam setelah pembayaran lunas terverifikasi. Pelacakan langsung tersinkronisasi pada menu Riwayat Pesanan di akun Anda.
                            </p>
                        </details>
                    </div>
                </section>
            `
        });

        // 3.8 Editorial Dispatch Newsletter (Hallmark Ft7 Archetype)
        bm.add('reloo-newsletter', {
            category: 'Komponen E-Commerce',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">Newsletter</span>
                </div>
            `,
            content: `
                <section class="max-w-6xl mx-auto px-6 py-16">
                    <div class="border border-[#EAEAEA] rounded-[6px] bg-white p-8 md:p-12">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                            <div class="md:col-span-7">
                                <span class="font-mono text-[10px] text-[#71717A] tracking-wider uppercase block mb-2">WARTA KURASI MINGGUAN</span>
                                <h3 class="font-serif text-2xl md:text-3xl text-[#111111] tracking-tight mb-2">
                                    Dapatkan Informasi Rilisan Preloved Terkurasi
                                </h3>
                                <p class="text-xs text-[#52525B] leading-relaxed max-w-lg">
                                    Kami mengabarkan produk langka dengan grade Like New yang baru saja lolos inspeksi fisik. Tanpa spam promosi tak perlu.
                                </p>
                            </div>
                            <div class="md:col-span-5">
                                <form onsubmit="event.preventDefault(); alert('Terima kasih, alamat Anda telah terdaftar.');" class="flex flex-col sm:flex-row gap-2">
                                    <input type="email" placeholder="alamat@domain.com" required class="flex-1 px-3 py-2 rounded-[5px] border border-[#EAEAEA] text-xs text-[#111111] placeholder:text-[#A1A1AA] bg-[#F7F6F3] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                                    <button type="submit" class="px-4 py-2 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                                        Langganan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            `
        });

        // 3.9 Tabular Spec Sheet (Hallmark F3 Archetype)
        bm.add('reloo-spec-sheet', {
            category: 'Komponen E-Commerce',
            label: `
                <div style="text-align:center; padding: 6px;">
                    <svg width="28" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 4px; display:block;">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <line x1="3" y1="9" x2="21" y2="9"/>
                        <line x1="9" y1="9" x2="9" y2="21"/>
                    </svg>
                    <span style="font-size:10px; font-weight:500;">Spec Sheet</span>
                </div>
            `,
            content: `
                <section class="max-w-4xl mx-auto px-6 py-12">
                    <div class="mb-6 pb-3 border-b border-[#EAEAEA]">
                        <span class="font-mono text-[10px] text-[#71717A] tracking-wider uppercase block mb-1">DATA TEKNIS ITEM</span>
                        <h3 class="font-serif text-2xl text-[#111111] tracking-tight">Lembar Spesifikasi & Grade Verifikasi</h3>
                    </div>
                    <div class="border border-[#EAEAEA] rounded-[6px] overflow-hidden bg-white">
                        <table class="w-full text-left text-xs">
                            <tbody class="divide-y divide-[#EAEAEA]">
                                <tr class="bg-[#F7F6F3]">
                                    <td class="py-2.5 px-4 font-mono text-[#71717A] w-1/3">PARAMETER</td>
                                    <td class="py-2.5 px-4 font-mono text-[#71717A]">HASIL INSPEKSI KURASI</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-4 font-mono text-[#52525B]">Kondisi Fisik</td>
                                    <td class="py-2.5 px-4 text-[#111111] font-medium">Like New (Tingkat keausan &lt; 2%)</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-4 font-mono text-[#52525B]">Kelengkapan</td>
                                    <td class="py-2.5 px-4 text-[#111111]">Dus Orisinal, Buku Panduan, Kartu Garansi</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-4 font-mono text-[#52525B]">Serial Number / SKU</td>
                                    <td class="py-2.5 px-4 font-mono text-[#111111]">RLO-SPEC-2026-X19</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-4 font-mono text-[#52525B]">Integritas Material</td>
                                    <td class="py-2.5 px-4 text-[#111111]">Kulit utuh, tanpa retakan, jahitan presisi</td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-4 font-mono text-[#52525B]">Status Keaslian</td>
                                    <td class="py-2.5 px-4">
                                        <span class="font-mono text-[10px] px-2 py-0.5 rounded-full bg-[#EDF3EC] text-[#26542A] border border-[#D1E2CF]">
                                            TERVERIFIKASI ASLI 100%
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            `
        });
    }

    // Expose plugin to window
    window.relooGjsPlugin = relooGjsPlugin;
})();
