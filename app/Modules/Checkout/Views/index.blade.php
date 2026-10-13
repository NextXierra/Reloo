@extends('layouts.store')

@section('title', 'Checkout Pesanan - Reloo Marketplace')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Checkout Pesanan</h1>
        <p class="text-xs text-slate-500">Lengkapi alamat pengiriman dan pilih metode pembayaran</p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- 1. Alamat Pengiriman -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <span class="w-7 h-7 rounded-full bg-indigo-600 text-white font-black text-xs flex items-center justify-center">1</span>
                        <h2 class="text-base font-black text-slate-900">Alamat Pengiriman Paket</h2>
                    </div>

                    @if($addresses->isNotEmpty())
                        <div class="space-y-3 mb-6">
                            <span class="block text-xs font-bold text-slate-700">Pilih dari Alamat Tersimpan:</span>
                            @foreach($addresses as $addr)
                                <label class="block p-4 rounded-2xl border cursor-pointer transition {{ $addr->is_default ? 'border-indigo-600 bg-indigo-50/20 ring-1 ring-indigo-600/20' : 'border-slate-200 hover:border-slate-300' }}">
                                    <div class="flex items-start gap-3">
                                        <input type="radio" name="address_id" value="{{ $addr->id }}" {{ $addr->is_default ? 'checked' : '' }} onchange="toggleManualAddress(false)" class="mt-1 text-indigo-600 focus:ring-indigo-500">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-bold text-xs text-slate-900">{{ $addr->recipient_name }}</span>
                                                <span class="text-xs text-slate-500">({{ $addr->phone_number }})</span>
                                                @if($addr->is_default)
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-indigo-100 text-indigo-700">Utama</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-slate-600 leading-relaxed">
                                                {{ $addr->address_line }}, {{ $addr->district_name ? $addr->district_name . ', ' : '' }}{{ $addr->city_name }}, {{ $addr->province_name }} {{ $addr->postal_code }}
                                            </p>
                                        </div>
                                    </div>
                                </label>
                            @endforeach

                            <label class="block p-4 rounded-2xl border border-slate-200 hover:border-slate-300 cursor-pointer transition">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="address_id" value="" id="use-manual-address" onchange="toggleManualAddress(true)" class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-xs font-bold text-slate-700">+ Masukkan Alamat Pengiriman Baru Lainnya</span>
                                </div>
                            </label>
                        </div>
                    @endif

                    <!-- Manual Address Inputs -->
                    <div id="manual-address-fields" class="{{ $addresses->isNotEmpty() ? 'hidden' : '' }} grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Penerima</label>
                            <input type="text" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}" placeholder="Nama penerima paket" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Telepon / WhatsApp</label>
                            <input type="tel" name="phone_number" value="{{ old('phone_number', auth()->user()->phone) }}" placeholder="08xxxxxxxxxx" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Jalan & Nomor Rumah</label>
                            <textarea name="address_line" rows="2" placeholder="Jl. Sudirman Kav. 52, RT 01/RW 03" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">{{ old('address_line') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Provinsi</label>
                            <input type="text" name="province_name" value="{{ old('province_name') }}" placeholder="DKI Jakarta" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kota / Kabupaten</label>
                            <input type="text" name="city_name" value="{{ old('city_name') }}" placeholder="Jakarta Selatan" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kecamatan (Opsional)</label>
                            <input type="text" name="district_name" value="{{ old('district_name') }}" placeholder="Kebayoran Baru" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kode Pos</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}" placeholder="12190" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                        </div>
                        <div class="md:col-span-2 flex items-center gap-2 pt-1">
                            <input type="checkbox" id="save_address" name="save_address" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="save_address" class="text-xs font-medium text-slate-700 cursor-pointer">
                                Simpan alamat ini ke buku alamat saya
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 2. Pilihan Kurir Pengiriman -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-full bg-indigo-600 text-white font-black text-xs flex items-center justify-center">2</span>
                            <h2 class="text-base font-black text-slate-900">Metode Pengiriman</h2>
                        </div>
                        <span class="text-xs text-slate-500">
                            Total Berat: <strong>{{ number_format($totals['total_weight'], 0, ',', '.') }} gram</strong> ({{ ceil($totals['total_weight'] / 1000) }} kg)
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($couriers as $code => $c)
                            <label class="courier-card p-4 rounded-2xl border cursor-pointer transition flex flex-col justify-between gap-3 {{ $loop->first ? 'border-indigo-600 bg-indigo-50/20 ring-1 ring-indigo-600/20' : 'border-slate-200 hover:border-slate-300' }}">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="courier_code" value="{{ $code }}" {{ $loop->first ? 'checked' : '' }} data-cost="{{ $c['cost'] }}" onchange="selectCourier(this)" class="text-indigo-600 focus:ring-indigo-500">
                                        <div>
                                            <span class="font-bold text-xs text-slate-900 block leading-tight">{{ $c['name'] }}</span>
                                            <span class="text-[11px] text-slate-500 block">{{ $c['service'] }}</span>
                                        </div>
                                    </div>
                                    <span class="text-xs font-black text-indigo-600">{{ $c['formatted_cost'] }}</span>
                                </div>
                                <span class="text-[10px] text-slate-400 font-medium">Estimasi tiba: {{ $c['etd'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 3. Metode Pembayaran -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <span class="w-7 h-7 rounded-full bg-indigo-600 text-white font-black text-xs flex items-center justify-center">3</span>
                        <h2 class="text-base font-black text-slate-900">Pilihan Pembayaran</h2>
                    </div>

                    <div class="space-y-4">
                        <!-- Option QRISLY -->
                        <label class="payment-card block p-5 rounded-2xl border border-indigo-600 bg-indigo-50/20 ring-1 ring-indigo-600/20 cursor-pointer transition">
                            <div class="flex items-start gap-4">
                                <input type="radio" name="payment_gateway" value="qrisly" checked onchange="selectPayment(this)" class="mt-1 text-indigo-600 focus:ring-indigo-500">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-xs text-slate-900">Pembayaran QRIS Instan (RajaOngkir Qrisly)</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-emerald-100 text-emerald-800">Verifikasi Otomatis</span>
                                    </div>
                                    <p class="text-xs text-slate-600 leading-relaxed mb-3">
                                        Pindai kode QRIS menggunakan mobile banking (BCA, Mandiri, BRI, BNI) atau dompet digital (GoPay, OVO, DANA, ShopeePay).
                                    </p>
                                    <div class="flex flex-wrap gap-2 text-[10px] font-bold text-slate-500">
                                        <span class="px-2 py-1 bg-white border border-slate-200 rounded">BCA</span>
                                        <span class="px-2 py-1 bg-white border border-slate-200 rounded">Mandiri</span>
                                        <span class="px-2 py-1 bg-white border border-slate-200 rounded">GoPay</span>
                                        <span class="px-2 py-1 bg-white border border-slate-200 rounded">OVO</span>
                                        <span class="px-2 py-1 bg-white border border-slate-200 rounded">DANA</span>
                                        <span class="px-2 py-1 bg-white border border-slate-200 rounded">ShopeePay</span>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Option Manual Transfer -->
                        <label class="payment-card block p-5 rounded-2xl border border-slate-200 hover:border-slate-300 cursor-pointer transition">
                            <div class="flex items-start gap-4">
                                <input type="radio" name="payment_gateway" value="manual_transfer" onchange="selectPayment(this)" class="mt-1 text-indigo-600 focus:ring-indigo-500">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-xs text-slate-900">Transfer Bank Manual (BCA / Mandiri)</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-amber-100 text-amber-800">Verifikasi Admin</span>
                                    </div>
                                    <p class="text-xs text-slate-600 leading-relaxed">
                                        Transfer langsung ke rekening resmi toko Reloo, kemudian unggah foto struk/bukti transfer Anda.
                                    </p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 4. Catatan Pesanan -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                    <label class="block text-xs font-bold text-slate-900 mb-2">Catatan Tambahan untuk Penjual (Opsional)</label>
                    <textarea name="customer_note" rows="2" placeholder="Contoh: Tolong bungkus bubble wrap lebih tebal ya min..." class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600"></textarea>
                </div>

            </div>

            <!-- Right: Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm sticky top-28 space-y-6">
                    <h2 class="text-base font-black text-slate-900 pb-4 border-b border-slate-100">Ringkasan Pembayaran</h2>

                    <!-- Items Mini List -->
                    <div class="space-y-3 max-h-56 overflow-y-auto pr-1">
                        @foreach($items as $item)
                            <div class="flex items-center gap-3 text-xs">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200">
                                    @if($item->product && $item->product->primaryImage)
                                        <img src="{{ $item->product->primaryImage->url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-slate-900 truncate">{{ $item->product ? $item->product->name : '' }}</h4>
                                    <span class="text-slate-400 text-[11px]">{{ $item->quantity }} x Rp {{ number_format($item->product ? $item->product->price : 0, 0, ',', '.') }}</span>
                                </div>
                                <span class="font-bold text-slate-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Breakdown Calculation -->
                    @php
                        $firstCourier = reset($couriers);
                        $defaultShippingCost = $firstCourier ? $firstCourier['cost'] : 0;
                        $initialGrandTotal = $totals['items_total'] + $defaultShippingCost;
                    @endphp
                    <div class="space-y-2.5 text-xs pt-4 border-t border-slate-100">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal Barang ({{ $totals['total_quantity'] }})</span>
                            <span class="font-bold text-slate-800">Rp {{ number_format($totals['items_total'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Ongkos Kirim Logistik</span>
                            <span id="summary-shipping" class="font-bold text-slate-800">Rp {{ number_format($defaultShippingCost, 0, ',', '.') }}</span>
                        </div>
                        <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                            <span class="text-sm font-black text-slate-900">Total Tagihan</span>
                            <span id="summary-grand-total" class="text-xl font-black text-indigo-600">
                                Rp {{ number_format($initialGrandTotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 rounded-xl font-black bg-indigo-600 hover:bg-indigo-500 text-white text-xs transition shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2">
                        Konfirmasi & Bayar Sekarang &rarr;
                    </button>

                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 text-[11px] text-slate-500 text-center leading-relaxed">
                        Barang diproses atomik dengan jaminan proteksi pembeli 100%.
                    </div>
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

    function selectCourier(radio) {
        document.querySelectorAll('.courier-card').forEach(c => {
            c.classList.remove('border-indigo-600', 'bg-indigo-50/20', 'ring-1', 'ring-indigo-600/20');
            c.classList.add('border-slate-200');
        });
        const parentCard = radio.closest('.courier-card');
        parentCard.classList.add('border-indigo-600', 'bg-indigo-50/20', 'ring-1', 'ring-indigo-600/20');
        parentCard.classList.remove('border-slate-200');

        const shippingCost = parseFloat(radio.getAttribute('data-cost'));
        const grandTotal = subtotal + shippingCost;

        document.getElementById('summary-shipping').textContent = 'Rp ' + shippingCost.toLocaleString('id-ID');
        document.getElementById('summary-grand-total').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    function selectPayment(radio) {
        document.querySelectorAll('.payment-card').forEach(c => {
            c.classList.remove('border-indigo-600', 'bg-indigo-50/20', 'ring-1', 'ring-indigo-600/20');
            c.classList.add('border-slate-200');
        });
        const parentCard = radio.closest('.payment-card');
        parentCard.classList.add('border-indigo-600', 'bg-indigo-50/20', 'ring-1', 'ring-indigo-600/20');
        parentCard.classList.remove('border-slate-200');
    }
</script>
@endsection
