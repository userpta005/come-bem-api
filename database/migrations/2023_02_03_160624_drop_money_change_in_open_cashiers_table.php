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
        Schema::table('open_cashiers', function (Blueprint $table) {
            $table->dropColumn('money_change');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('open_cashiers', function (Blueprint $table) {
            $table->decimal('money_change', 11, 2)->nullable();
        });
    }
};
