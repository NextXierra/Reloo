@extends('layouts.store')

@section('title', 'Katalog Belanja Terkurasi · Reloo')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">

    <!-- Breadcrumb & Header -->
    <div class="mb-8 pb-4 border-b border-[#EAEAEA]">
        <nav class="flex items-center gap-2 text-xs text-[#71717A] font-mono mb-2">
            <a href="{{ route('home') }}" class="hover:text-[#111111]">BERANDA</a>
            <span>/</span>
            <span class="text-[#111111] font-semibold">KATALOG</span>
            @if(request('category'))
                <span>/</span>
                <span class="text-[#111111] uppercase font-semibold">{{ request('category') }}</span>
            @endif
        </nav>
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="font-serif text-3xl sm:text-4xl text-[#111111] tracking-tight">Katalog Belanja Reloo</h1>
                <p class="text-xs text-[#71717A] font-mono mt-1">MENAMPILKAN {{ $products->total() }} ITEM TERVERIFIKASI FISIK</p>
            </div>

            <!-- Active Filters Reset -->
            @if(request()->hasAny(['q', 'category', 'condition', 'min_price', 'max_price', 'sort']))
                <div>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-[5px] border border-[#EAEAEA] bg-white text-xs text-[#8C2220] hover:bg-[#FDEBEC] transition-colors">
                        <span>&times;</span> Reset Semua Filter
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">

        <!-- Filter Sidebar -->
        <aside class="lg:col-span-1">
            <form action="{{ route('catalog.index') }}" method="GET" class="bg-white rounded-[6px] p-5 border border-[#EAEAEA] space-y-6">
                <!-- Preserve Search Keyword -->
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif

                <!-- Sorting -->
                <div>
                    <label class="block text-[10px] font-mono uppercase tracking-wider text-[#71717A] mb-2">Urutkan Koleksi</label>
                    <select name="sort" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru Masuk</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama: A &rarr; Z</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-[10px] font-mono uppercase tracking-wider text-[#71717A] mb-2">Kategori Produk</label>
                    <div class="space-y-1">
                        <label class="flex items-center justify-between p-2 rounded-[4px] text-xs hover:bg-[#F7F6F3] cursor-pointer transition-colors {{ !request('category') ? 'bg-[#F7F6F3] text-[#111111] font-medium' : 'text-[#52525B]' }}">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="category" value="" onchange="this.form.submit()" {{ !request('category') ? 'checked' : '' }} class="text-[#111111] focus:ring-[#111111]">
                                <span>Semua Kategori</span>
                            </div>
                        </label>
                        @foreach($categories as $category)
                            <label class="flex items-center justify-between p-2 rounded-[4px] text-xs hover:bg-[#F7F6F3] cursor-pointer transition-colors {{ request('category') == $category->slug ? 'bg-[#F7F6F3] text-[#111111] font-medium' : 'text-[#52525B]' }}">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="category" value="{{ $category->slug }}" onchange="this.form.submit()" {{ request('category') == $category->slug ? 'checked' : '' }} class="text-[#111111] focus:ring-[#111111]">
                                    <span>{{ $category->name }}</span>
                                </div>
                                <span class="font-mono text-[10px] text-[#71717A]">({{ $category->products_count }})</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Condition Filter -->
                <div>
                    <label class="block text-[10px] font-mono uppercase tracking-wider text-[#71717A] mb-2">Kondisi Barang</label>
                    <div class="space-y-1">
                        @php
                            $conditions = [
                                '' => 'Semua Kondisi',
                                'new' => 'Baru (Brand New)',
                                'like_new' => 'Seperti Baru (Like New)',
                                'good' => 'Bagus (Good)',
                                'fair' => 'Cukup (Fair)',
                            ];
                        @endphp
                        @foreach($conditions as $cKey => $cLabel)
                            <label class="flex items-center gap-2 p-2 rounded-[4px] text-xs hover:bg-[#F7F6F3] cursor-pointer transition-colors {{ request('condition') == $cKey ? 'bg-[#F7F6F3] text-[#111111] font-medium' : 'text-[#52525B]' }}">
                                <input type="radio" name="condition" value="{{ $cKey }}" onchange="this.form.submit()" {{ request('condition') == $cKey ? 'checked' : '' }} class="text-[#111111] focus:ring-[#111111]">
                                <span>{{ $cLabel }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range -->
                <div>
                    <label class="block text-[10px] font-mono uppercase tracking-wider text-[#71717A] mb-2">Rentang Harga (Rp)</label>
                    <div class="space-y-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min: Rp 0" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] font-mono focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Maks: Rp 10.000.000" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] font-mono focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        <button type="submit" class="w-full py-2 rounded-[5px] bg-[#111111] text-white font-medium text-xs hover:bg-[#27272A] transition-colors whitespace-nowrap">
                            Terapkan Rentang
                        </button>
                    </div>
                </div>
            </form>
        </aside>

        <!-- Product Grid (Hallmark Gate 50 minmax track) -->
        <main class="lg:col-span-3">
            @if($products->isEmpty())
                <div class="bg-white rounded-[6px] p-12 border border-[#EAEAEA] text-center">
                    <div class="font-mono text-xs text-[#71717A] uppercase mb-2">
                        [DATA NIHIL]
                    </div>
                    <h3 class="font-serif text-xl text-[#111111] mb-2">Tidak Ada Produk Sesuai Kriteria</h3>
                    <p class="text-xs text-[#52525B] max-w-sm mx-auto mb-6 leading-relaxed">
                        Coba sesuaikan kata kunci pencarian, rentang harga, atau reset filter kategori untuk melihat koleksi lainnya.
                    </p>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-[5px] bg-[#111111] text-white text-xs font-medium hover:bg-[#27272A] transition-colors">
                        Reset Semua Filter
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6" style="grid-template-columns: repeat(auto-fill, minmax(min(100%, 260px), 1fr));">
                    @foreach($products as $product)
                        <div class="bg-white rounded-[6px] border border-[#EAEAEA] p-4 flex flex-col group hover:border-[#111111] transition-colors">
                            <!-- Image & Condition Badge -->
                            <div class="relative aspect-square rounded-[4px] overflow-hidden bg-[#F7F6F3] mb-4">
                                @if($product->primaryImage)
                                    <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-[#71717A] font-mono text-xs">[FOTO PRODUK]</div>
                                @endif

                                <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase border {{ $product->condition_badge['color'] }}">
                                    {{ $product->condition_badge['label'] }}
                                </span>

                                @if($product->stock <= 2 && $product->stock > 0)
                                    <span class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#FDEBEC] text-[#8C2220] border border-[#F8C8CA]">
                                        Sisa {{ $product->stock }}!
                                    </span>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="flex flex-col flex-grow">
                                <span class="text-[10px] font-mono text-[#71717A] uppercase mb-1">
                                    {{ $product->category->name }}
                                </span>
                                <h3 class="font-medium text-xs text-[#111111] mb-2 line-clamp-2">
                                    <a href="{{ route('catalog.show', $product->slug) }}" class="hover:underline">
                                        {{ $product->name }}
                                    </a>
                                </h3>

                                <div class="mt-auto pt-3 border-t border-[#EAEAEA]">
                                    <div class="flex items-baseline justify-between mb-3">
                                        <span class="font-mono text-sm font-semibold text-[#111111]">
                                            {{ $product->formatted_price }}
                                        </span>
                                        @if($product->formatted_original_price)
                                            <span class="font-mono text-[10px] text-[#A1A1AA] line-through">
                                                {{ $product->formatted_original_price }}
                                            </span>
                                        @endif
                                    </div>
                                    <a href="{{ route('catalog.show', $product->slug) }}" class="w-full inline-flex items-center justify-center py-2 rounded-[5px] font-medium bg-[#111111] hover:bg-[#27272A] text-white text-xs transition-colors whitespace-nowrap">
                                        Rincian Barang
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-10 pt-6 border-t border-[#EAEAEA]">
                    {{ $products->links() }}
                </div>
            @endif
        </main>

    </div>
</div>
@endsection
