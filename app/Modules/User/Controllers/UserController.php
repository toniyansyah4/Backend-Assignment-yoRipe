<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\ApiController;
use App\Modules\User\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends ApiController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $data = $this->userService->get(10);

        if (empty($data)) {
            return $this->errorResponse(null, 'Data is empty');
        }
        return $this->successResponse($data, 'data has found');
    }

    public function update(Request $request)
    {
        $this->validate($request, $this->userService->rules());

        $data = $this->userService->update($request);

        return $this->successResponse($data, 'Update Data succes');
    }

    public function destroy($tableId)
    {
        $data = $this->userService->destroy($tableId);

        if (empty($data)) {
            return $this->errorResponse($data, 'Data error to Delete');
        }

        return $this->successResponse($data, 'Data success to Delete');
    }
}
