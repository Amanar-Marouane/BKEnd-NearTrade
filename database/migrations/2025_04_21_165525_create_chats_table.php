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
        Schema::create('chats', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('chat_id');
            $table->foreign('chat_id')->references('id')->on('chat_ids')->onDelete('restrict');
            $table->uuid('sender_id');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('restrict');
            $table->uuid('receiver_id');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('restrict');
            $table->string('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
