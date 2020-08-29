<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandsProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demands_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('users_demand_id')->unsigned()->nullable();
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->float('price');
            $table->timestamps();
            $table->foreign('users_demand_id')->references('id')->on('users_demands');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demands_products');
    }
}
