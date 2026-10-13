@extends('admin::layouts.admin')

@section('title', 'Kategori Produk')

@section('content')
<div class="space-y-8">
    
    <!-- Top Action & Create Form Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h2 class="text-xl font-black text-slate-900 mb-1">Kelola Kategori Produk</h2>
            <p class="text-xs text-slate-500">Kelompokkan produk ke dalam kategori etalase (Sepatu, Tas, Jam, Elektronik, Pakaian).</p>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex flex-wrap items-center gap-3">
            @csrf
            <div>
                <input type="text" name="name" required placeholder="Nama Kategori (cth: Aksesoris)" class="px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
            </div>
            <div>
                <input type="url" name="image_path" placeholder="URL Foto Banner (opsional)" class="px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition shadow-md shadow-indigo-600/20">
                + Tambah Kategori
            </button>
        </form>
    </div>

    <!-- Categories List Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">Daftar Kategori Aktif ({{ $categories->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wider font-bold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Foto</th>
                        <th class="py-4 px-6">Nama Kategori</th>
                        <th class="py-4 px-6">Slug</th>
                        <th class="py-4 px-6">Jumlah Produk</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $category)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden border border-slate-200 flex items-center justify-center">
                                    @if($category->image_path)
                                        <img src="{{ $category->image_path }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-slate-400 font-bold text-[10px]">KAT</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-900">
                                {{ $category->name }}
                            </td>
                            <td class="py-4 px-6 font-mono text-indigo-600">
                                {{ $category->slug }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ $category->products_count }} Produk
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                @if($category->products_count === 0)
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Kategori">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[10px] text-slate-400 italic">Terikat produk</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">Belum ada kategori yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
