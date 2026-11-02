<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // ক্যাশ রিসেট করা
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        
        $roles = ['Owner', 'Office', 'Salesman', 'Warehouse', 'Delivery'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
