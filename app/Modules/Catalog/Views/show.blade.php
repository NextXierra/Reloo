@extends('layouts.store')

@section('title', $product->name . ' - Reloo Marketplace')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Beranda</a>
        <span>/</span>
        <a href="{{ route('catalog.index') }}" class="hover:text-indigo-600">Katalog</a>
        <span>/</span>
        <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}" class="hover:text-indigo-600">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-slate-900 font-bold truncate max-w-xs">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
        
        <!-- Left: Image Gallery -->
        <div class="space-y-4">
            <!-- Main Preview -->
            <div class="aspect-square rounded-3xl overflow-hidden bg-slate-100 border border-slate-200 relative shadow-sm">
                @php
                    $primary = $product->primaryImage ?? $product->images->first();
                @endphp
                <img id="main-image" src="{{ $primary ? $primary->url : 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800' }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                
                <span class="absolute top-4 left-4 px-3 py-1.5 rounded-full text-xs font-bold border {{ $product->condition_badge['color'] }} shadow-sm">
                    Kondisi: {{ $product->condition_badge['label'] }}
                </span>
            </div>

            <!-- Thumbnails -->
            @if($product->images->count() > 1)
                <div class="flex items-center gap-3 overflow-x-auto pb-2">
                    @foreach($product->images as $img)
                        <button type="button" onclick="document.getElementById('main-image').src = '{{ $img->url }}'" class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-slate-200 hover:border-indigo-600 transition flex-shrink-0 focus:border-indigo-600 focus:outline-none">
                            <img src="{{ $img->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Product Info & Purchase Form -->
        <div class="flex flex-col">
            <!-- Category & SKU -->
            <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
                <span class="font-bold uppercase tracking-wider text-indigo-600">{{ $product->category->name }}</span>
                <span class="font-mono">SKU: {{ $product->sku }}</span>
            </div>

            <!-- Title -->
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-4">
                {{ $product->name }}
            </h1>

            <!-- Price -->
            <div class="p-5 rounded-2xl bg-indigo-50/50 border border-indigo-100 mb-6">
                <div class="flex items-baseline gap-3">
                    <span class="text-3xl font-black text-indigo-600">
                        {{ $product->formatted_price }}
                    </span>
                    @if($product->formatted_original_price)
                        <span class="text-sm text-slate-400 line-through">
                            {{ $product->formatted_original_price }}
                        </span>
                        @php
                            $discount = round((($product->original_price - $product->price) / $product->original_price) * 100);
                        @endphp
                        <span class="px-2 py-0.5 rounded text-xs font-black bg-rose-100 text-rose-700">
                            Hemat {{ $discount }}%
                        </span>
                    @endif
                </div>
                <p class="text-[11px] text-slate-500 mt-1">Harga resmi nett sudah termasuk PPN</p>
            </div>

            <!-- Stock & Weight Info Grid -->
            <div class="grid grid-cols-2 gap-4 mb-6 text-xs">
                <div class="p-3.5 rounded-2xl bg-white border border-slate-200">
                    <span class="text-slate-400 block mb-0.5">Ketersediaan Stok</span>
                    @if($product->stock > 0)
                        <span class="font-bold text-emerald-600 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Tersedia ({{ $product->stock }} unit)
                        </span>
                    @else
                        <span class="font-bold text-rose-600">Stok Habis</span>
                    @endif
                </div>
                <div class="p-3.5 rounded-2xl bg-white border border-slate-200">
                    <span class="text-slate-400 block mb-0.5">Berat Paket</span>
                    <span class="font-bold text-slate-800">{{ number_format($product->weight_grams, 0, ',', '.') }} gram</span>
                </div>
            </div>

            <!-- Purchase Form -->
            @if($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="space-y-4 mb-8">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <!-- Quantity -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jumlah Pembelian</label>
                        <div class="flex items-center gap-3">
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" required class="w-28 px-4 py-2.5 text-xs font-bold text-center bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                            <span class="text-xs text-slate-400">Maks. {{ $product->stock }} unit</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <button type="submit" class="py-3.5 px-6 rounded-xl font-bold bg-white text-indigo-600 border-2 border-indigo-600 hover:bg-indigo-50 transition text-xs flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            + Keranjang
                        </button>
                        <button type="submit" name="buy_now" value="1" class="py-3.5 px-6 rounded-xl font-black bg-indigo-600 hover:bg-indigo-500 text-white transition text-xs shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2">
                            Beli Sekarang
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>
            @else
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold mb-8">
                    Produk ini saat ini habis terjual. Silakan jelajahi katalog untuk produk serupa lainnya.
                </div>
            @endif

            <!-- Description Box -->
            <div class="border-t border-slate-200 pt-6">
                <h3 class="font-bold text-slate-900 text-sm mb-3">Deskripsi & Spesifikasi Produk</h3>
                <div class="text-xs text-slate-600 leading-relaxed whitespace-pre-line bg-slate-50 p-5 rounded-2xl border border-slate-100">
                    {{ $product->description }}
                </div>
            </div>
        </div>

    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <div class="border-t border-slate-200 pt-12">
            <h2 class="text-xl font-black text-slate-900 mb-6">Produk Serupa di Kategori {{ $product->category->name }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col group">
                        <div class="relative aspect-square bg-slate-100 overflow-hidden">
                            @if($related->primaryImage)
                                <img src="{{ $related->primaryImage->url }}" alt="{{ $related->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @endif
                            <span class="absolute top-3 left-3 px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $related->condition_badge['color'] }}">
                                {{ $related->condition_badge['label'] }}
                            </span>
                        </div>
                        <div class="p-4 flex flex-col flex-grow">
                            <h4 class="font-bold text-slate-900 text-xs mb-1 line-clamp-1">
                                <a href="{{ route('catalog.show', $related->slug) }}" class="hover:text-indigo-600">
                                    {{ $related->name }}
                                </a>
                            </h4>
                            <div class="mt-auto pt-2 text-sm font-black text-indigo-600">
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
