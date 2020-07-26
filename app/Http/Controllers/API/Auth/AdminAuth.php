<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\ApiController;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Admin;
use Exception;
use Illuminate\Support\Facades\Auth;

class AdminAuth extends ApiController
{
    /*
     * @return void
    */

    public function __construct()
    {
        $this->data = [
            'status' => false,
            'code' => 401,
            'data' => null,
            'err' => [
                'code' => 1,
                'message' => 'Unauthorized'
            ]
        ];
    }

    /*
        The authenticate method attempts to log a admin in and generates an authorization token if the admin is found in the database. It throws an error if the admin is not found or if an exception occurred while trying to find the admin.
    */
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|max:255',
            'password' => 'required'
        ];

        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return Response()->json($validator->errors());
        }

        $credentials = request()->only('email', 'password');

        try {
            if (!$token = Auth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            } else {
                $this->data = [
                    'status' => true,
                    'code' => 200,
                    'data' => [
                        '_token' => $token,
                        'token_type' => 'Bearer',
                        'expires_in' => admin()->factory()->getTTL() * 60
                    ],
                    'err' => null
                ];
            }
        } catch (Exception $e) {
            $this->data['err']['message'] = $e->getMessage();
            $this->data['code'] = 401;
        } catch (JWTException $e) {
            $this->data['err']['message'] = 'Could not create token';
            $this->data['code'] = 500;
        }
        return response()->json($this->data, $this->data['code']);
    }


    /*
        The register method validates a admin input and creates a admin if the admin credentials are validated. The admin is then passed on to JWTAuth to generate an access token for the created admin. This way, the admin would not need to log in to get it.
    */

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $admin = Admin::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($admin);

        $this->data = [
            'status' => true,
            'code' => 201,
            'data' => [
                'Admin' => $admin,
                '_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => admin()->factory()->getTTL() * 60
            ],
            'err' => null
        ];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * Bring the details of the verified user.
     *
     * @return JsonResponse
     */
    public function detail(): JsonResponse
    {
        $this->data = [
            'status' => true,
            'code' => 200,
            'data' => [
                'Admin' => auth()->user()
            ],
            'err' => null
        ];
        return response()->json($this->data);
    }

    /**
     *Log out the admin and make the token unusable.
     * @return JsonResponse
     */

    public function logout(): JsonResponse
    {
        admin()->logout();
        $data = [
            'status' => true,
            'code' => 200,
            'data' => [
                'message' => 'Successfully logged out'
            ],
            'err' => null
        ];
        return response()->json($data);
    }
    /**
     * Renewal process to make JWT reusable after expiry date.
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        $data = [
            'status' => true,
            'code' => 200,
            'data' => [
                '_token' => admin()->refresh(),
                'token_type' => 'Bearer',
                'expires_in' => admin()->factory()->getTTL() * 60
            ],
            'err' => null
        ];
        return response()->json($data, 200);
    }
}
