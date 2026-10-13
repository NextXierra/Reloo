<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Reloo · Marketplace Preloved & Pilihan')</title>

    <!-- Brand Favicon & Touch Icons -->
    <link rel="icon" type="image/png" href="{{ asset('images/brand/reloo-icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/brand/reloo-icon.png') }}">

    <!-- Typography (Instrument Sans, Instrument Serif, Geist Mono) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600|instrument-serif:400|geist-mono:400,500" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tailwind CSS CDN Fallback & Extended Design Tokens -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Instrument Sans"', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                        serif: ['"Instrument Serif"', 'Georgia', 'serif'],
                        mono: ['"Geist Mono"', 'Menlo', 'monospace'],
                    },
                    colors: {
                        paper: {
                            DEFAULT: '#FBFBFA',
                            card: '#FFFFFF',
                            subtle: '#F7F6F3',
                            dark: '#121214',
                        },
                        ink: {
                            DEFAULT: '#111111',
                            secondary: '#52525B',
                            muted: '#71717A',
                            inverted: '#FFFFFF',
                        },
                        rule: {
                            DEFAULT: '#EAEAEA',
                            dark: '#27272A',
                        },
                        brand: {
                            violet: '#6828FF',
                            pink: '#FA26A0',
                            dark: '#13091B',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --color-paper: #FBFBFA;
            --color-ink: #111111;
            --color-rule: #EAEAEA;
        }
        html, body {
            overflow-x: clip;
            background-color: var(--color-paper);
            color: var(--color-ink);
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#FBFBFA] text-[#111111] min-h-screen flex flex-col font-sans antialiased selection:bg-[#111111] selection:text-white">

    <!-- Main Navigation Bar -->
    <header class="bg-white border-b border-[#EAEAEA] sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">

                <!-- Section 1 (Left): Clean Reloo Wordmark & Core Links -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="flex items-center select-none group focus:outline-none focus:ring-1 focus:ring-[#111111] rounded py-1">
                        <img src="{{ asset('images/brand/reloo-horizontal.png') }}" alt="Reloo" class="h-6 sm:h-7 w-auto object-contain">
                    </a>

                    <!-- Navigation Links -->
                    <nav class="hidden md:flex items-center gap-6 text-xs font-medium text-[#52525B]">
                        <a href="{{ route('home') }}" class="hover:text-[#111111] transition-colors {{ request()->routeIs('home') ? 'text-[#111111] font-semibold' : '' }}">
                            Beranda
                        </a>
                        <a href="{{ url('/catalog') }}" class="hover:text-[#111111] transition-colors {{ request()->is('catalog*') ? 'text-[#111111] font-semibold' : '' }}">
                            Katalog Belanja
                        </a>
                        <div class="relative group py-2">
                            <button type="button" class="flex items-center gap-1 hover:text-[#111111] transition-colors focus:outline-none">
                                <span>Kategori</span>
                                <svg class="w-3.5 h-3.5 text-[#71717A] group-hover:text-[#111111] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="absolute left-0 mt-1 w-48 rounded-md bg-white border border-[#EAEAEA] p-1.5 hidden group-hover:block transition-all z-50 shadow-sm">
                                <a href="{{ url('/catalog?category=sepatu') }}" class="block px-3 py-1.5 text-xs text-[#52525B] hover:text-[#111111] hover:bg-[#F7F6F3] rounded">Sepatu & Sneakers</a>
                                <a href="{{ url('/catalog?category=tas') }}" class="block px-3 py-1.5 text-xs text-[#52525B] hover:text-[#111111] hover:bg-[#F7F6F3] rounded">Tas & Ransel</a>
                                <a href="{{ url('/catalog?category=jam-tangan') }}" class="block px-3 py-1.5 text-xs text-[#52525B] hover:text-[#111111] hover:bg-[#F7F6F3] rounded">Jam Tangan</a>
                                <a href="{{ url('/catalog?category=elektronik') }}" class="block px-3 py-1.5 text-xs text-[#52525B] hover:text-[#111111] hover:bg-[#F7F6F3] rounded">Elektronik & Gadget</a>
                                <a href="{{ url('/catalog?category=pakaian') }}" class="block px-3 py-1.5 text-xs text-[#52525B] hover:text-[#111111] hover:bg-[#F7F6F3] rounded">Pakaian</a>
                            </div>
                        </div>
                    </nav>
                </div>

                <!-- Section 2 (Center): Search Input -->
                <div class="flex-1 max-w-md hidden sm:block">
                    <form action="{{ url('/catalog') }}" method="GET">
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-[#71717A] pointer-events-none">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk, merek, atau kategori..." class="w-full pl-9 pr-4 py-1.5 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] placeholder:text-[#A1A1AA] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111] focus:border-[#111111] transition-colors">
                        </div>
                    </form>
                </div>

                <!-- Section 3 (Right): Cart & Account Action -->
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
                    <a href="{{ url('/cart') }}" class="relative inline-flex items-center justify-center p-2 rounded-[5px] border border-[#EAEAEA] text-[#111111] hover:bg-[#F7F6F3] transition-colors" title="Keranjang Belanja">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        @if($cartCount > 0)
                            <span class="absolute -top-1.5 -right-1.5 bg-[#111111] text-white font-mono text-[9px] min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center border border-white">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- User Account / Login Actions -->
                    @auth
                        <div class="relative group py-2">
                            <button type="button" class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-[5px] border border-[#EAEAEA] text-xs font-medium text-[#111111] hover:bg-[#F7F6F3] transition-colors focus:outline-none">
                                <span class="w-5 h-5 rounded bg-[#F7F6F3] text-[#111111] flex items-center justify-center font-mono font-medium text-[10px] uppercase border border-[#EAEAEA]">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </span>
                                <span class="max-w-[100px] truncate text-xs">{{ auth()->user()->name }}</span>
                                <svg class="w-3 h-3 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="absolute right-0 mt-1 w-48 rounded-md bg-white border border-[#EAEAEA] p-1.5 hidden group-hover:block transition-all z-50 shadow-sm">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ url('/admin') }}" class="flex items-center gap-2 px-3 py-1.5 text-xs font-semibold text-[#111111] hover:bg-[#F7F6F3] rounded">
                                        Studio Admin
                                    </a>
                                    <div class="my-1 border-t border-[#EAEAEA]"></div>
                                @endif
                                <a href="{{ url('/profile') }}" class="block px-3 py-1.5 text-xs text-[#52525B] hover:text-[#111111] hover:bg-[#F7F6F3] rounded">
                                    Profil & Alamat
                                </a>
                                <a href="{{ url('/orders') }}" class="block px-3 py-1.5 text-xs text-[#52525B] hover:text-[#111111] hover:bg-[#F7F6F3] rounded">
                                    Riwayat Pesanan
                                </a>
                                <div class="my-1 border-t border-[#EAEAEA]"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-3 py-1.5 text-xs text-[#8C2220] hover:bg-[#FDEBEC] rounded">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="px-3 py-1.5 text-xs font-medium text-[#52525B] hover:text-[#111111] transition-colors whitespace-nowrap">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-3.5 py-1.5 rounded-[5px] text-xs font-medium bg-[#111111] text-white hover:bg-[#27272A] transition-colors whitespace-nowrap">
                                Daftar
                            </a>
                        </div>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            <div class="p-3 mb-4 text-xs text-[#8C2220] rounded-[5px] bg-[#FDEBEC] border border-[#F8C8CA] flex items-center justify-between">
                <span class="font-medium">{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-[#8C2220] hover:text-[#111111] text-base leading-none font-bold">&times;</button>
            </div>
        </div>
    @endif

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Clean E-Commerce Footer -->
    <footer class="bg-white border-t border-[#EAEAEA] mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- Col 1: Brand & Contact -->
                <div class="space-y-3">
                    <div class="flex items-center select-none">
                        <img src="{{ asset('images/brand/reloo-horizontal.png') }}" alt="Reloo" class="h-6 w-auto object-contain">
                    </div>
                    <p class="text-xs text-[#52525B] leading-relaxed">
                        Marketplace e-commerce produk pilihan dan preloved berkualitas.
                    </p>
                    <div class="text-xs text-[#71717A]">
                        CS WhatsApp: <span class="text-[#111111] font-medium">+62 812-3456-7890</span>
                    </div>
                </div>

                <!-- Col 2: Kategori -->
                <div>
                    <h5 class="text-xs font-mono uppercase tracking-wider text-[#111111] mb-3">Kategori Produk</h5>
                    <ul class="space-y-2 text-xs text-[#52525B]">
                        <li><a href="{{ url('/catalog?category=sepatu') }}" class="hover:text-[#111111] transition-colors">Sepatu & Sneakers</a></li>
                        <li><a href="{{ url('/catalog?category=tas') }}" class="hover:text-[#111111] transition-colors">Tas & Ransel</a></li>
                        <li><a href="{{ url('/catalog?category=jam-tangan') }}" class="hover:text-[#111111] transition-colors">Jam Tangan</a></li>
                        <li><a href="{{ url('/catalog?category=elektronik') }}" class="hover:text-[#111111] transition-colors">Elektronik & Gadget</a></li>
                        <li><a href="{{ url('/catalog?category=pakaian') }}" class="hover:text-[#111111] transition-colors">Pakaian</a></li>
                    </ul>
                </div>

                <!-- Col 3: Layanan Pelanggan -->
                <div>
                    <h5 class="text-xs font-mono uppercase tracking-wider text-[#111111] mb-3">Layanan & Bantuan</h5>
                    <ul class="space-y-2 text-xs text-[#52525B]">
                        <li><a href="{{ url('/catalog') }}" class="hover:text-[#111111] transition-colors">Katalog Belanja</a></li>
                        <li><a href="{{ url('/cart') }}" class="hover:text-[#111111] transition-colors">Keranjang Belanja</a></li>
                        <li><a href="{{ url('/orders') }}" class="hover:text-[#111111] transition-colors">Pelacakan Pesanan</a></li>
                        <li><a href="{{ url('/profile') }}" class="hover:text-[#111111] transition-colors">Akun & Alamat</a></li>
                    </ul>
                </div>

                <!-- Col 4: Informasi Toko -->
                <div>
                    <h5 class="text-xs font-mono uppercase tracking-wider text-[#111111] mb-3">Tentang Reloo</h5>
                    <ul class="space-y-2 text-xs text-[#52525B]">
                        <li><a href="{{ url('/catalog') }}" class="hover:text-[#111111] transition-colors">Katalog</a></li>
                        <li><a href="{{ url('/cart') }}" class="hover:text-[#111111] transition-colors">Keranjang</a></li>
                        <li><a href="{{ url('/catalog') }}" class="hover:text-[#111111] transition-colors">Bantuan</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Colophon Strip -->
            <div class="border-t border-[#EAEAEA] pt-6 flex flex-col md:flex-row items-center justify-between text-xs text-[#71717A] gap-4">
                <p class="font-mono text-[11px]">&copy; {{ date('Y') }} RELOO. ALL RIGHTS RESERVED.</p>
                <div class="flex items-center gap-6 text-xs">
                    <a href="{{ url('/catalog') }}" class="hover:text-[#111111] transition-colors">Katalog</a>
                    <a href="{{ url('/cart') }}" class="hover:text-[#111111] transition-colors">Keranjang</a>
                    <span class="font-mono text-[10px] text-[#A1A1AA]">ID / IDR</span>
                </div>
            </div>

        </div>
    </footer>

    @stack('scripts')
</body>
</html>
