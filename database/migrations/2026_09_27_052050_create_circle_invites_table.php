<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('circle_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('circle_id')->constrained()->onDelete('cascade');
            $table->string('invite_code')->unique();
            $table->string('relation_tag'); // e.g. Brother, Sister, Father
            $table->boolean('is_used')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('circle_invites');
    }
};
