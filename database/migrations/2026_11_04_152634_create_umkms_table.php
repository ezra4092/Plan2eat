<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('umkm', function (Blueprint $table) {
            $table->id('id_umkm');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->string('nama_vendor');
            $table->text('deskripsi_toko')->nullable();
            $table->string('status_operasional')->nullable();
            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('titik_koordinat_lokasi')->nullable();
        });
    }
    public function down(): void {
        Schema::dropIfExists('umkm');
    }
};