<?php

namespace Database\Seeders;

use App\Enums\ParameterType;
use App\Models\Parameter;
use Illuminate\Database\Seeder;

class ParametersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Parameter::updateOrCreate(
            ['name' => 'test1'],
            [
                'name' => 'test1',
                'description' => 'teste 1',
                'type' => ParameterType::ALPHANUMERIC,
                'value' => 'teste',
            ]
        );

        Parameter::updateOrCreate(
            ['name' => 'test2'],
            [
                'name' => 'test2',
                'description' => 'teste 2',
                'type' => ParameterType::NUMERIC,
                'value' => 123,
            ]
        );

        Parameter::updateOrCreate(
            ['name' => 'test3'],
            [
                'name' => 'test3',
                'description' => 'teste 3',
                'type' => ParameterType::LOGICAL,
                'value' => true,
            ]
        );
    }
}
