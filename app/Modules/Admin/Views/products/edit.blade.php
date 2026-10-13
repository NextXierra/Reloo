@extends('admin::layouts.admin')

@section('title', 'Edit Produk: ' . $product->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between pb-3 border-b border-[#EAEAEA]">
        <div>
            <span class="font-mono text-[10px] uppercase tracking-wider text-[#71717A] block mb-0.5">PERBARUI INVENTARIS</span>
            <h2 class="font-serif text-2xl text-[#111111]">Perbarui Detail Produk</h2>
            <p class="text-xs text-[#71717A] font-mono">ID: #{{ $product->id }} | SKU: {{ $product->sku }}</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="px-3.5 py-1.5 rounded-[5px] border border-[#EAEAEA] text-xs font-medium text-[#52525B] hover:text-[#111111] hover:bg-[#F7F6F3] transition-colors">
            &larr; Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" class="bg-white rounded-[6px] p-6 sm:p-8 border border-[#EAEAEA] space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div class="md:col-span-2">
                <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
            </div>

            <!-- SKU -->
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">SKU</label>
                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] font-mono focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
            </div>

            <!-- Category -->
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Kategori Produk</label>
                <select name="category_id" required class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Condition -->
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Kondisi Barang</label>
                <select name="condition" required class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
                    <option value="new" {{ old('condition', $product->condition) === 'new' ? 'selected' : '' }}>Baru (Brand New)</option>
                    <option value="like_new" {{ old('condition', $product->condition) === 'like_new' ? 'selected' : '' }}>Seperti Baru (Like New)</option>
                    <option value="good" {{ old('condition', $product->condition) === 'good' ? 'selected' : '' }}>Bagus (Good Condition)</option>
                    <option value="fair" {{ old('condition', $product->condition) === 'fair' ? 'selected' : '' }}>Cukup (Fair Condition)</option>
                </select>
            </div>

            <!-- Stock -->
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Jumlah Stok</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] font-mono focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
            </div>

            <!-- Price -->
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Harga Jual (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" required class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] font-mono focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
            </div>

            <!-- Original Price -->
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Harga Coret (Rp, Opsional)</label>
                <input type="number" name="original_price" value="{{ old('original_price', $product->original_price) }}" min="0" class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] font-mono focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
            </div>

            <!-- Weight -->
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Berat Barang (Gram)</label>
                <input type="number" name="weight_grams" value="{{ old('weight_grams', $product->weight_grams) }}" min="1" required class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] font-mono focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
            </div>

            <!-- Image File Upload -->
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Tambah Foto Baru (File)</label>
                <input type="file" name="image" accept="image/*" class="w-full text-xs text-[#52525B] file:mr-3 file:py-1.5 file:px-3 file:rounded-[4px] file:border file:border-[#EAEAEA] file:text-xs file:font-medium file:bg-[#F7F6F3] file:text-[#111111] hover:file:bg-[#EAEAEA] file:cursor-pointer cursor-pointer">
            </div>

            <!-- Image URL (Alternative) -->
            <div class="md:col-span-2">
                <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Atau Tambah Foto via URL Eksternal</label>
                <input type="url" name="image_url" placeholder="https://images.unsplash.com/..." class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] font-mono focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-xs font-mono uppercase tracking-wider text-[#71717A] mb-1">Deskripsi & Kelengkapan</label>
                <textarea name="description" rows="5" required class="w-full px-3 py-2 text-xs bg-[#F7F6F3] border border-[#EAEAEA] rounded-[5px] text-[#111111] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#111111]">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-[#EAEAEA] flex items-center justify-end gap-2">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-[5px] border border-[#EAEAEA] text-xs font-medium text-[#52525B] hover:text-[#111111] hover:bg-[#F7F6F3] transition-colors">
                Batal
            </a>
            <button type="submit" class="px-5 py-2 rounded-[5px] bg-[#111111] hover:bg-[#27272A] text-white font-medium text-xs transition-colors whitespace-nowrap">
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>
@endsection
