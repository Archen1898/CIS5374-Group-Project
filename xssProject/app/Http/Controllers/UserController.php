<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Requests\UserRequest;
use App\Repositories\v1\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use ResponseTrait;

    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(UserRequest $request)
    {
        try {
            return $this->response(Response::HTTP_OK, trans('user.create'), $this->userRepository->create($request), null);
        } catch (Exception $exception) {
            return $this->response(Response::HTTP_BAD_REQUEST, $exception->getMessage(), [], $exception->getMessage());
        }
    }

    public function indexUsers()
    {
        try {
            return $this->response(Response::HTTP_OK, trans('user.index'), $this->userRepository->indexUsers(), null);
        } catch (Exception $exception) {
            return $this->response(Response::HTTP_BAD_REQUEST, $exception->getMessage(), [], $exception->getMessage());
        }
    }
}
