<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('location_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
            $table->decimal('speed_kmh', 5, 2)->default(0);
            $table->timestamp('recorded_at')->useCurrent();
            
            $table->index(['user_id', 'recorded_at']); // Very important for performance
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('location_histories');
    }
};
