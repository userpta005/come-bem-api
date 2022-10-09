<?php

namespace Database\Seeders;

use App\Models\CategoryNcm;
use App\Models\MeasurementUnit;
use App\Models\Ncm;
use Illuminate\Database\Seeder;

class NcmTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $handle = fopen(resource_path('utils/ncm.csv'), "r");
        $header = true;

        while ($csvLine = fgetcsv($handle, 1000, ",")) {

            if ($header) {
                $header = false;
            } else {
                $category = CategoryNcm::updateOrCreate(
                    ['description' => $csvLine[1]]
                );

                $measurement = MeasurementUnit::updateOrCreate(
                    ['name' => $csvLine[7]],
                    [
                        'name' => $csvLine[7],
                        'initials' => $csvLine[6],
                        'status' => \App\Enums\Common\Status::ACTIVE
                    ]
                );

                $csvLine[5] ? $fim = ukDate($csvLine[5]) : $fim = NULL;
                $csvLine[8] ? $gtinp = ukDate($csvLine[8]) : $gtinp = NULL;
                $csvLine[9] ? $gtinh = ukDate($csvLine[9]) : $gtinh = NULL;

                Ncm::create([
                    'code' => $csvLine[0],
                    'category_id' => $category->id,
                    'ipi' => $csvLine[3],
                    'dt_start' => ukDate($csvLine[4]),
                    'dt_end' => $fim,
                    'description' => $csvLine[2],
                    'gtinp' => $gtinp,
                    'gtinh' => $gtinh,
                    'um_id' => $measurement->id,
                ]);
            }
        }
    }
}
