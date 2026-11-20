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
<<<<<<< HEAD
            $table->string('status_operasional');
            $table->enum('status_verifikasi', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan')->nullable();
=======
            $table->boolean('status_operasional')->default(true);
            $table->boolean('status_verifikasi')->default(false);
            $table->decimal('rating', 3, 2)->default(0);
>>>>>>> 8df6092 (feat: regist process, interface and logical role pelanggan finish)
            $table->decimal('saldo', 15, 2)->default(0);
            $table->json('metode_pembayaran');
        });
    }

    public function down(): void {
        Schema::dropIfExists('umkm');
    }
};