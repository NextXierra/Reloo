@extends('admin::layouts.admin')

@section('title', 'Manajemen Produk')

@section('content')
<div class="space-y-6">

    <!-- Top Action Card -->
    <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA] flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-1">INVENTARIS TOKO</span>
            <h2 class="font-serif text-2xl text-[#111111] tracking-tight mb-1">Katalog Produk</h2>
            <p class="text-xs text-[#52525B]">Kelola inventaris barang baru dan preloved (SKU, stok, berat ongkir, dan multi-foto).</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <form action="{{ route('admin.products.index') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / SKU..." class="px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                <button type="submit" class="px-3 py-2 rounded-[5px] bg-[#111111] text-white font-medium text-xs hover:bg-[#27272A] transition-colors">
                    Cari
                </button>
            </form>
            <a href="{{ route('admin.products.create') }}" class="px-3.5 py-2 rounded-[5px] bg-[#111111] hover:bg-[#27272A] text-white font-medium text-xs transition-colors flex items-center gap-1.5 flex-shrink-0 whitespace-nowrap">
                <span>+ Tambah Produk</span>
            </a>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-[6px] border border-[#EAEAEA] overflow-hidden">
        <div class="px-6 py-4 border-b border-[#EAEAEA] flex items-center justify-between">
            <h3 class="font-mono text-xs uppercase tracking-wider text-[#111111]">Total Produk Terdaftar ({{ $products->total() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F7F6F3] text-[10px] font-mono uppercase tracking-wider text-[#71717A] border-b border-[#EAEAEA]">
                    <tr>
                        <th class="py-3 px-6">Foto</th>
                        <th class="py-3 px-6">Nama & SKU</th>
                        <th class="py-3 px-6">Kategori</th>
                        <th class="py-3 px-6">Kondisi</th>
                        <th class="py-3 px-6">Harga Jual</th>
                        <th class="py-3 px-6">Stok</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EAEAEA]">
                    @forelse($products as $product)
                        <tr class="hover:bg-[#F7F6F3] transition-colors">
                            <td class="py-3.5 px-6">
                                <div class="w-10 h-10 rounded-[3px] bg-[#F7F6F3] overflow-hidden border border-[#EAEAEA] p-0.5">
                                    @if($product->primaryImage)
                                        <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-[2px]">
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="font-medium text-[#111111] block leading-tight">{{ $product->name }}</span>
                                <span class="font-mono text-[10px] text-[#71717A]">SKU: {{ $product->sku }}</span>
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="text-[#52525B]">{{ $product->category ? $product->category->name : '-' }}</span>
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono uppercase border {{ $product->condition_badge['color'] }}">
                                    {{ $product->condition_badge['label'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 font-mono font-semibold text-[#111111]">
                                {{ $product->formatted_price }}
                            </td>
                            <td class="py-3.5 px-6 font-mono">
                                @if($product->stock > 0)
                                    <span class="text-[#26542A]">{{ $product->stock }} Unit</span>
                                @else
                                    <span class="text-[#8C2220]">Habis (0)</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right space-x-1">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center px-2.5 py-1 rounded-[4px] border border-[#EAEAEA] text-[#111111] font-medium text-xs hover:bg-[#F7F6F3] transition-colors">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="inline" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-[#8C2220] hover:bg-[#FDEBEC] rounded-[4px] transition-colors" title="Hapus Produk">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-[#71717A] font-mono text-xs">Tidak ada produk ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-[#EAEAEA]">
            {{ $products->links() }}
        </div>
    </div>

</div>
@endsection
