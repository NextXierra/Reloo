@extends('admin::layouts.admin')

@section('title', 'Manajemen Pesanan Masuk')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Filter Card -->
    <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA] flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-0.5">MANAJEMEN TRANSAKSI</span>
            <h2 class="font-serif text-2xl text-[#111111] mb-1">Daftar Transaksi Pesanan</h2>
            <p class="text-xs text-[#52525B]">Kelola pemrosesan pesanan masuk, verifikasi transfer manual, dan input nomor resi pengiriman.</p>
        </div>

        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari No. Invoice..." class="px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] font-mono focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
            <button type="submit" class="px-3 py-2 rounded-[5px] bg-[#111111] text-white font-medium text-xs hover:bg-[#27272A] transition-colors whitespace-nowrap">
                Cari
            </button>
        </form>
    </div>

    <!-- Status Tabs -->
    <div class="flex items-center gap-1.5 overflow-x-auto pb-2 text-xs font-mono">
        <a href="{{ route('admin.orders.index') }}" class="px-3 py-1.5 rounded-[5px] transition-colors flex-shrink-0 {{ !request('status') ? 'bg-[#111111] text-white font-medium' : 'bg-white border border-[#EAEAEA] text-[#52525B] hover:text-[#111111]' }}">
            SEMUA PESANAN
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending_payment']) }}" class="px-3 py-1.5 rounded-[5px] transition-colors flex-shrink-0 {{ request('status') === 'pending_payment' ? 'bg-[#111111] text-white font-medium' : 'bg-white border border-[#EAEAEA] text-[#52525B] hover:text-[#111111]' }}">
            BELUM BAYAR
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'paid']) }}" class="px-3 py-1.5 rounded-[5px] transition-colors flex-shrink-0 {{ request('status') === 'paid' ? 'bg-[#111111] text-white font-medium' : 'bg-white border border-[#EAEAEA] text-[#52525B] hover:text-[#111111]' }}">
            SUDAH BAYAR
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="px-3 py-1.5 rounded-[5px] transition-colors flex-shrink-0 {{ request('status') === 'processing' ? 'bg-[#111111] text-white font-medium' : 'bg-white border border-[#EAEAEA] text-[#52525B] hover:text-[#111111]' }}">
            DIPROSES
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="px-3 py-1.5 rounded-[5px] transition-colors flex-shrink-0 {{ request('status') === 'shipped' ? 'bg-[#111111] text-white font-medium' : 'bg-white border border-[#EAEAEA] text-[#52525B] hover:text-[#111111]' }}">
            DIKIRIM
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="px-3 py-1.5 rounded-[5px] transition-colors flex-shrink-0 {{ request('status') === 'completed' ? 'bg-[#111111] text-white font-medium' : 'bg-white border border-[#EAEAEA] text-[#52525B] hover:text-[#111111]' }}">
            SELESAI
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="px-3 py-1.5 rounded-[5px] transition-colors flex-shrink-0 {{ request('status') === 'cancelled' ? 'bg-[#111111] text-white font-medium' : 'bg-white border border-[#EAEAEA] text-[#52525B] hover:text-[#111111]' }}">
            DIBATALKAN
        </a>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-[6px] border border-[#EAEAEA] overflow-hidden">
        <div class="px-6 py-4 border-b border-[#EAEAEA] flex items-center justify-between">
            <h3 class="font-mono text-xs uppercase tracking-wider text-[#111111]">Total Transaksi Terdata ({{ $orders->total() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F7F6F3] text-[10px] font-mono uppercase tracking-wider text-[#71717A] border-b border-[#EAEAEA]">
                    <tr>
                        <th class="py-3 px-6">Invoice</th>
                        <th class="py-3 px-6">Pelanggan</th>
                        <th class="py-3 px-6">Tanggal</th>
                        <th class="py-3 px-6">Total Tagihan</th>
                        <th class="py-3 px-6">Status Pesanan</th>
                        <th class="py-3 px-6">Pembayaran</th>
                        <th class="py-3 px-6">Pengiriman</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EAEAEA]">
                    @forelse($orders as $order)
                        <tr class="hover:bg-[#F7F6F3] transition-colors">
                            <td class="py-3.5 px-6 font-mono font-medium text-[#111111]">
                                {{ $order->order_number }}
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="font-medium text-[#111111] block leading-tight">{{ $order->user ? $order->user->name : '-' }}</span>
                                <span class="font-mono text-[10px] text-[#71717A]">{{ $order->user ? $order->user->email : '' }}</span>
                            </td>
                            <td class="py-3.5 px-6 font-mono text-[11px] text-[#71717A]">
                                {{ $order->created_at->format('d/m/Y H:i') }}
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
                            <td class="py-3.5 px-6">
                                @if($order->shipment && $order->shipment->tracking_number)
                                    <span class="font-mono text-[#111111] font-medium block">{{ $order->shipment->tracking_number }}</span>
                                    <span class="font-mono text-[10px] text-[#71717A]">{{ $order->shipment->courier_name }}</span>
                                @else
                                    <span class="text-[#71717A] font-mono text-[10px] italic">Belum ada resi</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center px-2.5 py-1 rounded-[4px] bg-[#111111] hover:bg-[#27272A] text-white font-medium text-xs transition-colors whitespace-nowrap">
                                    Fulfillment &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-[#71717A] font-mono text-xs">Tidak ada pesanan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-[#EAEAEA]">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
