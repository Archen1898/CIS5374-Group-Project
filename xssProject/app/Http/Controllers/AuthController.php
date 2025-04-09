<?php

namespace App\Http\Controllers;

use Exception;
;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Repositories\AuthRepository;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ResponseTrait;

    public function __construct(private AuthRepository $auth)
    {
        $this->auth = $auth;
    }
    public function login(LoginRequest $request)
    {
        try {
            $data = $this->auth->login($request->all());
            return $data;
            //return $this->response(Response::HTTP_OK,trans('auth.success'),$data,null);
        } catch (Exception $exception) {
            return $this->response(Response::HTTP_NOT_FOUND, $exception->getMessage(),[],null);
        }

    }


    
}
