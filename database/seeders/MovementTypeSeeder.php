<?php

namespace Database\Seeders;

use App\Models\MovementType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MovementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MovementType::query()
            ->updateOrCreate(
                ['id' => 1],
                [
                    'name' => 'Crédito',
                    'class' => 'E',
                ]
            );

        MovementType::query()
            ->updateOrCreate(
                ['id' => 2],
                [
                    'name' => 'Débito',
                    'class' => 'S',
                ]
            );

    }
}
