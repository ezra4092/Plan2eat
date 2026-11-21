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
        if (Schema::hasColumn('umkm', 'metode_pembayaran')) {
            Schema::table('umkm', function (Blueprint $table) {
                $table->dropColumn('metode_pembayaran');
            });
        }

        if (! Schema::hasColumn('pesanan', 'biaya_admin')) {
            Schema::table('pesanan', function (Blueprint $table) {
                $table->decimal('biaya_admin', 12, 2)->default(0);
            });
        }

        if (! Schema::hasColumn('pesanan', 'dana_dicairkan')) {
            Schema::table('pesanan', function (Blueprint $table) {
                $table->boolean('dana_dicairkan')->default(false);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('umkm', 'metode_pembayaran')) {
            Schema::table('umkm', function (Blueprint $table) {
                $table->json('metode_pembayaran')->nullable();
            });
        }

        if (Schema::hasColumn('pesanan', 'biaya_admin')) {
            Schema::table('pesanan', function (Blueprint $table) {
                $table->dropColumn('biaya_admin');
            });
        }

        if (Schema::hasColumn('pesanan', 'dana_dicairkan')) {
            Schema::table('pesanan', function (Blueprint $table) {
                $table->dropColumn('dana_dicairkan');
            });
        }
    }
};
