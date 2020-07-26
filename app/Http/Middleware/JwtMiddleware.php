<?php

namespace App\Http\Middleware;

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
            $admin = $token->authenticate(); // Try authenticating user
            if (!$admin) throw new Exception('Admin Not Found');
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {  //Thrown if token invalid
                return response()->json([
                    'data' => null,
                    'status' => false,
                    'err_' => [
                        'message' => 'Token Invalid, Please Login Again ',
                        'code' => $e->getStatusCode()
                    ]
                ]);
            } else if ($e instanceof TokenExpiredException) {  //Thrown if token has expired
                return response()->json([
                    'data' => null,
                    'status' => false,
                    'err_' => [
                        'message' => 'Token Expired, Please Login Again',
                        'code' => $e->getStatusCode()
                    ]
                ]);
            } elseif ($e instanceof  JWTException) {  //Thrown if token was not found in the request.
                return response()->json([
                    'data' => null,
                    'status' => false,
                    'err_' => [
                        'message' => 'Please, Attach A Bearer Token Ao Your Request',
                        'code' => $e->getStatusCode()
                    ]
                ]);
            } else {
                if ($e->getMessage() === 'Admin Not Found') {
                    return response()->json([
                        "data" => null,
                        "status" => false,
                        "err_" => [
                            "message" => "Admin Not Found",
                            "code" => $e->getStatusCode()
                        ]
                    ]);
                }
                return response()->json([
                    'data' => null,
                    'status' => false,
                    'err_' => [
                        'message' => 'Authorization Token not found',
                        'code' => $e->getStatusCode()
                    ]
                ]);
            }
        }
        return $next($request);
    }
}
