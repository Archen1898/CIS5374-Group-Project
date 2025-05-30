<?php

namespace App\Repositories;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Symfony\Component\HttpFoundation\Response;


class UserRepository 
{


    public function create(UserRequest $userReqeust)
    {
        try{
            $user = new User();
            $newUser = $this->dataFormat($userReqeust, $user);

            return $newUser;
        }catch (Exception $exception) {
            throw new Exception($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function indexUsers()
    {
        try{
            return User::all();
        }catch (Exception $exception) {
            throw new Exception($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function dataFormat($request, User $newUser)
    {
        $newUser->name = $request['name'];
        $newUser->email = $request['email'];
        $newUser->password = $request['password'];
        $newUser->created_at = Carbon::now();
        

        return $newUser;
    }
}