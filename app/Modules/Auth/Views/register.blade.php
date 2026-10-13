@extends('layouts.store')

@section('title', 'Daftar Akun Baru - Reloo Marketplace')

@section('content')
<div class="py-16 px-4 max-w-md mx-auto">
    <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-black text-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-600/30">
                R
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Daftar Akun Baru</h1>
            <p class="text-xs text-slate-500 mt-1">Mulai belanja dan temukan barang preloved impian Anda</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="Contoh: Budi Santoso" class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('name') border-rose-400 bg-rose-50/20 @enderror">
                @error('name')
                    <p class="text-rose-500 text-[11px] font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('email') border-rose-400 bg-rose-50/20 @enderror">
                @error('email')
                    <p class="text-rose-500 text-[11px] font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="08123456789" class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('phone') border-rose-400 bg-rose-50/20 @enderror">
                @error('phone')
                    <p class="text-rose-500 text-[11px] font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi (Minimal 6 Karakter)</label>
                <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('password') border-rose-400 bg-rose-50/20 @enderror">
                @error('password')
                    <p class="text-rose-500 text-[11px] font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Kata Sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••" class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full py-3.5 rounded-xl font-bold bg-indigo-600 hover:bg-indigo-500 text-white text-xs transition shadow-lg shadow-indigo-600/30">
                Daftar Akun Sekarang
            </button>
        </form>

        <!-- Footer Link -->
        <div class="mt-6 text-center text-xs text-slate-500">
            Sudah memiliki akun? 
            <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:underline">
                Masuk ke Akun
            </a>
        </div>
    </div>
</div>
@endsection
