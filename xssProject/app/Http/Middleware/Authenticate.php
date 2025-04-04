<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class Authenticate
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return $this->response(response::HTTP_UNAUTHORIZED,"Unauthenticated access.",[],null);
        }
    }

    public function unathenticated(Request $request, Closure $next)
    {
        throw new HttpResponseException(response()->json(['error' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED));
    }
}
