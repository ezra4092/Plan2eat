<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'suspended_until')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->timestamp('suspended_until')->nullable()->after('is_suspended');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'suspended_until')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropColumn('suspended_until');
            });
        }
    }
};
