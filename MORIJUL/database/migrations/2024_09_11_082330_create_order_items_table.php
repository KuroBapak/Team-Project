<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // Define foreign keys to enforce relations
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('cascade');
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('price');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
