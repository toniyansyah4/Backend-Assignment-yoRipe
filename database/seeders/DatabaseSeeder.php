<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Request;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
            UserTableSeeder::class,
            RoleTableSeeder::class,
            PermissionTableSeeder::class,
            UserRoleTableSeeder::class,
            RolePermissionTableSeeder::class,
            CategoriesTableSeeder::class,
        ]);
    }
}
