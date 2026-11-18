<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('id_umkm')->constrained('umkm', 'id_umkm')->onDelete('cascade');
            $table->foreignId('id_plan')->nullable()->constrained('meal_plan', 'id_plan')->onDelete('set null');
            $table->enum('status_pesanan', ['menunggu pembayaran', 'persiapan', 'masak', 'siap diambil', 'selesai', 'dibatalkan'])->default('menunggu pembayaran');
            $table->decimal('total_pembayaran', 12, 2);
            $table->dateTime('tgl_mulai');
            $table->dateTime('tgl_selesai')->nullable();
            $table->string('metode_pembayaran');
            $table->string('metode_pengiriman');
            $table->dateTime('jadwal_pengiriman');
        });
    }
    public function down(): void {
        Schema::dropIfExists('pesanan');
    }
};