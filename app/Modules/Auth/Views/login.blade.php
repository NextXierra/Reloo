@extends('layouts.store')

@section('title', 'Masuk ke Akun · Reloo')

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

        <!-- Demo Accounts Hint -->
        <div class="mt-6 p-3.5 rounded-[4px] bg-[#F7F6F3] border border-[#EAEAEA] text-[11px] font-mono text-[#52525B]">
            <span class="font-bold text-[#111111] block mb-1 uppercase tracking-wider">AKUN UJI COBA (SANDBOX):</span>
            <div class="space-y-0.5">
                <div>Admin: <code class="text-[#111111]">admin@reloo.id</code> / <code class="text-[#71717A]">password</code></div>
                <div>User: <code class="text-[#111111]">user@reloo.id</code> / <code class="text-[#71717A]">password</code></div>
            </div>
        </div>

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
