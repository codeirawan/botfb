<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_post', function (Blueprint $table) {
            $table->id();

            // Foreign keys to groups and posts
            $table->foreignId('group_id')
                ->constrained('groups')
                ->onDelete('cascade');

            $table->foreignId('post_id')
                ->constrained('posts')
                ->onDelete('cascade');

            // Optional timestamps if you want to track when it was added
            $table->timestamps();

            // Prevent duplicates (same post sent to same group twice)
            $table->unique(['group_id', 'post_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_post');
    }
};
