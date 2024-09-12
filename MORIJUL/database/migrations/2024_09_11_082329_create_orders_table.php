<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('buyer_name');
            $table->string('room_number');
            $table->string('payment_type');
            $table->string('payment_status')->default('pending'); // Pastikan ada default value
            $table->decimal('total_amount', 10, 2)->nullable(); // Menambahkan kolom total_amount jika diperlukan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
