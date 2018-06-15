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
            $table->string('scheme_code', 20)->primary();
            $table->string('unique_no', 5);
            $table->string('rta_scheme_code', 10);
            $table->string('amc_scheme_code', 10);
            $table->string('isin', 12);
            $table->string('amc_code', 50);
            $table->string('scheme_type', 20);
            $table->string('scheme_plan', 10);
            $table->string('scheme_name', 200);
            $table->string('purchase_allowed', 20);
            $table->string('channel_partner_code', 20);
            $table->string('rta_agent_code', 50);
            $table->float('nav')->nullable();
            $table->timestamp('nav_date')->nullable();
            $table->string('start_date', 15)->nullable();
            $table->string('end_date', 15)->nullable();
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
        Schema::dropIfExists('schemes');
    }
}
