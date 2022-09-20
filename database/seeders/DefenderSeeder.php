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
