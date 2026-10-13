@extends('layouts.store')

@section('title', 'Keranjang Belanja - Reloo Marketplace')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Keranjang Belanja</h1>
        <p class="text-xs text-slate-500">Periksa kembali barang pilihan sebelum melanjutkan ke pembayaran</p>
    </div>

    @if($items->isEmpty())
        <div class="bg-white rounded-3xl p-12 border border-slate-200 text-center max-w-lg mx-auto shadow-sm">
            <div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4 text-xs font-black uppercase">
                Cart
            </div>
            <h3 class="text-base font-bold text-slate-900 mb-1">Keranjang Belanja Masih Kosong</h3>
            <p class="text-xs text-slate-500 mb-6">Anda belum menambahkan produk apa pun ke keranjang. Jelajahi katalog kami dan temukan barang favorit Anda!</p>
            <a href="{{ route('catalog.index') }}" class="px-6 py-3 rounded-xl font-bold bg-indigo-600 text-white text-xs hover:bg-indigo-500 transition shadow-md shadow-indigo-600/20">
                Mulai Belanja &rarr;
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Items List -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <span class="text-xs font-bold text-slate-800">Daftar Barang ({{ $totals['total_quantity'] }} Item)</span>
                        <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Kosongkan semua barang di keranjang?')">
                            @csrf
                            <button type="submit" class="text-[11px] font-bold text-rose-500 hover:underline">
                                Kosongkan Keranjang
                            </button>
                        </form>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @foreach($items as $item)
                            @if($item->product)
                                <div class="py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <!-- Product Info -->
                                    <div class="flex items-center gap-4">
                                        <div class="w-20 h-20 rounded-2xl bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200">
                                            @if($item->product->primaryImage)
                                                <img src="{{ $item->product->primaryImage->url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $item->product->condition_badge['color'] }}">
                                                {{ $item->product->condition_badge['label'] }}
                                            </span>
                                            <h3 class="font-bold text-slate-900 text-sm mt-1 leading-snug">
                                                <a href="{{ route('catalog.show', $item->product->slug) }}" class="hover:text-indigo-600">
                                                    {{ $item->product->name }}
                                                </a>
                                            </h3>
                                            <span class="text-xs font-black text-indigo-600 block mt-1">
                                                {{ $item->product->formatted_price }}
                                            </span>
                                            <span class="text-[10px] text-slate-400 block">
                                                Berat: {{ $item->product->weight_grams }}g / unit
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Quantity Update & Actions -->
                                    <div class="flex items-center sm:flex-col sm:items-end justify-between gap-3">
                                        <!-- Form Update Quantity -->
                                        <form method="POST" action="{{ route('cart.update', $item->id) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <div class="flex items-center border border-slate-200 rounded-xl overflow-hidden bg-slate-50">
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="w-16 px-2 py-1.5 text-xs text-center font-bold bg-transparent focus:outline-none" onchange="this.form.submit()">
                                            </div>
                                            <button type="submit" class="text-[10px] font-bold text-slate-500 hover:text-indigo-600" title="Simpan Kuantitas">
                                                Update
                                            </button>
                                        </form>

                                        <!-- Subtotal Item -->
                                        <div class="text-right">
                                            <span class="text-xs font-black text-slate-900 block">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </span>
                                            <!-- Delete Button -->
                                            <form method="POST" action="{{ route('cart.destroy', $item->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[11px] font-bold text-rose-500 hover:underline mt-1">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Summary Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm sticky top-28 space-y-6">
                    <h2 class="text-base font-black text-slate-900 pb-4 border-b border-slate-100">Ringkasan Keranjang</h2>

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Total Jumlah Barang</span>
                            <span class="font-bold text-slate-800">{{ $totals['total_quantity'] }} Unit</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Estimasi Total Berat</span>
                            <span class="font-bold text-slate-800">{{ number_format($totals['total_weight'], 0, ',', '.') }} gram ({{ ceil($totals['total_weight'] / 1000) }} kg)</span>
                        </div>
                        <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                            <span class="text-sm font-bold text-slate-900">Subtotal Belanja</span>
                            <span class="text-xl font-black text-indigo-600">
                                Rp {{ number_format($totals['items_total'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="w-full block text-center py-4 rounded-xl font-black bg-indigo-600 hover:bg-indigo-500 text-white text-xs transition shadow-lg shadow-indigo-600/30">
                        Lanjut ke Checkout &rarr;
                    </a>

                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 text-[11px] text-slate-500 text-center">
                        Transaksi aman bergaransi dengan pilihan QRIS RajaOngkir & Transfer Manual.
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>
@endsection
