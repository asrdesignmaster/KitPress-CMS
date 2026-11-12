<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commerce_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('commerce_categories')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('commerce_products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('sku')->unique()->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->json('builder_json')->nullable();
            $table->string('featured_image')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('commerce_categories')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('commerce_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('commerce_products')->cascadeOnDelete();
            $table->string('sku')->nullable();
            $table->string('title'); // e.g. "Size: L / Color: Blue"
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->json('attributes')->nullable(); // {"size": "L", "color": "Blue"}
            $table->timestamps();
        });

        Schema::create('commerce_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_email');
            $table->string('customer_name');
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
            $table->json('shipping_address')->nullable();
            $table->timestamps();
        });

        Schema::create('commerce_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('commerce_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('commerce_products')->nullOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('commerce_variants')->nullOnDelete();
            $table->string('product_title');
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commerce_order_items');
        Schema::dropIfExists('commerce_orders');
        Schema::dropIfExists('commerce_variants');
        Schema::dropIfExists('commerce_products');
        Schema::dropIfExists('commerce_categories');
    }
};

