<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Auth\Models\UserAddress;
use App\Modules\Cart\Models\CartItem;
use App\Modules\Cart\Services\CartService;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Cms\Models\CmsPage;
use App\Modules\Order\Models\Order;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MarketplaceAcceptanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
        // Seed initial database
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * TC-01: Database SQLite & Migrations
     */
    public function test_tc01_database_sqlite_and_seeders(): void
    {
        $this->assertDatabaseHas('users', ['email' => 'admin@reloo.id', 'role' => 'admin']);
        $this->assertDatabaseHas('users', ['email' => 'user@reloo.id', 'role' => 'customer']);
        $this->assertGreaterThanOrEqual(5, Category::count());
        $this->assertGreaterThanOrEqual(8, Product::count());
        $this->assertDatabaseHas('cms_pages', ['slug' => 'home', 'is_homepage' => 1]);
    }

    /**
     * TC-02: HMVC Module Loading
     */
    public function test_tc02_hmvc_modules_route_registration(): void
    {
        $expectedRoutes = [
            'home',
            'login',
            'register',
            'catalog.index',
            'catalog.show',
            'cart.index',
            'cart.add',
            'checkout.index',
            'checkout.process',
            'payment.pay',
            'payment.manual.upload',
            'payment.qrisly.webhook',
            'api.qris.check',
            'order.index',
            'order.show',
            'admin.dashboard',
            'admin.cms.index',
            'admin.cms.builder',
            'admin.cms.save',
            'admin.products.index',
            'admin.categories.index',
            'admin.orders.index',
        ];

        foreach ($expectedRoutes as $routeName) {
            $this->assertTrue(Route::has($routeName), "Route '{$routeName}' must be registered by HMVC provider.");
        }
    }

    /**
     * TC-03: Visual Builder GrapesJS Workspace
     */
    public function test_tc03_visual_builder_grapesjs_workspace(): void
    {
        $admin = User::where('role', 'admin')->first();
        $page = CmsPage::where('slug', 'home')->first();

        $response = $this->actingAs($admin)->get(route('admin.cms.builder', $page->id));

        $response->assertStatus(200);
        $response->assertSee('Visual Page Builder');
        $response->assertSee('reloo-gjs-blocks.js');
        $response->assertSee('Blok E-Commerce');
    }

    /**
     * TC-04: Simpan & Render Halaman GrapesJS
     */
    public function test_tc04_grapesjs_save_and_public_render(): void
    {
        $admin = User::where('role', 'admin')->first();
        $page = CmsPage::where('slug', 'home')->first();

        $updatedHtml = '<section class="py-12 bg-indigo-900 text-white text-center"><h1 class="text-4xl font-black">PROMO SPESIAL RELOO 2026</h1><p>Diskon hingga 70% untuk produk terkurasi.</p></section>';
        $updatedCss = '.promo-text { color: #f59e0b; }';

        // Admin saves via AJAX
        $saveResponse = $this->actingAs($admin)->postJson(route('admin.cms.save', $page->id), [
            'gjs_data' => ['components' => [['type' => 'text', 'content' => 'PROMO']]],
            'html' => $updatedHtml,
            'css' => $updatedCss,
        ]);

        $saveResponse->assertStatus(200);
        $saveResponse->assertJson(['status' => 'success']);

        // Public visits homepage '/'
        $publicResponse = $this->get('/');
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('PROMO SPESIAL RELOO 2026');
        $publicResponse->assertSee('.promo-text { color: #f59e0b; }', false);
    }

    /**
     * TC-05: Alur Keranjang & Validasi Stok
     */
    public function test_tc05_cart_and_stock_validation(): void
    {
        $customer = User::where('role', 'customer')->first();

        // Ambil produk dengan stok terbatas
        $product = Product::where('stock', '>', 0)->first();
        $product->update(['stock' => 2]);

        $cartService = app(CartService::class);

        // Tambah dalam batas stok (sukses)
        $cartService->addItem($product->id, 2, $customer->id);
        $this->assertEquals(2, CartItem::where('user_id', $customer->id)->where('product_id', $product->id)->value('quantity'));

        // Coba tambah lagi melebihi stok (harus ditolak validasi)
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $cartService->addItem($product->id, 1, $customer->id);
    }

    /**
     * TC-06: Alur Checkout Atomik & Pengurangan Stok
     */
    public function test_tc06_atomic_checkout_flow_and_stock_deduction(): void
    {
        $customer = User::where('role', 'customer')->first();
        $product = Product::first();
        $product->update(['stock' => 5, 'price' => 500000]);

        $cartService = app(CartService::class);
        $cartService->clearCart($customer->id);
        $cartService->addItem($product->id, 2, $customer->id);

        $response = $this->actingAs($customer)->post(route('checkout.process'), [
            'recipient_name' => 'Budi Santoso',
            'phone_number' => '081234567890',
            'address_line' => 'Jl. Kebon Jeruk No. 8',
            'province_name' => 'DKI Jakarta',
            'city_name' => 'Jakarta Barat',
            'district_name' => 'Kebon Jeruk',
            'postal_code' => '11530',
            'courier_code' => 'jne',
            'payment_gateway' => 'manual_transfer',
            'customer_note' => 'Packing aman',
        ]);

        $response->assertRedirect();

        // Cek order terbuat
        $order = Order::where('user_id', $customer->id)->latest()->first();
        $this->assertNotNull($order);
        $this->assertStringStartsWith('INV/', $order->order_number);
        $this->assertEquals('pending_payment', $order->status);
        $this->assertEquals(1000000, $order->items_total);
        $this->assertEquals(15000 * ceil($product->weight_grams * 2 / 1000), $order->shipping_cost);

        // Cek stok produk berkurang di database dari 5 menjadi 3
        $this->assertEquals(3, $product->fresh()->stock);

        // Cek keranjang dikosongkan
        $this->assertEquals(0, CartItem::where('user_id', $customer->id)->count());
    }

    /**
     * TC-07: Upload Bukti Bayar Manual
     */
    public function test_tc07_manual_payment_proof_upload(): void
    {
        Storage::fake('public');

        $customer = User::where('role', 'customer')->first();
        $product = Product::first();

        // Buat order untuk customer
        $order = Order::create([
            'order_number' => 'INV/20261013/RLO-TEST1',
            'user_id' => $customer->id,
            'status' => 'pending_payment',
            'items_total' => 500000,
            'shipping_cost' => 15000,
            'grand_total' => 515000,
            'shipping_address' => ['recipient_name' => 'Budi'],
        ]);

        $order->payment()->create([
            'payment_gateway' => 'manual_transfer',
            'payment_status' => 'pending',
        ]);

        $file = UploadedFile::fake()->image('struk_bca.jpg', 600, 800);

        $response = $this->actingAs($customer)->post(route('payment.manual.upload', $order->order_number), [
            'payment_proof' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Pastikan status pembayaran berubah ke waiting_verification
        $this->assertEquals('waiting_verification', $order->payment->fresh()->payment_status);
        $this->assertNotNull($order->payment->fresh()->payment_proof);
    }

    /**
     * TC-08: Admin Fulfillment & Input Resi
     */
    public function test_tc08_admin_fulfillment_and_resi_input(): void
    {
        $admin = User::where('role', 'admin')->first();
        $customer = User::where('role', 'customer')->first();

        $order = Order::create([
            'order_number' => 'INV/20261013/RLO-TEST2',
            'user_id' => $customer->id,
            'status' => 'pending_payment',
            'items_total' => 800000,
            'shipping_cost' => 15000,
            'grand_total' => 815000,
            'shipping_address' => ['recipient_name' => 'Budi Santoso'],
        ]);

        $order->payment()->create([
            'payment_gateway' => 'manual_transfer',
            'payment_status' => 'waiting_verification',
        ]);

        $order->shipment()->create([
            'courier_code' => 'jne',
            'courier_service' => 'REG',
            'shipping_status' => 'pending',
        ]);

        // 1. Admin Verifikasi Pembayaran
        $verifyResponse = $this->actingAs($admin)->post(route('admin.orders.verify', $order->id));
        $verifyResponse->assertRedirect();
        $this->assertEquals('paid', $order->payment->fresh()->payment_status);
        $this->assertEquals('processing', $order->fresh()->status);

        // 2. Admin Input Nomor Resi
        $shipResponse = $this->actingAs($admin)->post(route('admin.orders.ship', $order->id), [
            'tracking_number' => 'JNE9988776655',
        ]);
        $shipResponse->assertRedirect();

        // 3. Pastikan status beralih ke 'shipped' dan resi tersimpan
        $this->assertEquals('shipped', $order->fresh()->status);
        $this->assertEquals('JNE9988776655', $order->shipment->fresh()->tracking_number);
        $this->assertEquals('in_transit', $order->shipment->fresh()->shipping_status);

        // 4. Pastikan customer dapat melihat nomor resi pada detail order
        $customerViewResponse = $this->actingAs($customer)->get(route('order.show', $order->order_number));
        $customerViewResponse->assertStatus(200);
        $customerViewResponse->assertSee('JNE9988776655');
    }

    /**
     * TC-09: RajaOngkir Qrisly Dynamic QRIS Generation & Webhook Settlement
     */
    public function test_tc09_qrisly_generation_and_webhook_settlement(): void
    {
        $customer = User::where('role', 'customer')->first();
        $product = Product::first();
        $product->update(['stock' => 10, 'price' => 750000]);

        $cartService = app(CartService::class);
        $cartService->clearCart($customer->id);
        $cartService->addItem($product->id, 1, $customer->id);

        // 1. Checkout via QRISLY
        $response = $this->actingAs($customer)->post(route('checkout.process'), [
            'recipient_name' => 'Budi Santoso',
            'phone_number' => '081234567890',
            'address_line' => 'Jl. Kebon Jeruk No. 8',
            'province_name' => 'DKI Jakarta',
            'city_name' => 'Jakarta Barat',
            'district_name' => 'Kebon Jeruk',
            'postal_code' => '11530',
            'courier_code' => 'jne',
            'payment_gateway' => 'qrisly',
            'customer_note' => 'QRIS testing',
        ]);

        $response->assertRedirect();
        $order = Order::where('user_id', $customer->id)->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals('qrisly', $order->payment->payment_gateway);
        $this->assertNotNull($order->payment->transaction_id);

        // 2. Customer opens payment page and views QRIS
        $payPageResponse = $this->actingAs($customer)->get(route('payment.pay', $order->order_number));
        $payPageResponse->assertStatus(200);
        $payPageResponse->assertSee('RajaOngkir Qrisly');
        $payPageResponse->assertSee('quickchart.io/qr');

        // 3. Webhook push from Qrisly
        $webhookResponse = $this->postJson(route('payment.qrisly.webhook'), [
            'event' => 'payment.success',
            'data' => [
                'qris_history_id' => $order->payment->transaction_id,
                'status' => 'paid',
                'amount' => $order->grand_total,
            ],
        ]);

        $webhookResponse->assertStatus(200);
        $this->assertEquals('paid', $order->fresh()->status);
        $this->assertEquals('paid', $order->payment->fresh()->payment_status);

        // 4. Polling endpoint verifies paid status
        $checkResponse = $this->getJson(route('api.qris.check', $order->order_number));
        $checkResponse->assertStatus(200);
        $checkResponse->assertJson(['status' => 'paid']);
    }
}
