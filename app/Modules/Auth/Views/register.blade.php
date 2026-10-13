@extends('layouts.store')

@section('title', 'Daftar Akun Baru · Reloo')

@section('content')
<div class="py-16 px-4 max-w-md mx-auto">
    <div class="bg-white rounded-[6px] p-8 border border-[#EAEAEA]">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-1.5 mb-3 select-none">
                <span class="text-2xl font-black tracking-tight text-[#111111] font-sans flex items-center">
                    REL<span class="inline-block w-2.5 h-2.5 rounded-full bg-[#6828FF] ml-0.5 mr-0.5"></span><span class="inline-block w-2.5 h-2.5 rounded-full bg-[#FA26A0]"></span>
                </span>
            </div>
            <h1 class="font-serif text-2xl text-[#111111] tracking-tight">Daftar Akun Baru</h1>
            <p class="text-xs text-[#52525B] mt-1">Mulai belanja dan temukan barang preloved terkurasi.</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="Budi Santoso" class="h-11 w-full px-3.5 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111] transition-colors @error('name') border-[#F8C8CA] bg-[#FDEBEC] @enderror">
                @error('name')
                    <p class="text-[#8C2220] font-mono text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@domain.com" class="h-11 w-full px-3.5 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111] transition-colors @error('email') border-[#F8C8CA] bg-[#FDEBEC] @enderror">
                @error('email')
                    <p class="text-[#8C2220] font-mono text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Nomor WhatsApp / HP</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="08123456789" class="h-11 w-full px-3.5 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] font-mono focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111] transition-colors @error('phone') border-[#F8C8CA] bg-[#FDEBEC] @enderror">
                @error('phone')
                    <p class="text-[#8C2220] font-mono text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Kata Sandi (Min. 6 Karakter)</label>
                <input type="password" id="password" name="password" required placeholder="••••••••" class="h-11 w-full px-3.5 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111] transition-colors @error('password') border-[#F8C8CA] bg-[#FDEBEC] @enderror">
                @error('password')
                    <p class="text-[#8C2220] font-mono text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Konfirmasi Kata Sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••" class="h-11 w-full px-3.5 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111] transition-colors">
            </div>

            <!-- Submit -->
            <button type="submit" class="h-11 w-full rounded-[5px] font-medium bg-[#111111] hover:bg-[#27272A] text-white text-xs transition-colors whitespace-nowrap">
                Daftar Akun Sekarang &rarr;
            </button>
        </form>

        <!-- Footer Link -->
        <div class="mt-6 text-center text-xs text-[#71717A]">
            Sudah memiliki akun Reloo? 
            <a href="{{ route('login') }}" class="text-[#111111] font-medium underline">
                Masuk ke Akun
            </a>
        </div>
    </div>
</div>
@endsection
