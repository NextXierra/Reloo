<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Reloo Marketplace</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex">

    <!-- Admin Sidebar -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col flex-shrink-0 min-h-screen border-r border-slate-800">
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 gap-3 border-b border-slate-800">
            <span class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-black text-base shadow">R</span>
            <div>
                <span class="text-base font-black text-white tracking-tight block leading-none">Reloo</span>
                <span class="text-[9px] font-bold text-indigo-400 uppercase tracking-wider block">Admin Back-Office</span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 p-4 space-y-1.5 text-xs font-semibold">
            <a href="{{ url('/admin') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 transition {{ request()->is('admin') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            <div class="pt-3 pb-1 px-3 text-[10px] font-black uppercase tracking-wider text-slate-500">
                Visual CMS
            </div>

            <a href="{{ route('admin.cms.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 transition {{ request()->is('admin/cms*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                GrapesJS Page Builder
            </a>

            <div class="pt-3 pb-1 px-3 text-[10px] font-black uppercase tracking-wider text-slate-500">
                Katalog & Toko
            </div>

            <a href="{{ url('/admin/categories') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 transition {{ request()->is('admin/categories*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Kategori Produk
            </a>

            <a href="{{ url('/admin/products') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 transition {{ request()->is('admin/products*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Kelola Produk
            </a>

            <div class="pt-3 pb-1 px-3 text-[10px] font-black uppercase tracking-wider text-slate-500">
                Fulfillment
            </div>

            <a href="{{ url('/admin/orders') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-800 transition {{ request()->is('admin/orders*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Pesanan & Resi
            </a>
        </nav>

        <!-- Bottom Store Link & User Info -->
        <div class="p-4 border-t border-slate-800">
            <a href="{{ url('/') }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-2.5 mb-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold transition">
                <span>Lihat Toko Publik</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
            <div class="flex items-center justify-between text-xs text-slate-400">
                <span class="truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-rose-400 hover:underline">Keluar</button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Top Bar -->
        <header class="h-16 bg-white border-b border-slate-200 px-8 flex items-center justify-between flex-shrink-0">
            <h1 class="text-lg font-black text-slate-800">@yield('title')</h1>
            <div class="flex items-center gap-4">
                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold">
                    Role: Administrator
                </span>
            </div>
        </header>

        <!-- Page Body -->
        <main class="p-8 flex-1">
            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="p-4 mb-6 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Sukses:</span>
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 mb-6 text-sm text-rose-800 rounded-2xl bg-rose-50 border border-rose-200 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600">Perhatian:</span>
                        <span class="font-semibold">{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 font-bold">&times;</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
