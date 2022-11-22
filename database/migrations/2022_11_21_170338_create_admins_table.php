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
        Schema::create('admins', function (Blueprint $table) {
            $table->id('admin_id');
            $table->string('admin_name', 100);
            $table->string('admin_email', 100);
            $table->string('admin_password', 200);
            $table->boolean('admin_status')->comment("1: Active,0:Inactive")->default(1);
            $table->enum('admin_role', [1, 2])->comment("1: Super Admin, 2: Admin")->default(2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
};
