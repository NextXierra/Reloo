<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->string('condition')->default('good'); // new, like_new, good, fair
            $table->double('price', 12, 2);
            $table->double('original_price', 12, 2)->nullable();
            $table->integer('weight_grams')->default(1000);
            $table->integer('stock')->default(1);
            $table->text('description');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('vendor_id')->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
