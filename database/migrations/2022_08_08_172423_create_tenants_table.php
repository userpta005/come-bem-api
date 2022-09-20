<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained();
            $table->string('contact')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('cellphone')->nullable();
            $table->date('dt_accession')->nullable();
            $table->decimal('value', 12, 2)->default(0);
            $table->tinyInteger('signature');
            $table->tinyInteger('status');
            $table->tinyInteger('due_day');
            $table->date('due_date')->nullable();
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
        Schema::dropIfExists('tenants');
    }
}

