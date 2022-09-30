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
            ['name' => 'PARAM1'],
            [
                'name' => 'PARAM1',
                'description' => 'Primeiro parâmetro',
                'type' => ParameterType::ALPHANUMERIC,
                'value' => 'alfanumérico',
            ]
        );

        Parameter::updateOrCreate(
            ['name' => 'PARAM2'],
            [
                'name' => 'PARAM2',
                'description' => 'Segundo parâmetro',
                'type' => ParameterType::NUMERIC,
                'value' => 123,
            ]
        );

        Parameter::updateOrCreate(
            ['name' => 'PARAM3'],
            [
                'name' => 'PARAM4',
                'description' => 'terceiro parâmetro',
                'type' => ParameterType::LOGICAL,
                'value' => true,
            ]
        );

        Parameter::updateOrCreate(
            ['name' => 'CATEGF'],
            [
                'name' => 'CATEGF',
                'description' => 'Máscara da categoria financeira',
                'type' => ParameterType::NUMERIC,
                'value' => '1234',
            ]
        );
    }
}
