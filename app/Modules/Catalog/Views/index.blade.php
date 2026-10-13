@extends('layouts.store')

@section('title', 'Katalog Produk Pilihan & Preloved - Reloo Marketplace')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Breadcrumb & Header -->
    <div class="mb-8">
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-indigo-600">Beranda</a>
            <span>/</span>
            <span class="text-slate-900 font-bold">Katalog Produk</span>
            @if(request('category'))
                <span>/</span>
                <span class="text-indigo-600 font-bold capitalize">{{ request('category') }}</span>
            @endif
        </nav>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Katalog Belanja Reloo</h1>
                <p class="text-xs text-slate-500">Menampilkan {{ $products->total() }} barang terkurasi</p>
            </div>
            
            <!-- Active Filters Reset -->
            @if(request()->hasAny(['q', 'category', 'condition', 'min_price', 'max_price', 'sort']))
                <div>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-rose-600 hover:bg-rose-50 transition">
                        <span>&times;</span> Reset Semua Filter
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Filter Sidebar -->
        <aside class="lg:col-span-1 space-y-6">
            <form action="{{ route('catalog.index') }}" method="GET" class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-6">
                <!-- Preserve Search Keyword -->
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif

                <!-- Sorting -->
                <div>
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Urutkan</label>
                    <select name="sort" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama: A-Z</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Kategori</label>
                    <div class="space-y-1.5">
                        <label class="flex items-center justify-between p-2 rounded-xl text-xs hover:bg-slate-50 cursor-pointer font-medium {{ !request('category') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600' }}">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="category" value="" onchange="this.form.submit()" {{ !request('category') ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                <span>Semua Kategori</span>
                            </div>
                        </label>
                        @foreach($categories as $category)
                            <label class="flex items-center justify-between p-2 rounded-xl text-xs hover:bg-slate-50 cursor-pointer font-medium {{ request('category') == $category->slug ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600' }}">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="category" value="{{ $category->slug }}" onchange="this.form.submit()" {{ request('category') == $category->slug ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                    <span>{{ $category->name }}</span>
                                </div>
                                <span class="text-[10px] text-slate-400">({{ $category->products_count }})</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Condition Filter -->
                <div>
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Kondisi Barang</label>
                    <div class="space-y-1.5">
                        @php
                            $conditions = [
                                '' => 'Semua Kondisi',
                                'new' => 'Baru (Brand New)',
                                'like_new' => 'Seperti Baru (Like New)',
                                'good' => 'Bagus (Good Condition)',
                                'fair' => 'Cukup (Fair Condition)',
                            ];
                        @endphp
                        @foreach($conditions as $cKey => $cLabel)
                            <label class="flex items-center gap-2 p-2 rounded-xl text-xs hover:bg-slate-50 cursor-pointer font-medium {{ request('condition') == $cKey ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600' }}">
                                <input type="radio" name="condition" value="{{ $cKey }}" onchange="this.form.submit()" {{ request('condition') == $cKey ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                <span>{{ $cLabel }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range -->
                <div>
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Rentang Harga (Rp)</label>
                    <div class="space-y-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min: Rp 0" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Maks: Rp 10.000.000" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none">
                        <button type="submit" class="w-full py-2 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-indigo-600 transition">
                            Terapkan Harga
                        </button>
                    </div>
                </div>
            </form>
        </aside>

        <!-- Product Grid -->
        <main class="lg:col-span-3">
            @if($products->isEmpty())
                <div class="bg-white rounded-3xl p-12 border border-slate-200 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4 text-xs font-bold uppercase">
                        NIL
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-1">Tidak ada produk ditemukan</h3>
                    <p class="text-xs text-slate-500 mb-6">Coba ubah kata kunci pencarian atau reset filter yang dipilih.</p>
                    <a href="{{ route('catalog.index') }}" class="px-5 py-2.5 rounded-xl font-bold bg-indigo-600 text-white text-xs hover:bg-indigo-500 transition">
                        Reset Filter
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col group">
                            <!-- Image & Condition Badge -->
                            <div class="relative aspect-square bg-slate-100 overflow-hidden">
                                @if($product->primaryImage)
                                    <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300 text-xs">Foto Tidak Tersedia</div>
                                @endif
                                
                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $product->condition_badge['color'] }}">
                                    {{ $product->condition_badge['label'] }}
                                </span>

                                @if($product->stock <= 2 && $product->stock > 0)
                                    <span class="absolute top-3 right-3 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white shadow-sm">
                                        Sisa {{ $product->stock }}!
                                    </span>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="p-5 flex flex-col flex-grow">
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">
                                    {{ $product->category->name }}
                                </span>
                                <h3 class="font-bold text-slate-900 text-sm mb-2 line-clamp-2 hover:text-indigo-600 transition">
                                    <a href="{{ route('catalog.show', $product->slug) }}">
                                        {{ $product->name }}
                                    </a>
                                </h3>

                                <div class="mt-auto pt-4 border-t border-slate-100">
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-base font-black text-indigo-600">
                                            {{ $product->formatted_price }}
                                        </span>
                                        @if($product->formatted_original_price)
                                            <span class="text-[11px] text-slate-400 line-through">
                                                {{ $product->formatted_original_price }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('catalog.show', $product->slug) }}" class="w-full block text-center py-2.5 rounded-xl font-bold bg-slate-900 hover:bg-indigo-600 text-white text-xs transition">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </main>

    </div>
</div>
@endsection
