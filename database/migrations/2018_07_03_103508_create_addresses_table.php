<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id')->unique();
            $table->string('first_line', 50);
            $table->string('second_line', 20)->nullable();
            $table->string('third_line', 20)->nullable();
            $table->string('city', 30);
            $table->string('state', 35);
            $table->string('pincode', 6);
            $table->string('country', 30);
            $table->string('residence_phone', 10)->nullable();
            $table->string('residence_fax', 10)->nullable();
            $table->string('office_phone', 10)->nullable();
            $table->string('office_fax', 10)->nullable();
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
        Schema::dropIfExists('addresses');
    }
}
