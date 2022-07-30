<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {

            $table->increments('id');
            // $table->string('reference_no');

            $table->unsignedInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('set null');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('set null');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');

            $table->integer('items_count');
            $table->double('total_qty');
            // $table->double('total_discount');
            // $table->double('total_tax');
            $table->double('total_price');
            $table->double('grand_total');
            $table->double('tax_rate')->nullable();
            // $table->double('order_tax')->nullable();
            // $table->double('order_discount')->nullable();
            $table->double('shipping_cost')->nullable();
            // $table->integer('sale_status');
            $table->integer('payment_status');
            // $table->string('document')->nullable();
            $table->double('paid_amount')->nullable();
            $table->text('note')->nullable();
            // $table->text('sale_note')->nullable();
            // $table->text('staff_note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
