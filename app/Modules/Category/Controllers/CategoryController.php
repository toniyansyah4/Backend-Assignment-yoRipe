<?php

namespace App\Modules\Category\Controllers;

use App\Http\Controllers\ApiController;
use App\Modules\Category\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends ApiController
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $data = $this->categoryService->get(10);

        if (empty($data)) {
            return $this->errorResponse(null, 'Data is empty');
        }
        return $this->successResponse($data, 'data has found');
    }

    public function search(Request $request)
    {
        $data = $this->categoryService->search($request);

        if (empty($data)) {
            return $this->errorResponse($data, 'Data Not Found');
        }

        return $this->successResponse($data, 'Data has been find');
    }

    public function show($tableId)
    {
        $data = $this->categoryService->find($tableId);

        if (empty($data)) {
            return $this->errorResponse($data, 'Data Not Found');
        }

        return $this->successResponse($data, 'Data has been find');
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->categoryService->rules());

        $data = $this->categoryService->store($request);

        return $this->successResponse($data, 'Create Data succes');
    }

    public function update($tableId, Request $request)
    {
        $this->validate($request, $this->categoryService->rules());

        $data = $this->categoryService->update($tableId, $request);

        return $this->successResponse($data, 'Update Data succes');
    }

    public function destroy($tableId)
    {
        $data = $this->categoryService->destroy($tableId);

        if (empty($data)) {
            return $this->errorResponse($data, 'Data error to Delete');
        }

        return $this->successResponse($data, 'Data success to Delete');
    }
}
