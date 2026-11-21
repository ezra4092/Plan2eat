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
            $table->enum('status_verifikasi', ['menunggu', 'disetujui', 'ditolak', 'ditangguhkan'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->string('status_operasional', 50)->default('Buka');
            $table->decimal('rating', 3, 2)->default(0);
            $table->decimal('saldo', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('umkm');
    }
};