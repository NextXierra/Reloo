<?php

namespace App\Modules\Cms\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Cms\Models\CmsPage;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $page = CmsPage::where('is_homepage', true)->where('is_active', true)->first();

        if ($page) {
            return view('cms::render', compact('page'));
        }

        // Fallback if no homepage is configured yet
        $categories = Category::where('is_active', true)->get();
        $featuredProducts = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        return view('catalog::home', compact('categories', 'featuredProducts'));
    }

    public function show(string $slug): View
    {
        $page = CmsPage::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('cms::render', compact('page'));
    }
}
