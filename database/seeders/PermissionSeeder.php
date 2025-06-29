<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        $permissions = [
            ['name' => 'dashboard.view'],
            ['name' => 'transactions.view'],
            ['name' => 'transactions.create'],
            ['name' => 'transactions.edit'],
            ['name' => 'transactions.edit.own'],
            ['name' => 'transactions.destroy'],
            ['name' => 'transactions.destroy.own'],
            ['name' => 'parameters.view'],
            ['name' => 'ledgers.view'],
            ['name' => 'ledgers.manage'],
            ['name' => 'users.manage'],

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']]);
        }

        $adminRole->syncPermissions(Permission::all());

        $userRole->givePermissionTo([
            'dashboard.view',
            'transactions.view',
            'transactions.create',
            'transactions.edit.own',
            'transactions.destroy.own',

        ]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@localhost'], // Only used to look up the user
            [
                'name' => 'Admin',
                'password' => Hash::make('admin'),
            ]
        );

        $admin->assignRole('admin');




    }
}
