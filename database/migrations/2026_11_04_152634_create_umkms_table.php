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
            $table->text('alamat_lengkap');
            $table->string('foto_toko')->nullable();
            $table->time('jam_buka');
            $table->time('jam_tutup');
            $table->string('status_operasional');
            $table->boolean('status_verifikasi')->default(false);
            $table->decimal('saldo', 15, 2)->default(0);
            $table->json('metode_pembayaran');
        });
    }

    public function down(): void {
        Schema::dropIfExists('umkm');
    }
};