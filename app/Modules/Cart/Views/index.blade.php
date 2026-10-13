@extends('layouts.store')

@section('title', 'Keranjang Belanja · Reloo')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <div class="mb-8 pb-4 border-b border-[#EAEAEA]">
        <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-1">PROSES TRANSAKSI</span>
        <h1 class="font-serif text-3xl sm:text-4xl text-[#111111] tracking-tight">Keranjang Belanja</h1>
        <p class="text-xs text-[#52525B] mt-1">Periksa kembali daftar item pilihan Anda sebelum melanjutkan ke formulir pengiriman.</p>
    </div>

    @if($items->isEmpty())
        <div class="bg-white rounded-[6px] p-12 border border-[#EAEAEA] text-center max-w-lg mx-auto">
            <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-2">[KERANJANG KOSONG]</span>
            <h3 class="font-serif text-2xl text-[#111111] mb-2">Belum Ada Item Terpilih</h3>
            <p class="text-xs text-[#52525B] mb-6 leading-relaxed">
                Anda belum memasukkan produk ke keranjang. Jelajahi etalase terkurasi kami untuk menemukan sneakers, tas, jam tangan, atau gadget pilihan.
            </p>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-[5px] font-medium bg-[#111111] text-white text-xs hover:bg-[#27272A] transition-colors whitespace-nowrap">
                Mulai Belanja &rarr;
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <!-- Items List (2 Cols) -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA]">
                    <div class="flex items-center justify-between pb-3 border-b border-[#EAEAEA] mb-4">
                        <span class="font-mono text-xs uppercase tracking-wider text-[#111111]">
                            DAFTAR ITEM ({{ $totals['total_quantity'] }} UNIT)
                        </span>
                        <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Kosongkan semua barang di keranjang?')">
                            @csrf
                            <button type="submit" class="font-mono text-[11px] text-[#8C2220] hover:underline">
                                Kosongkan Keranjang
                            </button>
                        </form>
                    </div>

                    <div class="divide-y divide-[#EAEAEA]">
                        @foreach($items as $item)
                            @if($item->product)
                                <div class="py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <!-- Product Info -->
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 rounded-[4px] bg-[#F7F6F3] overflow-hidden flex-shrink-0 border border-[#EAEAEA] p-1">
                                            @if($item->product->primaryImage)
                                                <img src="{{ $item->product->primaryImage->url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-[2px]">
                                            @endif
                                        </div>
                                        <div>
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-mono uppercase border {{ $item->product->condition_badge['color'] }}">
                                                {{ $item->product->condition_badge['label'] }}
                                            </span>
                                            <h3 class="font-medium text-xs text-[#111111] mt-1 leading-snug">
                                                <a href="{{ route('catalog.show', $item->product->slug) }}" class="hover:underline">
                                                    {{ $item->product->name }}
                                                </a>
                                            </h3>
                                            <span class="font-mono text-xs font-semibold text-[#111111] block mt-1">
                                                {{ $item->product->formatted_price }}
                                            </span>
                                            <span class="font-mono text-[10px] text-[#71717A] block">
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
                                            <div class="flex items-center border border-[#EAEAEA] rounded-[4px] bg-[#F7F6F3]">
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="w-14 px-2 py-1 text-xs text-center font-mono font-medium bg-transparent focus:outline-none" onchange="this.form.submit()">
                                            </div>
                                            <button type="submit" class="font-mono text-[10px] text-[#71717A] hover:text-[#111111]" title="Simpan Perubahan Kuantitas">
                                                Update
                                            </button>
                                        </form>

                                        <!-- Subtotal Item -->
                                        <div class="text-right">
                                            <span class="font-mono text-xs font-semibold text-[#111111] block">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </span>
                                            <!-- Delete Button -->
                                            <form method="POST" action="{{ route('cart.destroy', $item->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-mono text-[10px] text-[#8C2220] hover:underline mt-1">
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

            <!-- Order Summary Card (1 Col) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-[6px] p-6 border border-[#EAEAEA] sticky top-24 space-y-6">
                    <div class="pb-3 border-b border-[#EAEAEA]">
                        <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-0.5">ESTIMASI BIAYA</span>
                        <h2 class="font-serif text-xl text-[#111111]">Ringkasan Belanja</h2>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between text-[#52525B]">
                            <span>Jumlah Barang</span>
                            <span class="font-mono font-medium text-[#111111]">{{ $totals['total_quantity'] }} Unit</span>
                        </div>
                        <div class="flex justify-between text-[#52525B]">
                            <span>Estimasi Total Berat</span>
                            <span class="font-mono font-medium text-[#111111]">{{ number_format($totals['total_weight'], 0, ',', '.') }} gram ({{ ceil($totals['total_weight'] / 1000) }} kg)</span>
                        </div>
                        <div class="pt-3 border-t border-[#EAEAEA] flex justify-between items-baseline">
                            <span class="text-xs font-medium text-[#111111]">Subtotal Produk</span>
                            <span class="font-mono text-2xl font-bold text-[#111111]">
                                Rp {{ number_format($totals['items_total'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="h-11 w-full flex items-center justify-center rounded-[5px] font-medium bg-[#111111] hover:bg-[#27272A] text-white text-xs transition-colors whitespace-nowrap">
                        Lanjut ke Checkout &rarr;
                    </a>

                    <div class="p-3 bg-[#F7F6F3] rounded-[4px] border border-[#EAEAEA] font-mono text-[10px] text-[#71717A] text-center leading-relaxed">
                        PEMBAYARAN RESMI VIA QRIS & TRANSFER BANK DILENGKAPI RESI PELACAKAN OTOMATIS
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>
@endsection
