@extends('admin::layouts.admin')

@section('title', 'Manajemen Produk')

@section('content')
<div class="space-y-8">
    
    <!-- Top Action Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-xl font-black text-slate-900 mb-1">Katalog Produk</h2>
            <p class="text-xs text-slate-500">Kelola inventaris barang baru dan preloved (SKU, stok, berat ongkir, dan multi-foto).</p>
        </div>

        <div class="flex items-center gap-3">
            <form action="{{ route('admin.products.index') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / SKU..." class="px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition">
                    Cari
                </button>
            </form>
            <a href="{{ route('admin.products.create') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition shadow-md shadow-indigo-600/20 flex items-center gap-1.5 flex-shrink-0">
                <span>+ Tambah Produk</span>
            </a>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">Total Produk ({{ $products->total() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wider font-bold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Foto</th>
                        <th class="py-4 px-6">Nama & SKU</th>
                        <th class="py-4 px-6">Kategori</th>
                        <th class="py-4 px-6">Kondisi</th>
                        <th class="py-4 px-6">Harga Jual</th>
                        <th class="py-4 px-6">Stok</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden border border-slate-200">
                                    @if($product->primaryImage)
                                        <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-900 block leading-tight">{{ $product->name }}</span>
                                <span class="font-mono text-[10px] text-slate-400">SKU: {{ $product->sku }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-semibold text-slate-700">{{ $product->category ? $product->category->name : '-' }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $product->condition_badge['color'] }}">
                                    {{ $product->condition_badge['label'] }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-black text-slate-900">
                                {{ $product->formatted_price }}
                            </td>
                            <td class="py-4 px-6">
                                @if($product->stock > 0)
                                    <span class="font-bold text-emerald-600">{{ $product->stock }} Unit</span>
                                @else
                                    <span class="font-bold text-rose-600">Habis (0)</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="px-3 py-1.5 rounded-xl border border-slate-200 text-slate-700 font-bold hover:bg-slate-50 transition">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="inline" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Produk">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">Tidak ada produk ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-slate-100">
            {{ $products->links() }}
        </div>
    </div>

</div>
@endsection
