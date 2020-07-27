<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use Closure;

class JwtMiddleware extends BaseMiddleware
// with this, we can catch token errors and return appropriate error codes to our users.
{

    use ApiResponser;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        try {
            $token = JWTAuth::parseToken(); // Access token from the request
            $user = $token->authenticate(); // Try authenticating user
            if (!$user) throw new Exception('User Not Found');
        } catch (Exception $e) {
            // if token invalid
            if ($e instanceof TokenInvalidException) {
                $data = null;
                $status = false;
                $error = 'Token invalid, please login again';
                $code = 1;
                $message = '';
                return $this->sendAuthResult($message, $data, $error, $code, $status);
            }
            // if token has expired
            else if ($e instanceof TokenExpiredException) {
                $data = null;
                $status = false;
                $error = 'Token expired, please login again';
                $code = 1;
                $message = '';
                return $this->sendAuthResult($message, $data, $error, $code, $status);
            }
            // if token was not found in the request
            // elseif ($e instanceof  JWTException) {
            //     $data = null;
            //     $status = false;
            //     $error = 'Please, attach a bearer token to your request';
            //     $code = 1;
            //     $message = '';
            //     return $this->sendAuthResult($message, $data, $error, $code, $status);
            // }
            else {
                if ($e->getMessage() === 'User Not Found') {
                    $data = null;
                    $status = false;
                    $error = 'User not found';
                    $code = 1;
                    $message = '';
                    return $this->sendAuthResult($message, $data, $error, $code, $status);
                }
                $data = null;
                $status = false;
                $error = 'Unauthenticated';
                $code = 1;
                $message = '';
                return $this->sendAuthResult($message, $data, $error, $code, $status);
            }
        }
        return $next($request);
    }
}
