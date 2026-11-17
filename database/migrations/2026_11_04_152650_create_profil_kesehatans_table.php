<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('profil_kesehatan', function (Blueprint $table) {
            $table->id('id_profil');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->json('riwayat_alergi')->nullable();
            $table->string('jenis_diet')->nullable();
            $table->text('catatan_khusus')->nullable();
            $table->bigInteger('batas_budget')->nullable();
        });
    }
    public function down(): void {
        Schema::dropIfExists('profil_kesehatan');
    }
};