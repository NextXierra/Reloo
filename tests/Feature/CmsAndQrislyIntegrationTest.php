<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Cms\Models\CmsPage;
use App\Modules\Payment\Services\QrislyService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsAndQrislyIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
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
     * Verify CMS page metadata editing (title, slug, is_active).
     */
    public function test_cms_page_metadata_update(): void
    {
        $admin = User::where('role', 'admin')->first();
        $page = CmsPage::where('slug', 'home')->first();

        $response = $this->actingAs($admin)->put(route('admin.cms.update', $page->id), [
            'title' => 'Beranda Utama Reloo Updated',
            'slug' => 'home-updated',
            'is_active' => '1',
        ]);

        $response->assertRedirect();
        $page->refresh();

        $this->assertEquals('Beranda Utama Reloo Updated', $page->title);
        $this->assertEquals('home-updated', $page->slug);
        $this->assertTrue($page->is_active);
    }

    /**
     * Verify homepage toggling and automatic fallback to dynamic catalog home.
     */
    public function test_cms_homepage_toggle_and_dynamic_catalog_fallback(): void
    {
        $admin = User::where('role', 'admin')->first();
        $page = CmsPage::where('is_homepage', true)->first();
        $this->assertNotNull($page);

        // 1. Initially, GET / renders CMS page
        $resInitial = $this->get('/');
        $resInitial->assertStatus(200);
        $resInitial->assertViewIs('cms::render');

        // 2. Admin toggles homepage off
        $toggleOffResponse = $this->actingAs($admin)->post(route('admin.cms.toggle_homepage', $page->id));
        $toggleOffResponse->assertRedirect();
        $page->refresh();
        $this->assertFalse($page->is_homepage);

        // 3. Now GET / automatically falls back to dynamic catalog home
        $resFallback = $this->get('/');
        $resFallback->assertStatus(200);
        $resFallback->assertViewIs('catalog::home');
        $resFallback->assertSee('Jelajahi Katalog');
        // 4. Admin toggles homepage back on
        $toggleOnResponse = $this->actingAs($admin)->post(route('admin.cms.toggle_homepage', $page->id));
        $toggleOnResponse->assertRedirect();
        $page->refresh();
        $this->assertTrue($page->is_homepage);

        // 5. GET / once again renders CMS page
        $resRestored = $this->get('/');
        $resRestored->assertStatus(200);
        $resRestored->assertViewIs('cms::render');
    }

    /**
     * Verify modern GrapesJS project data saving and builder elements.
     */
    public function test_modern_grapesjs_save_and_builder_view(): void
    {
        $admin = User::where('role', 'admin')->first();
        $page = CmsPage::where('is_homepage', true)->first();

        // 1. Visit visual builder
        $builderResponse = $this->actingAs($admin)->get(route('admin.cms.builder', $page->id));
        $builderResponse->assertStatus(200);
        $builderResponse->assertSee('tab-blocks');
        $builderResponse->assertSee('tab-styles');
        $builderResponse->assertSee('tab-traits');
        $builderResponse->assertSee('tab-layers');
        $builderResponse->assertSee('btn-desktop');
        $builderResponse->assertSee('btn-tablet');
        $builderResponse->assertSee('btn-mobile');
        $builderResponse->assertSee('btn-clear');
        $builderResponse->assertSee('btn-preview');

        // 2. Modern GrapesJS project data structure
        $modernProjectData = [
            'pages' => [
                [
                    'id' => 'page-main',
                    'frames' => [
                        [
                            'component' => [
                                'type' => 'wrapper',
                                'components' => [
                                    ['type' => 'text', 'content' => 'Hero Banner Modern']
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'styles' => [
                ['selectors' => ['.hero-custom'], 'style' => ['color' => '#6366f1']]
            ]
        ];

        $saveResponse = $this->actingAs($admin)->postJson(route('admin.cms.save', $page->id), [
            'gjs_data' => $modernProjectData,
            'html' => '<div class="hero-custom">Hero Banner Modern</div>',
            'css' => '.hero-custom { color: #6366f1; }',
        ]);

        $saveResponse->assertStatus(200);
        $saveResponse->assertJson(['status' => 'success']);

        $page->refresh();
        $this->assertIsArray($page->gjs_data);
        $this->assertArrayHasKey('pages', $page->gjs_data);
        $this->assertEquals('<div class="hero-custom">Hero Banner Modern</div>', $page->html_content);
    }

    /**
     * Verify deletion protection for active homepage.
     */
    public function test_homepage_delete_protection(): void
    {
        $admin = User::where('role', 'admin')->first();
        $homepage = CmsPage::where('is_homepage', true)->first();

        // Attempt deleting active homepage
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.cms.destroy', $homepage->id));
        $deleteResponse->assertRedirect();
        $this->assertDatabaseHas('cms_pages', ['id' => $homepage->id]);

        // Create a non-homepage page
        $dummyPage = CmsPage::create([
            'title' => 'Page to delete',
            'slug' => 'to-delete',
            'is_homepage' => false,
            'is_active' => false,
        ]);

        $deleteOkResponse = $this->actingAs($admin)->delete(route('admin.cms.destroy', $dummyPage->id));
        $deleteOkResponse->assertRedirect();
        $this->assertDatabaseMissing('cms_pages', ['id' => $dummyPage->id]);
    }
}
