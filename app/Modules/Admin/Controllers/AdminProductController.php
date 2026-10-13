<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'primaryImage'])->latest();

        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where('name', 'like', "%{$keyword}%")
                  ->orWhere('sku', 'like', "%{$keyword}%");
        }

        $products = $query->paginate(15)->withQueryString();

        return view('admin::products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin::products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'condition' => 'required|in:new,like_new,good,fair',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight_grams' => 'required|integer|min:1',
            'description' => 'required|string',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url' => 'nullable|url',
        ]);

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'condition' => $validated['condition'],
            'price' => $validated['price'],
            'original_price' => $validated['original_price'] ?? null,
            'stock' => $validated['stock'],
            'weight_grams' => $validated['weight_grams'],
            'description' => $validated['description'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Process image upload or URL
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_primary' => true,
                'order_index' => 0,
            ]);
        } elseif (!empty($validated['image_url'])) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $validated['image_url'],
                'is_primary' => true,
                'order_index' => 0,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', "Produk '{$product->name}' berhasil ditambahkan!");
    }

    public function edit(int $id): View
    {
        $product = Product::with(['category', 'images'])->findOrFail($id);
        $categories = Category::where('is_active', true)->get();

        return view('admin::products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'condition' => 'required|in:new,like_new,good,fair',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight_grams' => 'required|integer|min:1',
            'description' => 'required|string',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url' => 'nullable|url',
        ]);

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'condition' => $validated['condition'],
            'price' => $validated['price'],
            'original_price' => $validated['original_price'] ?? null,
            'stock' => $validated['stock'],
            'weight_grams' => $validated['weight_grams'],
            'description' => $validated['description'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Process image if added
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_primary' => true,
                'order_index' => 0,
            ]);
        } elseif (!empty($validated['image_url'])) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $validated['image_url'],
                'is_primary' => true,
                'order_index' => 0,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', "Produk '{$product->name}' berhasil diperbarui!");
    }

    public function destroy(int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
