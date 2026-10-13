<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('pending_payment');
            $table->double('items_total', 12, 2);
            $table->double('shipping_cost', 12, 2);
            $table->double('grand_total', 12, 2);
            $table->text('customer_note')->nullable();
            $table->text('shipping_address'); // JSON snapshot
            $table->unsignedBigInteger('vendor_id')->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
