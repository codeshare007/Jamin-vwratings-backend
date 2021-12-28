<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$user = User::where('username', '=', $request->get('username'))->first()) {
            return response()->json(['status' => 'error', 'message' => 'wrong username'], 401);
        }

        if ($user->role !== User::ROLE_ADMIN) {
            return response()->json(['status' => 'error', 'message' => 'is not admin'], 401);
        }

        if (!$token = JWTAuth::attempt($request->only('username', 'password'))) {
            return response()->json(['status' => 'error', 'message' => 'wrong password'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->role === User::ROLE_ADMIN) {
            return response()->json($user);
        }

        return response()->json(['status' => 'error'], 422);
    }


    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function clear()
    {

    }

    /**
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * @param $token
     * @return JsonResponse
     */
    protected function createNewToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
