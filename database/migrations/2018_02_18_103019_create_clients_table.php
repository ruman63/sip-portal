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
        /*  
            
            //contact
            'address_hno' => 'required',
            'address_area' => 'required',
            'city' => 'required',
            'pincode' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'communication_mode' => 'required',
    
            //account
            'account_type' => 'required',
            'pay_mode' => 'required',
            'nominee_name' => 'required',
            'nominee_relation' => 'required', 
        */
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 50);
            $table->string('email')->unique();
            $table->string('pan', 15);
            $table->string('mapin', 30);
            $table->date('dob');
            $table->char('gender', 1);
            $table->string('occupation', 50);
            $table->string('tax_status', 50);
            $table->string('father_name', 50);
            $table->string('guardian_name', 50);
            $table->string('guardian_pan', 15);

            $table->string('hno', 50);
            $table->string('area', 80);
            $table->string('city', 50);
            $table->string('pincode', 10);
            $table->string('mobile', 15);
            $table->string('communication_mode', 15);            

            $table->string('account_type', 15);
            $table->string('pay_mode', 15);
            $table->string('nominee_name', 50);
            $table->string('nominee_relation', 50);

            $table->string('bank_account_number', 20);
            
            $table->string('password');
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
