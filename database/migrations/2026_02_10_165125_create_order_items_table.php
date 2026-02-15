<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('product_variant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('quantity');
            $table->bigInteger('price');
            $table->bigInteger('subtotal');

            // Snapshot data (penting!)
            $table->string('product_name');
            $table->string('variant_sku')->nullable();
            $table->string('variant_color')->nullable();
            $table->string('variant_size')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'product_variant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
