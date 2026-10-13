<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Reloo Marketplace - E-Commerce Preloved & Pilihan')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN for instant styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        }
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
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    <!-- Top Announcement Bar -->
    <div class="bg-slate-900 text-slate-300 text-xs py-2 px-4 text-center font-medium border-b border-slate-800">
        <span class="inline-flex items-center gap-2">
            <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            Gratis Ongkir & Jaminan 100% Original untuk Seluruh Produk Terkurasi
        </span>
    </div>

    <!-- Main Navigation Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20 gap-4">
                
                <!-- Logo -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <span class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black text-xl shadow-md shadow-indigo-600/30">
                            R
                        </span>
                        <div>
                            <span class="text-xl font-black text-slate-900 tracking-tight block leading-none">Reloo</span>
                            <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider block">Marketplace</span>
                        </div>
                    </a>

                    <!-- Navigation Links -->
                    <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-slate-600">
                        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition {{ request()->routeIs('home') ? 'text-indigo-600' : '' }}">
                            Beranda
                        </a>
                        <a href="{{ url('/catalog') }}" class="hover:text-indigo-600 transition {{ request()->is('catalog*') ? 'text-indigo-600' : '' }}">
                            Katalog Belanja
                        </a>
                        <div class="relative group py-2">
                            <button type="button" class="flex items-center gap-1 hover:text-indigo-600 transition focus:outline-none">
                                Kategori
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="absolute left-0 mt-2 w-48 rounded-xl bg-white shadow-xl border border-slate-100 p-2 hidden group-hover:block transition z-50">
                                <a href="{{ url('/catalog?category=sepatu') }}" class="block px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 rounded-lg">Sepatu</a>
                                <a href="{{ url('/catalog?category=tas') }}" class="block px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 rounded-lg">Tas</a>
                                <a href="{{ url('/catalog?category=jam-tangan') }}" class="block px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 rounded-lg">Jam Tangan</a>
                                <a href="{{ url('/catalog?category=elektronik') }}" class="block px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 rounded-lg">Elektronik</a>
                                <a href="{{ url('/catalog?category=pakaian') }}" class="block px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 rounded-lg">Pakaian</a>
                            </div>
                        </div>
                    </nav>
                </div>

                <!-- Search Input -->
                <div class="flex-1 max-w-md hidden sm:block">
                    <form action="{{ url('/catalog') }}" method="GET">
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari sneakers, jam tangan, tas..." class="w-full pl-10 pr-4 py-2 text-sm bg-slate-100 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                            <span class="absolute left-3 top-2.5 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                        </div>
                    </form>
                </div>

                <!-- Right Action Icons (Cart, Auth) -->
                <div class="flex items-center gap-3">
                    <!-- Cart Button -->
                    @php
                        $cartCount = 0;
                        if (auth()->check()) {
                            $cartCount = \App\Modules\Cart\Models\CartItem::where('user_id', auth()->id())->sum('quantity');
                        } else {
                            $cartCount = \App\Modules\Cart\Models\CartItem::where('session_id', session()->getId())->sum('quantity');
                        }
                    @endphp
                    <a href="{{ url('/cart') }}" class="relative p-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 transition" title="Keranjang Belanja">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        @if($cartCount > 0)
                            <span class="absolute -top-1.5 -right-1.5 bg-rose-500 text-white font-black text-[10px] w-5 h-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- User Account / Login -->
                    @auth
                        <div class="relative group py-2">
                            <button type="button" class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                                <div class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs uppercase">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span class="max-w-[90px] truncate">{{ auth()->user()->name }}</span>
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 rounded-xl bg-white shadow-xl border border-slate-100 p-2 hidden group-hover:block transition z-50">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ url('/admin') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-bold text-indigo-600 hover:bg-indigo-50 rounded-lg">
                                        Panel Admin
                                    </a>
                                    <div class="my-1 border-t border-slate-100"></div>
                                @endif
                                <a href="{{ url('/profile') }}" class="block px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 rounded-lg">
                                    Profil & Alamat
                                </a>
                                <a href="{{ url('/orders') }}" class="block px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 rounded-lg">
                                    Riwayat Pesanan
                                </a>
                                <div class="my-1 border-t border-slate-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-lg">
                                        Keluar (Logout)
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="px-3 py-2 text-xs font-bold text-slate-700 hover:text-indigo-600 transition">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-indigo-600 text-white hover:bg-indigo-500 transition shadow-sm">
                                Daftar
                            </a>
                        </div>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Sukses:</span>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 text-lg font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-4 text-sm text-rose-800 rounded-2xl bg-rose-50 border border-rose-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-600">Perhatian:</span>
                    <span class="font-semibold">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 text-lg font-bold">&times;</button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Col 1 -->
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-black text-sm">R</span>
                        <span class="text-lg font-black text-slate-900">Reloo</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                        Marketplace e-commerce terkurasi untuk barang baru & secondhand berkualitas tinggi bergaransi resmi.
                    </p>
                    <div class="text-xs text-slate-600 font-semibold">
                        CS WhatsApp: <span class="text-indigo-600">+62 812-3456-7890</span>
                    </div>
                </div>

                <!-- Col 2 -->
                <div>
                    <h5 class="text-xs font-black uppercase tracking-wider text-slate-900 mb-4">Kategori Populer</h5>
                    <ul class="space-y-2 text-xs text-slate-600">
                        <li><a href="{{ url('/catalog?category=sepatu') }}" class="hover:text-indigo-600">Sepatu & Sneakers</a></li>
                        <li><a href="{{ url('/catalog?category=tas') }}" class="hover:text-indigo-600">Tas & Ransel Kulit</a></li>
                        <li><a href="{{ url('/catalog?category=jam-tangan') }}" class="hover:text-indigo-600">Jam Tangan Mewah</a></li>
                        <li><a href="{{ url('/catalog?category=elektronik') }}" class="hover:text-indigo-600">Elektronik & Audio</a></li>
                        <li><a href="{{ url('/catalog?category=pakaian') }}" class="hover:text-indigo-600">Pakaian & Vintage</a></li>
                    </ul>
                </div>

                <!-- Col 3 -->
                <div>
                    <h5 class="text-xs font-black uppercase tracking-wider text-slate-900 mb-4">Metode Pembayaran</h5>
                    <div class="grid grid-cols-2 gap-2 text-xs text-slate-600">
                        <span class="p-2 border border-slate-200 rounded-lg text-center font-bold">Midtrans QRIS</span>
                        <span class="p-2 border border-slate-200 rounded-lg text-center font-bold">BCA VA</span>
                        <span class="p-2 border border-slate-200 rounded-lg text-center font-bold">Transfer BCA</span>
                        <span class="p-2 border border-slate-200 rounded-lg text-center font-bold">Transfer Mandiri</span>
                    </div>
                </div>

                <!-- Col 4 -->
                <div>
                    <h5 class="text-xs font-black uppercase tracking-wider text-slate-900 mb-4">Ekspedisi Logistik</h5>
                    <div class="grid grid-cols-2 gap-2 text-xs text-slate-600">
                        <span class="p-2 border border-slate-200 rounded-lg text-center font-bold">JNE Reguler</span>
                        <span class="p-2 border border-slate-200 rounded-lg text-center font-bold">SiCepat Ekspres</span>
                        <span class="p-2 border border-slate-200 rounded-lg text-center font-bold">J&T Express</span>
                        <span class="p-2 border border-slate-200 rounded-lg text-center font-bold">Flat Rate</span>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-6 flex flex-col md:flex-row items-center justify-between text-xs text-slate-400 gap-4">
                <p>&copy; {{ date('Y') }} Reloo Marketplace. Seluruh hak cipta dilindungi.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ url('/p/home') }}" class="hover:text-slate-600">Tentang Kami</a>
                    <a href="{{ url('/catalog') }}" class="hover:text-slate-600">Katalog Belanja</a>
                    <a href="{{ url('/cart') }}" class="hover:text-slate-600">Keranjang</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
