<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class DateTimeController extends Controller
{
    public function up()
    {
        Schema::create('your_table', function (Blueprint $table) {
            $table->dateTime('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('your_table');
    }
}
