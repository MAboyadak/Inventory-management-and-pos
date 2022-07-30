<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            // $table->integer('customer_group_id');
            $table->string('name');
            $table->string('company_name')->nullable();
            // $table->string('email')->nullable();
            $table->string('phone_number');
            // $table->string('address');
            $table->string('city');
            // $table->string('state')->nullable();
            // $table->string('postal_code')->nullable();
            // $table->string('country')->nullable();
            // $table->boolean('is_active')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
