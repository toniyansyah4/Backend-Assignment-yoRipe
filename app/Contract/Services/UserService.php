<?php
namespace App\Contract\Services;

interface UserService
{
    public function get($param);
    public function destroy($param);
    public function update($data);
    public function rules();
}
