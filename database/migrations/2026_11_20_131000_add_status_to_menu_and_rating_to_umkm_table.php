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
        if (! Schema::hasColumn('menu', 'status')) {
            Schema::table('menu', function (Blueprint $table) {
                $table->boolean('status')->default(true);
            });
        }

        if (! Schema::hasColumn('umkm', 'rating')) {
            Schema::table('umkm', function (Blueprint $table) {
                $table->decimal('rating', 3, 2)->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('menu', 'status')) {
            Schema::table('menu', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        if (Schema::hasColumn('umkm', 'rating')) {
            Schema::table('umkm', function (Blueprint $table) {
                $table->dropColumn('rating');
            });
        }
    }
};
