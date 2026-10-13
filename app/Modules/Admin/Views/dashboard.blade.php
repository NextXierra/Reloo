@extends('admin::layouts.admin')

@section('title', 'Dashboard Analitik')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-8 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-400/20 mb-3">
                Reloo Back-Office Panel
            </span>
            <h2 class="text-2xl md:text-3xl font-black mb-2">Selamat Datang, {{ auth()->user()->name }}!</h2>
            <p class="text-slate-300 text-xs md:text-sm">Pantau statistik penjualan, verifikasi pembayaran pesanan, dan kelola halaman visual CMS.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.cms.index') }}" class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition shadow-lg shadow-indigo-600/30 flex items-center gap-2">
                <span>Desain CMS GrapesJS</span>
            </a>
            <a href="{{ route('admin.products.create') }}" class="px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition border border-white/20">
                + Tambah Produk
            </a>
        </div>
    </div>

    <!-- Stat Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Omset -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Omset</span>
                <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs uppercase">IDR</span>
            </div>
            <div class="text-xl font-black text-slate-900">{{ $stats['formatted_total_sales'] }}</div>
            <span class="text-[11px] text-slate-400 mt-1 block">Dari transaksi lunas & terverifikasi</span>
        </div>

        <!-- Total Pesanan -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pesanan</span>
                <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs uppercase">ORD</span>
            </div>
            <div class="text-xl font-black text-slate-900">{{ $stats['total_orders'] }} Transaksi</div>
            <span class="text-[11px] text-slate-400 mt-1 block">Semua pesanan masuk</span>
        </div>

        <!-- Menunggu Pembayaran -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Belum Bayar</span>
                <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs uppercase">WAIT</span>
            </div>
            <div class="text-xl font-black text-slate-900">{{ $stats['pending_orders'] }} Pesanan</div>
            <span class="text-[11px] text-slate-400 mt-1 block">Menunggu pembayaran customer</span>
        </div>

        <!-- Total Produk Aktif -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Produk Aktif</span>
                <span class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xs uppercase">SKU</span>
            </div>
            <div class="text-xl font-black text-slate-900">{{ $stats['total_products'] }} SKU</div>
            <span class="text-[11px] text-slate-400 mt-1 block">Siap dijual di etalase</span>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-sm text-slate-800">Pesanan Masuk Terbaru</h3>
                <p class="text-xs text-slate-400">Daftar transaksi pesanan terakhir yang perlu diproses</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                Lihat Semua Pesanan &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wider font-bold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Invoice</th>
                        <th class="py-4 px-6">Pelanggan</th>
                        <th class="py-4 px-6">Total Tagihan</th>
                        <th class="py-4 px-6">Status Pesanan</th>
                        <th class="py-4 px-6">Status Bayar</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-mono font-bold text-slate-900">
                                {{ $order->order_number }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-800 block">{{ $order->user ? $order->user->name : '-' }}</span>
                                <span class="text-[11px] text-slate-400">{{ $order->user ? $order->user->email : '' }}</span>
                            </td>
                            <td class="py-4 px-6 font-black text-slate-900">
                                {{ $order->formatted_grand_total }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $order->status_badge['color'] }}">
                                    {{ $order->status_badge['label'] }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @if($order->payment)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $order->payment->payment_status_badge['color'] }}">
                                        {{ $order->payment->payment_status_badge['label'] }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3.5 py-1.5 rounded-xl bg-slate-900 hover:bg-indigo-600 text-white font-bold text-xs transition">
                                    Detail & Resi &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                Belum ada transaksi pesanan masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
