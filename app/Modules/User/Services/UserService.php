<?php

namespace App\Modules\User\Services;

use App\Contract\Services\UserService as ServicesUserService;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserService implements ServicesUserService
{
    protected $entity;
    protected $user;
    protected $role;

    public function __construct(UserRole $entity, User $user, Role $role)
    {
        $this->entity = $entity;
        $this->user = $user;
        $this->role = $role;
    }

    public function rules()
    {
        return [
            'email' => 'required',
            'role' => 'required',
        ];
    }
    public function get($row)
    {
        return $this->entity::with(['user', 'role'])->paginate($row);
    }

    public function store($data)
    {
        $role = $this->user->create([
            'names' => $data['name_role'],
        ]);

        foreach ($data['permissions'] as $permission) {
            $data = $this->entity->create([
                'role_id' => $role->id,
                'permission_id' => $permission['value'],
            ]);
        }

        return $data;
    }

    public function update($data)
    {
        $user = $this->user->where('email', $data['email'])->first();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();

        $role = $this->role->where('names', $data['role'])->first();

        $this->entity->where('user_id', $user->id)->update([
            'role_id' => $role->id,
        ]);

        return $this->entity
            ::with(['role', 'user'])
            ->where('user_id', $user->id)
            ->get();
    }

    public function destroy($param)
    {
        $this->entity->where('user_id', $param)->delete();
        return $this->user->where('id', $param)->delete();
    }
}
