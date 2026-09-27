<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sos_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('circle_id')->constrained()->onDelete('cascade');
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
            $table->integer('battery_level');
            $table->enum('status', ['active', 'resolved'])->default('active');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sos_alerts');
    }
};
