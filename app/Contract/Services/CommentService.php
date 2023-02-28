<?php
namespace App\Contract\Services;

interface CommentService
{
    public function store($data);
    public function destroy($param);
    public function update($param, $data);
    public function rules();
}
