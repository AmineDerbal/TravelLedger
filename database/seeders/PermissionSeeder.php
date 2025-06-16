<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        $permissions = [
            ['name' => 'transactions.create'],
            ['name' => 'transactions.edit'],
            ['name' => 'transactions.edit.own'],
            ['name' => 'transactions.destroy'],
            ['name' => 'transactions.destroy.own'],
            ['name' => 'ledgers.view'],
            ['name' => 'ledgers.manage'],
            ['name' => 'users.manage'],

        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission['name']]);
        }

        $adminRole->syncPermissions(Permission::all());

        $userRole->givePermissionTo([
            'transactions.create',
            'transactions.edit.own',
            'transactions.destroy.own',

        ]);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@localhost',
            'password' => Hash::make('admin'),
            
        ]);

        $admin->assignRole('admin');




    }
}