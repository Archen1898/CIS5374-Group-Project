<?php

namespace App\Repositories;

use \Exception;
use Carbon\Carbon;
use App\Models\User;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Passport\PersonalAccessTokenResult;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;



class AuthRepository
{
    /**
     * @throws Exception
     */
    public function login(array $data)
    {
        try {
            $user =  User::where('email', $data['email'])
                ->first();
            
            /* if(!$user->password != $data['password']){
                throw new Exception(trans('auth.password'), response::HTTP_UNAUTHORIZED);
            } */

            if (!$user || !Hash::check($data['password'], $user->password)) {
                throw new Exception(trans('auth.user'), response::HTTP_NOT_FOUND);
            }
            $user->accessToken = $this->createAuthToken($user)->accessToken;
            //return $user;
            return view('UserPost',compact('user'));
        } catch (ResourceNotFoundException $e) {
            Log::error($e);
            throw new ResourceNotFoundException($e->getMessage(),$e->getCode());
        } catch (QueryException $e){
            Log::error($e);
            throw new HttpException($e->getCode(),response::HTTP_INTERNAL_SERVER_ERROR);
        }
        catch (Exception $e){
            Log::error($e);
            throw new Exception($e->getMessage(), response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createAuthToken(User $user): PersonalAccessTokenResult
    {
        return $user->createToken('authToken');
    }

    public function getAuthData(User $user, PersonalAccessTokenResult $tokenInstance, $roles, $permission): array
    {
        return [
            'user'         => $user,
            'access_token' => $tokenInstance->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse($tokenInstance->token->expires_at)->toDateTimeString()
        ];
    }
}