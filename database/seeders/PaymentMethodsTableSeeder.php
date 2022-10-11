<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::query()
            ->updateOrCreate(
                ['id' => 1],
                [
                    'code' => '0001',
                    'name' => 'Dinheiro',
                    'status' => \App\Enums\Common\Status::ACTIVE
                ]
            );

        PaymentMethod::query()
            ->updateOrCreate(
                ['id' => 2],
                [
                    'code' => '0002',
                    'name' => 'Cartão de Débito',
                    'status' => \App\Enums\Common\Status::ACTIVE
                ]
            );

        PaymentMethod::query()
            ->updateOrCreate(
                ['id' => 3],
                [
                    'code' => '0003',
                    'name' => 'Cartão de Crédito',
                    'status' => \App\Enums\Common\Status::ACTIVE
                ]
            );

        PaymentMethod::query()
            ->updateOrCreate(
                ['id' => 4],
                [
                    'code' => '0004',
                    'name' => 'Pix',
                    'status' => \App\Enums\Common\Status::ACTIVE
                ]
            );
    }
}
