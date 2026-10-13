@extends('layouts.store')

@section('title', 'Masuk ke Akun - Reloo Marketplace')

@section('content')
<div class="py-16 px-4 max-w-md mx-auto">
    <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-black text-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-600/30">
                R
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Selamat Datang Kembali</h1>
            <p class="text-xs text-slate-500 mt-1">Masuk ke akun Anda untuk bertransaksi di Reloo</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com" class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('email') border-rose-400 bg-rose-50/20 @enderror">
                @error('email')
                    <p class="text-rose-500 text-[11px] font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi</label>
                <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('password') border-rose-400 bg-rose-50/20 @enderror">
                @error('password')
                    <p class="text-rose-500 text-[11px] font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-slate-600 font-medium">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    Ingat saya
                </label>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full py-3.5 rounded-xl font-bold bg-indigo-600 hover:bg-indigo-500 text-white text-xs transition shadow-lg shadow-indigo-600/30">
                Masuk ke Akun
            </button>
        </form>

        <!-- Demo Accounts Hint -->
        <div class="mt-6 p-3 rounded-2xl bg-indigo-50 border border-indigo-100 text-[11px] text-indigo-900">
            <span class="font-bold block mb-1">Akun Percobaan (Demo):</span>
            <div class="space-y-0.5 text-slate-600">
                <div>Admin: <code class="font-mono text-indigo-700">admin@reloo.id</code> / <code class="font-mono text-indigo-700">password</code></div>
                <div>Customer: <code class="font-mono text-indigo-700">user@reloo.id</code> / <code class="font-mono text-indigo-700">password</code></div>
            </div>
        </div>

        <!-- Footer Link -->
        <div class="mt-6 text-center text-xs text-slate-500">
            Belum punya akun Reloo? 
            <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:underline">
                Daftar Sekarang
            </a>
        </div>
    </div>
</div>
@endsection
