@extends('layouts.store')

@section('title', 'Detail Pesanan: ' . $order->order_number . ' · Reloo')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <div class="mb-8 pb-4 border-b border-[#EAEAEA] flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-[#71717A] font-mono mb-2">
                <a href="{{ route('home') }}" class="hover:text-[#111111]">BERANDA</a>
                <span>/</span>
                <a href="{{ route('order.index') }}" class="hover:text-[#111111]">PESANAN</a>
                <span>/</span>
                <span class="text-[#111111] font-semibold">{{ $order->order_number }}</span>
            </nav>
            <h1 class="font-serif text-3xl text-[#111111] tracking-tight">Detail Pesanan</h1>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('order.invoice', $order->order_number) }}" target="_blank" class="px-3 py-2 rounded-[5px] border border-[#EAEAEA] hover:bg-[#F7F6F3] text-[#111111] text-xs font-medium transition-colors flex items-center gap-1.5 whitespace-nowrap">
                <span>Cetak Invoice</span>
            </a>
            @if($order->status === 'pending_payment')
                <a href="{{ route('payment.pay', $order->order_number) }}" class="px-4 py-2 rounded-[5px] bg-[#111111] hover:bg-[#27272A] text-white text-xs font-medium transition-colors whitespace-nowrap">
                    Bayar Sekarang &rarr;
                </a>
            @endif
        </div>
    </div>

    <div class="space-y-6">

        <!-- Status & Delivery Card -->
        <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA] space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-[#EAEAEA] gap-2">
                <div>
                    <span class="text-[10px] font-mono text-[#71717A] uppercase block">STATUS TRANSAKSI:</span>
                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[10px] font-mono uppercase border {{ $order->status_badge['color'] }}">
                        {{ $order->status_badge['label'] }}
                    </span>
                </div>
                <div class="text-left sm:text-right text-xs">
                    <span class="text-[10px] font-mono text-[#71717A] uppercase block">WAKTU TRANSAKSI:</span>
                    <span class="font-mono text-xs text-[#111111]">{{ $order->created_at->format('d M Y, H:i:s') }} WIB</span>
                </div>
            </div>

            <!-- Shipment Tracking Card -->
            <div class="p-4 rounded-[5px] bg-[#F7F6F3] border border-[#EAEAEA] space-y-2 text-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <span class="text-[#71717A] block text-[10px] font-mono uppercase tracking-wider">Kurir Pengiriman</span>
                        <span class="font-medium text-[#111111]">{{ $order->shipment ? $order->shipment->courier_name : '-' }} ({{ $order->shipment ? $order->shipment->courier_service : '-' }})</span>
                    </div>
                    <div>
                        <span class="text-[#71717A] block text-[10px] font-mono uppercase tracking-wider">NOMOR RESI PENGIRIMAN</span>
                        @if($order->shipment && $order->shipment->tracking_number)
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-semibold text-[#111111] text-xs">{{ $order->shipment->tracking_number }}</span>
                                <button type="button" onclick="navigator.clipboard.writeText('{{ $order->shipment->tracking_number }}'); alert('Nomor resi berhasil disalin.');" class="px-2 py-0.5 text-[10px] font-mono border border-[#EAEAEA] rounded bg-white hover:bg-[#F7F6F3] text-[#111111]" title="Salin Resi">
                                    Salin
                                </button>
                            </div>
                        @else
                            <span class="text-[#71717A] font-mono text-xs italic">Menunggu input resi kurator</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Address Card -->
        <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA]">
            <div class="pb-3 border-b border-[#EAEAEA] mb-3">
                <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-0.5">TUJUAN PENGIRIMAN</span>
                <h3 class="font-serif text-lg text-[#111111]">Alamat Penerima Paket</h3>
            </div>
            @php
                $addr = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
            @endphp
            <div class="text-xs text-[#52525B] leading-relaxed bg-[#F7F6F3] p-4 rounded-[5px] border border-[#EAEAEA]">
                <div class="font-medium text-[#111111] mb-0.5">
                    {{ $addr['recipient_name'] ?? '-' }}
                    <span class="font-mono text-[#71717A]">({{ $addr['phone_number'] ?? '-' }})</span>
                </div>
                <div>
                    {{ $addr['address_line'] ?? '-' }},
                    {{ !empty($addr['district_name']) ? $addr['district_name'] . ', ' : '' }}
                    {{ $addr['city_name'] ?? '-' }},
                    {{ $addr['province_name'] ?? '-' }} {{ $addr['postal_code'] ?? '-' }}
                </div>
                @if($order->customer_note)
                    <div class="mt-2 pt-2 border-t border-[#EAEAEA] text-[#71717A] italic">
                        Catatan Khusus: "{{ $order->customer_note }}"
                    </div>
                @endif
            </div>
        </div>

        <!-- Items Table Card -->
        <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA] overflow-hidden">
            <div class="pb-3 border-b border-[#EAEAEA] mb-4">
                <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-0.5">RINCIAN ITEM</span>
                <h3 class="font-serif text-lg text-[#111111]">Barang yang Dipesan</h3>
            </div>

            <div class="divide-y divide-[#EAEAEA]">
                @foreach($order->items as $item)
                    <div class="py-4 flex items-center justify-between text-xs gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-[3px] bg-[#F7F6F3] overflow-hidden border border-[#EAEAEA] flex-shrink-0 p-0.5">
                                @if($item->product && $item->product->primaryImage)
                                    <img src="{{ $item->product->primaryImage->url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover rounded-[2px]">
                                @endif
                            </div>
                            <div>
                                <h4 class="font-medium text-[#111111]">{{ $item->product_name }}</h4>
                                <span class="font-mono text-[10px] text-[#71717A]">{{ $item->quantity }}x {{ $item->formatted_price }} ({{ $item->weight_grams }}g)</span>
                            </div>
                        </div>
                        <span class="font-mono font-semibold text-[#111111]">{{ $item->formatted_subtotal }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Financial Summary -->
            <div class="pt-4 border-t border-[#EAEAEA] space-y-2 text-xs">
                <div class="flex justify-between text-[#52525B]">
                    <span>Subtotal Produk</span>
                    <span class="font-mono font-medium text-[#111111]">Rp {{ number_format($order->items_total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-[#52525B]">
                    <span>Biaya Pengiriman Logistik</span>
                    <span class="font-mono font-medium text-[#111111]">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="pt-2 border-t border-[#EAEAEA] flex justify-between text-sm items-baseline">
                    <span class="font-medium text-[#111111]">Total Tagihan</span>
                    <span class="font-mono text-xl font-bold text-[#111111]">{{ $order->formatted_grand_total }}</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
