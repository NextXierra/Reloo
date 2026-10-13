@extends('layouts.store')

@section('title', 'Masuk ke Akun · Reloo')

@section('content')
<div class="py-16 px-4 max-w-md mx-auto">
    <div class="bg-white rounded-[6px] p-8 border border-[#EAEAEA]">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="mb-4 flex justify-center select-none">
                <img src="{{ asset('images/brand/reloo-horizontal.png') }}" alt="Reloo" class="h-6 sm:h-7 w-auto object-contain mx-auto">
            </div>
            <h1 class="font-serif text-2xl text-[#111111] tracking-tight">Selamat Datang Kembali</h1>
            <p class="text-xs text-[#52525B] mt-1">Masuk ke akun Anda untuk bertransaksi aman di Reloo.</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@domain.com" class="h-11 w-full px-3.5 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111] transition-colors @error('email') border-[#F8C8CA] bg-[#FDEBEC] @enderror">
                @error('email')
                    <p class="text-[#8C2220] font-mono text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Kata Sandi</label>
                <input type="password" id="password" name="password" required placeholder="••••••••" class="h-11 w-full px-3.5 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111] transition-colors @error('password') border-[#F8C8CA] bg-[#FDEBEC] @enderror">
                @error('password')
                    <p class="text-[#8C2220] font-mono text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-[#52525B]">
                    <input type="checkbox" name="remember" class="rounded border-[#EAEAEA] text-[#111111] focus:ring-[#111111]">
                    <span>Ingat sesi saya</span>
                </label>
            </div>

            <!-- Submit -->
            <button type="submit" class="h-11 w-full rounded-[5px] font-medium bg-[#111111] hover:bg-[#27272A] text-white text-xs transition-colors whitespace-nowrap">
                Masuk ke Akun &rarr;
            </button>
        </form>


        <!-- Footer Link -->
        <div class="mt-6 text-center text-xs text-[#71717A]">
            Belum memiliki akun Reloo? 
            <a href="{{ route('register') }}" class="text-[#111111] font-medium underline">
                Daftar Sekarang
            </a>
        </div>
    </div>
</div>
@endsection
