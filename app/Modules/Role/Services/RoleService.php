<?php

namespace App\Modules\Role\Services;

use App\Contract\Services\BaseService;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RoleService implements BaseService
{
    protected $entity;
    protected $role;

    public function __construct(RolePermission $entity, Role $role)
    {
        $this->entity = $entity;
        $this->role = $role;
    }

    public function rules()
    {
        return [
            'name_role' => 'required',
            'permissions' => 'required',
        ];
    }

    public function all()
    {
        return $this->entity::with(['permission', 'role'])->get();
    }

    public function get($row)
    {
        return $this->entity::with(['permission', 'role'])->paginate($row);
    }

    public function search($request)
    {
        $search = [
            'category' => $request->get('category'),
            'slug' => Str::slug($request->get('category'), '-'),
        ];
        return $this->entity
            ->where(function ($query) use ($search) {
                foreach ($search as $key => $value) {
                    $objectFirst = 'getKeyFirst' ? null : $key;
                    if (isset($value)) {
                        if ($key == $objectFirst) {
                            $objectFirst = 'getKeyFirst';
                            $query->where($key, 'LIKE', '%' . $value . '%');
                        } else {
                            $query->orWhere($key, 'LIKE', '%' . $value . '%');
                        }
                    }
                }
            })
            ->paginate(10);
    }

    public function find($param)
    {
        return $this->entity->find($param);
    }

    public function store($data)
    {
        $role = $this->role->create([
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

    public function update($param, $data)
    {
        $role = $this->role->where('id', $param)->first();
        $role->names = $data['name_role'];
        $role->save();

        $this->entity->where('role_id', $role->id)->delete();
        foreach ($data['permissions'] as $permission) {
            $this->entity->where('role_id', $role->id)->create([
                'role_id' => $role->id,
                'permission_id' => $permission['value'],
            ]);
        }

        return $this->entity->where('role_id', $role->id)->get();
    }

    public function destroy($param)
    {
        $this->role->where('id', $param)->delete();

        return $this->entity->where('role_id', $param)->delete();
    }
}
