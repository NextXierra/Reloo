<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Visual Page Builder: {{ $page->title }} - Reloo Marketplace</title>

    <!-- GrapesJS Core Stylesheet -->
    <link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css">
    
    <!-- Tailwind CSS for Builder Shell -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* GrapesJS Customization */
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            font-family: ui-sans-serif, system-ui, sans-serif;
        }

        .gjs-cv-canvas {
            top: 0;
            width: 100%;
            height: 100%;
        }

        .gjs-one-bg {
            background-color: #0f172a;
        }

        .gjs-two-color {
            color: #94a3b8;
        }

        .gjs-four-color, .gjs-four-color-h:hover {
            color: #6366f1;
        }

        .gjs-block {
            user-select: none;
            min-height: 80px;
            padding: 8px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: #1e293b;
            color: #cbd5e1;
            transition: all 0.2s ease;
        }

        .gjs-block:hover {
            border-color: #6366f1;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
            transform: translateY(-2px);
        }

        .gjs-block-category .gjs-title {
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-size: 11px;
            color: #818cf8;
            padding: 12px 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            background: #0f172a;
        }

        /* Toast notification */
        #toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 99999;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        #toast.show {
            transform: translateY(0);
            opacity: 1;
        }
    </style>
</head>
<body class="flex flex-col h-full bg-slate-900 text-slate-100 select-none">

    <!-- Top Action Header -->
    <header class="h-16 bg-slate-950 border-b border-slate-800 px-6 flex items-center justify-between z-50 flex-shrink-0">
        <!-- Left: Back & Title -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.cms.index') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-300 transition" title="Kembali ke Daftar">
                <span>&larr;</span> Kembali
            </a>
            <div class="h-6 w-px bg-slate-800"></div>
            <div>
                <h1 class="text-sm font-bold text-white flex items-center gap-2">
                    {{ $page->title }}
                    @if($page->is_homepage)
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Beranda</span>
                    @endif
                </h1>
                <p class="text-[10px] text-slate-400 font-mono">
                    {{ $page->is_homepage ? '/' : '/p/' . $page->slug }}
                </p>
            </div>
        </div>

        <!-- Center: Device Switcher -->
        <div class="flex items-center bg-slate-900 p-1 rounded-xl border border-slate-800">
            <button type="button" id="btn-desktop" class="px-3 py-1.5 rounded-lg text-xs font-bold transition bg-indigo-600 text-white flex items-center gap-1.5" title="Tampilan Desktop">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>Desktop</span>
            </button>
            <button type="button" id="btn-tablet" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-400 hover:text-white transition flex items-center gap-1.5" title="Tampilan Tablet">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <span>Tablet</span>
            </button>
            <button type="button" id="btn-mobile" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-400 hover:text-white transition flex items-center gap-1.5" title="Tampilan Mobile">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <span>Mobile</span>
            </button>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-3">
            <button type="button" id="btn-undo" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 transition" title="Undo (Ctrl+Z)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            </button>
            <button type="button" id="btn-redo" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 transition" title="Redo (Ctrl+Y)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2m18-10l-6 6m6-6l-6-6"/></svg>
            </button>
            <button type="button" id="btn-preview" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>Preview</span>
            </button>
            <button type="button" id="btn-save" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold text-xs transition shadow-lg shadow-indigo-600/30 flex items-center gap-2">
                <svg id="save-spinner" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                <span id="save-text">Simpan Perubahan</span>
            </button>
        </div>
    </header>

    <!-- Workspace Area -->
    <div class="flex-1 flex overflow-hidden relative">
        <!-- GrapesJS Canvas Host -->
        <div id="gjs" class="flex-1 h-full"></div>

        <!-- Right Side Panel (Blocks & Styles) -->
        <div class="w-80 bg-slate-950 border-l border-slate-800 flex flex-col flex-shrink-0 z-40">
            <!-- Tab Header -->
            <div class="flex border-b border-slate-800 text-xs font-bold text-slate-400">
                <button type="button" id="tab-blocks" class="flex-1 py-3 px-4 text-center border-b-2 border-indigo-500 text-white transition">
                    Blok E-Commerce
                </button>
                <button type="button" id="tab-styles" class="flex-1 py-3 px-4 text-center border-b-2 border-transparent hover:text-white transition">
                    Styling
                </button>
                <button type="button" id="tab-layers" class="flex-1 py-3 px-4 text-center border-b-2 border-transparent hover:text-white transition">
                    Layers
                </button>
            </div>

            <!-- Tab Content Host Containers -->
            <div class="flex-1 overflow-y-auto">
                <div id="panel-blocks" class="p-3"></div>
                <div id="panel-styles" class="p-3 hidden"></div>
                <div id="panel-layers" class="p-3 hidden"></div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="bg-emerald-600 text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 text-xs font-bold">
        <span class="text-xs uppercase tracking-wider">[OK]</span>
        <span id="toast-message">Halaman visual berhasil disimpan.</span>
    </div>

    <!-- Scripts -->
    <!-- GrapesJS Core -->
    <script src="https://unpkg.com/grapesjs"></script>
    
    <!-- Reloo Custom E-Commerce Blocks Plugin -->
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
                storageManager: false, // We handle manual save via AJAX
                noticeOnUnload: false,
                canvas: {
                    styles: [
                        'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap',
                    ],
                    scripts: [
                        'https://cdn.tailwindcss.com'
                    ]
                },
                blockManager: {
                    appendTo: '#panel-blocks',
                },
                styleManager: {
                    appendTo: '#panel-styles',
                },
                layerManager: {
                    appendTo: '#panel-layers',
                },
                deviceManager: {
                    devices: [
                        { id: 'desktop', name: 'Desktop', width: '' },
                        { id: 'tablet', name: 'Tablet', width: '768px', widthMedia: '768px' },
                        { id: 'mobile', name: 'Mobile', width: '375px', widthMedia: '375px' },
                    ]
                },
                plugins: [
                    window.relooGjsPlugin
                ]
            });

            // Load initial content
            if (rawGjsData && rawGjsData.components && rawGjsData.components.length > 0) {
                editor.loadProjectData(rawGjsData);
            } else if (initialHtml) {
                editor.setComponents(initialHtml);
                if (initialCss) {
                    editor.setStyle(initialCss);
                }
            }

            // Tab switching
            const tabBlocks = document.getElementById('tab-blocks');
            const tabStyles = document.getElementById('tab-styles');
            const tabLayers = document.getElementById('tab-layers');
            const panelBlocks = document.getElementById('panel-blocks');
            const panelStyles = document.getElementById('panel-styles');
            const panelLayers = document.getElementById('panel-layers');

            function switchTab(activeTab, activePanel) {
                [tabBlocks, tabStyles, tabLayers].forEach(t => {
                    t.classList.remove('border-indigo-500', 'text-white');
                    t.classList.add('border-transparent', 'text-slate-400');
                });
                [panelBlocks, panelStyles, panelLayers].forEach(p => p.classList.add('hidden'));

                activeTab.classList.add('border-indigo-500', 'text-white');
                activeTab.classList.remove('border-transparent', 'text-slate-400');
                activePanel.classList.remove('hidden');
            }

            tabBlocks.addEventListener('click', () => switchTab(tabBlocks, panelBlocks));
            tabStyles.addEventListener('click', () => switchTab(tabStyles, panelStyles));
            tabLayers.addEventListener('click', () => switchTab(tabLayers, panelLayers));

            // Device Switching
            const btnDesktop = document.getElementById('btn-desktop');
            const btnTablet = document.getElementById('btn-tablet');
            const btnMobile = document.getElementById('btn-mobile');

            function setActiveDevice(btn, deviceId) {
                [btnDesktop, btnTablet, btnMobile].forEach(b => {
                    b.classList.remove('bg-indigo-600', 'text-white');
                    b.classList.add('text-slate-400');
                });
                btn.classList.add('bg-indigo-600', 'text-white');
                btn.classList.remove('text-slate-400');
                editor.setDevice(deviceId);
            }

            btnDesktop.addEventListener('click', () => setActiveDevice(btnDesktop, 'desktop'));
            btnTablet.addEventListener('click', () => setActiveDevice(btnTablet, 'tablet'));
            btnMobile.addEventListener('click', () => setActiveDevice(btnMobile, 'mobile'));

            // Undo / Redo
            document.getElementById('btn-undo').addEventListener('click', () => editor.UndoManager.undo());
            document.getElementById('btn-redo').addEventListener('click', () => editor.UndoManager.redo());

            // Preview
            let isPreview = false;
            document.getElementById('btn-preview').addEventListener('click', () => {
                isPreview = !isPreview;
                editor.runCommand(isPreview ? 'preview' : 'preview');
            });

            // Save Functionality via AJAX
            const btnSave = document.getElementById('btn-save');
            const saveSpinner = document.getElementById('save-spinner');
            const saveText = document.getElementById('save-text');
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');

            function showToast(msg, isError = false) {
                toastMessage.textContent = msg;
                toast.className = isError 
                    ? 'bg-rose-600 text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 text-xs font-bold show'
                    : 'bg-emerald-600 text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 text-xs font-bold show';
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3500);
            }

            btnSave.addEventListener('click', async function () {
                btnSave.disabled = true;
                saveSpinner.classList.remove('hidden');
                saveText.textContent = 'Menyimpan...';

                try {
                    const projectData = editor.getProjectData();
                    const html = editor.getHtml();
                    const css = editor.getCss();

                    const response = await fetch("{{ route('admin.cms.save', $page->id) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            gjs_data: projectData,
                            html: html,
                            css: css
                        })
                    });

                    const data = await response.json();

                    if (response.ok && data.status === 'success') {
                        showToast(data.message || 'Halaman berhasil disimpan!');
                    } else {
                        showToast(data.message || 'Gagal menyimpan perubahan.', true);
                    }
                } catch (err) {
                    console.error('Save error:', err);
                    showToast('Terjadi kesalahan koneksi saat menyimpan.', true);
                } finally {
                    btnSave.disabled = false;
                    saveSpinner.classList.add('hidden');
                    saveText.textContent = 'Simpan Perubahan';
                }
            });
        });
    </script>
</body>
</html>
