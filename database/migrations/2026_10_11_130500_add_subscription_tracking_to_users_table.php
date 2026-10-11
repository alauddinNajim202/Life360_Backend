<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('subscription_status')->nullable()->after('is_subscribed'); // active, cancelled, expired, paused
            $table->string('entitlement_id')->nullable()->after('subscription_status');
            $table->timestamp('subscription_expires_at')->nullable()->after('entitlement_id');
            $table->timestamp('subscription_purchased_at')->nullable()->after('subscription_expires_at');
            $table->string('subscription_event')->nullable()->after('subscription_purchased_at'); // last event type
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_status',
                'entitlement_id',
                'subscription_expires_at',
                'subscription_purchased_at',
                'subscription_event',
            ]);
        });
    }
};
