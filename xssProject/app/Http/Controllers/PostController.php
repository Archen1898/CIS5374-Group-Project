<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Requests\PostRequest;
use App\Repositories\v1\PostRepository;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    use ResponseTrait;

    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function createPost(PostRequest $postRequest)
    {
        try {
            return $this->response(Response::HTTP_OK, trans('post.create'), $this->postRepository->create($postRequest), null);
        } catch (Exception $exception) {
            return $this->response(Response::HTTP_BAD_REQUEST, $exception->getMessage(), [], $exception->getMessage());
        }
    }
    public function indexPosts()
    {
        try {
            return $this->response(Response::HTTP_OK, trans('post.index'), $this->postRepository->viewAll(), null);
        } catch (Exception $exception) {
            return $this->response(Response::HTTP_BAD_REQUEST, $exception->getMessage(), [], $exception->getMessage());
        }
    }
}
