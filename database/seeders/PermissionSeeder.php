<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        $permissions = [
            ['name' => 'transactions.create','group' => 'transactions'],
            ['name' => 'transactions.edit','group' => 'transactions'],
            ['name' => 'transactions.edit.own','group' => 'transactions'],
            ['name' => 'transactions.destroy','group' => 'transactions'],
            ['name' => 'transactions.destroy.own','group' => 'transactions'],
            ['name' => 'ledgers.view', 'group' => 'ledgers'],
            ['name' => 'ledgers.manage', 'group' => 'ledgers'],
            ['name' => 'users.manage', 'group' => 'users'],

        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission['name'], 'group' => $permission['group']]);
        }

        $admin->givePermissionTo('*');

        $user->givePermissionTo([
            'transactions.create',
            'transactions.edit.own',
            'transactions.destroy.own',

        ]);


    }
}
