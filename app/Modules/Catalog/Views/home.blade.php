{{-- Hallmark · macrostructure: Wide Hero Carousel · genre: editorial · theme: Lumen --}}
@extends('layouts.store')

@section('title', 'Reloo · Pasar Barang Pilihan & Preloved Berkualitas')

@section('content')
<div class="space-y-16 py-8 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

        <!-- 1. Hero Banner Products Carousel (Wide / Panjang) -->
        <section>
            <!-- Wide Carousel Card Shell -->
            <div id="hero-carousel" class="w-full bg-white rounded-[6px] border border-[#EAEAEA] overflow-hidden relative">
                <div id="hero-carousel-slides" class="relative min-h-[420px]">
                    @foreach($featuredProducts as $index => $item)
                        <div class="hero-slide transition-opacity duration-500 ease-in-out {{ $index === 0 ? 'opacity-100 relative' : 'opacity-0 absolute inset-0 pointer-events-none' }}" data-slide="{{ $index }}">
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center p-6 sm:p-10 md:p-12">

                                <!-- Editorial Information (Spans 7) -->
                                <div class="lg:col-span-7 flex flex-col justify-between">
                                    <div>
                                        <div class="inline-flex items-center gap-2 mb-3">
                                            <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A]">
                                                {{ $item->category ? $item->category->name : 'Katalog' }}
                                            </span>
                                            <span class="text-[#D4D4D8]">/</span>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono uppercase border {{ $item->condition_badge['color'] }}">
                                                {{ $item->condition_badge['label'] }}
                                            </span>
                                        </div>

                                        <h2 class="font-serif text-3xl sm:text-4xl lg:text-[44px] text-[#111111] tracking-tight leading-[1.1] mb-4">
                                            <a href="{{ route('catalog.show', $item->slug) }}" class="hover:underline">
                                                {{ $item->name }}
                                            </a>
                                        </h2>

                                        <p class="text-[#52525B] text-xs sm:text-sm leading-relaxed mb-6 max-w-xl">
                                            {{ $item->description ? Str::limit(strip_tags($item->description), 160) : 'Produk pilihan berkualitas dengan transparansi kondisi fisik dan kelayakan fungsi.' }}
                                        </p>

                                        <div class="flex items-baseline gap-3 mb-8">
                                            <span class="font-mono text-2xl sm:text-3xl font-bold text-[#111111]">
                                                {{ $item->formatted_price }}
                                            </span>
                                            @if($item->formatted_original_price)
                                                <span class="font-mono text-xs text-[#A1A1AA] line-through">
                                                    {{ $item->formatted_original_price }}
                                                </span>
                                            @endif
                                            @if($item->stock <= 2 && $item->stock > 0)
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#FDEBEC] text-[#8C2220] border border-[#F8C8CA]">
                                                    Sisa {{ $item->stock }}!
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-[#EAEAEA]">
                                        <a href="{{ route('catalog.show', $item->slug) }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                                            Rincian Barang &rarr;
                                        </a>
                                        <a href="{{ route('catalog.index', ['category' => $item->category ? $item->category->slug : '']) }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-[5px] text-xs font-medium bg-transparent text-[#111111] border border-[#EAEAEA] hover:bg-[#F7F6F3] transition-colors whitespace-nowrap">
                                            Koleksi {{ $item->category ? $item->category->name : 'Lainnya' }}
                                        </a>
                                    </div>
                                </div>

                                <!-- Large Product Photo Frame (Spans 5) -->
                                <div class="lg:col-span-5">
                                    <div class="aspect-[4/3] sm:aspect-[16/10] lg:aspect-[4/3] rounded-[4px] overflow-hidden bg-[#F7F6F3] relative border border-[#EAEAEA] group">
                                        @if($item->primaryImage)
                                            <img src="{{ $item->primaryImage->url }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center font-mono text-xs text-[#71717A]">
                                                [FOTO PRODUK]
                                            </div>
                                        @endif

                                        <div class="absolute bottom-3 right-3 font-mono text-[10px] px-2 py-0.5 rounded bg-white/95 backdrop-blur-sm border border-[#EAEAEA] text-[#52525B]">
                                            {{ sprintf('%02d', $index + 1) }} / {{ sprintf('%02d', count($featuredProducts)) }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Bottom Indicators Strip -->
                <div class="px-6 py-3 bg-[#FBFBFA] border-t border-[#EAEAEA] flex items-center justify-center">
                    <div class="flex items-center gap-1.5" id="hero-carousel-indicators">
                        @foreach($featuredProducts as $index => $item)
                            <button type="button" data-goto="{{ $index }}" aria-label="Lihat slide {{ $index + 1 }}" class="hero-indicator h-1.5 rounded-full transition-all {{ $index === 0 ? 'w-6 bg-[#111111]' : 'w-2 bg-[#D4D4D8] hover:bg-[#A1A1AA]' }}"></button>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. Category Explorer Matrix -->
        <section>
            <div class="flex items-end justify-between mb-6 pb-3 border-b border-[#EAEAEA]">
                <div>
                    <h2 class="font-serif text-2xl sm:text-3xl text-[#111111] tracking-tight">Kategori</h2>
                </div>
                <a href="{{ route('catalog.index') }}" class="font-mono text-xs text-[#111111] hover:underline">
                    Semua Kategori &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($categories as $cat)
                    <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" class="group block p-4 bg-white rounded-[6px] border border-[#EAEAEA] hover:border-[#111111] transition-colors">
                        <div class="aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-3 relative border border-[#EAEAEA]">
                            @if($cat->image_path)
                                <img src="{{ $cat->image_path }}" alt="{{ $cat->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center font-mono text-xs text-[#71717A]">
                                    {{ strtoupper(substr($cat->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-xs text-[#111111]">{{ $cat->name }}</span>
                            <span class="font-mono text-[10px] text-[#71717A]">{{ $cat->products_count }} SKU</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- 3. Curated Products Showcase Grid -->
        <section>
            <div class="flex items-end justify-between mb-6 pb-3 border-b border-[#EAEAEA]">
                <div>
                    <h2 class="font-serif text-2xl sm:text-3xl text-[#111111] tracking-tight">Koleksi Terbaru di Etalase</h2>
                </div>
                <a href="{{ route('catalog.index') }}" class="font-mono text-xs text-[#111111] hover:underline">
                    Lihat Semua Produk &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $item)
                    <div class="bg-white rounded-[6px] border border-[#EAEAEA] p-4 flex flex-col group hover:border-[#111111] transition-colors">
                        <div class="relative aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-4 border border-[#EAEAEA]">
                            @if($item->primaryImage)
                                <img src="{{ $item->primaryImage->url }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[#71717A] font-mono text-xs">
                                    [FOTO PRODUK]
                                </div>
                            @endif

                            <!-- Spot Pastel Condition Badge -->
                            <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase border {{ $item->condition_badge['color'] }}">
                                {{ $item->condition_badge['label'] }}
                            </span>

                            <!-- Low Stock Indicator if <= 2 -->
                            @if($item->stock <= 2 && $item->stock > 0)
                                <span class="absolute bottom-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#FDEBEC] text-[#8C2220] border border-[#F8C8CA]">
                                    Sisa {{ $item->stock }}!
                                </span>
                            @endif
                        </div>

                        <span class="text-[10px] font-mono text-[#71717A] uppercase mb-1">
                            {{ $item->category ? $item->category->name : '' }}
                        </span>

                        <h3 class="font-medium text-xs text-[#111111] mb-2 line-clamp-1">
                            <a href="{{ route('catalog.show', $item->slug) }}" class="hover:underline">
                                {{ $item->name }}
                            </a>
                        </h3>

                        <div class="mt-auto pt-3 border-t border-[#EAEAEA]">
                            <div class="flex items-baseline justify-between mb-3">
                                <span class="font-mono text-sm font-semibold text-[#111111]">{{ $item->formatted_price }}</span>
                                @if($item->formatted_original_price)
                                    <span class="font-mono text-[10px] text-[#A1A1AA] line-through">{{ $item->formatted_original_price }}</span>
                                @endif
                            </div>
                            <a href="{{ route('catalog.show', $item->slug) }}" class="w-full inline-flex items-center justify-center py-2 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                                Rincian Barang
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const slides = document.querySelectorAll('.hero-slide');
        const indicators = document.querySelectorAll('.hero-indicator');
        const total = slides.length;

        if (total <= 1) return;

        let currentIndex = 0;
        let timer = null;

        function updateSlide(index) {
            slides.forEach((slide, idx) => {
                if (idx === index) {
                    slide.classList.remove('opacity-0', 'absolute', 'pointer-events-none');
                    slide.classList.add('opacity-100', 'relative');
                } else {
                    slide.classList.remove('opacity-100', 'relative');
                    slide.classList.add('opacity-0', 'absolute', 'pointer-events-none');
                }
            });

            indicators.forEach((indicator, idx) => {
                if (idx === index) {
                    indicator.classList.remove('w-2', 'bg-[#D4D4D8]');
                    indicator.classList.add('w-6', 'bg-[#111111]');
                } else {
                    indicator.classList.remove('w-6', 'bg-[#111111]');
                    indicator.classList.add('w-2', 'bg-[#D4D4D8]');
                }
            });

            currentIndex = index;
        }

        function nextSlide() {
            updateSlide((currentIndex + 1) % total);
        }

        function startAutoplay() {
            stopAutoplay();
            timer = setInterval(nextSlide, 5000);
        }

        function stopAutoplay() {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        }

        indicators.forEach((btn, idx) => {
            btn.addEventListener('click', () => {
                updateSlide(idx);
                startAutoplay();
            });
        });

        const carouselEl = document.getElementById('hero-carousel');
        if (carouselEl) {
            carouselEl.addEventListener('mouseenter', stopAutoplay);
            carouselEl.addEventListener('mouseleave', startAutoplay);
        }

        startAutoplay();
    });
</script>
@endpush
