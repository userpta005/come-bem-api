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
        Schema::table('stores', function (Blueprint $table) {
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('pix_key')->nullable();
            $table->string('bank')->nullable();
            $table->string('agency')->nullable();
            $table->string('account')->nullable();
            $table->string('whatsapp')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->string('email_digital')->nullable();
            $table->tinyInteger('signature')->nullable();
            $table->date('dt_accession')->nullable();
            $table->date('due_date')->nullable();
            $table->tinyInteger('due_day')->nullable();
            $table->tinyInteger('number_equipment')->default(0);
            $table->decimal('lending_value', 12, 2)->default(0);
            $table->decimal('pix_rate', 12, 2)->default(0);
            $table->decimal('card_rate', 12, 2)->default(0);
            $table->string('observation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn([
                'latitude',
                'longitude',
                'pix_key',
                'bank',
                'agency',
                'account',
                'whatsapp',
                'type',
                'email_digital',
                'signature',
                'dt_accession',
                'due_date',
                'due_day',
                'number_equipment',
                'lending_value',
                'pix_rate',
                'card_rate',
                'observation',
            ]);
        });
    }
};
