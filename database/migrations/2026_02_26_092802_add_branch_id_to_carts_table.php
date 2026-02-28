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
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('branch_id')
                ->nullable()              // sementara nullable, nanti bisa diubah jadi required setelah logic pilih cabang mateng
                ->constrained('branches') // asumsi tabel lu namanya 'branches'
                ->nullOnDelete();         // kalau cabang dihapus, cart jadi tanpa cabang (bisa dihandle di app)

            // Optional: index biar query cepat
            $table->index('branch_id');
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });
    }
};
