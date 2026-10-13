<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Studio') · Reloo</title>

    <!-- Brand Favicon & Touch Icons -->
    <link rel="icon" type="image/png" href="{{ asset('images/brand/reloo-icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/brand/reloo-icon.png') }}">

    <!-- Typography -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600|instrument-serif:400|geist-mono:400,500" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tailwind CDN with Studio Tokens -->
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
                            studio: '#18181B',
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
<body class="bg-[#FBFBFA] text-[#111111] min-h-screen flex antialiased">

    <!-- Admin Studio Sidebar (Dark Utilitarian #121214 Shell) -->
    <aside class="w-64 bg-[#121214] text-[#A1A1AA] flex flex-col flex-shrink-0 min-h-screen border-r border-[#27272A]">
        <!-- Brand Header with Official Reloo Icon -->
        <div class="h-16 flex items-center px-6 gap-3 border-b border-[#27272A]">
            <img src="{{ asset('images/brand/reloo-icon.png') }}" alt="Reloo Icon" class="w-7 h-7 object-contain rounded">
            <div>
                <div class="flex items-center gap-1.5 leading-none">
                    <span class="text-sm font-extrabold tracking-tight text-white font-sans">REL<span class="inline-block w-2 h-2 rounded-full bg-[#6828FF] ml-0.5 mr-0.5"></span><span class="inline-block w-2 h-2 rounded-full bg-[#FA26A0]"></span></span>
                </div>
                <span class="text-[9px] font-mono tracking-widest text-[#71717A] uppercase block mt-0.5">STUDIO ADMIN</span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 p-3 space-y-1 text-xs">
            <!-- Overview -->
            <a href="{{ url('/admin') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-[5px] transition-colors {{ request()->is('admin') ? 'bg-[#27272A] text-white font-medium' : 'text-[#A1A1AA] hover:text-white hover:bg-[#1C1C1F]' }}">
                <svg class="w-4 h-4 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Dashboard</span>
            </a>

            <!-- Section 01: Konten -->
            <div class="pt-4 pb-1 px-3 text-[10px] font-mono uppercase tracking-wider text-[#71717A]">
                01 / VISUAL CMS
            </div>

            <a href="{{ route('admin.cms.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-[5px] transition-colors {{ request()->is('admin/cms*') ? 'bg-[#27272A] text-white font-medium' : 'text-[#A1A1AA] hover:text-white hover:bg-[#1C1C1F]' }}">
                <svg class="w-4 h-4 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                <span>Visual Builder (GrapesJS)</span>
            </a>

            <!-- Section 02: Katalog -->
            <div class="pt-4 pb-1 px-3 text-[10px] font-mono uppercase tracking-wider text-[#71717A]">
                02 / KATALOG
            </div>

            <a href="{{ url('/admin/categories') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-[5px] transition-colors {{ request()->is('admin/categories*') ? 'bg-[#27272A] text-white font-medium' : 'text-[#A1A1AA] hover:text-white hover:bg-[#1C1C1F]' }}">
                <svg class="w-4 h-4 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span>Kategori Produk</span>
            </a>

            <a href="{{ url('/admin/products') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-[5px] transition-colors {{ request()->is('admin/products*') ? 'bg-[#27272A] text-white font-medium' : 'text-[#A1A1AA] hover:text-white hover:bg-[#1C1C1F]' }}">
                <svg class="w-4 h-4 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>Kelola Produk</span>
            </a>

            <!-- Section 03: Fulfillment -->
            <div class="pt-4 pb-1 px-3 text-[10px] font-mono uppercase tracking-wider text-[#71717A]">
                03 / PESANAN
            </div>

            <a href="{{ url('/admin/orders') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-[5px] transition-colors {{ request()->is('admin/orders*') ? 'bg-[#27272A] text-white font-medium' : 'text-[#A1A1AA] hover:text-white hover:bg-[#1C1C1F]' }}">
                <svg class="w-4 h-4 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <span>Pesanan & Resi</span>
            </a>
        </nav>

        <!-- Bottom Store Link & User Profile -->
        <div class="p-3 border-t border-[#27272A]">
            <a href="{{ url('/') }}" target="_blank" class="flex items-center justify-between w-full px-3 py-2 mb-2 rounded-[5px] bg-[#1E1E22] hover:bg-[#27272A] text-white text-xs transition-colors">
                <span>Lihat Toko Publik</span>
                <svg class="w-3.5 h-3.5 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
            <div class="flex items-center justify-between text-xs px-1 text-[#71717A]">
                <span class="truncate max-w-[120px] font-mono text-[11px]">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-[#A1A1AA] hover:text-white text-xs transition-colors">Keluar</button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Top Header Bar -->
        <header class="h-16 bg-white border-b border-[#EAEAEA] px-8 flex items-center justify-between flex-shrink-0">
            <h1 class="font-serif text-xl text-[#111111] tracking-tight">@yield('title')</h1>
            <div class="flex items-center gap-3">
                <span class="font-mono text-[11px] px-2 py-0.5 rounded bg-[#F7F6F3] border border-[#EAEAEA] text-[#52525B]">
                    ROLE: ADMINISTRATOR
                </span>
            </div>
        </header>

        <!-- Page Body Container -->
        <main class="p-8 flex-1">
            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="p-3 mb-6 text-xs text-[#26542A] rounded-[5px] bg-[#EDF3EC] border border-[#D1E2CF] flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="font-mono uppercase font-bold tracking-wider">[SUKSES]</span>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-[#26542A] hover:text-[#111111] text-base leading-none font-bold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-3 mb-6 text-xs text-[#8C2220] rounded-[5px] bg-[#FDEBEC] border border-[#F8C8CA] flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="font-mono uppercase font-bold tracking-wider">[PERHATIAN]</span>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-[#8C2220] hover:text-[#111111] text-base leading-none font-bold">&times;</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
