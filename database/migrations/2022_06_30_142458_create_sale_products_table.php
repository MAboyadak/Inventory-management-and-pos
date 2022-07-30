<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sale_id')->nullable();
            $table->foreign('sale_id')->references('id')->on('sales')->onUpdate('cascade')->onDelete('set null');
            $table->unsignedInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('set null');

            $table->double('qty');
            $table->integer('unit_id');
            $table->double('unit_price');
            // $table->double('discount');
            // $table->double('tax_rate');
            // $table->double('tax');
            $table->double('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_products');
    }
}
