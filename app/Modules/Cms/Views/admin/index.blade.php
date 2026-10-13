@extends('admin::layouts.admin')

@section('title', 'Manajemen Visual CMS')

@section('content')
<div class="space-y-8">

    <!-- Top Action & Form Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-xl font-black text-slate-900 mb-1">Daftar Halaman Visual Builder</h2>
            <p class="text-xs text-slate-500">Rancang beranda, halaman promo, dan landing page secara visual menggunakan Native GrapesJS drag-and-drop.</p>
        </div>
        
        <!-- Quick Create Form -->
        <form action="{{ route('admin.cms.store') }}" method="POST" class="flex flex-wrap items-center gap-3">
            @csrf
            <div>
                <input type="text" name="title" required placeholder="Judul Halaman (misal: Promo Lebaran)" class="px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 w-64">
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition shadow-md shadow-indigo-600/20 flex items-center gap-2">
                <span>+ Buat Halaman Baru</span>
            </button>
        </form>
    </div>

    <!-- Table of CMS Pages -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">Total Halaman Terdaftar ({{ $pages->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wider font-bold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Judul Halaman</th>
                        <th class="py-4 px-6">Slug URL</th>
                        <th class="py-4 px-6">Status Beranda</th>
                        <th class="py-4 px-6">Terakhir Diperbarui</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pages as $page)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-bold text-slate-900">
                                {{ $page->title }}
                            </td>
                            <td class="py-4 px-6 font-mono text-[11px] text-indigo-600">
                                @if($page->is_homepage)
                                    <span class="text-slate-700 font-bold">/</span> (Beranda Utama)
                                @else
                                    /p/{{ $page->slug }}
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($page->is_homepage)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Beranda Aktif
                                    </span>
                                @else
                                    <form action="{{ route('admin.cms.homepage', $page->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-lg text-[11px] font-bold border border-slate-200 text-slate-600 hover:bg-slate-100 transition">
                                            Jadikan Beranda
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-slate-400">
                                {{ $page->updated_at->format('d M Y, H:i') }}
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <!-- Builder Button -->
                                <a href="{{ route('admin.cms.builder', $page->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    Buka Visual Builder
                                </a>

                                <!-- Preview Button -->
                                <a href="{{ $page->is_homepage ? url('/') : url('/p/' . $page->slug) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 transition font-bold text-xs">
                                    Lihat Publik &rarr;
                                </a>

                                <!-- Delete (If not homepage) -->
                                @if(!$page->is_homepage)
                                    <form action="{{ route('admin.cms.destroy', $page->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus halaman ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus Halaman">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                Belum ada halaman CMS dibuat. Silakan tambahkan halaman baru melalui formulir di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
