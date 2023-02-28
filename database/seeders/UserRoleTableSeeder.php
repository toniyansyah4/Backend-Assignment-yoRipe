<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleTableSeeder extends Seeder
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
            $user = $this->check($role->names);
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]);
        }
    }

    public function check($condition)
    {
        return User::select('id')
            ->where('email', $condition . '@example.com')
            ->first();
    }
}
