<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ১. প্রথমে আমরা রোল এবং পারমিশন সিডারটি রান করবো
        $this->call([
            RolePermissionSeeder::class,
            DefaultErpDataSeeder::class,
        ]);


    }
}
