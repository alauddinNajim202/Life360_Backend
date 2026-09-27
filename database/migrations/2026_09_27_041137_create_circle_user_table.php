<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('circle_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('circle_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['admin', 'member'])->default('member');
            $table->string('relation_tag')->nullable(); // Relation with admin (e.g. Brother)
            $table->timestamp('joined_at')->useCurrent();
            
            $table->unique(['circle_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('circle_user');
    }
};
