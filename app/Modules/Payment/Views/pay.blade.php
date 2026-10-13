@extends('layouts.store')

@section('title', 'Selesaikan Pembayaran: ' . $order->order_number . ' - Reloo Marketplace')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8 text-center">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $order->payment->payment_status_badge['color'] }} mb-3">
            Status: {{ $order->payment->payment_status_badge['label'] }}
        </span>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Selesaikan Pembayaran Pesanan</h1>
        <p class="text-xs text-slate-500 font-mono mt-1">Invoice: {{ $order->order_number }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Left: Payment Action Box -->
        <div class="md:col-span-2 space-y-6">
            
            @if($order->payment->payment_gateway === 'qrisly')
                <!-- RajaOngkir Qrisly Dynamic QRIS -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6 text-center">
                    <div class="pb-4 border-b border-slate-100">
                        <span class="text-[10px] font-black uppercase tracking-wider text-indigo-600 block mb-1">RajaOngkir Qrisly Sandbox</span>
                        <h2 class="font-bold text-slate-900 text-base">Pindai QRIS untuk Membayar</h2>
                        <p class="text-xs text-slate-500">Mendukung BCA, Mandiri, BRI, BNI, GoPay, OVO, DANA, ShopeePay</p>
                    </div>

                    @if($order->payment->payment_status === 'paid')
                        <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs">
                            <strong class="block text-sm font-bold mb-1">Pembayaran Berhasil Diterima</strong>
                            Pesanan Anda telah lunas dan sedang diproses toko untuk pengiriman.
                        </div>
                    @else
                        <!-- QR Code Container -->
                        <div class="flex flex-col items-center justify-center space-y-4">
                            @if(!empty($qrisData['qr_image_url']))
                                <div class="p-4 bg-white rounded-3xl border-2 border-indigo-600 shadow-lg inline-block">
                                    <img src="{{ $qrisData['qr_image_url'] }}" alt="QRIS Reloo" class="w-64 h-64 object-contain mx-auto rounded-xl">
                                </div>
                            @endif

                            <div class="space-y-1">
                                <span class="text-xs text-slate-500 block">Total Tagihan Termasuk Kode Unik:</span>
                                <div class="text-2xl font-black text-indigo-600 font-mono">
                                    Rp {{ number_format($qrisData['amount'] ?? $order->grand_total, 0, ',', '.') }}
                                </div>
                                <span class="text-[10px] text-slate-400 block">Mohon transfer tepat sesuai nominal di atas</span>
                            </div>

                            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 text-[11px] text-slate-600 max-w-sm text-left space-y-1">
                                <span class="font-bold block text-slate-800">Petunjuk Pembayaran:</span>
                                <div>1. Buka aplikasi m-Banking atau e-Wallet pilihan Anda.</div>
                                <div>2. Pilih menu Scan / Pindai QRIS.</div>
                                <div>3. Arahkan kamera ke kode QR di atas.</div>
                                <div>4. Konfirmasi pembayaran. Halaman akan otomatis memperbarui status.</div>
                            </div>
                        </div>

                        <!-- Realtime Polling & Actions -->
                        <div class="space-y-3 pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-center gap-2 text-xs text-slate-500 font-medium">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                <span id="polling-status">Menunggu pembayaran terdeteksi...</span>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3">
                                <button type="button" onclick="checkPaymentStatusNow()" class="flex-1 py-3 px-4 rounded-xl font-bold bg-indigo-600 hover:bg-indigo-500 text-white text-xs transition shadow-md shadow-indigo-600/20">
                                    Cek Status Sekarang
                                </button>
                                
                                <form method="POST" action="{{ route('payment.mock.success', $order->order_number) }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-3 px-4 rounded-xl font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs transition border border-slate-200">
                                        Simulasi Sukses (Mode Sandbox)
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>

            @else
                <!-- Manual Bank Transfer -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
                    <div class="pb-4 border-b border-slate-100">
                        <h2 class="font-bold text-slate-900 text-sm">Transfer Bank Manual</h2>
                        <p class="text-[11px] text-slate-400">Transfer ke rekening resmi toko Reloo</p>
                    </div>

                    <!-- Bank Accounts -->
                    <div class="space-y-3">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider block">Bank BCA</span>
                                <span class="font-mono text-sm font-black text-slate-900">8830-1234-5678</span>
                                <span class="text-[11px] text-slate-500 block">a.n. PT Reloo Marketplace Indonesia</span>
                            </div>
                            <button type="button" onclick="navigator.clipboard.writeText('883012345678'); alert('Nomor rekening BCA disalin.');" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-700 hover:bg-white bg-white/50">
                                Salin
                            </button>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider block">Bank Mandiri</span>
                                <span class="font-mono text-sm font-black text-slate-900">137-00-9876543-2</span>
                                <span class="text-[11px] text-slate-500 block">a.n. PT Reloo Marketplace Indonesia</span>
                            </div>
                            <button type="button" onclick="navigator.clipboard.writeText('1370098765432'); alert('Nomor rekening Mandiri disalin.');" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-700 hover:bg-white bg-white/50">
                                Salin
                            </button>
                        </div>
                    </div>

                    <!-- Existing Proof Preview -->
                    @if($order->payment->payment_proof)
                        <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-indigo-900">Bukti Transfer Terunggah:</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $order->payment->payment_status_badge['color'] }}">
                                    {{ $order->payment->payment_status_badge['label'] }}
                                </span>
                            </div>
                            <div class="w-32 h-32 rounded-xl overflow-hidden border border-slate-200 bg-white">
                                <img src="{{ $order->payment->payment_proof_url }}" alt="Bukti Transfer" class="w-full h-full object-cover">
                            </div>
                        </div>
                    @endif

                    <!-- Upload Proof Form -->
                    @if($order->payment->payment_status !== 'paid')
                        <div class="pt-4 border-t border-slate-100">
                            <h3 class="text-xs font-bold text-slate-900 mb-2">Unggah Struk / Bukti Pembayaran</h3>
                            <form method="POST" action="{{ route('payment.manual.upload', $order->order_number) }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <input type="file" name="payment_proof" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white hover:file:bg-indigo-600 file:cursor-pointer cursor-pointer">
                                </div>
                                <button type="submit" class="w-full py-3.5 rounded-xl font-black bg-slate-900 hover:bg-indigo-600 text-white text-xs transition shadow-md">
                                    Kirim Bukti Pembayaran
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            @endif

        </div>

        <!-- Right: Invoice Breakdown -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-6 sticky top-28">
                <h3 class="font-bold text-slate-900 text-sm pb-4 border-b border-slate-100">Rincian Tagihan</h3>

                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Total Belanja</span>
                        <span class="font-bold text-slate-800">Rp {{ number_format($order->items_total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Ongkos Kirim ({{ $order->shipment ? $order->shipment->courier_name : 'Kurir' }})</span>
                        <span class="font-bold text-slate-800">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                        <span class="text-sm font-black text-slate-900">Total Harus Dibayar</span>
                        <span class="text-lg font-black text-indigo-600">
                            {{ $order->formatted_grand_total }}
                        </span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 space-y-2">
                    <a href="{{ route('order.show', $order->order_number) }}" class="w-full block text-center py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                        Lihat Detail Pesanan
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

@if($order->payment && $order->payment->payment_gateway === 'qrisly' && $order->payment->payment_status !== 'paid')
<script>
    const checkUrl = "{{ route('api.qris.check', $order->order_number) }}";
    const redirectUrl = "{{ route('order.show', $order->order_number) }}";

    async function checkPaymentStatusNow() {
        const statusEl = document.getElementById('polling-status');
        if (statusEl) statusEl.textContent = 'Memeriksa status pembayaran...';

        try {
            const res = await fetch(checkUrl, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();

            if (data.status === 'paid' || data.payment_status === 'paid') {
                if (statusEl) statusEl.textContent = 'Pembayaran terkonfirmasi! Mengalihkan...';
                window.location.href = redirectUrl;
            } else {
                if (statusEl) statusEl.textContent = 'Menunggu pembayaran terdeteksi...';
            }
        } catch (e) {
            if (statusEl) statusEl.textContent = 'Menunggu pembayaran terdeteksi...';
        }
    }

    // Auto-poll every 3.5 seconds
    setInterval(checkPaymentStatusNow, 3500);
</script>
@endif
@endsection
