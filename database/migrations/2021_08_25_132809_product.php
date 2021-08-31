<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Product extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('level')->nullable();
            $table->string('name')->nullable();
            $table->integer('price')->nullable();
            $table->float('price_num')->nullable();
            $table->string('image')->nullable();
            $table->integer('order')->nullable();
            $table->float('point')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
