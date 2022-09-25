<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $person = Person::create([
            'name' => 'Dix BPO',
            'email' => 'dix@dixbpo.com',
            'full_name' => 'Dix BPO',
            'nif' => '04496906000108',
            'phone' => '6332155400',
            'address' => 'Quadra 606 Sul, Al. Oscar Niemeyer, QI - 02, HM - 06, Lt. 02',
            'zip_code' => '77016524',
            'city_id' => '443'
        ]);

        $user = User::create([
            'name' => 'Dix BPO',
            'person_id' => $person->id,
            'email' => 'dix@dixbpo.com',
            'password' => bcrypt('1234567o'),
            'status' => \App\Enums\Common\Status::ACTIVE
        ]);

        $user->assignRole('administrator');

        $this->command->info('User ' . $user->name . ' created');
    }
}
