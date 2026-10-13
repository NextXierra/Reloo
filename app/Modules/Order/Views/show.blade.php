@extends('layouts.store')

@section('title', 'Detail Pesanan: ' . $order->order_number . ' - Reloo Marketplace')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                <a href="{{ route('home') }}" class="hover:text-indigo-600">Beranda</a>
                <span>/</span>
                <a href="{{ route('order.index') }}" class="hover:text-indigo-600">Pesanan</a>
                <span>/</span>
                <span class="text-slate-900 font-bold font-mono">{{ $order->order_number }}</span>
            </nav>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Detail Pesanan</h1>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('order.invoice', $order->order_number) }}" target="_blank" class="px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center gap-1.5">
                <span>Cetak Invoice</span>
            </a>
            @if($order->status === 'pending_payment')
                <a href="{{ route('payment.pay', $order->order_number) }}" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition shadow-sm">
                    Bayar Sekarang &rarr;
                </a>
            @endif
        </div>
    </div>

    <div class="space-y-6">
        
        <!-- Status & Delivery Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                <div>
                    <span class="text-xs text-slate-400 block">Status Pesanan:</span>
                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-bold border {{ $order->status_badge['color'] }}">
                        {{ $order->status_badge['label'] }}
                    </span>
                </div>
                <div class="text-right sm:text-right text-xs">
                    <span class="text-slate-400 block">Waktu Transaksi:</span>
                    <span class="font-bold text-slate-800">{{ $order->created_at->format('d M Y, H:i:s') }} WIB</span>
                </div>
            </div>

            <!-- Shipment Tracking Card -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2 text-xs">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Ekspedisi Logistik</span>
                        <span class="font-bold text-slate-900">{{ $order->shipment ? $order->shipment->courier_name : '-' }} ({{ $order->shipment ? $order->shipment->courier_service : '-' }})</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Nomor Resi Pengiriman</span>
                        @if($order->shipment && $order->shipment->tracking_number)
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-black text-indigo-600 text-sm">{{ $order->shipment->tracking_number }}</span>
                                <button type="button" onclick="navigator.clipboard.writeText('{{ $order->shipment->tracking_number }}'); alert('Nomor resi berhasil disalin.');" class="px-2 py-0.5 text-[10px] font-bold border border-slate-200 rounded hover:bg-slate-100" title="Salin Resi">
                                    Salin
                                </button>
                            </div>
                        @else
                            <span class="text-slate-400 italic">Menunggu input resi toko</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Address Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <h3 class="font-bold text-sm text-slate-900 mb-3">Alamat Pengiriman Penerima</h3>
            @php
                $addr = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
            @endphp
            <div class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <div class="font-bold text-slate-900 mb-0.5">
                    {{ $addr['recipient_name'] ?? '-' }} 
                    <span class="text-slate-400 font-normal">({{ $addr['phone_number'] ?? '-' }})</span>
                </div>
                <div>
                    {{ $addr['address_line'] ?? '-' }}, 
                    {{ !empty($addr['district_name']) ? $addr['district_name'] . ', ' : '' }}
                    {{ $addr['city_name'] ?? '-' }}, 
                    {{ $addr['province_name'] ?? '-' }} {{ $addr['postal_code'] ?? '-' }}
                </div>
                @if($order->customer_note)
                    <div class="mt-2 pt-2 border-t border-slate-200/60 text-slate-500 italic">
                        Catatan: "{{ $order->customer_note }}"
                    </div>
                @endif
            </div>
        </div>

        <!-- Items Table Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm overflow-hidden">
            <h3 class="font-bold text-sm text-slate-900 mb-4">Rincian Barang yang Dipesan</h3>

            <div class="divide-y divide-slate-100">
                @foreach($order->items as $item)
                    <div class="py-4 flex items-center justify-between text-xs gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden border border-slate-200 flex-shrink-0">
                                @if($item->product && $item->product->primaryImage)
                                    <img src="{{ $item->product->primaryImage->url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">{{ $item->product_name }}</h4>
                                <span class="text-slate-400 text-[11px]">{{ $item->quantity }} x {{ $item->formatted_price }} ({{ $item->weight_grams }}g)</span>
                            </div>
                        </div>
                        <span class="font-black text-slate-900">{{ $item->formatted_subtotal }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Financial Summary -->
            <div class="pt-4 border-t border-slate-100 space-y-2 text-xs">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal Produk</span>
                    <span class="font-bold text-slate-800">Rp {{ number_format($order->items_total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Biaya Pengiriman Logistik</span>
                    <span class="font-bold text-slate-800">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="pt-2 border-t border-slate-100 flex justify-between text-sm items-baseline">
                    <span class="font-black text-slate-900">Total Pembayaran</span>
                    <span class="text-lg font-black text-indigo-600">{{ $order->formatted_grand_total }}</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
