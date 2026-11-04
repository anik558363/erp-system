<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultErpDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Default Users with Roles & Active Status
        $owner = User::firstOrCreate(
            ['email' => 'owner@erp.com'],
            [
                'name' => 'System Owner',
                'status' => 'active', // স্ট্যাটাস একটিভ করা হলো
                'password' => Hash::make('password123'),
            ]
        );
        $owner->assignRole('Owner');

        $office = User::firstOrCreate(
            ['email' => 'office@erp.com'],
            [
                'name' => 'Office Staff',
                'status' => 'active',
                'password' => Hash::make('password123'),
            ]
        );
        $office->assignRole('Office');

        $salesman = User::firstOrCreate(
            ['email' => 'sales@erp.com'],
            [
                'name' => 'Salesman Rahim',
                'status' => 'active',
                'password' => Hash::make('password123'),
            ]
        );
        $salesman->assignRole('Salesman');

        $warehouse = User::firstOrCreate(
            ['email' => 'warehouse@erp.com'],
            [
                'name' => 'Warehouse Manager',
                'status' => 'active',
                'password' => Hash::make('password123'),
            ]
        );
        $warehouse->assignRole('Warehouse');

        $delivery = User::firstOrCreate(
            ['email' => 'delivery@erp.com'],
            [
                'name' => 'Delivery Man Karim',
                'status' => 'active',
                'password' => Hash::make('password123'),
            ]
        );
        $delivery->assignRole('Delivery');
    }
}
