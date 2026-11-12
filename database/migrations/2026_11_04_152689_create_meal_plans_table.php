<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('meal_plan', function (Blueprint $table) {
            $table->id('id_plan');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->string('judul_plan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
        });
    }

    public function down(): void {
        Schema::dropIfExists('meal_plan');
    }
};