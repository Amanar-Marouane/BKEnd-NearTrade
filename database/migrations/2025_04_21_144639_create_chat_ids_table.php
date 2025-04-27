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
        Schema::create('chat_ids', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user1');
            $table->foreign('user1')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('user2');
            $table->foreign('user2')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_ids');
    }
};
