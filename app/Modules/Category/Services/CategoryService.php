<?php

namespace App\Modules\Category\Services;

use App\Contract\Services\BaseService;
use App\Modules\Category\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategoryService implements BaseService
{
    protected $entity;

    public function __construct(Category $entity)
    {
        $this->entity = $entity;
    }

    public function rules()
    {
        return [
            'category' => 'required',
            'slug' => 'unique:categories',
        ];
    }

    public function all()
    {
        return $this->entity->get();
    }

    public function get($row)
    {
        return $this->entity->paginate($row);
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
        $data = $this->entity->create([
            'category' => $data['category'],
            'slug' => Str::slug($data['category'], '-'),
        ]);
        return $data;
    }

    public function update($param, $data)
    {
        $data = [
            'category' => $data['category'],
            'slug' => Str::slug($data['category'], '-'),
        ];
        return $this->entity->where('id', $param)->update($data);
    }

    public function destroy($param)
    {
        return $this->entity->where('id', $param)->delete();
    }
}
