@php
    $shipment = $order->shipment;
    $hasDeliveryOrder = $shipment && !empty($shipment->delivery_order_no);
    $pickupStatus = $shipment?->pickup_status;
@endphp

<div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA] space-y-4">
    <div class="flex items-center justify-between pb-3 border-b border-[#EAEAEA]">
        <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 text-[#111111]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
            </svg>
            <h3 class="font-mono text-xs uppercase tracking-wider text-[#111111]">Komerce Delivery Order</h3>
        </div>

        @if(!$hasDeliveryOrder)
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-[#F7F6F3] text-[#71717A] border border-[#EAEAEA]">
                Belum Disubmit
            </span>
        @elseif($pickupStatus === 'scheduled')
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-indigo-50 text-indigo-700 border border-indigo-200">
                Pickup Terjadwal
            </span>
        @elseif($shipment->shipping_status === 'in_transit')
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-blue-50 text-blue-700 border border-blue-200">
                Sedang Dikirim
            </span>
        @elseif($shipment->shipping_status === 'delivered')
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                Terkirim
            </span>
        @elseif($pickupStatus === 'cancelled' || $shipment->shipping_status === 'cancelled')
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-rose-50 text-rose-700 border border-rose-200">
                Dibatalkan
            </span>
        @else
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono uppercase bg-amber-50 text-amber-700 border border-amber-200">
                Order Dibuat
            </span>
        @endif
    </div>

    @if(!$hasDeliveryOrder)
        <!-- Action 1: Create Order in Komerce -->
        <div class="text-xs text-[#52525B] leading-relaxed">
            Pesanan ini belum disinkronkan ke gateway Komerce Delivery. Tekan tombol di bawah untuk otomatis membuat order pengiriman, meneruskan data ekspedisi, dan menerima nomor delivery Komerce.
        </div>

        <form method="POST" action="{{ route('admin.shipping.store', $order->id) }}">
            @csrf
            <button type="submit" class="w-full py-2.5 rounded-[5px] bg-[#111111] hover:bg-[#27272A] text-white font-medium text-xs transition-colors flex items-center justify-center space-x-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Submit ke Komerce Delivery</span>
            </button>
        </form>
    @else
        <!-- Delivery Order Details -->
        <div class="bg-[#F7F6F3] p-3 rounded-[5px] border border-[#EAEAEA] text-xs space-y-1.5 text-[#52525B]">
            <div class="flex justify-between items-center">
                <span class="text-[#71717A]">No. Delivery Komerce:</span>
                <span class="font-mono font-semibold text-[#111111]">{{ $shipment->delivery_order_no }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-[#71717A]">Kurir / Layanan:</span>
                <span class="font-medium text-[#111111]">{{ strtoupper($shipment->courier_code) }} ({{ $shipment->courier_service }})</span>
            </div>
            @if($shipment->tracking_number)
                <div class="flex justify-between items-center pt-1 border-t border-[#EAEAEA]">
                    <span class="text-[#71717A]">No. Resi (AWB):</span>
                    <span class="font-mono font-bold text-emerald-700">{{ $shipment->tracking_number }}</span>
                </div>
            @endif
            @if($shipment->pickup_time)
                <div class="flex justify-between items-center pt-1 border-t border-[#EAEAEA]">
                    <span class="text-[#71717A]">Jadwal Pickup:</span>
                    <span class="font-mono text-[#111111]">{{ $shipment->pickup_time->format('d/m/Y H:i') }} ({{ $shipment->pickup_vehicle ?: 'Motor' }})</span>
                </div>
            @endif
        </div>

        <!-- Action 2: Schedule Pickup (if not yet scheduled) -->
        @if($pickupStatus !== 'scheduled' && $shipment->shipping_status !== 'delivered' && $shipment->shipping_status !== 'cancelled')
            <form method="POST" action="{{ route('admin.shipping.pickup', $order->id) }}" class="p-3 bg-white rounded-[5px] border border-[#EAEAEA] space-y-3">
                @csrf
                <div class="text-xs font-semibold text-[#111111]">Jadwalkan Penjemputan (Pickup)</div>

                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <label class="block text-[11px] font-mono text-[#71717A] mb-1">Tanggal Pickup</label>
                        <input type="date" name="pickup_date" value="{{ date('Y-m-d') }}" required class="w-full px-2 py-1.5 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[4px] focus:bg-white focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-mono text-[#71717A] mb-1">Waktu</label>
                        <input type="time" name="pickup_time" value="14:00" required class="w-full px-2 py-1.5 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[4px] focus:bg-white focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-mono text-[#71717A] mb-1">Kendaraan Penjemput</label>
                    <select name="pickup_vehicle" class="w-full px-2 py-1.5 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[4px] focus:bg-white focus:outline-none">
                        <option value="Motor">Motor (Paket Kecil / Sedang)</option>
                        <option value="Mobil">Mobil (Paket Banyak / Sedang)</option>
                        <option value="Truk">Truk (Kargo Besar)</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-2 rounded-[4px] bg-[#111111] hover:bg-[#27272A] text-white font-medium text-xs transition-colors">
                    Konfirmasi Request Pickup
                </button>
            </form>
        @endif

        <!-- Action 3: Thermal Label & Tracking -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
            <a href="{{ route('admin.shipping.label', $order->id) }}" target="_blank" class="py-2 px-3 text-center rounded-[5px] border border-[#EAEAEA] bg-[#F7F6F3] hover:bg-white text-[#111111] font-medium text-xs transition-colors flex items-center justify-center space-x-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Label Thermal</span>
            </a>

            @if($shipment->tracking_number)
                <button type="button" onclick="loadLiveTracking('{{ route('admin.shipping.track', $order->id) }}')" class="py-2 px-3 text-center rounded-[5px] border border-[#EAEAEA] bg-[#F7F6F3] hover:bg-white text-[#111111] font-medium text-xs transition-colors flex items-center justify-center space-x-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <span>Lacak Resi</span>
                </button>
            @endif
        </div>

        <!-- Tracking Modal / Container -->
        <div id="trackingContainer" class="hidden p-3 bg-[#F7F6F3] rounded-[5px] border border-[#EAEAEA] text-xs space-y-2">
            <div class="flex justify-between items-center font-mono text-[11px] text-[#71717A] border-b border-[#EAEAEA] pb-1">
                <span>RIWAYAT PELACAKAN RESI</span>
                <button type="button" onclick="document.getElementById('trackingContainer').classList.add('hidden')" class="hover:text-[#111111]">&times; Tutup</button>
            </div>
            <div id="trackingContent" class="space-y-2 pt-1 text-[#52525B]">
                Memuat data pelacakan...
            </div>
        </div>

        <!-- Cancel Komerce Delivery Order (if not finished) -->
        @if($shipment->shipping_status !== 'delivered' && $shipment->shipping_status !== 'cancelled' && $pickupStatus !== 'cancelled')
            <form method="POST" action="{{ route('admin.shipping.cancel', $order->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan pengiriman Komerce ini?')" class="pt-2 border-t border-[#EAEAEA]">
                @csrf
                <button type="submit" class="w-full py-1.5 text-center rounded-[4px] text-[11px] text-[#8C2220] hover:bg-[#FDEBEC] border border-transparent hover:border-[#F8C8CA] transition-colors">
                    Batalkan Delivery Order Komerce
                </button>
            </form>
        @endif
    @endif
</div>

<script>
function loadLiveTracking(url) {
    const container = document.getElementById('trackingContainer');
    const content = document.getElementById('trackingContent');
    container.classList.remove('hidden');
    content.innerHTML = '<span class="italic text-[#71717A]">Sedang menghubungi gateway kurir...</span>';

    fetch(url)
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success' && res.data) {
                let html = '<div class="space-y-2">';
                const timeline = res.data.manifest || res.data.history || res.data.timeline || [];
                if (Array.isArray(timeline) && timeline.length > 0) {
                    timeline.forEach(step => {
                        const date = step.manifest_date || step.date || step.created_at || '';
                        const desc = step.manifest_description || step.description || step.note || '';
                        const city = step.city_name || '';
                        html += `
                            <div class="border-l-2 border-[#111111] pl-2 text-[11px]">
                                <div class="font-mono text-[#71717A]">${date} ${city ? '(' + city + ')' : ''}</div>
                                <div class="text-[#111111] font-medium">${desc}</div>
                            </div>
                        `;
                    });
                } else {
                    html += '<div class="text-xs text-[#71717A]">Belum ada riwayat pergerakan paket tercatat di gateway kurir.</div>';
                }
                html += '</div>';
                content.innerHTML = html;
            } else {
                content.innerHTML = `<span class="text-[#8C2220] text-xs">${res.message || 'Resi belum tercatat di sistem gateway.'}</span>`;
            }
        })
        .catch(err => {
            content.innerHTML = '<span class="text-[#8C2220] text-xs">Gagal memuat status pelacakan resi.</span>';
        });
}
</script>
