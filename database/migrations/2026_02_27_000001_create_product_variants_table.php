<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {

            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            // FIX: branch_id harus constrained
            $table->foreignId('branch_id')
                ->constrained('branches')
                ->cascadeOnDelete();

            $table->string('sku')->nullable()->unique();

            $table->string('color', 50)->nullable();
            $table->string('size', 50)->nullable();

            $table->unsignedInteger('price');

            $table->unsignedInteger('stock')->default(0);

            $table->softDeletes();
            $table->timestamps();

            // unique variant per branch
            $table->unique(
                ['product_id', 'branch_id', 'color', 'size'],
                'product_variant_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
