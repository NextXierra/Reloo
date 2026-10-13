@extends('layouts.store')

@section('title', $product->name . ' · Reloo')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center gap-2 text-xs text-[#71717A] font-mono mb-8 pb-3 border-b border-[#EAEAEA]">
        <a href="{{ route('home') }}" class="hover:text-[#111111]">BERANDA</a>
        <span>/</span>
        <a href="{{ route('catalog.index') }}" class="hover:text-[#111111]">KATALOG</a>
        <span>/</span>
        <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}" class="hover:text-[#111111] uppercase">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-[#111111] font-semibold truncate max-w-xs">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-16 items-start">

        <!-- Left: Image Gallery (5 Cols) -->
        <div class="lg:col-span-5 space-y-4">
            <!-- Main Preview Container -->
            <div class="aspect-square rounded-[6px] overflow-hidden bg-white border border-[#EAEAEA] p-3 relative">
                @php
                    $primary = $product->primaryImage ?? $product->images->first();
                @endphp
                <div class="w-full h-full rounded-[4px] overflow-hidden bg-[#F7F6F3]">
                    <img id="main-image" src="{{ $primary ? $primary->url : 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800' }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>

                <span class="absolute top-5 left-5 px-2.5 py-0.5 rounded-full text-[10px] font-mono uppercase border {{ $product->condition_badge['color'] }}">
                    Kondisi: {{ $product->condition_badge['label'] }}
                </span>
            </div>

            <!-- Thumbnails Strip -->
            @if($product->images->count() > 1)
                <div class="flex items-center gap-2 overflow-x-auto pb-1">
                    @foreach($product->images as $img)
                        <button type="button" onclick="document.getElementById('main-image').src = '{{ $img->url }}'" class="w-16 h-16 rounded-[4px] overflow-hidden border border-[#EAEAEA] hover:border-[#111111] transition-colors flex-shrink-0 focus:outline-none focus:border-[#111111] p-1 bg-white">
                            <img src="{{ $img->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-[2px]">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Product Info, Specs & Purchase Form (7 Cols) -->
        <div class="lg:col-span-7 flex flex-col space-y-6">

            <!-- Category, SKU & Title -->
            <div>
                <div class="flex items-center justify-between text-xs text-[#71717A] font-mono mb-2">
                    <span class="uppercase font-medium text-[#111111]">{{ $product->category->name }}</span>
                    <span>SKU: {{ $product->sku }}</span>
                </div>

                <h1 class="font-serif text-3xl sm:text-4xl text-[#111111] tracking-tight leading-tight">
                    {{ $product->name }}
                </h1>
            </div>

            <!-- Price & Tax Notice -->
            <div class="p-5 rounded-[6px] bg-[#F7F6F3] border border-[#EAEAEA]">
                <div class="flex items-baseline gap-3 flex-wrap">
                    <span class="font-mono text-3xl font-bold text-[#111111]">
                        {{ $product->formatted_price }}
                    </span>
                    @if($product->formatted_original_price)
                        <span class="font-mono text-sm text-[#A1A1AA] line-through">
                            {{ $product->formatted_original_price }}
                        </span>
                        @php
                            $discount = round((($product->original_price - $product->price) / $product->original_price) * 100);
                        @endphp
                        <span class="font-mono text-[10px] px-2 py-0.5 rounded-full bg-[#FDEBEC] text-[#8C2220] border border-[#F8C8CA]">
                            Hemat {{ $discount }}%
                        </span>
                    @endif
                </div>
                <p class="font-mono text-[10px] text-[#71717A] mt-1 uppercase">Harga nett · Sudah termasuk pajak</p>
            </div>

            <!-- Technical Specification Table -->
            <div class="border border-[#EAEAEA] rounded-[6px] overflow-hidden bg-white text-xs">
                <div class="px-4 py-2 bg-[#F7F6F3] border-b border-[#EAEAEA] font-mono text-[10px] uppercase text-[#71717A] tracking-wider">
                    SPESIFIKASI PRODUK
                </div>
                <div class="divide-y divide-[#EAEAEA]">
                    <div class="grid grid-cols-3 px-4 py-2.5">
                        <span class="font-mono text-[#71717A]">Kondisi Fisik</span>
                        <span class="col-span-2 font-medium text-[#111111]">{{ $product->condition_badge['label'] }}</span>
                    </div>
                    <div class="grid grid-cols-3 px-4 py-2.5">
                        <span class="font-mono text-[#71717A]">Ketersediaan</span>
                        <span class="col-span-2">
                            @if($product->stock > 0)
                                <span class="text-[#26542A] font-medium font-mono">Tersedia ({{ $product->stock }} unit)</span>
                            @else
                                <span class="text-[#8C2220] font-medium font-mono">Stok Habis</span>
                            @endif
                        </span>
                    </div>
                    <div class="grid grid-cols-3 px-4 py-2.5">
                        <span class="font-mono text-[#71717A]">Berat Paket</span>
                        <span class="col-span-2 font-mono text-[#111111]">{{ number_format($product->weight_grams, 0, ',', '.') }} gram</span>
                    </div>
                </div>
            </div>

            <!-- Purchase Form -->
            @if($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="space-y-4 pt-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <!-- Quantity Stepper -->
                    <div>
                        <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1.5">Jumlah Item</label>
                        <div class="flex items-center gap-3">
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" required class="w-24 px-3 py-2 text-xs font-mono font-medium text-center bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                            <span class="font-mono text-[11px] text-[#71717A]">Maksimal {{ $product->stock }} unit</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <button type="submit" class="h-11 px-5 rounded-[5px] font-medium bg-transparent text-[#111111] border border-[#EAEAEA] hover:bg-[#F7F6F3] transition-colors text-xs flex items-center justify-center gap-2 whitespace-nowrap focus:outline-none focus:ring-1 focus:ring-[#111111]">
                            <svg class="w-4 h-4 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span>+ Tambah Keranjang</span>
                        </button>
                        <button type="submit" name="buy_now" value="1" class="h-11 px-6 rounded-[5px] font-medium bg-[#111111] hover:bg-[#27272A] text-white transition-colors text-xs flex items-center justify-center gap-2 whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-[#111111]">
                            <span>Beli Sekarang</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </form>
            @else
                <div class="p-4 rounded-[5px] bg-[#FDEBEC] border border-[#F8C8CA] text-[#8C2220] text-xs">
                    <strong class="font-semibold block mb-0.5">Stok Habis</strong>
                    Produk ini telah terjual. Silakan jelajahi katalog untuk menemukan produk serupa.
                </div>
            @endif

            <!-- Description Box -->
            <div class="border-t border-[#EAEAEA] pt-6">
                <span class="font-mono text-[10px] text-[#71717A] uppercase tracking-wider block mb-2">CATATAN & DESKRIPSI</span>
                <div class="text-xs text-[#52525B] leading-relaxed whitespace-pre-line bg-white p-5 rounded-[6px] border border-[#EAEAEA]">
                    {{ $product->description }}
                </div>
            </div>

        </div>

    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <div class="border-t border-[#EAEAEA] pt-12">
            <div class="flex items-end justify-between mb-6 pb-3 border-b border-[#EAEAEA]">
                <div>
                    <span class="font-mono text-[10px] text-[#71717A] tracking-wider uppercase block mb-1">PRODUK TERKAIT</span>
                    <h2 class="font-serif text-2xl text-[#111111] tracking-tight">Produk Serupa di Kategori {{ $product->category->name }}</h2>
                </div>
                <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}" class="font-mono text-xs text-[#111111] hover:underline">Semua &rarr;</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    <div class="bg-white rounded-[6px] border border-[#EAEAEA] p-4 flex flex-col group hover:border-[#111111] transition-colors">
                        <div class="relative aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-4">
                            @if($related->primaryImage)
                                <img src="{{ $related->primaryImage->url }}" alt="{{ $related->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[#71717A] font-mono text-xs">[FOTO]</div>
                            @endif
                            <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase border {{ $related->condition_badge['color'] }}">
                                {{ $related->condition_badge['label'] }}
                            </span>
                        </div>
                        <div class="flex flex-col flex-grow">
                            <h4 class="font-medium text-xs text-[#111111] mb-2 line-clamp-1">
                                <a href="{{ route('catalog.show', $related->slug) }}" class="hover:underline">
                                    {{ $related->name }}
                                </a>
                            </h4>
                            <div class="mt-auto pt-2 border-t border-[#EAEAEA] font-mono text-sm font-semibold text-[#111111]">
                                {{ $related->formatted_price }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
