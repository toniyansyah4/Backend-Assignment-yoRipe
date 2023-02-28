<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::select('id', 'names')->get();

        foreach ($roles as $role) {
            $this->check($role);
        }
    }

    public function check($role)
    {
        if ($role->names == 'user') {
            $conditions = [
                'blog-create',
                'blog-edit',
                'blog-delete',
                'blogs-list',
                'blogs-search',
                'blogs-show',
                'category-list',
                'comment-create',
                'comment-edit',
                'comment-delete',
            ];
        } elseif ($role->names == 'manager') {
            $conditions = [
                'blog-create',
                'blog-edit',
                'blog-delete',
                'blogs-list',
                'blogs-create',
                'blogs-edit',
                'blogs-delete',
                'blogs-show',
                'blogs-search',
                'category-list',
                'comment-create',
                'comment-edit',
                'comment-delete',
            ];
        } elseif ($role->names == 'admin') {
            $conditions = [
                'role-list',
                'role-create',
                'role-edit',
                'role-delete',
                'blog-list',
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
        }

        foreach ($conditions as $condition) {
            $permission = Permission::select('id', 'names')
                ->where('names', $condition)
                ->first();
            RolePermission::create([
                'role_id' => $role->id,
                'permission_id' => $permission->id,
            ]);
        }
    }
}
