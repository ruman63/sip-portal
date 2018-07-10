<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_number', 25);
            $table->string('account_type_code', 2);
            $table->string('micr', 10)->nullable();
            $table->string('ifsc_code', 15);
            $table->unsignedInteger('owner_id');
            $table->string('bank_name', 70)->nullable();
            $table->string('bank_branch', 50)->nullable();
            $table->string('bank_code', 7)->nullable();
            $table->string('bank_city', 30)->nullable();
            $table->string('bank_address', 70)->nullable();
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
        Schema::dropIfExists('bank_accounts');
    }
}
