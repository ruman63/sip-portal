<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUccAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ucc_accounts', function (Blueprint $table) {
            $table->string('ucc', 6)->primaryKey();
            $table->unsignedInteger('owner_id')->unique();
            $table->string('holding_code', 2);
            $table->string('first_applicant_name', 70);
            $table->string('second_applicant_name', 70);
            $table->char('transaction_mode', 1);
            $table->string('depository', 5);
            $table->string('depository_dp_id', 8);
            $table->string('depository_client_id', 16);
            $table->string('tax_status_code', 2);
            $table->string('occupation_code', 2);
            $table->string('mapin_no', 16);
            $table->string('first_applicant_pan', 12);
            $table->string('second_applicant_pan', 12);
            $table->string('communication_mode', 2);
            $table->string('dividend_pay_mode', 2);
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
        Schema::dropIfExists('ucc_accounts');
    }
}
