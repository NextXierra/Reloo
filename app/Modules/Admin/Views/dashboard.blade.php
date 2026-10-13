@extends('admin::layouts.admin')

@section('title', 'Dashboard Analitik')

@section('content')
<div class="space-y-6">

    <!-- Welcome Header Banner (Clean Editorial White Card) -->
    <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA] flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-1">
                STUDIO ADMINISTRASI RELOO
            </span>
            <h2 class="font-serif text-2xl text-[#111111] tracking-tight mb-1">Ringkasan Operasional & Analitik</h2>
            <p class="text-xs text-[#52525B] max-w-xl leading-relaxed">
                Pantau omset harian, verifikasi pelunasan QRIS dan transfer manual, kelola stok katalog produk, serta publikasikan tata letak visual landing page.
            </p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.cms.index') }}" class="px-4 py-2 rounded-[5px] bg-[#111111] hover:bg-[#27272A] text-white font-medium text-xs transition-colors flex items-center gap-1.5 whitespace-nowrap">
                <span>Visual Builder GrapesJS</span>
            </a>
            <a href="{{ route('admin.products.create') }}" class="px-4 py-2 rounded-[5px] border border-[#EAEAEA] hover:bg-[#F7F6F3] text-[#111111] font-medium text-xs transition-colors whitespace-nowrap">
                + Tambah Produk
            </a>
        </div>
    </div>

    <!-- Stat Metric Cards (4 Bento Hairline Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Omset -->
        <div class="bg-white p-5 rounded-[6px] border border-[#EAEAEA]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-mono uppercase tracking-wider text-[#71717A]">TOTAL OMSET</span>
                <span class="font-mono text-[10px] px-1.5 py-0.2 rounded bg-[#EDF3EC] text-[#26542A] border border-[#D1E2CF]">IDR</span>
            </div>
            <div class="text-2xl font-bold font-mono text-[#111111]">{{ $stats['formatted_total_sales'] }}</div>
            <span class="text-[11px] font-mono text-[#71717A] mt-1 block">Transaksi lunas & valid</span>
        </div>

        <!-- Total Pesanan -->
        <div class="bg-white p-5 rounded-[6px] border border-[#EAEAEA]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-mono uppercase tracking-wider text-[#71717A]">TOTAL PESANAN</span>
                <span class="font-mono text-[10px] px-1.5 py-0.2 rounded bg-[#F7F6F3] text-[#52525B] border border-[#EAEAEA]">ORD</span>
            </div>
            <div class="text-2xl font-bold font-mono text-[#111111]">{{ $stats['total_orders'] }} Transaksi</div>
            <span class="text-[11px] font-mono text-[#71717A] mt-1 block">Total keseluruhan pesanan</span>
        </div>

        <!-- Menunggu Pembayaran -->
        <div class="bg-white p-5 rounded-[6px] border border-[#EAEAEA]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-mono uppercase tracking-wider text-[#71717A]">BELUM BAYAR</span>
                <span class="font-mono text-[10px] px-1.5 py-0.2 rounded bg-[#FBF3DB] text-[#785100] border border-[#F5E5B5]">WAIT</span>
            </div>
            <div class="text-2xl font-bold font-mono text-[#111111]">{{ $stats['pending_orders'] }} Pesanan</div>
            <span class="text-[11px] font-mono text-[#71717A] mt-1 block">Menunggu verifikasi dana</span>
        </div>

        <!-- Total Produk Aktif -->
        <div class="bg-white p-5 rounded-[6px] border border-[#EAEAEA]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-mono uppercase tracking-wider text-[#71717A]">PRODUK AKTIF</span>
                <span class="font-mono text-[10px] px-1.5 py-0.2 rounded bg-[#F7F6F3] text-[#52525B] border border-[#EAEAEA]">SKU</span>
            </div>
            <div class="text-2xl font-bold font-mono text-[#111111]">{{ $stats['total_products'] }} SKU</div>
            <span class="text-[11px] font-mono text-[#71717A] mt-1 block">Aktif di etalase toko</span>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-[6px] border border-[#EAEAEA] overflow-hidden">
        <div class="px-6 py-4 border-b border-[#EAEAEA] flex items-center justify-between">
            <div>
                <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-0.5">LOG TRANSAKSI TERAKHIR</span>
                <h3 class="font-serif text-lg text-[#111111]">Pesanan Masuk Terbaru</h3>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="font-mono text-xs text-[#111111] hover:underline">
                Semua Pesanan &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F7F6F3] text-[10px] font-mono uppercase tracking-wider text-[#71717A] border-b border-[#EAEAEA]">
                    <tr>
                        <th class="py-3 px-6">Invoice</th>
                        <th class="py-3 px-6">Pelanggan</th>
                        <th class="py-3 px-6">Total Tagihan</th>
                        <th class="py-3 px-6">Status Pesanan</th>
                        <th class="py-3 px-6">Status Bayar</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EAEAEA]">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-[#F7F6F3] transition-colors">
                            <td class="py-3.5 px-6 font-mono font-medium text-[#111111]">
                                {{ $order->order_number }}
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="font-medium text-[#111111] block">{{ $order->user ? $order->user->name : '-' }}</span>
                                <span class="font-mono text-[10px] text-[#71717A]">{{ $order->user ? $order->user->email : '' }}</span>
                            </td>
                            <td class="py-3.5 px-6 font-mono font-semibold text-[#111111]">
                                {{ $order->formatted_grand_total }}
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono uppercase border {{ $order->status_badge['color'] }}">
                                    {{ $order->status_badge['label'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6">
                                @if($order->payment)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-mono uppercase border {{ $order->payment->payment_status_badge['color'] }}">
                                        {{ $order->payment->payment_status_badge['label'] }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center px-2.5 py-1 rounded-[4px] bg-[#111111] hover:bg-[#27272A] text-white font-medium text-xs transition-colors whitespace-nowrap">
                                    Detail & Resi &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-[#71717A] font-mono text-xs">
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
