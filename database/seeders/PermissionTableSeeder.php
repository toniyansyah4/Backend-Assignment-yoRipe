<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'blog-create',
            'blog-edit',
            'blog-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'blogs-list',
            'blogs-create',
            'blogs-edit',
            'blogs-delete',
            'blogs-show',
            'blogs-search',
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
            'category-show',
            'category-search',
            'comment-create',
            'comment-edit',
            'comment-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'names' => $permission,
            ]);
        }
    }
}
