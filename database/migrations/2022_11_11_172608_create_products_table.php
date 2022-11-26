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
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('name', 200);
            $table->string('slug', 200);
            $table->string('sku', 200);
            $table->text('ingredients');
            $table->text('uses');
            $table->text('doses');
            $table->text('short_description');
            $table->text('long_description');
            $table->decimal('gst', 5, 2)->nullable();
            $table->text('main_image');
            $table->boolean('most_selling')->default(0)->comment('0:No, 1:Yes');
            $table->boolean('featured')->default(0)->comment('0:No, 1:Yes');
            $table->boolean('seasonal')->default(0)->comment('0:No, 1:Yes');
            $table->boolean('status')->default(1)->comment('1:Yes, 0:No');
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
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
        Schema::dropIfExists('products');
    }
};
