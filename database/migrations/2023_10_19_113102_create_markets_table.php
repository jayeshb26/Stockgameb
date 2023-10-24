<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('gamename');
            $table->string('market');
            $table->string('mon_start_time');
            $table->string('mon_close_time');
            $table->string('tue_start_time');
            $table->string('tue_close_time');
            $table->string('wed_start_time');
            $table->string('wed_close_time');
            $table->string('thu_start_time');
            $table->string('thu_close_time');
            $table->string('fri_start_time');
            $table->string('fri_close_time');
            $table->string('sat_start_time');
            $table->string('sat_close_time');
            $table->string('sun_start_time');
            $table->string('sun_close_time');
            $table->boolean('status')->default('Active');
            $table->bigInteger('bucket');
            $table->bigInteger('bucket3');
            $table->bigInteger('bucket5');
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
        Schema::dropIfExists('markets');
    }
}
