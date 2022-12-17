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
        Schema::create('credit_purchases', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->decimal('amount', 12, 2);
            $table->foreignId('payment_method_id')->constrained();
            $table->foreignId('account_id')->constrained();
            $table->char('status', 1);
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
        Schema::dropIfExists('credit_purchases');
    }
};
