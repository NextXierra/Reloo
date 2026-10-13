<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Payment\Services\QrislyService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class QrislyAndStorefrontIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_real_sandbox_qrisly_generation_and_status(): void
    {
        $service = app(QrislyService::class);
        $result = $service->generateQris(15000, 'TEST-REAL-E2E-01');

        $this->assertIsArray($result);
        $this->assertFalse($result['is_dummy']);
        $this->assertNotNull($result['history_id']);
        $this->assertNotEmpty($result['qris_string']);
        $this->assertStringStartsWith('000201', $result['qris_string']);
        $this->assertGreaterThanOrEqual(15000, $result['amount']);
        $this->assertStringContainsString('quickchart.io/qr', $result['qr_image_url']);

        // Check payment status with the generated history_id
        $status = $service->checkStatus($result['history_id']);
        $this->assertIsArray($status);
        $this->assertEquals(200, $status['meta']['code'] ?? null);
        $this->assertEquals('unpaid', $status['data']['payment_status'] ?? null);
    }

    /**
     * Verify that route '/' loads active products dynamically without CMS table.
     */
    public function test_native_storefront_loads_dynamically_without_cms(): void
    {
        $this->assertFalse(Schema::hasTable('cms_pages'), 'Tabel cms_pages harus tidak ada di skema database.');

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('catalog::home');

        // Check that products from database appear on the page
        $products = Product::where('is_active', true)->take(3)->get();
        $this->assertNotEmpty($products);
        foreach ($products as $prod) {
            $response->assertSee($prod->name);
        }

        // Check that categories appear
        $categories = Category::where('is_active', true)->take(3)->get();
        $this->assertNotEmpty($categories);
        foreach ($categories as $cat) {
            $response->assertSee($cat->name);
        }

        // Assert no CMS builder script or artifacts
        $response->assertDontSee('grapesjs');
        $response->assertDontSee('reloo-gjs-blocks.js');
    }

    /**
     * Verify that admin dashboard does not expose CMS builder and /admin/cms returns 404.
     */
    public function test_admin_dashboard_free_from_cms_builder_endpoints(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertDontSee('Visual Builder GrapesJS');
        $response->assertDontSee('admin.cms.index');

        // Verify CMS builder routes are not registered
        $this->assertFalse(Route::has('admin.cms.index'));
        $this->assertFalse(Route::has('admin.cms.builder'));
        $this->assertFalse(Route::has('admin.cms.save'));

        // Direct request to legacy CMS endpoint must be 404
        $cmsResponse = $this->actingAs($admin)->get('/admin/cms');
        $cmsResponse->assertStatus(404);
    }
}
