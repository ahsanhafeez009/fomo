<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_email');
            $table->string('company_type');
            $table->string('company_number');
            $table->string('decoded');
            $table->string('active');
            $table->string('status');
            $table->string('sale_rept');
            $table->string('sale_terms');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('area');
            $table->string('designation');
            $table->string('website');
            $table->string('contact_person');
            $table->string('contact_email');
            $table->string('contact_number');
            $table->string('address');
            $table->string('remarks');
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
        Schema::dropIfExists('companies');
    }
}
