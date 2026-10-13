<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained('orders')->onDelete('cascade');
            $table->string('payment_gateway'); // midtrans, manual_transfer
            $table->string('payment_type')->nullable(); // qris, bca_va, etc.
            $table->string('transaction_id')->nullable();
            $table->string('payment_status')->default('pending'); // pending, waiting_verification, paid, expired, failed
            $table->string('payment_proof')->nullable(); // proof file path
            $table->string('snap_token')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->longText('gateway_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};
