<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\ApiController;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class UserAuth extends ApiController
{
    /*
        /*
        The authenticate method attempts to log a user in and generates an authorization token if the user is found in the database. It throws an error if the user is not found or if an exception occurred while trying to find the user.
    */
    public function login()
    {
        $rules = [
            'email' => 'required|string|email|max:255',
            'password' => 'required'
        ];

        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return $this->SendExceptionErr($validator->errors(), 400);
        }

        $credentials = request()->only('email', 'password');
        try {
            if (!$token = Auth::guard('api')->attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            } else {
                $message = 'logged in successfully';
                $error = '';
                $status = true;
                $code = 200;
                $data = [
                    '_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
                ];
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            $code = 401;
        } catch (JWTException $e) {
            $error = 'Could not create token';
            $code = 500;
        }
        return $this->sendAuthResult($message, $data, $error, $code, $status);
    }

    /*
        The register method validates a user input and creates a user if the user credentials are validated. The user is then passed on to JWTAuth to generate an access token for the created user. This way, the user would not need to log in to get it.
    */

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->SendExceptionErr($validator->errors(), 400);
        }
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);
        $token = JWTAuth::fromUser($user);
        $status = true;
        $message = "signed up successfully";
        $code = 201;
        $data = [
            'user' => $user,
            '_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' =>  Auth::guard('api')->factory()->getTTL() * 60
        ];

        return $this->sendAuthResult($message, $data, '', $code, $status);
    }

    /**
     * Bring the details of the verified user.
     *
     * @return JsonResponse
     */
    public function detail(): JsonResponse
    {
        try {
            if (!$user = Auth::guard('api')->user()) {
                return $this->SendExceptionErr(["error" => "user_not_found"], 404);
            }
        } catch (TokenExpiredException $e) {
            return $this->SendExceptionErr(["error" => "token_expired"], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return $this->SendExceptionErr(["error" => "oken_invalid"], $e->getStatusCode());
        } catch (JWTException $e) {
            return $this->SendExceptionErr(["error" => "token_absent"], $e->getStatusCode());
        }

        $message = "success";
        $status = true;
        $code = 200;
        $data = [
            'user' => $user
        ];
        return $this->sendAuthResult($message, $data, '', $code, $status);
    }

    /**
     *Log out the user and make the token unusable.
     * @return JsonResponse
     */

    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();
        $message = "logout successfully";
        $status = true;
        $code = 200;
        $data = [];
        $error = '';
        return $this->sendAuthResult($message, $data, $error, $code, $status);
    }
    /**
     * Renewal process to make JWT reusable after expiry date.
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        $status = true;
        $code = 200;
        $message = "refresh token successfully";
        $error = "";
        $data = [
            '_token' => Auth::guard('api')->refresh(),
            'token_type' => 'Bearer',
            'expires_in' =>  Auth::guard('api')->factory()->getTTL() * 60
        ];;
        return $this->sendAuthResult($message, $data, $error, $code, $status);
    }
}
