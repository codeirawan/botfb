<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('facebook_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('fb_user_id', 100)->unique();
            $table->text('access_token');
            $table->string('app_id', 100)->nullable();
            $table->string('app_secret', 100)->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facebook_accounts');
    }
};
