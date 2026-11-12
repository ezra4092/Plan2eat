<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('flash_sale', function (Blueprint $table) {
            $table->id('id_flash_sale');
            $table->foreignId('id_menu')->constrained('menu', 'id_menu')->onDelete('cascade');
            $table->integer('stok_sisa');
            $table->integer('kuantitas');
            $table->date('tgl_flash_sale');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->decimal('harga_diskon', 12, 2);
            $table->string('status_promo');
        });
    }
    public function down(): void {
        Schema::dropIfExists('flash_sale');
    }
};