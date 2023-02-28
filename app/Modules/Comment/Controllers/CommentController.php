<?php

namespace App\Modules\Comment\Controllers;

use App\Http\Controllers\ApiController;
use App\Modules\Comment\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends ApiController
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->commentService->rules());

        $data = $this->commentService->store($request);

        return $this->successResponse($data, 'Create Data succes');
    }

    public function update($tableId, Request $request)
    {
        $this->validate($request, $this->commentService->rules());

        $data = $this->commentService->update($tableId, $request);

        return $this->successResponse($data, 'Update Data succes');
    }

    public function destroy($tableId)
    {
        $data = $this->commentService->destroy($tableId);

        if (empty($data)) {
            return $this->errorResponse($data, 'Data error to Delete');
        }

        return $this->successResponse($data, 'Data success to Delete');
    }
}
