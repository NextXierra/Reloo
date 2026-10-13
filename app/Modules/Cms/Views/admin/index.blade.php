@extends('admin::layouts.admin')

@section('title', 'Manajemen Visual CMS')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Form Card -->
    <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA] flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-1">MANAJEMEN HALAMAN</span>
            <h2 class="font-serif text-2xl text-[#111111] tracking-tight mb-1">Daftar Halaman Visual Builder</h2>
            <p class="text-xs text-[#52525B] max-w-xl leading-relaxed">
                Rancang beranda toko, landing page promo, atau halaman panduan menggunakan Visual Builder GrapesJS. Anda dapat mengaktifkan beranda visual atau kembali ke katalog produk dinamis secara fleksibel.
            </p>
        </div>

        <!-- Quick Create Form -->
        <form action="{{ route('admin.cms.store') }}" method="POST" class="flex flex-wrap items-center gap-2">
            @csrf
            <div>
                <input type="text" name="title" required placeholder="Judul Halaman (cth: Promo Spesial)" class="px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] placeholder:text-[#A1A1AA] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111] w-64">
            </div>
            <button type="submit" class="px-4 py-2 rounded-[5px] bg-[#111111] hover:bg-[#27272A] text-white text-xs font-medium transition-colors flex items-center gap-1.5 whitespace-nowrap">
                <span>+ Buat Halaman</span>
            </button>
        </form>
    </div>

    <!-- Table of CMS Pages -->
    <div class="bg-white rounded-[6px] border border-[#EAEAEA] overflow-hidden">
        <div class="px-6 py-4 border-b border-[#EAEAEA] flex items-center justify-between">
            <h3 class="font-mono text-xs uppercase tracking-wider text-[#111111]">Semua Halaman Visual ({{ $pages->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F7F6F3] text-[10px] font-mono uppercase tracking-wider text-[#71717A] border-b border-[#EAEAEA]">
                    <tr>
                        <th class="py-3 px-6">Judul Halaman</th>
                        <th class="py-3 px-6">URL / Path</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6">Tipe Beranda</th>
                        <th class="py-3 px-6">Pembaruan</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EAEAEA]">
                    @forelse($pages as $page)
                        <tr class="hover:bg-[#F7F6F3] transition-colors">
                            <td class="py-3.5 px-6 font-medium text-[#111111]">
                                <div class="flex items-center gap-2">
                                    <span>{{ $page->title }}</span>
                                    @if($page->is_homepage)
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-mono uppercase bg-[#EDF3EC] text-[#26542A] border border-[#D1E2CF]">
                                            Beranda
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-6 font-mono text-[11px] text-[#52525B]">
                                @if($page->is_homepage)
                                    <span class="text-[#111111] font-semibold">/</span> <span class="text-[#71717A]">(Root)</span>
                                @else
                                    /p/{{ $page->slug }}
                                @endif
                            </td>
                            <td class="py-3.5 px-6">
                                @if($page->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#EDF3EC] text-[#26542A] border border-[#D1E2CF]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#26542A]"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#F7F6F3] text-[#71717A] border border-[#EAEAEA]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#A1A1AA]"></span>
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6">
                                <form action="{{ route('admin.cms.toggle_homepage', $page->id) }}" method="POST" class="inline">
                                    @csrf
                                    @if($page->is_homepage)
                                        <button type="submit" class="px-2.5 py-1 rounded-[4px] text-[11px] font-mono bg-[#FDEBEC] hover:bg-[#F8C8CA] text-[#8C2220] border border-[#F8C8CA] transition-colors" title="Nonaktifkan untuk kembali ke katalog toko">
                                            Kembalikan ke Katalog
                                        </button>
                                    @else
                                        <button type="submit" class="px-2.5 py-1 rounded-[4px] text-[11px] font-mono bg-[#F7F6F3] hover:bg-[#EAEAEA] text-[#52525B] hover:text-[#111111] border border-[#EAEAEA] transition-colors">
                                            Jadikan Beranda
                                        </button>
                                    @endif
                                </form>
                            </td>
                            <td class="py-3.5 px-6 font-mono text-[11px] text-[#71717A]">
                                {{ $page->updated_at->format('d M Y, H:i') }}
                            </td>
                            <td class="py-3.5 px-6 text-right space-x-1">
                                <!-- Builder Button -->
                                <a href="{{ route('admin.cms.builder', $page->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-[4px] bg-[#111111] hover:bg-[#27272A] text-white text-xs font-medium transition-colors">
                                    <span>Visual Builder</span>
                                </a>

                                <!-- Edit Metadata Modal Button -->
                                <button type="button" onclick="openEditModal({{ $page->id }}, '{{ addslashes($page->title) }}', '{{ $page->slug }}', {{ $page->is_active ? 'true' : 'false' }}, {{ $page->is_homepage ? 'true' : 'false' }})" class="inline-flex items-center p-1 rounded-[4px] border border-[#EAEAEA] text-[#52525B] hover:text-[#111111] hover:bg-[#F7F6F3] transition-colors" title="Edit Metadata Halaman">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>

                                <!-- View Public Button -->
                                <a href="{{ $page->is_homepage ? url('/') : url('/p/' . $page->slug) }}" target="_blank" class="inline-flex items-center p-1 rounded-[4px] border border-[#EAEAEA] text-[#52525B] hover:text-[#111111] hover:bg-[#F7F6F3] transition-colors" title="Lihat Tampilan Publik">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>

                                <!-- Delete Button -->
                                @if(!$page->is_homepage || !$page->is_active)
                                    <form action="{{ route('admin.cms.destroy', $page->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus halaman CMS ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-[#8C2220] hover:bg-[#FDEBEC] rounded-[4px] transition-colors" title="Hapus Halaman">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-[#71717A] font-mono text-xs">
                                Belum ada halaman CMS dibuat. Silakan tambahkan halaman baru melalui formulir di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Edit Metadata Halaman -->
<div id="edit-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-[2px] hidden">
    <div class="bg-white rounded-[6px] p-6 max-w-md w-full mx-4 border border-[#EAEAEA] shadow-lg">
        <div class="flex items-center justify-between pb-3 border-b border-[#EAEAEA] mb-5">
            <h3 class="font-serif text-lg text-[#111111]">Edit Metadata Halaman</h3>
            <button type="button" onclick="closeEditModal()" class="text-[#71717A] hover:text-[#111111] text-lg font-bold">&times;</button>
        </div>

        <form id="edit-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-medium text-[#111111] mb-1">Judul Halaman</label>
                <input type="text" id="edit-title" name="title" required class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
            </div>

            <div>
                <label class="block text-xs font-medium text-[#111111] mb-1">Slug URL</label>
                <div class="flex items-center">
                    <span class="px-2.5 py-2 bg-[#F7F6F3] border border-r-0 border-[#EAEAEA] rounded-l-[5px] text-xs text-[#71717A] font-mono">/p/</span>
                    <input type="text" id="edit-slug" name="slug" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-r-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111] font-mono">
                </div>
                <span class="text-[10px] text-[#71717A] mt-1 block">Kosongkan untuk membuat slug otomatis dari judul.</span>
            </div>

            <div class="pt-2 space-y-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="edit-active" name="is_active" value="1" class="rounded border-[#EAEAEA] text-[#111111] focus:ring-[#111111]">
                    <span class="text-xs text-[#111111]">Halaman Aktif (Dapat diakses publik)</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="edit-homepage" name="is_homepage" value="1" class="rounded border-[#EAEAEA] text-[#111111] focus:ring-[#111111]">
                    <span class="text-xs text-[#111111]">Jadikan Beranda Utama Toko</span>
                </label>
            </div>

            <div class="pt-4 flex justify-end gap-2 border-t border-[#EAEAEA] mt-5">
                <button type="button" onclick="closeEditModal()" class="px-3.5 py-2 rounded-[5px] border border-[#EAEAEA] text-[#52525B] hover:bg-[#F7F6F3] text-xs font-medium transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-[5px] bg-[#111111] hover:bg-[#27272A] text-white text-xs font-medium transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, title, slug, isActive, isHomepage) {
        const modal = document.getElementById('edit-modal');
        const form = document.getElementById('edit-form');
        form.action = `/admin/cms/${id}`;

        document.getElementById('edit-title').value = title;
        document.getElementById('edit-slug').value = slug;
        document.getElementById('edit-active').checked = isActive;
        document.getElementById('edit-homepage').checked = isHomepage;

        modal.classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeEditModal();
    });
</script>
@endsection
