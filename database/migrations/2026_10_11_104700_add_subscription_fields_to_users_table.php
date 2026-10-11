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
        Schema::table('users', function (Blueprint $table) {
            $table->string('product_id')->nullable()->after('remember_token');
            $table->string('package')->nullable()->after('product_id');
            $table->boolean('is_subscribed')->default(false)->after('package');
            $table->string('revenuecat_id')->nullable()->after('is_subscribed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['product_id', 'package', 'is_subscribed', 'revenuecat_id']);
        });
    }
};
