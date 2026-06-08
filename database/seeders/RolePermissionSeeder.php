<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view dashboard',
            'manage categories',
            'manage products',
            'manage tables',
            'manage users',
            'view reports',
            'view logs',
            'manage settings',
            'create transactions',
            'view transactions',
            'reprint receipt',
            'reprint kot',
            'manage settings',
            'void transactions'
        ];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $kasirRole = Role::firstOrCreate(['name' => 'kasir']);

        $adminRole->givePermissionTo(Permission::all());
        $kasirRole->givePermissionTo(['view dashboard', 'create transactions', 'view transactions', 'reprint receipt', 'reprint kot']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@pos.com'],
            ['name' => 'Admin', 'password' => bcrypt('password')]
        );
        $admin->assignRole('admin');

        $kasir = User::firstOrCreate(
            ['email' => 'kasir@pos.com'],
            ['name' => 'Kasir', 'password' => bcrypt('password')]
        );
        $kasir->assignRole('kasir');
    }
}
