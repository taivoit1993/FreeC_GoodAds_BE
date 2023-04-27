<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->string("social_id",55)->unique()->nullable();
            $table->string("social_name",55)->nullable();
            $table->string("social_email",55)->nullable();
            $table->string("social_avatar",255)->nullable();
            $table->string("token",512)->nullable();
            $table->string("refreshToken", 512)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};
