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
        Schema::table('orders', function (Blueprint $table) {
            // 1. Tambah kolom hanya jika BELUM ADA
            if (!Schema::hasColumn('orders', 'branch_id')) {
                $table->foreignId('branch_id')
                    ->nullable()                              // nullable dulu biar aman
                    ->constrained('branches')
                    ->cascadeOnDelete();
            }

            // 2. Re-apply foreign key (aman meski sudah ada)
            // Kita drop dulu kalau mungkin ada (tidak error kalau tidak ada di MySQL)
            // Tapi karena drop sering bermasalah di down(), kita pakai cara aman di up()
            // Langsung add foreign tanpa drop dulu
            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->cascadeOnDelete()
                ->name('orders_branch_id_foreign');       // pakai nama eksplisit

            // 3. Index gabungan (jika belum ada)
            if (!Schema::hasIndex('orders', 'orders_branch_id_status_index')) {
                $table->index(['branch_id', 'status'], 'orders_branch_id_status_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop foreign key dengan nama eksplisit (lebih aman)
            // Laravel tidak akan error kalau constraint tidak ada di MySQL
            $table->dropForeign('orders_branch_id_foreign');

            // Drop kolom hanya kalau ada
            if (Schema::hasColumn('orders', 'branch_id')) {
                $table->dropColumn('branch_id');
            }
        });
    }
};
