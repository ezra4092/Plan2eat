<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('detail_meal_plan', function (Blueprint $table) {
            $table->id('id_detail_plan');
            $table->foreignId('id_plan')->constrained('meal_plan', 'id_plan')->onDelete('cascade');
            $table->foreignId('id_menu')->constrained('menu', 'id_menu')->onDelete('cascade');
            $table->date('jadwal_makan');
            $table->string('waktu_makan'); // Contoh isi: 'Sarapan', 'Makan Siang', 'Makan Malam'
        });
    }

    public function down(): void {
        Schema::dropIfExists('detail_meal_plan');
    }
};