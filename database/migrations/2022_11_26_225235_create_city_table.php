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
        Schema::create('cities', function (Blueprint $table) {
            $table->id('city_id');
            $table->string('city_name', 100);
            $table->bigInteger('state_id')->unsigned();
            $table->foreign('state_id')->references('state_id')->on("states");
            $table->boolean('city_status')->default(1)->comment("1:Active, 0:Inactive");
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
        Schema::dropIfExists('city');
    }
};
