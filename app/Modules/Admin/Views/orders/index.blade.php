@extends('admin::layouts.admin')

@section('title', 'Manajemen Pesanan Masuk')

@section('content')
<div class="space-y-8">
    
    <!-- Top Action & Filter Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-xl font-black text-slate-900 mb-1">Daftar Transaksi Pesanan</h2>
            <p class="text-xs text-slate-500">Kelola pemrosesan pesanan, verifikasi transfer manual, dan input nomor resi kurir.</p>
        </div>

        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari No. Invoice..." class="px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
            <button type="submit" class="px-4 py-2.5 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition">
                Cari
            </button>
        </form>
    </div>

    <!-- Status Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 text-xs font-bold">
        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-xl transition flex-shrink-0 {{ !request('status') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Semua Pesanan
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending_payment']) }}" class="px-4 py-2 rounded-xl transition flex-shrink-0 {{ request('status') === 'pending_payment' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Belum Bayar
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'paid']) }}" class="px-4 py-2 rounded-xl transition flex-shrink-0 {{ request('status') === 'paid' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Sudah Bayar
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="px-4 py-2 rounded-xl transition flex-shrink-0 {{ request('status') === 'processing' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Diproses
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="px-4 py-2 rounded-xl transition flex-shrink-0 {{ request('status') === 'shipped' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Dikirim
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="px-4 py-2 rounded-xl transition flex-shrink-0 {{ request('status') === 'completed' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Selesai
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="px-4 py-2 rounded-xl transition flex-shrink-0 {{ request('status') === 'cancelled' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Dibatalkan
        </a>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">Total Transaksi ({{ $orders->total() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wider font-bold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Invoice</th>
                        <th class="py-4 px-6">Pelanggan</th>
                        <th class="py-4 px-6">Tanggal</th>
                        <th class="py-4 px-6">Total Tagihan</th>
                        <th class="py-4 px-6">Status Pesanan</th>
                        <th class="py-4 px-6">Pembayaran</th>
                        <th class="py-4 px-6">Pengiriman</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-mono font-bold text-slate-900">
                                {{ $order->order_number }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-800 block leading-tight">{{ $order->user ? $order->user->name : '-' }}</span>
                                <span class="text-[11px] text-slate-400">{{ $order->user ? $order->user->email : '' }}</span>
                            </td>
                            <td class="py-4 px-6 text-slate-400">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-4 px-6 font-black text-slate-900">
                                {{ $order->formatted_grand_total }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $order->status_badge['color'] }}">
                                    {{ $order->status_badge['label'] }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @if($order->payment)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $order->payment->payment_status_badge['color'] }}">
                                        {{ $order->payment->payment_status_badge['label'] }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($order->shipment && $order->shipment->tracking_number)
                                    <span class="font-mono text-indigo-600 font-bold block">{{ $order->shipment->tracking_number }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $order->shipment->courier_name }}</span>
                                @else
                                    <span class="text-slate-400 italic">Belum ada resi</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3.5 py-1.5 rounded-xl bg-slate-900 hover:bg-indigo-600 text-white font-bold text-xs transition">
                                    Fulfillment &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">Tidak ada pesanan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
