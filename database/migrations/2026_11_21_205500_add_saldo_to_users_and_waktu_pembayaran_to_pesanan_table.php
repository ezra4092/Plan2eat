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
        if (! Schema::hasColumn('users', 'saldo')) {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('saldo', 15, 2)->default(0);
            });
        }

        if (! Schema::hasColumn('pesanan', 'waktu_pembayaran')) {
            Schema::table('pesanan', function (Blueprint $table) {
                $table->timestamp('waktu_pembayaran')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'saldo')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('saldo');
            });
        }

        if (Schema::hasColumn('pesanan', 'waktu_pembayaran')) {
            Schema::table('pesanan', function (Blueprint $table) {
                $table->dropColumn('waktu_pembayaran');
            });
        }
    }
};
