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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('id_umkm')->constrained('umkm', 'id_umkm')->onDelete('cascade');
            $table->foreignId('id_plan')->nullable()->constrained('meal_plan', 'id_plan')->onDelete('set null');
            $table->dateTime('tanggal_waktu_pesanan');
            $table->dateTime('jadwal_pengiriman');
            $table->string('metode_pembayaran');
            $table->decimal('total_pembayaran', 12, 2);
            $table->string('status_pesanan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
