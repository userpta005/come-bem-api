<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('logo')->nullable()->change();
            $table->string('nif')->nullable()->change();
            $table->string('full_name')->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('zip_code')->nullable()->change();
            $table->string('address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('logo')->change();
            $table->string('nif')->change();
            $table->string('full_name')->change();
            $table->string('name')->change();
            $table->string('email')->change();
            $table->string('phone')->change();
            $table->string('zip_code')->change();
            $table->string('address')->change();
        });
    }
}
