@extends('layouts.store')

@section('title', 'Checkout Pesanan · Reloo')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <div class="mb-8 pb-4 border-b border-[#EAEAEA]">
        <h1 class="font-serif text-3xl sm:text-4xl text-[#111111] tracking-tight">Checkout Pesanan</h1>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <!-- Left Form Sections (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- 01. Alamat Pengiriman -->
                <div class="bg-white rounded-[6px] p-6 sm:p-8 border border-[#EAEAEA] space-y-6">
                    <div class="flex items-center gap-3 pb-3 border-b border-[#EAEAEA]">
                        <span class="font-mono text-xs px-2 py-0.5 rounded bg-[#F7F6F3] border border-[#EAEAEA] text-[#111111] font-semibold">01</span>
                        <h2 class="font-serif text-xl text-[#111111]">Alamat Tujuan Pengiriman</h2>
                    </div>

                    @if($addresses->isNotEmpty())
                        <div class="space-y-3 mb-6">
                            <span class="block text-xs font-mono uppercase tracking-wider text-[#71717A]">PILIH DARI BUKU ALAMAT:</span>
                            @foreach($addresses as $addr)
                                <label class="address-card block p-4 rounded-[5px] border cursor-pointer transition-colors {{ $addr->is_default ? 'border-[#111111] bg-[#F7F6F3]' : 'border-[#EAEAEA] hover:border-[#A1A1AA]' }}">
                                    <div class="flex items-start gap-3">
                                        <input type="radio" name="address_id" value="{{ $addr->id }}" {{ $addr->is_default ? 'checked' : '' }} onchange="toggleManualAddress(false); selectAddress(this);" class="mt-0.5 text-[#111111] focus:ring-[#111111]">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-medium text-xs text-[#111111]">{{ $addr->recipient_name }}</span>
                                                <span class="font-mono text-xs text-[#71717A]">({{ $addr->phone_number }})</span>
                                                @if($addr->is_default)
                                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-mono uppercase bg-[#EDF3EC] text-[#26542A] border border-[#D1E2CF]">Utama</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-[#52525B] leading-relaxed">
                                                {{ $addr->address_line }}, {{ $addr->district_name ? $addr->district_name . ', ' : '' }}{{ $addr->city_name }}, {{ $addr->province_name }} {{ $addr->postal_code }}
                                            </p>
                                        </div>
                                    </div>
                                </label>
                            @endforeach

                            <label class="address-card block p-3.5 rounded-[5px] border border-[#EAEAEA] hover:border-[#111111] cursor-pointer transition-colors">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="address_id" value="" id="use-manual-address" onchange="toggleManualAddress(true); selectAddress(this);" class="text-[#111111] focus:ring-[#111111]">
                                    <span class="text-xs font-medium text-[#111111]">+ Masukkan Alamat Pengiriman Baru Lainnya</span>
                                </div>
                            </label>
                        </div>
                    @endif

                    <!-- Manual Address Inputs -->
                    <div id="manual-address-fields" class="{{ $addresses->isNotEmpty() ? 'hidden' : '' }} grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Nama Penerima</label>
                            <input type="text" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}" placeholder="Nama penerima paket" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>
                        <div>
                            <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Nomor Telepon / WA</label>
                            <input type="tel" name="phone_number" value="{{ old('phone_number', auth()->user()->phone) }}" placeholder="08xxxxxxxxxx" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] font-mono focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Alamat Lengkap & Nomor Rumah</label>
                            <textarea name="address_line" rows="2" placeholder="Jl. Sudirman Kav. 52, RT 01/RW 03" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">{{ old('address_line') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Provinsi</label>
                            <input type="text" name="province_name" value="{{ old('province_name') }}" placeholder="DKI Jakarta" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>
                        <div>
                            <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Kota / Kabupaten</label>
                            <input type="text" name="city_name" value="{{ old('city_name') }}" placeholder="Jakarta Selatan" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>
                        <div>
                            <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Kecamatan (Opsional)</label>
                            <input type="text" name="district_name" value="{{ old('district_name') }}" placeholder="Kebayoran Baru" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>
                        <div>
                            <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Kode Pos</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}" placeholder="12190" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] font-mono focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                        </div>
                        <div class="md:col-span-2 flex items-center gap-2 pt-1">
                            <input type="checkbox" id="save_address" name="save_address" value="1" class="rounded border-[#EAEAEA] text-[#111111] focus:ring-[#111111]">
                            <label for="save_address" class="text-xs text-[#52525B] cursor-pointer">
                                Simpan alamat ini ke buku alamat saya
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 02. Kurir & Pengiriman -->
                <div class="bg-white rounded-[6px] p-6 sm:p-8 border border-[#EAEAEA] space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-[#EAEAEA]">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-xs px-2 py-0.5 rounded bg-[#F7F6F3] border border-[#EAEAEA] text-[#111111] font-semibold">02</span>
                            <h2 class="font-serif text-xl text-[#111111]">Layanan Kurir Logistik</h2>
                        </div>
                        <span class="font-mono text-xs text-[#71717A]">
                            BERAT: {{ number_format($totals['total_weight'], 0, ',', '.') }}g
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($couriers as $code => $c)
                            <label class="courier-card p-4 rounded-[5px] border cursor-pointer transition-colors flex flex-col justify-between gap-3 {{ $loop->first ? 'border-[#111111] bg-[#F7F6F3]' : 'border-[#EAEAEA] hover:border-[#A1A1AA]' }}">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="courier_code" value="{{ $code }}" {{ $loop->first ? 'checked' : '' }} data-cost="{{ $c['cost'] }}" onchange="selectCourier(this)" class="text-[#111111] focus:ring-[#111111]">
                                        <div>
                                            <span class="font-medium text-xs text-[#111111] block leading-tight">{{ $c['name'] }}</span>
                                            <span class="font-mono text-[11px] text-[#71717A] block">{{ $c['service'] }}</span>
                                        </div>
                                    </div>
                                    <span class="font-mono text-xs font-semibold text-[#111111]">{{ $c['formatted_cost'] }}</span>
                                </div>
                                <span class="font-mono text-[10px] text-[#71717A]">Estimasi Tiba: {{ $c['etd'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 03. Pembayaran -->
                <div class="bg-white rounded-[6px] p-6 sm:p-8 border border-[#EAEAEA] space-y-6">
                    <div class="flex items-center gap-3 pb-3 border-b border-[#EAEAEA]">
                        <span class="font-mono text-xs px-2 py-0.5 rounded bg-[#F7F6F3] border border-[#EAEAEA] text-[#111111] font-semibold">03</span>
                        <h2 class="font-serif text-xl text-[#111111]">Metode Pembayaran</h2>
                    </div>

                    <div class="space-y-3">
                        <!-- Option QRISLY -->
                        <label class="payment-card block p-5 rounded-[5px] border border-[#111111] bg-[#F7F6F3] cursor-pointer transition-colors">
                            <div class="flex items-start gap-4">
                                <input type="radio" name="payment_gateway" value="qrisly" checked onchange="selectPayment(this)" class="mt-0.5 text-[#111111] focus:ring-[#111111]">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-medium text-xs text-[#111111]">QRIS</span>
                                    </div>
                                    <div class="flex flex-wrap gap-1.5 text-[9px] font-mono text-[#71717A]">
                                        <span class="px-1.5 py-0.5 bg-white border border-[#EAEAEA] rounded">BCA</span>
                                        <span class="px-1.5 py-0.5 bg-white border border-[#EAEAEA] rounded">MANDIRI</span>
                                        <span class="px-1.5 py-0.5 bg-white border border-[#EAEAEA] rounded">GOPAY</span>
                                        <span class="px-1.5 py-0.5 bg-white border border-[#EAEAEA] rounded">OVO</span>
                                        <span class="px-1.5 py-0.5 bg-white border border-[#EAEAEA] rounded">DANA</span>
                                        <span class="px-1.5 py-0.5 bg-white border border-[#EAEAEA] rounded">SHOPEEPAY</span>
                                    </div>
                                </div>
                            </div>
                        </label>

                    </div>
                </div>

                <!-- 04. Catatan Pesanan -->
                <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA]">
                    <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-2">Catatan Pesanan (Opsional)</label>
                    <textarea name="customer_note" rows="2" placeholder="Contoh: Tolong lapisi bubble wrap tebal..." class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]"></textarea>
                </div>

            </div>

            <!-- Right: Order Summary Sidebar (1 Col) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA] sticky top-24 space-y-6">
                    <div class="pb-3 border-b border-[#EAEAEA]">
                        <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-0.5">RINGKASAN BIAYA</span>
                        <h2 class="font-serif text-xl text-[#111111]">Rincian Tagihan</h2>
                    </div>

                    <!-- Items Mini List -->
                    <div class="space-y-3 max-h-56 overflow-y-auto pr-1 divide-y divide-[#EAEAEA]">
                        @foreach($items as $item)
                            <div class="flex items-center gap-3 text-xs pt-2.5 first:pt-0">
                                <div class="w-10 h-10 rounded-[3px] bg-[#F7F6F3] overflow-hidden flex-shrink-0 border border-[#EAEAEA] p-0.5">
                                    @if($item->product && $item->product->primaryImage)
                                        <img src="{{ $item->product->primaryImage->url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-[2px]">
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-[#111111] truncate">{{ $item->product ? $item->product->name : '' }}</h4>
                                    <span class="font-mono text-[10px] text-[#71717A]">{{ $item->quantity }}x Rp {{ number_format($item->product ? $item->product->price : 0, 0, ',', '.') }}</span>
                                </div>
                                <span class="font-mono text-xs font-semibold text-[#111111]">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Breakdown Calculation -->
                    @php
                        $firstCourier = reset($couriers);
                        $defaultShippingCost = $firstCourier ? $firstCourier['cost'] : 0;
                        $initialGrandTotal = $totals['items_total'] + $defaultShippingCost;
                    @endphp
                    <div class="space-y-2 text-xs pt-4 border-t border-[#EAEAEA]">
                        <div class="flex justify-between text-[#52525B]">
                            <span>Subtotal Barang ({{ $totals['total_quantity'] }})</span>
                            <span class="font-mono font-medium text-[#111111]">Rp {{ number_format($totals['items_total'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[#52525B]">
                            <span>Ongkos Kirim Logistik</span>
                            <span id="summary-shipping" class="font-mono font-medium text-[#111111]">Rp {{ number_format($defaultShippingCost, 0, ',', '.') }}</span>
                        </div>
                        <div class="pt-3 border-t border-[#EAEAEA] flex justify-between items-baseline">
                            <span class="text-xs font-medium text-[#111111]">Total Pembayaran</span>
                            <span id="summary-grand-total" class="font-mono text-2xl font-bold text-[#111111]">
                                Rp {{ number_format($initialGrandTotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="h-11 w-full rounded-[5px] font-medium bg-[#111111] hover:bg-[#27272A] text-white text-xs transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                        <span>Konfirmasi & Bayar Sekarang</span>
                        <span>&rarr;</span>
                    </button>

                </div>
            </div>

        </div>
    </form>
</div>

<script>
    const subtotal = {{ (float) $totals['items_total'] }};

    function toggleManualAddress(isManual) {
        const manualFields = document.getElementById('manual-address-fields');
        if (isManual) {
            manualFields.classList.remove('hidden');
        } else {
            manualFields.classList.add('hidden');
        }
    }

    function selectAddress(radio) {
        document.querySelectorAll('.address-card').forEach(c => {
            c.classList.remove('border-[#111111]', 'bg-[#F7F6F3]');
            c.classList.add('border-[#EAEAEA]');
        });
        const parentCard = radio.closest('.address-card');
        if (parentCard) {
            parentCard.classList.add('border-[#111111]', 'bg-[#F7F6F3]');
            parentCard.classList.remove('border-[#EAEAEA]');
        }
    }

    function selectCourier(radio) {
        document.querySelectorAll('.courier-card').forEach(c => {
            c.classList.remove('border-[#111111]', 'bg-[#F7F6F3]');
            c.classList.add('border-[#EAEAEA]');
        });
        const parentCard = radio.closest('.courier-card');
        parentCard.classList.add('border-[#111111]', 'bg-[#F7F6F3]');
        parentCard.classList.remove('border-[#EAEAEA]');

        const shippingCost = parseFloat(radio.getAttribute('data-cost'));
        const grandTotal = subtotal + shippingCost;

        document.getElementById('summary-shipping').textContent = 'Rp ' + shippingCost.toLocaleString('id-ID');
        document.getElementById('summary-grand-total').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    function selectPayment(radio) {
        document.querySelectorAll('.payment-card').forEach(c => {
            c.classList.remove('border-[#111111]', 'bg-[#F7F6F3]');
            c.classList.add('border-[#EAEAEA]', 'bg-white');
        });
        const parentCard = radio.closest('.payment-card');
        parentCard.classList.add('border-[#111111]', 'bg-[#F7F6F3]');
        parentCard.classList.remove('border-[#EAEAEA]', 'bg-white');
    }
</script>
@endsection
