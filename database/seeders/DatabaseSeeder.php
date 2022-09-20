<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
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
            NcmTableSeeder::class
        ]);
    }
}
