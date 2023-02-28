<?php

namespace App\Modules\Blog\Controllers;

use App\Http\Controllers\ApiController;
use App\Modules\Blog\Services\BlogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogController extends ApiController
{
    protected $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function index()
    {
        $data = $this->blogService->get(10);

        if (empty($data)) {
            return $this->errorResponse(null, 'Data is empty');
        }
        return $this->successResponse($data, 'data has found');
    }

    public function search(Request $request)
    {
        $data = $this->blogService->search($request);

        if (empty($data->data)) {
            return $this->errorResponse($data, 'Data Not Found');
        }

        return $this->successResponse($data, 'Data has been find');
    }

    public function show($tableId)
    {
        $data = $this->blogService->find($tableId);

        if (empty($data)) {
            return $this->errorResponse($data, 'Data Not Found');
        }

        return $this->successResponse($data, 'Data has been find');
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->blogService->rules());

        $data = $this->blogService->store($request);

        return $this->successResponse($data, 'Create Data succes');
    }

    public function update($tableId, Request $request)
    {
        $this->validate($request, $this->blogService->rules());

        $data = $this->blogService->update($tableId, $request);

        if (empty($data)) {
            return $this->errorResponse($data, 'Update error');
        }

        return $this->successResponse($data, 'Update Data succes');
    }

    public function destroy($tableId)
    {
        $data = $this->blogService->destroy($tableId);

        if (empty($data)) {
            return $this->errorResponse($data, 'Data error to Delete');
        }

        return $this->successResponse($data, 'Data success to Delete');
    }

    public function destroyByUser($tableId, Request $request)
    {
        $data = $this->blogService->destroyByUser($tableId, $request);

        if (empty($data)) {
            return $this->errorResponse($data, 'Data error to Delete');
        }

        return $this->successResponse($data, 'Data success to Delete');
    }
}
