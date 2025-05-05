<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('order_code');                 // “pinjam” dari orders
            $table->enum('sender', ['user', 'admin', 'delivery']);
            $table->text('message');
            $table->timestamps();

            // Relasi:
            $table->foreign('order_code')
                  ->references('order_code')
                  ->on('orders')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropForeign(['order_code']);
        });
        Schema::dropIfExists('chats');
    }
};

