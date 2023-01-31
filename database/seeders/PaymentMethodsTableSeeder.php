<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
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
                    'code' => 'DINHEIRO',
                    'name' => 'Dinheiro',
                    'status' => \App\Enums\Common\Status::ACTIVE
                ]
            );

        PaymentMethod::query()
            ->updateOrCreate(
                ['id' => 2],
                [
                    'code' => 'DINHEIRO',
                    'name' => 'Cartão de Débito',
                    'status' => \App\Enums\Common\Status::ACTIVE
                ]
            );

        PaymentMethod::query()
            ->updateOrCreate(
                ['id' => 3],
                [
                    'code' => 'CARTAO',
                    'name' => 'Cartão de Crédito',
                    'status' => \App\Enums\Common\Status::ACTIVE
                ]
            );

        PaymentMethod::query()
            ->updateOrCreate(
                ['id' => 4],
                [
                    'code' => 'PIX',
                    'name' => 'Pix',
                    'status' => \App\Enums\Common\Status::ACTIVE
                ]
            );
    }
}
