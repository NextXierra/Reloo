<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Catalog\Models\Product;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Models\OrderItem;
use App\Modules\Order\Models\OrderShipment;
use App\Modules\Shipping\Services\KomerceDeliveryOrderService;
use App\Modules\Shipping\Services\RajaOngkirShippingCostService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ShippingIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->adminUser = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);
        $this->regularUser = User::where('role', 'user')->first() ?? User::factory()->create(['role' => 'user']);
    }

    /**
     * Test live RajaOngkir API destination search and live domestic cost calculation with provided key.
     */
    public function test_live_rajaongkir_domestic_destination_search_and_cost_calculation(): void
    {
        $service = app(RajaOngkirShippingCostService::class);

        // 1. Live Destination Search
        $destinations = $service->searchDestinations('Grogol', 5, 0);
        $this->assertIsArray($destinations);
        $this->assertNotEmpty($destinations, 'RajaOngkir harus mengembalikan data destinasi untuk query Grogol');

        $firstDest = $destinations[0];
        $this->assertArrayHasKey('id', $firstDest);
        $this->assertArrayHasKey('label', $firstDest);
        $this->assertStringContainsString('GROGOL', strtoupper($firstDest['label']));

        // 2. Live Domestic Cost Calculation (Grogol 17473 to Kediri 34072)
        $rates = $service->calculateDomesticCost(17473, 34072, 1000, 'jne');
        $this->assertIsArray($rates);
        $this->assertNotEmpty($rates, 'RajaOngkir harus mengembalikan tarif pengiriman live');

        $firstRate = $rates[0];
        $this->assertEquals('jne', $firstRate['code']);
        $this->assertGreaterThan(0, $firstRate['cost']);
        $this->assertTrue($firstRate['is_live']);
        $this->assertNotEmpty($firstRate['formatted_cost']);
    }

    /**
     * Test public Shipping API endpoints for destinations, calculate, and tracking.
     */
    public function test_shipping_api_endpoints(): void
    {
        // 1. Destination search API
        $response = $this->getJson('/api/shipping/destinations?search=Grogol');
        $response->assertStatus(200)
            ->assertJsonPath('meta.code', 200)
            ->assertJsonPath('meta.status', 'success')
            ->assertJsonStructure([
                'meta' => ['code', 'status', 'message'],
                'data' => [
                    '*' => ['id', 'label', 'subdistrict_name', 'city_name', 'province_name', 'postal_code']
                ]
            ]);

        // Validation error on short query
        $shortResponse = $this->getJson('/api/shipping/destinations?search=ab');
        $shortResponse->assertStatus(422);

        // 2. Calculate shipping cost API
        $calcResponse = $this->postJson('/api/shipping/calculate', [
            'destination_id' => 34072,
            'weight_grams' => 1000,
            'courier' => 'jne',
        ]);
        $calcResponse->assertStatus(200)
            ->assertJsonPath('meta.code', 200)
            ->assertJsonStructure([
                'meta' => ['code', 'status', 'message'],
                'data' => [
                    '*' => ['code', 'name', 'service', 'cost', 'formatted_cost', 'etd']
                ]
            ]);

        // 3. Track AWB API (non-existent AWB expects 404 error from gateway)
        $trackResponse = $this->postJson('/api/shipping/track', [
            'courier' => 'jne',
            'awb' => 'SOCAG99999NONEXISTENT',
        ]);
        $trackResponse->assertStatus(404)
            ->assertJsonPath('meta.status', 'error');
    }

    /**
     * Test Komerce Delivery Order lifecycle via service with HTTP fakes.
     */
    public function test_komerce_delivery_order_lifecycle_with_http_fake(): void
    {
        $baseUrl = rtrim(config('services.komerce_delivery.base_url'), '/');

        Http::fake([
            "{$baseUrl}/order/api/v1/orders/store" => Http::response([
                'meta' => ['code' => 200, 'status' => 'success', 'message' => 'Order created'],
                'data' => [
                    'order_id' => 8888,
                    'order_no' => 'KOM12345678',
                ],
            ], 200),
            "{$baseUrl}/order/api/v1/pickup/request" => Http::response([
                'meta' => ['code' => 200, 'status' => 'success', 'message' => 'Pickup scheduled'],
                'data' => [
                    [
                        'order_no' => 'KOM12345678',
                        'status' => 'scheduled',
                        'awb' => 'SOCAG1234567890',
                    ]
                ],
            ], 200),
            "{$baseUrl}/order/api/v1/orders/print-label*" => Http::response([
                'meta' => ['code' => 200, 'status' => 'success', 'message' => 'Label generated'],
                'data' => [
                    'path' => 'https://komerce.id/labels/KOM12345678.pdf',
                    'base_64' => base64_encode('%PDF-1.4 Fake PDF Content'),
                ],
            ], 200),
            "{$baseUrl}/order/api/v1/orders/cancel" => Http::response([
                'meta' => ['code' => 200, 'status' => 'success', 'message' => 'Order cancelled'],
                'data' => [
                    'order_no' => 'KOM12345678',
                    'status' => 'cancelled',
                ],
            ], 200),
        ]);

        $order = $this->createTestOrder();
        $service = app(KomerceDeliveryOrderService::class);

        // 1. Store Order
        $storeResult = $service->storeOrder($order);
        $this->assertTrue($storeResult['success']);
        $this->assertEquals('KOM12345678', $storeResult['order_no']);

        // 2. Request Pickup
        $pickupResult = $service->requestPickup(['KOM12345678'], '2026-03-25', '14:00', 'Motor');
        $this->assertTrue($pickupResult['success']);
        $this->assertEquals('SOCAG1234567890', $pickupResult['data'][0]['awb']);

        // 3. Print Label
        $labelResult = $service->printLabel('KOM12345678');
        $this->assertTrue($labelResult['success']);
        $this->assertNotEmpty($labelResult['base_64']);

        // 4. Cancel Order
        $cancelResult = $service->cancelOrder('KOM12345678');
        $this->assertTrue($cancelResult['success']);
    }

    /**
     * Test that incoming delivery status webhooks update OrderShipment and Order status.
     */
    public function test_shipping_delivery_webhook_updates_shipment_and_order_status(): void
    {
        $order = $this->createTestOrder();
        $shipment = $order->shipment;
        $shipment->update([
            'delivery_order_no' => 'KOM998877',
            'shipping_status' => 'pending',
        ]);
        $order->update(['status' => 'processing']);

        // Webhook 1: Pickup / Dikirim (in_transit)
        $response1 = $this->postJson('/api/shipping/webhook', [
            'order_no' => 'KOM998877',
            'cnote' => 'SOCAG888111222',
            'status' => 'Dikirim',
        ]);

        $response1->assertStatus(200)
            ->assertJsonPath('meta.code', 200)
            ->assertJsonPath('data.shipping_status', 'in_transit')
            ->assertJsonPath('data.tracking_number', 'SOCAG888111222');

        $shipment->refresh();
        $order->refresh();

        $this->assertEquals('in_transit', $shipment->shipping_status);
        $this->assertEquals('SOCAG888111222', $shipment->tracking_number);
        $this->assertEquals('shipped', $order->status);
        $this->assertNotNull($shipment->shipped_at);

        // Webhook 2: Selesai / Delivered
        $response2 = $this->putJson('/api/shipping/webhook', [
            'order_no' => 'KOM998877',
            'cnote' => 'SOCAG888111222',
            'status' => 'Selesai',
        ]);

        $response2->assertStatus(200)
            ->assertJsonPath('data.shipping_status', 'delivered')
            ->assertJsonPath('data.order_status', 'completed');

        $shipment->refresh();
        $order->refresh();

        $this->assertEquals('delivered', $shipment->shipping_status);
        $this->assertEquals('completed', $order->status);
        $this->assertNotNull($shipment->delivered_at);
    }

    /**
     * Test admin shipping controller routes and fulfillment actions.
     */
    public function test_admin_shipping_controller_actions(): void
    {
        $baseUrl = rtrim(config('services.komerce_delivery.base_url'), '/');

        Http::fake([
            "{$baseUrl}/order/api/v1/orders/store" => Http::response([
                'meta' => ['code' => 200, 'status' => 'success'],
                'data' => [
                    'order_id' => 9911,
                    'order_no' => 'KOMADM123',
                ],
            ], 200),
            "{$baseUrl}/order/api/v1/pickup/request" => Http::response([
                'meta' => ['code' => 200, 'status' => 'success'],
                'data' => [
                    [
                        'order_no' => 'KOMADM123',
                        'status' => 'scheduled',
                        'awb' => 'AWBADM9999',
                    ]
                ],
            ], 200),
            "{$baseUrl}/order/api/v1/orders/print-label*" => Http::response([
                'meta' => ['code' => 200, 'status' => 'success'],
                'data' => [
                    'base_64' => base64_encode('%PDF-1.4 Mock Thermal Label'),
                ],
            ], 200),
            "{$baseUrl}/order/api/v1/orders/cancel" => Http::response([
                'meta' => ['code' => 200, 'status' => 'success'],
                'data' => ['order_no' => 'KOMADM123'],
            ], 200),
        ]);

        $order = $this->createTestOrder();

        // 1. Admin Store Order
        $storeResponse = $this->actingAs($this->adminUser)
            ->post(route('admin.shipping.store', $order->id));
        $storeResponse->assertRedirect();

        $order->shipment->refresh();
        $this->assertEquals('KOMADM123', $order->shipment->delivery_order_no);

        // 2. Admin Schedule Pickup
        $pickupResponse = $this->actingAs($this->adminUser)
            ->post(route('admin.shipping.pickup', $order->id), [
                'pickup_date' => date('Y-m-d'),
                'pickup_time' => '15:00',
                'pickup_vehicle' => 'Motor',
            ]);
        $pickupResponse->assertRedirect();

        $order->shipment->refresh();
        $this->assertEquals('scheduled', $order->shipment->pickup_status);
        $this->assertEquals('AWBADM9999', $order->shipment->tracking_number);
        $this->assertEquals('in_transit', $order->shipment->shipping_status);

        // 3. Admin Print Label
        $labelResponse = $this->actingAs($this->adminUser)
            ->get(route('admin.shipping.label', $order->id));
        $labelResponse->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $labelResponse->headers->get('Content-Type'));

        // 4. Admin Cancel Delivery Order
        $cancelResponse = $this->actingAs($this->adminUser)
            ->post(route('admin.shipping.cancel', $order->id));
        $cancelResponse->assertRedirect();

        $order->shipment->refresh();
        $this->assertEquals('cancelled', $order->shipment->pickup_status);
        $this->assertEquals('cancelled', $order->shipment->shipping_status);
    }

    /**
     * Helper to create a test order with shipment and items.
     */
    protected function createTestOrder(): Order
    {
        $product = Product::first() ?? Product::create([
            'category_id' => 1,
            'vendor_id' => 1,
            'name' => 'Produk Tes Logistik',
            'slug' => 'produk-tes-logistik-' . uniqid(),
            'description' => 'Deskripsi produk',
            'price' => 50000,
            'stock' => 10,
            'weight_grams' => 250,
            'is_active' => true,
        ]);

        $order = Order::create([
            'order_number' => 'INV/TEST/' . strtoupper(uniqid()),
            'user_id' => $this->regularUser->id,
            'status' => 'processing',
            'items_total' => 100000,
            'shipping_cost' => 15000,
            'grand_total' => 115000,
            'customer_note' => 'Tes pengiriman',
            'shipping_address' => [
                'recipient_name' => 'Budi Santoso',
                'phone_number' => '081234567890',
                'address_line' => 'Jl. Kebon Jeruk No. 12',
                'district_name' => 'Kebon Jeruk',
                'city_name' => 'Jakarta Barat',
                'province_name' => 'DKI Jakarta',
                'postal_code' => '11530',
            ],
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => 50000,
            'quantity' => 2,
            'weight_grams' => 250,
            'subtotal' => 100000,
        ]);

        OrderShipment::create([
            'order_id' => $order->id,
            'courier_code' => 'jne',
            'courier_service' => 'REG',
            'shipping_status' => 'pending',
            'destination_id' => 17473,
            'origin_id' => 17473,
        ]);

        return $order->fresh(['shipment', 'items', 'user']);
    }
}
