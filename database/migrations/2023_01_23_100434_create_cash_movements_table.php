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
        Schema::create('cash_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained();
            $table->foreignId('cashier_id')->constrained();
            $table->uuid('token')->unique();
            $table->foreignId('movement_type_id')->constrained();
            $table->foreignId('client_id')->nullable()->constrained();
            $table->foreignId('payment_method_id')->constrained();
            $table->decimal('amount', 11, 2);
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
        Schema::dropIfExists('cash_movements');
    }
};
