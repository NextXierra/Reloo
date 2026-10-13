@extends('layouts.store')

@section('title', 'Profil & Pengaturan Akun · Reloo')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <div class="mb-8 pb-4 border-b border-[#EAEAEA]">
        <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-1">PENGATURAN PENGGUNA</span>
        <h1 class="font-serif text-3xl sm:text-4xl text-[#111111] tracking-tight">Profil & Buku Alamat</h1>
        <p class="text-xs text-[#52525B] mt-1">Kelola informasi identitas pribadi dan daftar alamat penerima kurir pengiriman.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        <!-- Left: Profile Details Update (1 Col) -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA]">
                <div class="flex items-center gap-3.5 mb-6 pb-4 border-b border-[#EAEAEA]">
                    <div class="w-12 h-12 rounded-[5px] bg-[#111111] text-white flex items-center justify-center font-mono font-bold text-lg uppercase">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="font-medium text-sm text-[#111111] leading-tight">{{ $user->name }}</h2>
                        <span class="font-mono text-xs text-[#71717A] block mt-0.5">{{ $user->email }}</span>
                        <span class="inline-block mt-1 px-1.5 py-0.2 rounded text-[9px] font-mono uppercase bg-[#F7F6F3] border border-[#EAEAEA] text-[#71717A]">
                            ROLE: {{ $user->role }}
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                    </div>

                    <div>
                        <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] font-mono focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                    </div>

                    <div class="pt-4 border-t border-[#EAEAEA]">
                        <span class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-2">Ganti Kata Sandi (Opsional)</span>
                        <div class="space-y-2">
                            <input type="password" name="current_password" placeholder="Kata Sandi Saat Ini" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                            <input type="password" name="new_password" placeholder="Kata Sandi Baru" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                            <input type="password" name="new_password_confirmation" placeholder="Ulangi Kata Sandi Baru" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-[5px] font-medium bg-[#111111] hover:bg-[#27272A] text-white text-xs transition-colors whitespace-nowrap">
                        Simpan Perubahan Profil
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Shipping Addresses (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA]">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#EAEAEA]">
                    <div>
                        <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-0.5">BUKU ALAMAT</span>
                        <h2 class="font-serif text-xl text-[#111111]">Daftar Alamat Pengiriman</h2>
                    </div>
                </div>

                <!-- Existing Addresses List -->
                <div class="space-y-3 mb-8">
                    @forelse($user->addresses as $address)
                        <div class="p-4 rounded-[5px] border {{ $address->is_default ? 'border-[#111111] bg-[#F7F6F3]' : 'border-[#EAEAEA] bg-white' }} relative flex flex-col justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-xs text-[#111111]">{{ $address->recipient_name }}</span>
                                    <span class="font-mono text-xs text-[#71717A]">({{ $address->phone_number }})</span>
                                    @if($address->is_default)
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-mono uppercase bg-[#EDF3EC] text-[#26542A] border border-[#D1E2CF]">
                                            Utama
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-[#52525B] leading-relaxed">
                                    {{ $address->address_line }}, {{ $address->district_name ? $address->district_name . ', ' : '' }}{{ $address->city_name }}, {{ $address->province_name }} {{ $address->postal_code }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3 pt-2 border-t border-[#EAEAEA] text-xs">
                                <form method="POST" action="{{ route('profile.address.delete', $address->id) }}" onsubmit="return confirm('Hapus alamat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[#8C2220] font-mono text-[11px] hover:underline">Hapus Alamat</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-[#71717A] bg-[#F7F6F3] rounded-[5px] border border-dashed border-[#EAEAEA]">
                            Belum ada alamat pengiriman yang tersimpan.
                        </div>
                    @endforelse
                </div>

                <!-- Form Add New Address -->
                <div class="border-t border-[#EAEAEA] pt-6">
                    <h3 class="font-mono text-xs uppercase tracking-wider text-[#111111] mb-4">+ Tambah Alamat Pengiriman Baru</h3>

                    <form method="POST" action="{{ route('profile.address.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Nama Penerima</label>
                            <input type="text" name="recipient_name" required placeholder="Nama lengkap penerima" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>

                        <div>
                            <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Nomor Telepon Penerima</label>
                            <input type="text" name="phone_number" required placeholder="08xxxxxxxxxx" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] font-mono focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Alamat Lengkap (Jalan, No. Rumah, RT/RW)</label>
                            <textarea name="address_line" rows="2" required placeholder="Jl. Mawar No. 12 RT 01/RW 02" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Provinsi</label>
                            <input type="text" name="province_name" required placeholder="DKI Jakarta" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>

                        <div>
                            <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Kota / Kabupaten</label>
                            <input type="text" name="city_name" required placeholder="Jakarta Selatan" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>

                        <div>
                            <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Kecamatan (Opsional)</label>
                            <input type="text" name="district_name" placeholder="Kebayoran Baru" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>

                        <div>
                            <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Kode Pos</label>
                            <input type="text" name="postal_code" required placeholder="12190" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] font-mono focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>

                        <div class="md:col-span-2 flex items-center gap-2 pt-1">
                            <input type="checkbox" id="is_default" name="is_default" value="1" class="rounded border-[#EAEAEA] text-[#111111] focus:ring-[#111111]">
                            <label for="is_default" class="text-xs text-[#52525B] cursor-pointer">
                                Jadikan sebagai alamat pengiriman utama
                            </label>
                        </div>

                        <div class="md:col-span-2 pt-2">
                            <button type="submit" class="px-5 py-2.5 rounded-[5px] font-medium bg-[#111111] hover:bg-[#27272A] text-white text-xs transition-colors whitespace-nowrap">
                                Simpan Alamat Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
