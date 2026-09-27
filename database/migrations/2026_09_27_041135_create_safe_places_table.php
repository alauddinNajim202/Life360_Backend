<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('safe_places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('circle_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
            $table->integer('radius')->default(200); // in meters
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('safe_places');
    }
};
