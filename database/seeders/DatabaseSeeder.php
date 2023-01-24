<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            StatesTableSeeder::class,
            CitiesTableSeeder::class,
            DefenderSeeder::class,         
            UsersTableSeeder::class,
            ParametersTableSeeder::class,
            PaymentMethodsTableSeeder::class,
            NcmTableSeeder::class,
            MovementTypeSeeder::class
        ]);
    }
}
