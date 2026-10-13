@extends('layouts.store')

@section('title', 'Riwayat Pesanan Saya · Reloo')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <div class="mb-8 pb-4 border-b border-[#EAEAEA]">
        <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-1">MANAJEMEN TRANSAKSI</span>
        <h1 class="font-serif text-3xl sm:text-4xl text-[#111111] tracking-tight">Riwayat Pesanan Saya</h1>
        <p class="text-xs text-[#52525B] mt-1">Pantau status transaksi, resi ekspedisi resmi, dan arsip pembelian barang terkurasi.</p>
    </div>

    <!-- Status Filter Tabs -->
    <div class="flex items-center gap-1.5 overflow-x-auto pb-4 mb-6 text-xs font-mono">
        <a href="{{ route('order.index') }}" class="px-3 py-1.5 rounded-[5px] transition-colors flex-shrink-0 {{ !request('status') ? 'bg-[#111111] text-white font-medium' : 'bg-white border border-[#EAEAEA] text-[#52525B] hover:text-[#111111]' }}">
            SEMUA PESANAN
        </a>
        <a href="{{ route('order.index', ['status' => 'pending_payment']) }}" class="px-3 py-1.5 rounded-[5px] transition-colors flex-shrink-0 {{ request('status') === 'pending_payment' ? 'bg-[#111111] text-white font-medium' : 'bg-white border border-[#EAEAEA] text-[#52525B] hover:text-[#111111]' }}">
            BELUM BAYAR
        </a>
        <a href="{{ route('order.index', ['status' => 'paid']) }}" class="px-3 py-1.5 rounded-[5px] transition-colors flex-shrink-0 {{ request('status') === 'paid' ? 'bg-[#111111] text-white font-medium' : 'bg-white border border-[#EAEAEA] text-[#52525B] hover:text-[#111111]' }}">
            SUDAH DIBAYAR
        </a>
        <a href="{{ route('order.index', ['status' => 'processing']) }}" class="px-3 py-1.5 rounded-[5px] transition-colors flex-shrink-0 {{ request('status') === 'processing' ? 'bg-[#111111] text-white font-medium' : 'bg-white border border-[#EAEAEA] text-[#52525B] hover:text-[#111111]' }}">
            DIPROSES
        </a>
        <a href="{{ route('order.index', ['status' => 'shipped']) }}" class="px-3 py-1.5 rounded-[5px] transition-colors flex-shrink-0 {{ request('status') === 'shipped' ? 'bg-[#111111] text-white font-medium' : 'bg-white border border-[#EAEAEA] text-[#52525B] hover:text-[#111111]' }}">
            DIKIRIM
        </a>
        <a href="{{ route('order.index', ['status' => 'completed']) }}" class="px-3 py-1.5 rounded-[5px] transition-colors flex-shrink-0 {{ request('status') === 'completed' ? 'bg-[#111111] text-white font-medium' : 'bg-white border border-[#EAEAEA] text-[#52525B] hover:text-[#111111]' }}">
            SELESAI
        </a>
    </div>

    <!-- Orders List -->
    @if($orders->isEmpty())
        <div class="bg-white rounded-[6px] p-12 border border-[#EAEAEA] text-center max-w-md mx-auto">
            <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-2">[NIHIL]</span>
            <h3 class="font-serif text-xl text-[#111111] mb-2">Belum Ada Pesanan</h3>
            <p class="text-xs text-[#52525B] mb-6 leading-relaxed">Anda belum memiliki riwayat transaksi pada filter status yang dipilih.</p>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-[5px] bg-[#111111] text-white text-xs font-medium hover:bg-[#27272A] transition-colors whitespace-nowrap">
                Mulai Berbelanja &rarr;
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA] space-y-4">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-[#EAEAEA] gap-2">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-xs font-semibold text-[#111111]">{{ $order->order_number }}</span>
                            <span class="font-mono text-[11px] text-[#71717A]">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono uppercase border {{ $order->status_badge['color'] }}">
                                {{ $order->status_badge['label'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Items Preview -->
                    <div class="space-y-2">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between text-xs py-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-[#111111]">{{ $item->product_name }}</span>
                                    <span class="font-mono text-[#71717A]">x{{ $item->quantity }}</span>
                                </div>
                                <span class="font-mono text-[#111111] font-semibold">{{ $item->formatted_subtotal }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Footer Details & Actions -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-4 border-t border-[#EAEAEA] gap-4">
                        <div>
                            <span class="text-[10px] font-mono uppercase text-[#71717A] block">TOTAL TAGIHAN:</span>
                            <span class="font-mono text-base font-bold text-[#111111]">{{ $order->formatted_grand_total }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($order->status === 'pending_payment')
                                <a href="{{ route('payment.pay', $order->order_number) }}" class="px-3.5 py-2 rounded-[5px] bg-[#111111] hover:bg-[#27272A] text-white text-xs font-medium transition-colors whitespace-nowrap">
                                    Bayar Sekarang &rarr;
                                </a>
                            @endif
                            <a href="{{ route('order.show', $order->order_number) }}" class="px-3 py-2 rounded-[5px] border border-[#EAEAEA] hover:bg-[#F7F6F3] text-[#111111] text-xs font-medium transition-colors whitespace-nowrap">
                                Detail Pesanan
                            </a>
                            <a href="{{ route('order.invoice', $order->order_number) }}" target="_blank" class="px-3 py-2 rounded-[5px] border border-[#EAEAEA] hover:bg-[#F7F6F3] text-[#71717A] hover:text-[#111111] text-xs font-medium transition-colors whitespace-nowrap" title="Cetak Invoice">
                                Invoice
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="mt-6 pt-4 border-t border-[#EAEAEA]">
                {{ $orders->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
