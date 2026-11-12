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
        Schema::create('menu', function (Blueprint $table) {
            $table->id('id_menu');
            $table->foreignId('id_umkm')->constrained('umkm', 'id_umkm')->onDelete('cascade');
            $table->foreignId('id_plan')->nullable()->constrained('meal_plan', 'id_plan')->onDelete('set null');
            $table->string('nama_makanan');
            $table->text('deskripsi_makanan')->nullable();
            $table->integer('kandungan_kalori')->nullable();
            $table->integer('stok_harian')->default(0);
            $table->decimal('harga_normal', 12, 2);
            $table->string('kategori_waktu');
            $table->string('foto_menu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
