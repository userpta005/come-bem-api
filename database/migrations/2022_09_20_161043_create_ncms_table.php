<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNcmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ncms', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignId('category_id')->constrained('category_ncms');
            $table->string('ipi')->nullable();
            $table->date('dt_start')->nullable();
            $table->date('dt_end')->nullable();
            $table->foreignId('um_id')->constrained('measurement_units');
            $table->text('description');
            $table->date('gtinp')->nullable();
            $table->date('gtinh')->nullable();
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
        Schema::dropIfExists('ncms');
    }
}
