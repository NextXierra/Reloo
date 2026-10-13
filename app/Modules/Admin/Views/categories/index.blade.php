@extends('admin::layouts.admin')

@section('title', 'Kategori Produk')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Create Form Card -->
    <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA] flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-0.5">STRUKTUR KATALOG</span>
            <h2 class="font-serif text-2xl text-[#111111] mb-1">Kelola Kategori Produk</h2>
            <p class="text-xs text-[#52525B]">Kelompokkan produk ke dalam kategori etalase (Sepatu, Tas, Jam Tangan, Elektronik, Pakaian).</p>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex flex-wrap items-center gap-2">
            @csrf
            <div>
                <input type="text" name="name" required placeholder="Nama Kategori (cth: Aksesoris)" class="px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
            </div>
            <div>
                <input type="url" name="image_path" placeholder="URL Foto Banner (opsional)" class="px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] font-mono focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
            </div>
            <button type="submit" class="px-4 py-2 rounded-[5px] bg-[#111111] hover:bg-[#27272A] text-white font-medium text-xs transition-colors whitespace-nowrap">
                + Tambah Kategori
            </button>
        </form>
    </div>

    <!-- Categories List Table -->
    <div class="bg-white rounded-[6px] border border-[#EAEAEA] overflow-hidden">
        <div class="px-6 py-4 border-b border-[#EAEAEA] flex items-center justify-between">
            <h3 class="font-mono text-xs uppercase tracking-wider text-[#111111]">Daftar Kategori Terdaftar ({{ $categories->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F7F6F3] text-[10px] font-mono uppercase tracking-wider text-[#71717A] border-b border-[#EAEAEA]">
                    <tr>
                        <th class="py-3 px-6">Foto</th>
                        <th class="py-3 px-6">Nama Kategori</th>
                        <th class="py-3 px-6">Slug</th>
                        <th class="py-3 px-6">Jumlah Produk</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EAEAEA]">
                    @forelse($categories as $category)
                        <tr class="hover:bg-[#F7F6F3] transition-colors">
                            <td class="py-3.5 px-6">
                                <div class="w-10 h-10 rounded-[3px] bg-[#F7F6F3] overflow-hidden border border-[#EAEAEA] flex items-center justify-center p-0.5">
                                    @if($category->image_path)
                                        <img src="{{ $category->image_path }}" alt="{{ $category->name }}" class="w-full h-full object-cover rounded-[2px]">
                                    @else
                                        <span class="text-[#71717A] font-mono text-[9px] uppercase">KAT</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-6 font-medium text-[#111111]">
                                {{ $category->name }}
                            </td>
                            <td class="py-3.5 px-6 font-mono text-[11px] text-[#52525B]">
                                {{ $category->slug }}
                            </td>
                            <td class="py-3.5 px-6 font-mono">
                                <span class="px-2 py-0.5 rounded-full text-[10px] bg-[#F7F6F3] text-[#52525B] border border-[#EAEAEA]">
                                    {{ $category->products_count }} Produk
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                @if($category->products_count === 0)
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-[#8C2220] hover:bg-[#FDEBEC] rounded-[4px] transition-colors" title="Hapus Kategori">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="font-mono text-[10px] text-[#71717A]">Terikat item</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-[#71717A] font-mono text-xs">Belum ada kategori yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
