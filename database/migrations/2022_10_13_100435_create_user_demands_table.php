<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_demands', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name');
            $table->string('type');
            $table->string('barcode');
            $table->string('product_name');
            $table->string('avg_price_aed');
            $table->string('avg_price_usd');
            $table->string('avg_price_eur');
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
        Schema::dropIfExists('user_demands');
    }
};
