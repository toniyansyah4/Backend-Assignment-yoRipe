<?php

namespace App\Modules\Blog\Services;

use App\Contract\Services\BlogService as ServicesBlogService;
use App\Modules\Blog\Models\Blog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BlogService implements ServicesBlogService
{
    protected $entity;

    public function __construct(Blog $entity)
    {
        $this->entity = $entity;
    }

    public function rules()
    {
        return [
            'title' => 'required',
            'slug' => 'unique:blogs',
            'content' => 'required',
            'banner' => 'required',
            'category_id' => 'required',
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
            'title' => $request->get('title'),
            'slug' => Str::slug($request->get('title'), '-'),
            'content' => $request->get('content'),
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
            'title' => $data['title'],
            'slug' => Str::slug($data['title'], '-'),
            'content' => $data['content'],
            'banner' => $data['banner'],
            'category_id' => $data['category_id'],
            'author_id' => isset($data['author_id'])
                ? $data['author_id']
                : $data['decodedUserId'],
        ]);
        return $data;
    }

    public function update($param, $data)
    {
        if (!isset($data['author_id'])) {
            $blog = $this->entity
                ->where('id', $param)
                ->where('author_id', $data['decodedUserId'])
                ->first();
            if (!isset($blog)) {
                return [];
            }
        }
        $data = [
            'title' => $data['title'],
            'slug' => Str::slug($data['title'], '-'),
            'content' => $data['content'],
            'banner' => $data['banner'],
            'category_id' => $data['category_id'],
            'author_id' => isset($data['author_id'])
                ? $data['author_id']
                : $data['decodedUserId'],
        ];
        return $this->entity->where('id', $param)->update($data);
    }

    public function destroy($param)
    {
        return $this->entity->where('id', $param)->delete();
    }

    public function destroyByUser($param, $user)
    {
        return $this->entity
            ->where('id', $param)
            ->where('author_id', $user->decodedUserId)
            ->delete();
    }
}
