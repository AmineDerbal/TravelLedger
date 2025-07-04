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
            ['name' => 'view Dashboard'],
            ['name' => 'view Transaction'],
            ['name' => 'create Transaction'],
            ['name' => 'edit Transaction'],
            ['name' => 'edit Transaction own'],
            ['name' => 'destroy Transaction'],
            ['name' => 'destroy Transaction own'],
            ['name' => 'view Parameter'],
            ['name' => 'view Ledger'],
            ['name' => 'manage Ledger'],
            ['name' => 'manage User'],

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']]);
        }

        $adminRole->syncPermissions(Permission::all());

        $userRole->givePermissionTo([
            'view Dashboard',
            'view Transaction',
            'create Transaction',
            'edit Transaction own',
            'destroy Transaction own',

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
