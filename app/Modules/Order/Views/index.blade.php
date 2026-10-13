@extends('layouts.store')

@section('title', 'Riwayat Pesanan Saya - Reloo Marketplace')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Riwayat Pesanan Saya</h1>
        <p class="text-xs text-slate-500">Pantau status transaksi, nomor resi kurir, dan riwayat belanja Anda</p>
    </div>

    <!-- Status Filter Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 text-xs font-bold">
        <a href="{{ route('order.index') }}" class="px-4 py-2 rounded-xl transition flex-shrink-0 {{ !request('status') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Semua Pesanan
        </a>
        <a href="{{ route('order.index', ['status' => 'pending_payment']) }}" class="px-4 py-2 rounded-xl transition flex-shrink-0 {{ request('status') === 'pending_payment' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Belum Bayar
        </a>
        <a href="{{ route('order.index', ['status' => 'paid']) }}" class="px-4 py-2 rounded-xl transition flex-shrink-0 {{ request('status') === 'paid' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Sudah Dibayar
        </a>
        <a href="{{ route('order.index', ['status' => 'processing']) }}" class="px-4 py-2 rounded-xl transition flex-shrink-0 {{ request('status') === 'processing' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Diproses
        </a>
        <a href="{{ route('order.index', ['status' => 'shipped']) }}" class="px-4 py-2 rounded-xl transition flex-shrink-0 {{ request('status') === 'shipped' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Dikirim
        </a>
        <a href="{{ route('order.index', ['status' => 'completed']) }}" class="px-4 py-2 rounded-xl transition flex-shrink-0 {{ request('status') === 'completed' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Selesai
        </a>
    </div>

    <!-- Orders List -->
    @if($orders->isEmpty())
        <div class="bg-white rounded-3xl p-12 border border-slate-200 text-center max-w-md mx-auto shadow-sm">
            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4 text-xs font-bold uppercase">
                KOSONG
            </div>
            <h3 class="text-base font-bold text-slate-900 mb-1">Belum Ada Pesanan</h3>
            <p class="text-xs text-slate-500 mb-6">Anda belum memiliki riwayat pesanan dengan status yang dipilih.</p>
            <a href="{{ route('catalog.index') }}" class="px-6 py-2.5 rounded-xl font-bold bg-indigo-600 text-white text-xs hover:bg-indigo-500 transition shadow-sm">
                Mulai Berbelanja &rarr;
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-xs font-bold text-slate-900">{{ $order->order_number }}</span>
                            <span class="text-xs text-slate-400">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $order->status_badge['color'] }}">
                                {{ $order->status_badge['label'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Items Preview -->
                    <div class="space-y-2">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between text-xs py-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-800">{{ $item->product_name }}</span>
                                    <span class="text-slate-400">x{{ $item->quantity }}</span>
                                </div>
                                <span class="font-bold text-slate-900">{{ $item->formatted_subtotal }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Footer Details & Actions -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-4 border-t border-slate-100 gap-4">
                        <div>
                            <span class="text-[11px] text-slate-500 block">Total Tagihan:</span>
                            <span class="text-base font-black text-indigo-600">{{ $order->formatted_grand_total }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($order->status === 'pending_payment')
                                <a href="{{ route('payment.pay', $order->order_number) }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition shadow-sm">
                                    Bayar Sekarang &rarr;
                                </a>
                            @endif
                            <a href="{{ route('order.show', $order->order_number) }}" class="px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold transition">
                                Detail Pesanan
                            </a>
                            <a href="{{ route('order.invoice', $order->order_number) }}" target="_blank" class="px-3 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold transition" title="Cetak Invoice">
                                Invoice
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
