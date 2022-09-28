<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Rule;

class DefenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createPermissions();
        $this->createRoles();
        $this->createRules();
    }

    private function createPermissions()
    {
        $array = Permission::defaultPermissions();

        $percorrerArray = function ($array) use (&$percorrerArray) {
            foreach ($array as $value) {
                if (is_array($value) && !array_key_exists('name', $value)) {
                    $percorrerArray($value);
                }
                if (is_array($value) && array_key_exists('name', $value)) {
                    Permission::updateOrCreate(
                        ['name' => $value["name"]],
                        $value
                    );
                }
            }
        };

        $percorrerArray($array);

        $this->command->info('Default Permissions added.');
    }

    private function createRoles()
    {
        $admin = Role::updateOrCreate([
            'name' => 'administrator'
        ], [
            'name' => 'administrator',
            'description' => 'Administrador'
        ]);

        $admin->permissions()->sync(Permission::all());

        $this->command->info('Admin will have full rights');

        $contractor = Role::updateOrCreate([
            'name' => 'contractor'
        ], [
            'name' => 'contractor',
            'description' => 'Contratante'
        ]);

        $contractor->permissions()->sync(Permission::query()->where(
            [
                ['name', 'not like', '%tenants_%'],
                ['name', 'not like', '%sections_%'],
                ['name', 'not like', '%payment-methods_%'],
                ['name', 'not like', '%measurement-units_%'],
                ['name', 'not like', '%cities_%'],
                ['name', 'not like', '%states_%'],
                ['name', 'not like', '%ncms_%'],
                ['name', 'not like', '%roles_%'],
                ['name', 'not like', '%permissions_%'],
                ['name', 'not like', '%parameters_%'],
                ['name', 'not like', '%faqs_%'],
                ['name', 'not like', '%banners_%'],
            ]
        )->get());
    }

    private function createRules()
    {
        $rules =  Rule::defaultRules();

        foreach ($rules as $rule) {
            Rule::updateOrCreate(
                ['name' => $rule["name"]],
                $rule
            );
        }
    }
}
