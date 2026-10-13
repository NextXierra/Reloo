<?php

namespace App\Modules\Catalog\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount(['products' => function ($q) {
            $q->where('is_active', true);
        }])->where('is_active', true)->get();

        $featuredProducts = Product::with(['category', 'primaryImage'])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $spotlightProduct = $featuredProducts->first();

        $totalProducts = Product::where('is_active', true)->count();
        $likeNewCount = Product::where('is_active', true)->where('condition', 'like_new')->count();
        $brandNewCount = Product::where('is_active', true)->where('condition', 'new')->count();

        return view('catalog::home', compact(
            'categories',
            'featuredProducts',
            'spotlightProduct',
            'totalProducts',
            'likeNewCount',
            'brandNewCount'
        ));
    }
}
