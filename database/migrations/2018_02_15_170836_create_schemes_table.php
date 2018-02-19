<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schemes', function (Blueprint $table) {
            $table->string('scode')->primary();
            $table->unsignedInteger('company_id')->index();
            $table->unsignedInteger('scheme_category_id')->index();
            $table->string('payout')->nullable();
            $table->string('reinvestment')->nullable();
            $table->string('name');
            $table->double('net_value')->nullable();
            $table->double('repurchase_price')->nullable();
            $table->double('sale_price')->nullable();
            $table->timestamp('date');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('scheme_category_id')->references('id')->on('scheme_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schemes');
    }
}
