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
        Schema::create('states', function (Blueprint $table) {
            $table->id('state_id');
            $table->string('state_name', 100);
            $table->bigInteger('country_id')->unsigned();
            $table->foreign('country_id',)->references('country_id')->on('countries');
            $table->boolean('state_status')->default(1)->comment("1:Active, 0:Inactive");
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
        Schema::dropIfExists('states');
    }
};
