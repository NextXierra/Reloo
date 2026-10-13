@extends('layouts.store')

@section('title', 'Selesaikan Pembayaran: ' . $order->order_number . ' · Reloo')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <div class="mb-8 text-center pb-6 border-b border-[#EAEAEA]">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-mono uppercase border {{ $order->payment->payment_status_badge['color'] }} mb-3">
            STATUS: {{ $order->payment->payment_status_badge['label'] }}
        </span>
        <h1 class="font-serif text-3xl sm:text-4xl text-[#111111] tracking-tight">Selesaikan Pembayaran Pesanan</h1>
        <p class="font-mono text-xs text-[#71717A] mt-1">INVOICE: {{ $order->order_number }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">

        <!-- Left: Payment Action Box (2 Cols) -->
        <div class="md:col-span-2 space-y-6">

            @if($order->payment->payment_gateway === 'qrisly')
                <!-- RajaOngkir Qrisly Dynamic QRIS -->
                <div class="bg-white rounded-[6px] p-6 sm:p-8 border border-[#EAEAEA] space-y-6 text-center">
                    <div class="pb-4 border-b border-[#EAEAEA]">
                        <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-1">GERBANG PEMBAYARAN QRIS NASIONAL</span>
                        <h2 class="font-serif text-xl text-[#111111]">Pindai Kode QRIS untuk Membayar</h2>
                        <p class="text-xs text-[#52525B] mt-0.5">Mendukung BCA, Mandiri, BRI, BNI, GoPay, OVO, DANA, dan ShopeePay</p>
                    </div>

                    @if($order->payment->payment_status === 'paid')
                        <div class="p-5 rounded-[5px] bg-[#EDF3EC] border border-[#D1E2CF] text-[#26542A] text-xs font-mono">
                            <strong class="block text-sm font-bold mb-1">[LUNAS · PEMBAYARAN TERKONFIRMASI]</strong>
                            Pesanan Anda telah berhasil diverifikasi oleh sistem dan sedang disiapkan oleh kurator untuk pengiriman.
                        </div>
                    @else
                        <!-- QR Code Container -->
                        <div class="flex flex-col items-center justify-center space-y-4">
                            @if(!empty($qrisData['qr_image_url']))
                                <div class="p-4 bg-white rounded-[6px] border border-[#111111] inline-block">
                                    <img src="{{ $qrisData['qr_image_url'] }}" alt="QRIS Reloo" class="w-60 h-60 object-contain mx-auto">
                                </div>
                            @endif

                            <div class="space-y-1.5">
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-[4px] bg-[#F7F6F3] border border-[#EAEAEA] text-[#52525B] text-xs font-mono">
                                    <span>ID REFERENSI:</span>
                                    <span class="text-[#111111] font-semibold">#{{ $order->payment->transaction_id ?? $qrisData['history_id'] ?? '-' }}</span>
                                </div>
                                <span class="text-xs text-[#71717A] block">Total Nominal Transfer:</span>
                                <div class="font-mono text-3xl font-bold text-[#111111]">
                                    Rp {{ number_format($qrisData['amount'] ?? $order->grand_total, 0, ',', '.') }}
                                </div>
                                @if(!empty($qrisData['expiry_time']))
                                    <div class="text-[11px] font-mono text-[#785100] bg-[#FBF3DB] border border-[#F5E5B5] px-2.5 py-0.5 rounded-[4px] inline-block">
                                        Batas Waktu: {{ $qrisData['expiry_time'] }}
                                    </div>
                                @endif
                                <span class="font-mono text-[10px] text-[#71717A] block">Transfer tepat hingga 3 digit terakhir untuk verifikasi instan</span>
                            </div>

                            <div class="p-4 bg-[#F7F6F3] rounded-[5px] border border-[#EAEAEA] text-xs text-[#52525B] max-w-sm text-left space-y-1">
                                <span class="font-medium text-[#111111] block mb-1">Panduan Pembayaran:</span>
                                <div>1. Buka aplikasi m-Banking atau dompet digital Anda.</div>
                                <div>2. Pilih menu Bayar / Scan QRIS.</div>
                                <div>3. Arahkan kamera ke kode QR di atas.</div>
                                <div>4. Konfirmasi pembayaran. Halaman akan menyinkronkan status otomatis.</div>
                            </div>
                        </div>

                        <!-- Realtime Polling & Actions -->
                        <div class="space-y-3 pt-4 border-t border-[#EAEAEA]">
                            <div class="flex items-center justify-center gap-2 text-xs font-mono text-[#71717A]">
                                <span class="w-2 h-2 rounded-full bg-[#111111] animate-pulse"></span>
                                <span id="polling-status">Menunggu pembayaran terdeteksi di jaringan...</span>
                            </div>

                            <div>
                                <button type="button" onclick="checkPaymentStatusNow()" class="w-full sm:w-auto px-5 py-2.5 rounded-[5px] font-medium bg-[#111111] hover:bg-[#27272A] text-white text-xs transition-colors whitespace-nowrap">
                                    Cek Status Sekarang
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

            @else
                <!-- Manual Bank Transfer -->
                <div class="bg-white rounded-[6px] p-6 sm:p-8 border border-[#EAEAEA] space-y-6">
                    <div class="pb-3 border-b border-[#EAEAEA]">
                        <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-1">VERIFIKASI MANUAL</span>
                        <h2 class="font-serif text-xl text-[#111111]">Transfer Rekening Bank Resmi</h2>
                        <p class="text-xs text-[#52525B]">Transfer langsung ke salah satu rekening terverifikasi PT Reloo Marketplace Indonesia.</p>
                    </div>

                    <!-- Bank Accounts -->
                    <div class="space-y-3">
                        <div class="p-4 rounded-[5px] bg-[#F7F6F3] border border-[#EAEAEA] flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-mono uppercase text-[#71717A] tracking-wider block">BANK BCA</span>
                                <span class="font-mono text-base font-semibold text-[#111111]">8830-1234-5678</span>
                                <span class="text-xs text-[#52525B] block">a.n. PT Reloo Marketplace Indonesia</span>
                            </div>
                            <button type="button" onclick="navigator.clipboard.writeText('883012345678'); alert('Nomor rekening BCA disalin.');" class="px-3 py-1.5 rounded-[4px] border border-[#EAEAEA] text-xs font-mono text-[#111111] hover:bg-white bg-white/80 transition-colors">
                                Salin
                            </button>
                        </div>

                        <div class="p-4 rounded-[5px] bg-[#F7F6F3] border border-[#EAEAEA] flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-mono uppercase text-[#71717A] tracking-wider block">BANK MANDIRI</span>
                                <span class="font-mono text-base font-semibold text-[#111111]">137-00-9876543-2</span>
                                <span class="text-xs text-[#52525B] block">a.n. PT Reloo Marketplace Indonesia</span>
                            </div>
                            <button type="button" onclick="navigator.clipboard.writeText('1370098765432'); alert('Nomor rekening Mandiri disalin.');" class="px-3 py-1.5 rounded-[4px] border border-[#EAEAEA] text-xs font-mono text-[#111111] hover:bg-white bg-white/80 transition-colors">
                                Salin
                            </button>
                        </div>
                    </div>

                    <!-- Existing Proof Preview -->
                    @if($order->payment->payment_proof)
                        <div class="p-4 rounded-[5px] bg-[#F7F6F3] border border-[#EAEAEA] space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-xs text-[#111111]">BUKTI TRANSFER TERUNGGAH:</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono uppercase border {{ $order->payment->payment_status_badge['color'] }}">
                                    {{ $order->payment->payment_status_badge['label'] }}
                                </span>
                            </div>
                            <div class="w-32 h-32 rounded-[4px] overflow-hidden border border-[#EAEAEA] bg-white p-1">
                                <img src="{{ $order->payment->payment_proof_url }}" alt="Bukti Transfer" class="w-full h-full object-cover rounded-[2px]">
                            </div>
                        </div>
                    @endif

                    <!-- Upload Proof Form -->
                    @if($order->payment->payment_status !== 'paid')
                        <div class="pt-4 border-t border-[#EAEAEA]">
                            <h3 class="font-mono text-xs uppercase tracking-wider text-[#111111] mb-2">Unggah Struk Bukti Transfer</h3>
                            <form method="POST" action="{{ route('payment.manual.upload', $order->order_number) }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <input type="file" name="payment_proof" accept="image/*" required class="w-full text-xs text-[#52525B] file:mr-3 file:py-2 file:px-3 file:rounded-[4px] file:border file:border-[#EAEAEA] file:text-xs file:font-medium file:bg-[#F7F6F3] file:text-[#111111] hover:file:bg-[#EAEAEA] file:cursor-pointer cursor-pointer">
                                </div>
                                <button type="submit" class="w-full py-2.5 rounded-[5px] font-medium bg-[#111111] hover:bg-[#27272A] text-white text-xs transition-colors whitespace-nowrap">
                                    Kirim Bukti Pembayaran
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            @endif

        </div>

        <!-- Right: Invoice Breakdown (1 Col) -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA] space-y-6 sticky top-24">
                <div class="pb-3 border-b border-[#EAEAEA]">
                    <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-0.5">RINGKASAN PESANAN</span>
                    <h3 class="font-serif text-xl text-[#111111]">Rincian Tagihan</h3>
                </div>

                <div class="space-y-2 text-xs">
                    <div class="flex justify-between text-[#52525B]">
                        <span>Total Belanja</span>
                        <span class="font-mono font-medium text-[#111111]">Rp {{ number_format($order->items_total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-[#52525B]">
                        <span>Ongkos Kirim ({{ $order->shipment ? $order->shipment->courier_name : 'Kurir' }})</span>
                        <span class="font-mono font-medium text-[#111111]">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-3 border-t border-[#EAEAEA] flex justify-between items-baseline">
                        <span class="text-xs font-medium text-[#111111]">Total Harus Dibayar</span>
                        <span class="font-mono text-xl font-bold text-[#111111]">
                            {{ $order->formatted_grand_total }}
                        </span>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#EAEAEA] space-y-2">
                    <a href="{{ route('order.show', $order->order_number) }}" class="w-full inline-flex items-center justify-center py-2.5 rounded-[5px] border border-[#EAEAEA] text-[#111111] hover:bg-[#F7F6F3] text-xs font-medium transition-colors">
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
                if (statusEl) statusEl.textContent = 'Menunggu pembayaran terdeteksi di jaringan...';
            }
        } catch (e) {
            if (statusEl) statusEl.textContent = 'Menunggu pembayaran terdeteksi di jaringan...';
        }
    }

    setInterval(checkPaymentStatusNow, 3500);
</script>
@endif
@endsection
