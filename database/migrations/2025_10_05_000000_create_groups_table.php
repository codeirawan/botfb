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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('categories')
                  ->nullOnDelete();

            $table->foreignId('facebook_account_id')
                  ->nullable()
                  ->constrained('facebook_accounts')
                  ->cascadeOnDelete();

            $table->string('fb_group_id', 100)->unique();
            $table->string('name', 150);
            $table->enum('privacy', ['public', 'closed', 'secret'])->default('public');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
