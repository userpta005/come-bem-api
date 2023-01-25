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
        Schema::table('movement_types', function (Blueprint $table) {
            $table->char('class', 1)->change();
            $table->renameColumn('type', 'name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movement_types', function (Blueprint $table) {
            $table->renameColumn('name', 'type');
        });
    }
};
