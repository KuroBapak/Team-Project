<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image');
            $table->decimal('price', 15, 0);
            $table->enum('size', ['kecil', 'sedang', 'besar']);
            $table->integer('stock')->default(0);
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
