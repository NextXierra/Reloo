@extends('layouts.store')

@section('title', 'Reloo · Marketplace Preloved & Pilihan')

@section('content')
<div class="space-y-16 py-8 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

        <!-- Hero Campaign Section -->
        <section class="border border-[#EAEAEA] rounded-[6px] bg-white p-6 sm:p-10 md:p-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <!-- Left Editorial Text (Spans 7 Cols) -->
                <div class="lg:col-span-7">
                    <h1 class="font-serif text-3xl sm:text-5xl lg:text-6xl text-[#111111] tracking-tight leading-[1.08] mb-6">
                        Pasar Barang Pilihan & Preloved Berkualitas
                    </h1>
                    <p class="text-[#52525B] text-xs sm:text-sm leading-relaxed mb-8 max-w-xl">
                        Belanja sneakers, tas branded, jam tangan, dan gadget berkualitas dengan harga transparan dan transaksi aman.
                    </p>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                            Jelajahi Katalog Lengkap &rarr;
                        </a>
                        <a href="{{ route('catalog.index', ['condition' => 'like_new']) }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-[5px] text-xs font-medium bg-transparent text-[#111111] border border-[#EAEAEA] hover:bg-[#F7F6F3] transition-colors whitespace-nowrap">
                            Koleksi Like New
                        </a>
                    </div>
                </div>

                <!-- Right Visual Frame (Spans 5 Cols) -->
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

        <!-- Category Navigator Rail -->
        <section>
            <div class="flex items-end justify-between mb-6 pb-3 border-b border-[#EAEAEA]">
                <div>
                    <span class="font-mono text-[10px] text-[#71717A] tracking-wider uppercase block mb-1">KATEGORI PRODUK</span>
                    <h2 class="font-serif text-2xl sm:text-3xl text-[#111111] tracking-tight">Kategori Pilihan</h2>
                </div>
                <a href="{{ route('catalog.index') }}" class="font-mono text-xs text-[#111111] hover:underline">Semua Kategori &rarr;</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($categories as $cat)
                    <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" class="group block p-4 bg-white rounded-[6px] border border-[#EAEAEA] hover:border-[#111111] transition-colors">
                        <div class="aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-3 relative">
                            @if($cat->image_path)
                                <img src="{{ $cat->image_path }}" alt="{{ $cat->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center font-mono text-xs text-[#71717A]">
                                    {{ substr($cat->name, 0, 2) }}
                                </div>
                            @endif
                        </div>
                        <span class="font-medium text-xs text-[#111111] block">{{ $cat->name }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- Product Showcase Grid -->
        <section>
            <div class="flex items-end justify-between mb-6 pb-3 border-b border-[#EAEAEA]">
                <div>
                    <span class="font-mono text-[10px] text-[#71717A] tracking-wider uppercase block mb-1">PRODUK TERBARU</span>
                    <h2 class="font-serif text-2xl sm:text-3xl text-[#111111] tracking-tight">Katalog Produk Pilihan</h2>
                </div>
                <a href="{{ route('catalog.index') }}" class="font-mono text-xs text-[#111111] hover:underline">Lihat Semua &rarr;</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $item)
                    <div class="bg-white rounded-[6px] border border-[#EAEAEA] p-4 flex flex-col group hover:border-[#111111] transition-colors">
                        <div class="relative aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-4">
                            @if($item->primaryImage)
                                <img src="{{ $item->primaryImage->url }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[#71717A] font-mono text-xs">
                                    [FOTO PRODUK]
                                </div>
                            @endif
                            <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase border {{ $item->condition_badge['color'] }}">
                                {{ $item->condition_badge['label'] }}
                            </span>
                        </div>
                        <span class="text-[10px] font-mono text-[#71717A] uppercase mb-1">
                            {{ $item->category->name }}
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
