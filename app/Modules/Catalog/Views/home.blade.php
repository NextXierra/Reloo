@extends('layouts.store')

@section('title', 'Reloo Marketplace - E-Commerce Preloved & Barang Pilihan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

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
                <a href="{{ route('catalog.index') }}" class="px-6 py-3.5 rounded-xl font-bold bg-indigo-600 hover:bg-indigo-500 text-white transition shadow-lg shadow-indigo-600/30 inline-flex items-center gap-2">
                    Jelajahi Katalog
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="{{ route('catalog.index', ['condition' => 'like_new']) }}" class="px-6 py-3.5 rounded-xl font-bold bg-white/10 hover:bg-white/20 text-white backdrop-blur transition border border-white/20">
                    Koleksi Like New
                </a>
            </div>
        </div>
        <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Category Grid -->
    <div class="mb-16">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Kategori Pilihan</h2>
                <p class="text-slate-500 text-xs">Pilih kategori favorit Anda</p>
            </div>
            <a href="{{ route('catalog.index') }}" class="text-indigo-600 font-bold text-xs hover:underline">Semua Kategori &rarr;</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($categories as $cat)
                <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" class="group relative rounded-2xl overflow-hidden aspect-square border border-slate-200 bg-slate-100 flex flex-col justify-end p-4 hover:shadow-lg transition">
                    @if($cat->image_path)
                        <img src="{{ $cat->image_path }}" alt="{{ $cat->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                    <span class="relative z-10 font-bold text-white text-sm">{{ $cat->name }}</span>
                    <span class="relative z-10 text-[10px] text-slate-300">{{ $cat->products_count }} Produk</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Featured Products Grid -->
    <div>
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Produk Pilihan Terbaru</h2>
                <p class="text-slate-500 text-xs">Barang segar baru masuk di etalase</p>
            </div>
            <a href="{{ route('catalog.index') }}" class="text-indigo-600 font-bold text-xs hover:underline">Lihat Semua &rarr;</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $item)
                <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col group">
                    <div class="relative aspect-square bg-slate-100 overflow-hidden">
                        @if($item->primaryImage)
                            <img src="{{ $item->primaryImage->url }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @endif
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $item->condition_badge['color'] }}">
                            {{ $item->condition_badge['label'] }}
                        </span>
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">
                            {{ $item->category->name }}
                        </span>
                        <h3 class="font-bold text-slate-900 text-sm mb-2 line-clamp-1">
                            <a href="{{ route('catalog.show', $item->slug) }}" class="hover:text-indigo-600">
                                {{ $item->name }}
                            </a>
                        </h3>
                        <div class="mt-auto pt-3 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-sm font-black text-indigo-600">{{ $item->formatted_price }}</span>
                            </div>
                            <a href="{{ route('catalog.show', $item->slug) }}" class="px-3 py-1.5 rounded-lg bg-slate-900 text-white font-bold text-[11px] hover:bg-indigo-600 transition">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
