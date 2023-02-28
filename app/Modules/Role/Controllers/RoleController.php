<?php

namespace App\Modules\Role\Controllers;

use App\Http\Controllers\ApiController;
use App\Modules\Role\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends ApiController
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        $data = $this->roleService->get(10);

        if (empty($data)) {
            return $this->errorResponse(null, 'Data is empty');
        }
        return $this->successResponse($data, 'data has found');
    }

    public function search(Request $request)
    {
        $data = $this->roleService->search($request);

        if (empty($data)) {
            return $this->errorResponse($data, 'Data Not Found');
        }

        return $this->successResponse($data, 'Data has been find');
    }

    public function show($tableId)
    {
        $data = $this->roleService->find($tableId);

        if (empty($data)) {
            return $this->errorResponse($data, 'Data Not Found');
        }

        return $this->successResponse($data, 'Data has been find');
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->roleService->rules());

        $data = $this->roleService->store($request);

        return $this->successResponse($data, 'Create Data succes');
    }

    public function update($tableId, Request $request)
    {
        $this->validate($request, $this->roleService->rules());

        $data = $this->roleService->update($tableId, $request);

        return $this->successResponse($data, 'Update Data succes');
    }

    public function destroy($tableId)
    {
        $data = $this->roleService->destroy($tableId);

        if (empty($data)) {
            return $this->errorResponse($data, 'Data error to Delete');
        }

        return $this->successResponse($data, 'Data success to Delete');
    }
}
