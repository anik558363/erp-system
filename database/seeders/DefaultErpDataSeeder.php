<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DefaultErpDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Default Users with Roles
        $owner = User::firstOrCreate(
            ['email' => 'owner@erp.com'],
            [
                'name' => 'System Owner',
                'password' => Hash::make('password123'),
            ]
        );
        $owner->assignRole('Owner');

        $office = User::firstOrCreate(
            ['email' => 'office@erp.com'],
            [
                'name' => 'Office Staff',
                'password' => Hash::make('password123'),
            ]
        );
        $office->assignRole('Office');

        $salesman = User::firstOrCreate(
            ['email' => 'sales@erp.com'],
            [
                'name' => 'Salesman Rahim',
                'password' => Hash::make('password123'),
            ]
        );
        $salesman->assignRole('Salesman');

        $warehouse = User::firstOrCreate(
            ['email' => 'warehouse@erp.com'],
            [
                'name' => 'Warehouse Manager',
                'password' => Hash::make('password123'),
            ]
        );
        $warehouse->assignRole('Warehouse');

        $delivery = User::firstOrCreate(
            ['email' => 'delivery@erp.com'],
            [
                'name' => 'Delivery Man Karim',
                'password' => Hash::make('password123'),
            ]
        );
        $delivery->assignRole('Delivery');
        
    }
}
