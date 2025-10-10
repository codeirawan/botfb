<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->longText('content')->nullable(); // Post message or caption
            $table->string('media_path')->nullable(); // File path if photo/video
            $table->enum('status', ['draft', 'scheduled', 'posted'])->default('draft'); // Current post state
            $table->timestamp('scheduled_at')->nullable(); // When to post (if scheduled)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
