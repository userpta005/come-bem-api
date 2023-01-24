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
                    'type' => 'Crédito',
                    'class' => 1
                ]
            );

    }
}
