<?php

namespace App\Modules\Cms\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cms\Models\CmsPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminCmsController extends Controller
{
    public function index(): View
    {
        $pages = CmsPage::latest()->get();
        return view('cms::admin.index', compact('pages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);

        // Ensure slug uniqueness
        $originalSlug = $slug;
        $counter = 1;
        while (CmsPage::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $isHomepage = $request->boolean('is_homepage');

        if ($isHomepage) {
            CmsPage::where('is_homepage', true)->update(['is_homepage' => false]);
        }

        $page = CmsPage::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'is_homepage' => $isHomepage,
            'is_active' => true,
            'gjs_data' => null,
            'html_content' => '',
            'css_content' => '',
        ]);

        return redirect()->route('admin.cms.builder', $page->id)->with('success', 'Halaman berhasil dibuat. Silakan mulai mendesain.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $page = CmsPage::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cms_pages,slug,' . $page->id,
            'is_active' => 'nullable',
            'is_homepage' => 'nullable',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $isActive = $request->boolean('is_active');
        $isHomepage = $request->boolean('is_homepage');

        if ($isHomepage) {
            CmsPage::where('id', '!=', $page->id)->update(['is_homepage' => false]);
            $isActive = true; // Homepage must be active
        }

        $page->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'is_active' => $isActive,
            'is_homepage' => $isHomepage,
        ]);

        return back()->with('success', "Metadata halaman '{$page->title}' berhasil diperbarui.");
    }

    public function builder(int $id): View
    {
        $page = CmsPage::findOrFail($id);
        return view('cms::admin.builder', compact('page'));
    }

    public function save(Request $request, int $id): JsonResponse
    {
        $page = CmsPage::findOrFail($id);

        $validated = $request->validate([
            'gjs_data' => 'nullable',
            'html' => 'nullable|string',
            'css' => 'nullable|string',
        ]);

        $gjsData = $request->input('gjs_data');
        if (is_string($gjsData)) {
            $decoded = json_decode($gjsData, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $gjsData = $decoded;
            }
        }

        $page->update([
            'gjs_data' => $gjsData,
            'html_content' => $request->input('html', ''),
            'css_content' => $request->input('css', ''),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Halaman visual berhasil disimpan!',
        ]);
    }

    public function setHomepage(int $id): RedirectResponse
    {
        $page = CmsPage::findOrFail($id);

        CmsPage::where('is_homepage', true)->update(['is_homepage' => false]);
        $page->update(['is_homepage' => true, 'is_active' => true]);

        return back()->with('success', "Halaman '{$page->title}' berhasil diatur sebagai Beranda utama.");
    }

    public function toggleHomepage(int $id): RedirectResponse
    {
        $page = CmsPage::findOrFail($id);

        if ($page->is_homepage) {
            // Deactivate homepage: sets is_homepage false, returning store front to dynamic catalog
            $page->update(['is_homepage' => false]);
            return back()->with('success', "Beranda CMS dinonaktifkan. Beranda utama toko kini kembali menampilkan katalog dinamis.");
        } else {
            // Activate as homepage
            CmsPage::where('is_homepage', true)->update(['is_homepage' => false]);
            $page->update([
                'is_homepage' => true,
                'is_active' => true,
            ]);
            return back()->with('success', "Halaman '{$page->title}' berhasil diatur sebagai Beranda utama toko.");
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $page = CmsPage::findOrFail($id);

        if ($page->is_homepage && $page->is_active) {
            return back()->with('error', 'Halaman beranda utama yang aktif tidak dapat dihapus. Nonaktifkan status beranda terlebih dahulu.');
        }

        $page->delete();

        return back()->with('success', 'Halaman CMS berhasil dihapus.');
    }
}
