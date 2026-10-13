@extends('admin::layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-slate-900">Form Produk Baru</h2>
            <p class="text-xs text-slate-500">Lengkapi detail spesifikasi, harga, stok, dan unggah foto produk</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
            &larr; Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Nike Air Max 97 Silver Bullet" class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
            </div>

            <!-- SKU -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">SKU (Stock Keeping Unit)</label>
                <input type="text" name="sku" value="{{ old('sku') }}" required placeholder="RLO-SH-099" class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
            </div>

            <!-- Category -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                <select name="category_id" required class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Condition -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Kondisi Barang</label>
                <select name="condition" required class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                    <option value="new" {{ old('condition') === 'new' ? 'selected' : '' }}>Baru (Brand New)</option>
                    <option value="like_new" {{ old('condition', 'like_new') === 'like_new' ? 'selected' : '' }}>Seperti Baru (Like New)</option>
                    <option value="good" {{ old('condition') === 'good' ? 'selected' : '' }}>Bagus (Good Condition)</option>
                    <option value="fair" {{ old('condition') === 'fair' ? 'selected' : '' }}>Cukup (Fair Condition)</option>
                </select>
            </div>

            <!-- Stock -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Stok</label>
                <input type="number" name="stock" value="{{ old('stock', 1) }}" min="0" required class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none">
            </div>

            <!-- Price -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Harga Jual (Rp)</label>
                <input type="number" name="price" value="{{ old('price') }}" min="0" required placeholder="1250000" class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none">
            </div>

            <!-- Original Price -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Harga Coret / Ritel Awal (Rp, Opsional)</label>
                <input type="number" name="original_price" value="{{ old('original_price') }}" min="0" placeholder="2000000" class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none">
            </div>

            <!-- Weight -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Berat Barang (Gram)</label>
                <input type="number" name="weight_grams" value="{{ old('weight_grams', 1000) }}" min="1" required class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none">
            </div>

            <!-- Image File Upload -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Unggah Foto Utama (File)</label>
                <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white hover:file:bg-indigo-600 file:cursor-pointer cursor-pointer">
            </div>

            <!-- Image URL (Alternative) -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1">Atau Gunakan URL Gambar Eksternal (Unsplash / CDN)</label>
                <input type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://images.unsplash.com/..." class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none">
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi & Kelengkapan Produk</label>
                <textarea name="description" rows="5" required placeholder="Jelaskan kondisi fisik, keaslian, kelengkapan box, dan minus jika ada..." class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition shadow-md shadow-indigo-600/20">
                Simpan Produk Baru
            </button>
        </div>
    </form>

</div>
@endsection
