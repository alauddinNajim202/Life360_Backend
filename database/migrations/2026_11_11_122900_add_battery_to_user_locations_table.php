<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_locations', function (Blueprint $table) {
            $table->tinyInteger('battery_level')->nullable()->after('heading'); // 0–100
            $table->boolean('is_charging')->default(false)->after('battery_level');
        });
    }

    public function down(): void
    {
        Schema::table('user_locations', function (Blueprint $table) {
            $table->dropColumn(['battery_level', 'is_charging']);
        });
    }
};

