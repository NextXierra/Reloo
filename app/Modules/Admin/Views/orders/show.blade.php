@extends('admin::layouts.admin')

@section('title', 'Fulfillment Pesanan: ' . $order->order_number)

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-black text-slate-900 font-mono">{{ $order->order_number }}</h2>
                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $order->status_badge['color'] }}">
                    {{ $order->status_badge['label'] }}
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1">Dipesan pada {{ $order->created_at->format('d M Y, H:i:s') }} WIB oleh {{ $order->user ? $order->user->name : 'Guest' }} ({{ $order->user ? $order->user->email : '' }})</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('order.invoice', $order->order_number) }}" target="_blank" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                Cetak Invoice
            </a>
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                &larr; Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Order Details & Items -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Items Table Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold text-sm text-slate-900 pb-3 border-b border-slate-100">Daftar Barang Dipesan</h3>

                <div class="divide-y divide-slate-100">
                    @foreach($order->items as $item)
                        <div class="py-3 flex items-center justify-between text-xs gap-4">
                            <div>
                                <h4 class="font-bold text-slate-900">{{ $item->product_name }}</h4>
                                <span class="text-slate-400 text-[11px]">{{ $item->quantity }} unit x {{ $item->formatted_price }} ({{ $item->weight_grams }}g)</span>
                            </div>
                            <span class="font-black text-slate-900">{{ $item->formatted_subtotal }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 border-t border-slate-100 space-y-2 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal Produk</span>
                        <span class="font-bold text-slate-800">Rp {{ number_format($order->items_total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Biaya Pengiriman</span>
                        <span class="font-bold text-slate-800">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-2 border-t border-slate-100 flex justify-between text-sm items-baseline">
                        <span class="font-black text-slate-900">Total Tagihan</span>
                        <span class="text-lg font-black text-indigo-600">{{ $order->formatted_grand_total }}</span>
                    </div>
                </div>
            </div>

            <!-- Shipping Address Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-3">
                <h3 class="font-bold text-sm text-slate-900 pb-3 border-b border-slate-100">Alamat Pengiriman Tujuan</h3>
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
                            Catatan Pembeli: "{{ $order->customer_note }}"
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Right: Fulfillment & Actions (Payment & Shipment) -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- 1. Payment Verification Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-slate-900">Status Pembayaran</h3>
                    @if($order->payment)
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $order->payment->payment_status_badge['color'] }}">
                            {{ $order->payment->payment_status_badge['label'] }}
                        </span>
                    @endif
                </div>

                <div class="text-xs space-y-2 text-slate-600">
                    <div>Metode: <strong>{{ strtoupper($order->payment ? $order->payment->payment_gateway : '-') }}</strong></div>
                    @if($order->payment && $order->payment->paid_at)
                        <div>Waktu Lunas: <strong>{{ $order->payment->paid_at->format('d/m/Y H:i') }}</strong></div>
                    @endif
                </div>

                <!-- Proof Preview & Verification Action -->
                @if($order->payment && $order->payment->payment_proof)
                    <div class="space-y-3 pt-2 border-t border-slate-100">
                        <span class="block text-xs font-bold text-slate-700">Foto Bukti Transfer:</span>
                        <a href="{{ $order->payment->payment_proof_url }}" target="_blank" class="block w-full h-44 rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 hover:opacity-90 transition" title="Klik untuk memperbesar">
                            <img src="{{ $order->payment->payment_proof_url }}" alt="Struk Pembayaran" class="w-full h-full object-cover">
                        </a>

                        @if($order->payment->payment_status !== 'paid')
                            <form method="POST" action="{{ route('admin.orders.verify', $order->id) }}">
                                @csrf
                                <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs transition shadow-md shadow-emerald-600/20">
                                    Konfirmasi Pembayaran Lunas
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>

            <!-- 2. Shipment Fulfillment Card (Input Resi) -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-slate-900">Pengiriman & Resi</h3>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                        {{ $order->shipment ? $order->shipment->shipping_status : 'Pending' }}
                    </span>
                </div>

                <div class="text-xs space-y-1 text-slate-600">
                    <div>Ekspedisi: <strong>{{ $order->shipment ? $order->shipment->courier_name : '-' }}</strong></div>
                    <div>Layanan: <strong>{{ $order->shipment ? $order->shipment->courier_service : '-' }}</strong></div>
                </div>

                <form method="POST" action="{{ route('admin.orders.ship', $order->id) }}" class="space-y-3 pt-2 border-t border-slate-100">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Resi Ekspedisi</label>
                        <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->shipment ? $order->shipment->tracking_number : '') }}" required placeholder="Contoh: JNE9988776655" class="w-full px-3.5 py-2.5 text-xs font-mono font-bold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                    </div>

                    <button type="submit" class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-black text-xs transition shadow-md shadow-indigo-600/20">
                        Simpan Resi & Set Shipped
                    </button>
                </form>
            </div>

            <!-- 3. Cancellation Card -->
            @if($order->status !== 'cancelled' && $order->status !== 'completed')
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                    <form method="POST" action="{{ route('admin.orders.cancel', $order->id) }}" onsubmit="return confirm('Batalkan pesanan ini? Stok produk akan otomatis dikembalikan ke inventaris.')">
                        @csrf
                        <button type="submit" class="w-full py-2.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold text-xs transition">
                            Batalkan Pesanan (Auto Restock)
                        </button>
                    </form>
                </div>
            @endif

        </div>

    </div>
</div>
@endsection
