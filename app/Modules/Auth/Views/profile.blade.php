@extends('layouts.store')

@section('title', 'Profil & Buku Alamat - Reloo Marketplace')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pengaturan Akun & Alamat</h1>
        <p class="text-xs text-slate-500">Kelola informasi pribadi dan daftar alamat pengiriman pesanan Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Profile Details Update -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-black text-xl uppercase shadow-md shadow-indigo-600/30">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-900 text-base leading-tight">{{ $user->name }}</h2>
                        <span class="text-xs text-slate-400 block">{{ $user->email }}</span>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                            {{ $user->role }}
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <span class="block text-xs font-bold text-slate-900 mb-2">Ubah Kata Sandi (Opsional)</span>
                        <div class="space-y-2">
                            <input type="password" name="current_password" placeholder="Kata Sandi Saat Ini" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                            <input type="password" name="new_password" placeholder="Kata Sandi Baru" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                            <input type="password" name="new_password_confirmation" placeholder="Ulangi Kata Sandi Baru" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl font-bold bg-indigo-600 hover:bg-indigo-500 text-white text-xs transition shadow-md shadow-indigo-600/20">
                        Simpan Perubahan Profil
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Shipping Addresses -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="font-black text-slate-900 text-base">Buku Alamat Pengiriman</h2>
                        <p class="text-xs text-slate-500">Alamat tujuan untuk kurir pengiriman barang Anda</p>
                    </div>
                </div>

                <!-- Existing Addresses List -->
                <div class="space-y-4 mb-8">
                    @forelse($user->addresses as $address)
                        <div class="p-4 rounded-2xl border {{ $address->is_default ? 'border-indigo-600 bg-indigo-50/20 ring-1 ring-indigo-600/20' : 'border-slate-200 bg-white' }} relative flex flex-col justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-bold text-slate-900 text-sm">{{ $address->recipient_name }}</span>
                                    <span class="text-xs text-slate-500">({{ $address->phone_number }})</span>
                                    @if($address->is_default)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-indigo-100 text-indigo-700">
                                            Alamat Utama
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    {{ $address->address_line }}, {{ $address->district_name ? $address->district_name . ', ' : '' }}{{ $address->city_name }}, {{ $address->province_name }} {{ $address->postal_code }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3 pt-2 border-t border-slate-100/80 text-xs">
                                <form method="POST" action="{{ route('profile.address.delete', $address->id) }}" onsubmit="return confirm('Hapus alamat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 font-bold hover:underline">Hapus Alamat</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            Belum ada alamat tersimpan. Silakan isi formulir di bawah ini.
                        </div>
                    @endforelse
                </div>

                <!-- Form Add New Address -->
                <div class="border-t border-slate-100 pt-6">
                    <h3 class="font-bold text-slate-900 text-sm mb-4">+ Tambah Alamat Pengiriman Baru</h3>

                    <form method="POST" action="{{ route('profile.address.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Penerima</label>
                            <input type="text" name="recipient_name" required placeholder="Nama lengkap penerima" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Telepon Penerima</label>
                            <input type="text" name="phone_number" required placeholder="08xxxxxxxxxx" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap (Jalan, No. Rumah, RT/RW)</label>
                            <textarea name="address_line" rows="2" required placeholder="Jl. Mawar No. 12 RT 01/RW 02" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Provinsi</label>
                            <input type="text" name="province_name" required placeholder="DKI Jakarta" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kota / Kabupaten</label>
                            <input type="text" name="city_name" required placeholder="Jakarta Selatan" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kecamatan (Opsional)</label>
                            <input type="text" name="district_name" placeholder="Kebayoran Baru" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kode Pos</label>
                            <input type="text" name="postal_code" required placeholder="12190" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        </div>

                        <div class="md:col-span-2 flex items-center gap-2 pt-1">
                            <input type="checkbox" id="is_default" name="is_default" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="is_default" class="text-xs font-semibold text-slate-700 cursor-pointer">
                                Jadikan sebagai alamat pengiriman utama
                            </label>
                        </div>

                        <div class="md:col-span-2 pt-2">
                            <button type="submit" class="px-6 py-2.5 rounded-xl font-bold bg-slate-900 hover:bg-indigo-600 text-white text-xs transition">
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
