<?php

namespace App\Http\Controllers;

use Exception;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Repositories\CommentRepository;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    use ResponseTrait;

    protected $commentRepository;
    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function createComment(CommentRequest $request)
    {
        try {
            return $this->response(Response::HTTP_OK, trans('comment.create'), $this->commentRepository->create($request->all()), null);
        } catch (Exception $exception) {
            return $this->response(Response::HTTP_BAD_REQUEST, $exception->getMessage(), [], $exception->getMessage());
        }
    }

    public function viewPostComments($id)
    {
        try {
            return $this->response(Response::HTTP_OK, trans('comment.viewPost'), $this->commentRepository->viewComments($id), null);
        } catch (Exception $exception) {
            return $this->response(Response::HTTP_BAD_REQUEST, $exception->getMessage(), [], $exception->getMessage());
        }
    }
}
