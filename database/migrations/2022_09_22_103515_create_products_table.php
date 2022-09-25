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
            $table->id();
            $table->string('image')->nullable();
            $table->string('name');
            $table->foreignId('section_id')->constrained();
            $table->foreignId('ncm_id')->constrained();
            $table->foreignId('um_id')->constrained('measurement_units');
            $table->tinyInteger('nutritional_classification')->default(1);
            $table->tinyInteger('status');
            $table->boolean('has_lot')->default(false);
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
