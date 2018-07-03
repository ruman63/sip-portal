<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 35);
            $table->string('last_name', 35);
            $table->string('email')->nullable();
            $table->string('pan', 15)->unique();
            $table->date('dob')->nullable();
            $table->char('gender', 1)->default('M');
            $table->string('mobile', 15)->nullable();
            $table->string('password');
            $table->string('guardian', 35)->nullable();
            $table->string('guardian_pan', 15)->nullable();
            $table->string('nominee', 35)->nullable();
            $table->string('nominee_relation', 20)->nullable();
            $table->string('tax_status_code', 2)->nullable();
            $table->string('occupation_code', 2)->nullable();
            $table->string('communication_mode', 2)->nullable();
            $table->string('dividend_pay_mode', 2)->nullable();
            $table->string('mapin_no', 16)->nullable();
            $table->unsignedInteger('default_bank_id')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('clients');
    }
}
