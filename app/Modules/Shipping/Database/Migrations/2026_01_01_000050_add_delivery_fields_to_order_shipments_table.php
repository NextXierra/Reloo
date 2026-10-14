<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_shipments', function (Blueprint $table) {
            $table->string('delivery_order_no')->nullable()->after('tracking_number');
            $table->unsignedBigInteger('destination_id')->nullable()->after('delivery_order_no');
            $table->unsignedBigInteger('origin_id')->nullable()->after('destination_id');
            $table->string('pickup_status')->nullable()->after('shipping_status');
            $table->timestamp('pickup_time')->nullable()->after('pickup_status');
            $table->string('pickup_vehicle')->nullable()->after('pickup_time');
            $table->string('label_path')->nullable()->after('pickup_vehicle');
            $table->text('live_tracking_url')->nullable()->after('label_path');
            $table->decimal('shipping_cashback', 12, 2)->default(0)->after('live_tracking_url');
            $table->json('delivery_payload')->nullable()->after('shipping_cashback');
        });
    }

    public function down(): void
    {
        Schema::table('order_shipments', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_order_no',
                'destination_id',
                'origin_id',
                'pickup_status',
                'pickup_time',
                'pickup_vehicle',
                'label_path',
                'live_tracking_url',
                'shipping_cashback',
                'delivery_payload',
            ]);
        });
    }
};
