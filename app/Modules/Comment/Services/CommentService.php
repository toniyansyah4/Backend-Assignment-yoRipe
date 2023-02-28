<?php

namespace App\Modules\Comment\Services;

use App\Contract\Services\CommentService as ServicesCommentService;
use App\Modules\Comment\Models\Comment;
use Hashids\Hashids;
use Illuminate\Support\Facades\Log;

class CommentService implements ServicesCommentService
{
    protected $entity;
    protected $hashids;

    public function __construct(Comment $entity)
    {
        $this->entity = $entity;
        $this->hashids = new Hashids('', 9);
    }

    public function rules()
    {
        return [
            'content' => 'required',
            'id_post' => 'required',
            // 'id_user' => 'required',
        ];
    }

    public function store($data)
    {
        $id_post = $this->hashids->decode($data['id_post']);
        $data = $this->entity->create([
            'content' => $data['content'],
            'id_post' => $id_post[0],
            'id_user' => $data['decodedUserId'],
        ]);
        return $data;
    }

    public function update($param, $data)
    {
        $id_post = $this->hashids->decode($data['id_post']);
        $data = [
            'content' => $data['content'],
            'id_post' => $id_post[0],
        ];
        return $this->entity->where('id', $param)->update($data);
    }

    public function destroy($param)
    {
        return $this->entity->where('id', $param)->delete();
    }
}
