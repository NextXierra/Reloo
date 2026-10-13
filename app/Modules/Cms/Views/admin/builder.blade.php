<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Visual Page Builder: {{ $page->title }} · Reloo</title>

    <!-- Brand Favicon & Touch Icons -->
    <link rel="icon" type="image/png" href="{{ asset('images/brand/reloo-icon.png') }}">

    <!-- Typography -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600|instrument-serif:400|geist-mono:400,500" rel="stylesheet" />

    <!-- GrapesJS Core Stylesheet -->
    <link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css">

    <!-- Tailwind CSS for Builder Shell -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Instrument Sans"', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                        serif: ['"Instrument Serif"', 'Georgia', 'serif'],
                        mono: ['"Geist Mono"', 'Menlo', 'monospace'],
                    }
                }
            }
        }
    </script>

    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #121214;
            color: #E4E4E7;
        }

        .gjs-cv-canvas {
            top: 0;
            width: 100%;
            height: 100%;
            background-color: #FBFBFA;
        }

        .gjs-one-bg {
            background-color: #121214;
        }

        .gjs-two-color {
            color: #A1A1AA;
        }

        .gjs-four-color, .gjs-four-color-h:hover {
            color: #FFFFFF;
        }

        /* Blocks Panel Styling */
        .gjs-block {
            user-select: none;
            min-height: 70px;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #27272A;
            background: #18181B;
            color: #A1A1AA;
            transition: background-color 150ms ease, border-color 150ms ease, color 150ms ease;
        }

        .gjs-block:hover {
            border-color: #52525B;
            background: #27272A;
            color: #FFFFFF;
        }

        .gjs-block-category .gjs-title {
            font-family: 'Geist Mono', monospace;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-size: 10px;
            color: #71717A;
            padding: 10px 12px;
            border-bottom: 1px solid #27272A;
            background: #121214;
        }

        /* Style Manager Dark Studio Theme */
        .gjs-sm-sector .gjs-sm-sector-title {
            background-color: #18181B;
            border-bottom: 1px solid #27272A;
            color: #E4E4E7;
            font-size: 11px;
            font-weight: 500;
            padding: 9px 12px;
            font-family: 'Geist Mono', monospace;
        }

        .gjs-sm-sector .gjs-sm-properties {
            background-color: #121214;
            padding: 10px;
        }

        .gjs-sm-property {
            color: #A1A1AA;
            font-size: 11px;
            margin-bottom: 8px;
        }

        .gjs-sm-property input,
        .gjs-sm-property select {
            background-color: #18181B;
            border: 1px solid #27272A;
            color: #FFFFFF;
            border-radius: 4px;
            padding: 4px 8px;
            font-size: 11px;
            font-family: 'Geist Mono', monospace;
        }

        .gjs-sm-property input:focus,
        .gjs-sm-property select:focus {
            outline: 1px solid #52525B;
            border-color: #52525B;
        }

        /* Trait Manager */
        .gjs-trt-traits {
            background-color: #121214;
            padding: 10px;
        }

        .gjs-trt-trait {
            padding: 6px 0;
            border-bottom: 1px solid #27272A;
            color: #A1A1AA;
            font-size: 11px;
        }

        .gjs-trt-trait input,
        .gjs-trt-trait select {
            background-color: #18181B;
            border: 1px solid #27272A;
            color: #FFFFFF;
            border-radius: 4px;
            padding: 4px 8px;
            font-size: 11px;
            width: 100%;
            font-family: 'Geist Mono', monospace;
        }

        /* Layer Manager */
        .gjs-layer {
            background-color: #121214;
            color: #A1A1AA;
            font-size: 11px;
            border-bottom: 1px solid #27272A;
        }

        .gjs-layer:hover, .gjs-layer.gjs-hovered {
            background-color: #18181B;
            color: #FFFFFF;
        }

        .gjs-layer.gjs-selected {
            background-color: #27272A;
            color: #FFFFFF;
        }

        /* Toast notification */
        #toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 99999;
            transform: translateY(100px);
            opacity: 0;
            transition: transform 200ms ease, opacity 200ms ease;
        }
        #toast.show {
            transform: translateY(0);
            opacity: 1;
        }
    </style>
</head>
<body class="flex flex-col h-full bg-[#121214] text-[#E4E4E7] select-none">

    <!-- Top Action Header (Minimalist Studio Masthead) -->
    <header class="h-14 bg-[#121214] border-b border-[#27272A] px-5 flex items-center justify-between z-50 flex-shrink-0">
        <!-- Left: Back & Title -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.cms.index') }}" class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-[5px] border border-[#27272A] bg-[#18181B] hover:bg-[#27272A] text-xs text-[#A1A1AA] hover:text-white transition-colors" title="Kembali ke Daftar">
                <span>&larr;</span> Kembali
            </a>
            <div class="h-4 w-px bg-[#27272A]"></div>
            <div>
                <h1 class="text-xs font-semibold text-white flex items-center gap-2">
                    {{ $page->title }}
                    @if($page->is_homepage)
                        <span class="px-1.5 py-0.2 rounded text-[9px] font-mono uppercase bg-[#EDF3EC] text-[#26542A] border border-[#D1E2CF]">Beranda Aktif</span>
                    @endif
                </h1>
                <p class="text-[10px] text-[#71717A] font-mono">
                    {{ $page->is_homepage ? '/' : '/p/' . $page->slug }}
                </p>
            </div>
        </div>

        <!-- Center: Device Switcher -->
        <div class="flex items-center bg-[#18181B] p-0.5 rounded-[5px] border border-[#27272A]">
            <button type="button" id="btn-desktop" class="px-3 py-1 rounded-[4px] text-xs font-medium transition-colors bg-[#27272A] text-white flex items-center gap-1.5" title="Tampilan Desktop (Full Width)">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>Desktop</span>
            </button>
            <button type="button" id="btn-tablet" class="px-3 py-1 rounded-[4px] text-xs font-medium text-[#71717A] hover:text-white transition-colors flex items-center gap-1.5" title="Tampilan Tablet (768px)">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <span>Tablet</span>
            </button>
            <button type="button" id="btn-mobile" class="px-3 py-1 rounded-[4px] text-xs font-medium text-[#71717A] hover:text-white transition-colors flex items-center gap-1.5" title="Tampilan Mobile (375px)">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <span>Mobile</span>
            </button>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-2">
            <button type="button" id="btn-undo" class="p-1.5 rounded-[5px] border border-[#27272A] bg-[#18181B] hover:bg-[#27272A] text-[#A1A1AA] hover:text-white transition-colors" title="Urungkan (Undo)">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            </button>
            <button type="button" id="btn-redo" class="p-1.5 rounded-[5px] border border-[#27272A] bg-[#18181B] hover:bg-[#27272A] text-[#A1A1AA] hover:text-white transition-colors" title="Ulangi (Redo)">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2m18-10l-6 6m6-6l-6-6"/></svg>
            </button>
            <button type="button" id="btn-clear" class="p-1.5 rounded-[5px] border border-[#27272A] bg-[#18181B] hover:bg-[#8C2220]/20 text-[#A1A1AA] hover:text-[#F8C8CA] transition-colors" title="Kosongkan Kanvas">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>

            <div class="h-4 w-px bg-[#27272A]"></div>

            <a href="{{ $page->is_homepage ? url('/') : url('/p/' . $page->slug) }}" target="_blank" class="px-2.5 py-1.5 rounded-[5px] border border-[#27272A] bg-[#18181B] hover:bg-[#27272A] text-[#A1A1AA] hover:text-white text-xs transition-colors flex items-center gap-1.5" title="Buka Halaman Publik">
                <span>Lihat Publik</span>
                <svg class="w-3 h-3 text-[#71717A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>

            <button type="button" id="btn-preview" class="px-2.5 py-1.5 rounded-[5px] border border-[#27272A] bg-[#18181B] hover:bg-[#27272A] text-[#A1A1AA] hover:text-white text-xs transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span id="preview-text">Preview</span>
            </button>

            <button type="button" id="btn-save" class="px-3.5 py-1.5 rounded-[5px] bg-white text-[#111111] hover:bg-neutral-200 text-xs font-medium transition-colors flex items-center gap-1.5">
                <svg id="save-spinner" class="w-3.5 h-3.5 animate-spin hidden" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                <span id="save-text">Simpan Perubahan</span>
            </button>
        </div>
    </header>

    <!-- Workspace Area -->
    <div class="flex-1 flex overflow-hidden relative">
        <!-- GrapesJS Canvas Host -->
        <div id="gjs" class="flex-1 h-full"></div>

        <!-- Right Side Panel (Blocks, Styles, Traits, Layers) -->
        <div class="w-80 bg-[#121214] border-l border-[#27272A] flex flex-col flex-shrink-0 z-40">
            <!-- Tab Header -->
            <div class="flex border-b border-[#27272A] text-[11px] font-mono text-[#71717A]">
                <button type="button" id="tab-blocks" class="flex-1 py-2.5 px-2 text-center border-b border-white text-white transition-colors">
                    Blok E-Commerce
                </button>
                <button type="button" id="tab-styles" class="flex-1 py-2.5 px-2 text-center border-b border-transparent hover:text-white transition-colors">
                    Styling
                </button>
                <button type="button" id="tab-traits" class="flex-1 py-2.5 px-2 text-center border-b border-transparent hover:text-white transition-colors">
                    Pengaturan
                </button>
                <button type="button" id="tab-layers" class="flex-1 py-2.5 px-2 text-center border-b border-transparent hover:text-white transition-colors">
                    Layers
                </button>
            </div>

            <!-- Tab Content Host Containers -->
            <div class="flex-1 overflow-y-auto">
                <div id="panel-blocks" class="p-2"></div>
                <div id="panel-styles" class="p-2 hidden"></div>
                <div id="panel-traits" class="p-2 hidden"></div>
                <div id="panel-layers" class="p-2 hidden"></div>
            </div>
        </div>
    </div>

    <!-- Toast Notification (Minimalist Pastel Strip) -->
    <div id="toast" class="bg-[#EDF3EC] text-[#26542A] border border-[#D1E2CF] px-4 py-2.5 rounded-[5px] flex items-center gap-2 text-xs font-mono">
        <span class="font-bold">[SUKSES]</span>
        <span id="toast-message">Halaman visual berhasil disimpan.</span>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/grapesjs"></script>
    <script src="{{ asset('js/cms/reloo-gjs-blocks.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rawGjsData = @json($page->gjs_data ?? null);
            const initialHtml = @json($page->html_content ?? '');
            const initialCss = @json($page->css_content ?? '');

            // Initialize GrapesJS
            const editor = grapesjs.init({
                container: '#gjs',
                fromElement: false,
                height: '100%',
                width: 'auto',
                panels: { defaults: [] },
                storageManager: false,
                noticeOnUnload: false,
                canvas: {
                    styles: [
                        'https://fonts.bunny.net/css?family=instrument-sans:400,500,600|instrument-serif:400|geist-mono:400,500',
                        '{{ asset('css/cms/canvas.css') }}'
                    ],
                    scripts: [
                        'https://cdn.tailwindcss.com'
                    ]
                },
                blockManager: {
                    appendTo: '#panel-blocks',
                },
                traitManager: {
                    appendTo: '#panel-traits',
                },
                layerManager: {
                    appendTo: '#panel-layers',
                },
                styleManager: {
                    appendTo: '#panel-styles',
                    sectors: [
                        {
                            name: 'Tata Letak & Dimensi',
                            open: true,
                            buildProps: ['width', 'min-height', 'max-width', 'height', 'margin', 'padding'],
                            properties: [
                                { name: 'Lebar', property: 'width', type: 'integer', units: ['px', '%', 'vw', 'auto'], defaults: 'auto', min: 0 },
                                { name: 'Tinggi', property: 'height', type: 'integer', units: ['px', '%', 'vh', 'auto'], defaults: 'auto', min: 0 },
                                { name: 'Maksimal Lebar', property: 'max-width', type: 'integer', units: ['px', '%', 'vw'], defaults: 'auto' },
                                { name: 'Minimal Tinggi', property: 'min-height', type: 'integer', units: ['px', 'vh'], defaults: 'auto' },
                                {
                                    name: 'Margin',
                                    property: 'margin',
                                    type: 'composite',
                                    properties: [
                                        { name: 'Atas', property: 'margin-top', type: 'integer', units: ['px', 'rem', '%', 'auto'] },
                                        { name: 'Kanan', property: 'margin-right', type: 'integer', units: ['px', 'rem', '%', 'auto'] },
                                        { name: 'Bawah', property: 'margin-bottom', type: 'integer', units: ['px', 'rem', '%', 'auto'] },
                                        { name: 'Kiri', property: 'margin-left', type: 'integer', units: ['px', 'rem', '%', 'auto'] },
                                    ]
                                },
                                {
                                    name: 'Padding',
                                    property: 'padding',
                                    type: 'composite',
                                    properties: [
                                        { name: 'Atas', property: 'padding-top', type: 'integer', units: ['px', 'rem', '%'] },
                                        { name: 'Kanan', property: 'padding-right', type: 'integer', units: ['px', 'rem', '%'] },
                                        { name: 'Bawah', property: 'padding-bottom', type: 'integer', units: ['px', 'rem', '%'] },
                                        { name: 'Kiri', property: 'padding-left', type: 'integer', units: ['px', 'rem', '%'] },
                                    ]
                                }
                            ]
                        },
                        {
                            name: 'Tipografi',
                            open: false,
                            buildProps: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'line-height', 'text-align'],
                            properties: [
                                {
                                    name: 'Font Family',
                                    property: 'font-family',
                                    type: 'select',
                                    defaults: 'inherit',
                                    options: [
                                        { value: 'inherit', name: 'Default (Sans)' },
                                        { value: "'Instrument Sans', sans-serif", name: 'Instrument Sans' },
                                        { value: "'Instrument Serif', Georgia, serif", name: 'Instrument Serif' },
                                        { value: "'Geist Mono', monospace", name: 'Geist Mono' },
                                    ]
                                },
                                { name: 'Ukuran Font', property: 'font-size', type: 'integer', units: ['px', 'rem', 'em'], defaults: '14px' },
                                {
                                    name: 'Ketebalan Font',
                                    property: 'font-weight',
                                    type: 'select',
                                    defaults: '400',
                                    options: [
                                        { value: '400', name: '400 (Regular)' },
                                        { value: '500', name: '500 (Medium)' },
                                        { value: '600', name: '600 (Semibold)' },
                                        { value: '700', name: '700 (Bold)' },
                                    ]
                                },
                                { name: 'Warna Teks', property: 'color', type: 'color' },
                                { name: 'Line Height', property: 'line-height', type: 'integer', units: ['px', 'rem', 'em', 'normal'], defaults: 'normal' },
                                { name: 'Letter Spacing', property: 'letter-spacing', type: 'integer', units: ['px', 'em'], defaults: 'normal' },
                                {
                                    name: 'Perataan Teks',
                                    property: 'text-align',
                                    type: 'radio',
                                    defaults: 'left',
                                    options: [
                                        { value: 'left', name: 'Kiri' },
                                        { value: 'center', name: 'Tengah' },
                                        { value: 'right', name: 'Kanan' },
                                        { value: 'justify', name: 'Rata' },
                                    ]
                                }
                            ]
                        },
                        {
                            name: 'Warna & Background',
                            open: false,
                            buildProps: ['background-color', 'opacity'],
                            properties: [
                                { name: 'Warna Latar', property: 'background-color', type: 'color' },
                                { name: 'Transparansi', property: 'opacity', type: 'integer', units: ['%'], min: 0, max: 100, defaults: 100 }
                            ]
                        },
                        {
                            name: 'Garis Batas (Border)',
                            open: false,
                            buildProps: ['border-width', 'border-style', 'border-color', 'border-radius'],
                            properties: [
                                { name: 'Ketebalan', property: 'border-width', type: 'integer', units: ['px'], defaults: '1px' },
                                {
                                    name: 'Gaya Garis',
                                    property: 'border-style',
                                    type: 'select',
                                    defaults: 'solid',
                                    options: [
                                        { value: 'none', name: 'Tidak Ada' },
                                        { value: 'solid', name: 'Solid' },
                                        { value: 'dashed', name: 'Dashed' },
                                        { value: 'dotted', name: 'Dotted' },
                                    ]
                                },
                                { name: 'Warna Garis', property: 'border-color', type: 'color', defaults: '#EAEAEA' },
                                { name: 'Radius Sudut', property: 'border-radius', type: 'integer', units: ['px'], defaults: '5px' }
                            ]
                        }
                    ]
                },
                plugins: [window.relooGjsPlugin]
            });

            // Load Content
            if (rawGjsData && (rawGjsData.pages || rawGjsData.components || Object.keys(rawGjsData).length > 0)) {
                try {
                    editor.loadProjectData(rawGjsData);
                } catch (e) {
                    if (initialHtml) {
                        editor.setComponents(initialHtml);
                    }
                    if (initialCss) {
                        editor.setStyle(initialCss);
                    }
                }
            } else if (initialHtml) {
                editor.setComponents(initialHtml);
                if (initialCss) {
                    editor.setStyle(initialCss);
                }
            }

            // Tab Switching Logic
            const tabs = [
                { tab: document.getElementById('tab-blocks'), panel: document.getElementById('panel-blocks') },
                { tab: document.getElementById('tab-styles'), panel: document.getElementById('panel-styles') },
                { tab: document.getElementById('tab-traits'), panel: document.getElementById('panel-traits') },
                { tab: document.getElementById('tab-layers'), panel: document.getElementById('panel-layers') }
            ];

            tabs.forEach(({ tab, panel }) => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => {
                        t.tab.classList.remove('border-white', 'text-white');
                        t.tab.classList.add('border-transparent', 'text-[#71717A]');
                        t.panel.classList.add('hidden');
                    });
                    tab.classList.add('border-white', 'text-white');
                    tab.classList.remove('border-transparent', 'text-[#71717A]');
                    panel.classList.remove('hidden');
                });
            });

            // Device Switcher
            const deviceMap = {
                'btn-desktop': '',
                'btn-tablet': 'Tablet',
                'btn-mobile': 'Mobile'
            };

            const deviceBtns = [
                document.getElementById('btn-desktop'),
                document.getElementById('btn-tablet'),
                document.getElementById('btn-mobile')
            ];

            // Add devices to GrapesJS
            const dm = editor.DeviceManager;
            dm.add('Desktop', '');
            dm.add('Tablet', '768px');
            dm.add('Mobile', '375px');

            deviceBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    deviceBtns.forEach(b => {
                        b.classList.remove('bg-[#27272A]', 'text-white');
                        b.classList.add('text-[#71717A]');
                    });
                    btn.classList.add('bg-[#27272A]', 'text-white');
                    btn.classList.remove('text-[#71717A]');

                    const targetDevice = btn.id === 'btn-desktop' ? 'Desktop' : (btn.id === 'btn-tablet' ? 'Tablet' : 'Mobile');
                    editor.setDevice(targetDevice);
                });
            });

            // History Undo & Redo
            const um = editor.UndoManager;
            document.getElementById('btn-undo').addEventListener('click', () => um.undo());
            document.getElementById('btn-redo').addEventListener('click', () => um.redo());

            // Clear Canvas
            document.getElementById('btn-clear').addEventListener('click', () => {
                if (confirm('Kosongkan seluruh kanvas? Tindakan ini dapat di-undo.')) {
                    editor.setComponents('');
                    editor.setStyle('');
                }
            });

            // Preview Mode Toggle
            let isPreview = false;
            const previewBtn = document.getElementById('btn-preview');
            const previewText = document.getElementById('preview-text');

            previewBtn.addEventListener('click', () => {
                isPreview = !isPreview;
                if (isPreview) {
                    editor.runCommand('preview');
                    previewText.textContent = 'Keluar Preview';
                    previewBtn.classList.add('bg-white', 'text-[#111111]');
                    previewBtn.classList.remove('bg-[#18181B]', 'text-[#A1A1AA]');
                } else {
                    editor.stopCommand('preview');
                    previewText.textContent = 'Preview';
                    previewBtn.classList.remove('bg-white', 'text-[#111111]');
                    previewBtn.classList.add('bg-[#18181B]', 'text-[#A1A1AA]');
                }
            });

            // Save Handler
            const saveBtn = document.getElementById('btn-save');
            const saveSpinner = document.getElementById('save-spinner');
            const saveText = document.getElementById('save-text');
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');

            function showToast(message, isError = false) {
                toastMessage.textContent = message;
                if (isError) {
                    toast.className = 'bg-[#FDEBEC] text-[#8C2220] border border-[#F8C8CA] px-4 py-2.5 rounded-[5px] flex items-center gap-2 text-xs font-mono show';
                } else {
                    toast.className = 'bg-[#EDF3EC] text-[#26542A] border border-[#D1E2CF] px-4 py-2.5 rounded-[5px] flex items-center gap-2 text-xs font-mono show';
                }
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            }

            saveBtn.addEventListener('click', async function () {
                saveBtn.disabled = true;
                saveSpinner.classList.remove('hidden');
                saveText.textContent = 'Menyimpan...';

                try {
                    const projectData = editor.getProjectData();
                    const html = editor.getHtml();
                    const css = editor.getCss();

                    const response = await fetch('{{ route('admin.cms.save', $page->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            gjs_data: projectData,
                            html: html,
                            css: css
                        })
                    });

                    const data = await response.json();
                    if (response.ok && data.status === 'success') {
                        showToast('Perubahan halaman berhasil disimpan ke database.');
                    } else {
                        showToast(data.message || 'Gagal menyimpan halaman.', true);
                    }
                } catch (err) {
                    showToast('Terjadi kesalahan koneksi saat menyimpan.', true);
                } finally {
                    saveBtn.disabled = false;
                    saveSpinner.classList.add('hidden');
                    saveText.textContent = 'Simpan Perubahan';
                }
            });
        });
    </script>
</body>
</html>
