@extends('admin::layouts.admin')

@section('title', 'Fulfillment Pesanan: ' . $order->order_number)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#EAEAEA]">
        <div>
            <div class="flex items-center gap-3">
                <span class="font-mono text-xl font-bold text-[#111111]">{{ $order->order_number }}</span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono uppercase border {{ $order->status_badge['color'] }}">
                    {{ $order->status_badge['label'] }}
                </span>
            </div>
            <p class="text-xs text-[#71717A] font-mono mt-1">Dipesan pada {{ $order->created_at->format('d M Y, H:i:s') }} WIB oleh {{ $order->user ? $order->user->name : 'Guest' }} ({{ $order->user ? $order->user->email : '' }})</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('order.invoice', $order->order_number) }}" target="_blank" class="px-3.5 py-1.5 rounded-[5px] border border-[#EAEAEA] text-xs font-medium text-[#111111] hover:bg-[#F7F6F3] transition-colors whitespace-nowrap">
                Cetak Invoice
            </a>
            <a href="{{ route('admin.orders.index') }}" class="px-3.5 py-1.5 rounded-[5px] border border-[#EAEAEA] text-xs font-medium text-[#71717A] hover:text-[#111111] hover:bg-[#F7F6F3] transition-colors whitespace-nowrap">
                &larr; Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        <!-- Left: Order Details & Items (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Items Table Card -->
            <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA] space-y-4">
                <div class="pb-3 border-b border-[#EAEAEA]">
                    <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-0.5">MANIFES PESANAN</span>
                    <h3 class="font-serif text-lg text-[#111111]">Daftar Barang yang Dipesan</h3>
                </div>

                <div class="divide-y divide-[#EAEAEA]">
                    @foreach($order->items as $item)
                        <div class="py-3 flex items-center justify-between text-xs gap-4">
                            <div>
                                <h4 class="font-medium text-[#111111]">{{ $item->product_name }}</h4>
                                <span class="text-[#71717A] font-mono text-[11px]">{{ $item->quantity }} unit x {{ $item->formatted_price }} ({{ $item->weight_grams }}g)</span>
                            </div>
                            <span class="font-mono font-semibold text-[#111111]">{{ $item->formatted_subtotal }}</span>
                        </div>
                    @endforeach
                </div>

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

            <!-- Shipping Address Card -->
            <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA] space-y-3">
                <div class="pb-3 border-b border-[#EAEAEA]">
                    <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-0.5">DESTINASI KURIR</span>
                    <h3 class="font-serif text-lg text-[#111111]">Alamat Pengiriman Tujuan</h3>
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
                            Catatan Pembeli: "{{ $order->customer_note }}"
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Right: Fulfillment & Actions (1 Col) -->
        <div class="lg:col-span-1 space-y-6">

            <!-- 1. Payment Verification Card -->
            <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA] space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-[#EAEAEA]">
                    <h3 class="font-mono text-xs uppercase tracking-wider text-[#111111]">Status Pembayaran</h3>
                    @if($order->payment)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-mono uppercase border {{ $order->payment->payment_status_badge['color'] }}">
                            {{ $order->payment->payment_status_badge['label'] }}
                        </span>
                    @endif
                </div>

                <div class="text-xs space-y-1.5 text-[#52525B]">
                    <div>Metode: <strong class="text-[#111111] font-mono">{{ strtoupper($order->payment ? $order->payment->payment_gateway : '-') }}</strong></div>
                    @if($order->payment && $order->payment->paid_at)
                        <div>Waktu Lunas: <strong class="font-mono text-[#111111]">{{ $order->payment->paid_at->format('d/m/Y H:i') }}</strong></div>
                    @endif
                </div>

                <!-- Proof Preview & Verification Action -->
                @if($order->payment && $order->payment->payment_proof)
                    <div class="space-y-3 pt-2 border-t border-[#EAEAEA]">
                        <span class="block text-xs font-mono uppercase text-[#71717A]">FOTO STRUK BUKTI TRANSFER:</span>
                        <a href="{{ $order->payment->payment_proof_url }}" target="_blank" class="block w-full h-44 rounded-[4px] overflow-hidden border border-[#EAEAEA] bg-[#F7F6F3] hover:opacity-90 transition-opacity p-1" title="Buka foto ukuran penuh">
                            <img src="{{ $order->payment->payment_proof_url }}" alt="Struk Pembayaran" class="w-full h-full object-cover rounded-[2px]">
                        </a>

                        @if($order->payment->payment_status !== 'paid')
                            <form method="POST" action="{{ route('admin.orders.verify', $order->id) }}">
                                @csrf
                                <button type="submit" class="w-full py-2.5 rounded-[5px] bg-[#111111] hover:bg-[#27272A] text-white font-medium text-xs transition-colors whitespace-nowrap">
                                    Konfirmasi Pembayaran Lunas
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>

            <!-- 2. Shipment Fulfillment Card (Input Resi) -->
            <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA] space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-[#EAEAEA]">
                    <h3 class="font-mono text-xs uppercase tracking-wider text-[#111111]">Pengiriman & Resi</h3>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#F7F6F3] text-[#52525B] border border-[#EAEAEA]">
                        {{ $order->shipment ? $order->shipment->shipping_status : 'Pending' }}
                    </span>
                </div>

                <div class="text-xs space-y-1 text-[#52525B]">
                    <div>Ekspedisi: <strong class="text-[#111111]">{{ $order->shipment ? $order->shipment->courier_name : '-' }}</strong></div>
                    <div>Layanan: <strong class="text-[#111111]">{{ $order->shipment ? $order->shipment->courier_service : '-' }}</strong></div>
                </div>

                <form method="POST" action="{{ route('admin.orders.ship', $order->id) }}" class="space-y-3 pt-2 border-t border-[#EAEAEA]">
                    @csrf
                    <div>
                        <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Nomor Resi Ekspedisi</label>
                        <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->shipment ? $order->shipment->tracking_number : '') }}" required placeholder="Contoh: JNE9988776655" class="w-full px-3 py-2 text-xs font-mono bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-[5px] bg-[#111111] hover:bg-[#27272A] text-white font-medium text-xs transition-colors whitespace-nowrap">
                        Simpan Resi & Set Shipped
                    </button>
                </form>
            </div>

            <!-- 3. Cancellation Card -->
            @if($order->status !== 'cancelled' && $order->status !== 'completed')
                <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA]">
                    <form method="POST" action="{{ route('admin.orders.cancel', $order->id) }}" onsubmit="return confirm('Batalkan pesanan ini? Stok produk akan otomatis dikembalikan ke inventaris.')">
                        @csrf
                        <button type="submit" class="w-full py-2 rounded-[5px] border border-[#F8C8CA] text-[#8C2220] hover:bg-[#FDEBEC] font-medium text-xs transition-colors whitespace-nowrap">
                            Batalkan Pesanan (Auto Restock)
                        </button>
                    </form>
                </div>
            @endif

        </div>

    </div>
</div>
@endsection
