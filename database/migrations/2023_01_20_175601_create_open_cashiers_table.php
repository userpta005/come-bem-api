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
        Schema::create('open_cashiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained();
            $table->foreignId('cashier_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->integer('operation');
            $table->uuid('token')->unique();
            $table->decimal('balance', 11, 2);
            $table->decimal('money_change', 11, 2);
            $table->dateTime('date_operation');
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
        Schema::dropIfExists('open_cashiers');
    }
};
