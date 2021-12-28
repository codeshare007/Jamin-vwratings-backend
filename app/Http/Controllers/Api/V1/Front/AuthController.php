<?php

namespace App\Http\Controllers\Api\V1\Front;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Illuminate\Http\{Request, JsonResponse, RedirectResponse};
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\{Password, Hash};
use Illuminate\Foundation\Auth\{ResetsPasswords, SendsPasswordResetEmails};
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use SendsPasswordResetEmails, ResetsPasswords {
        SendsPasswordResetEmails::broker insteadof ResetsPasswords;
        ResetsPasswords::credentials insteadof SendsPasswordResetEmails;
    }

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => [
            'login',
            'register',
            'sendPasswordResetLink',
            'callResetPassword'
        ]]);
    }

    /**
     * @param $user
     * @param $password
     */
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->save();

        event(new PasswordReset($user));
    }

    /**
     * @param Request $request
     * @param $response
     * @return JsonResponse
     */
    protected function sendResetResponse(Request $request, $response): JsonResponse
    {
        if ($user = User::where('email', '=', $request->get('email'))->first()) {
            return response()->json([
                'status' => 'success',
                'data' => $user->username,
                'message' => 'Password reset successfully.'
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * @param Request $request
     * @param $response
     * @return JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response): JsonResponse
    {
        return response()->json(['errors' => ['email' => ['Failed, Invalid Token.']]], 422);
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function sendPasswordResetLink(Request $request)
    {
        return $this->sendResetLinkEmail($request);
    }

    /**
     * @param Request $request
     * @param $response
     * @return JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response): JsonResponse
    {
        return response()->json(['errors' => ['email' => ['Email does not exist or already sent']]], 422);
    }

    /**
     * Handle reset password
     * @param Request $request
     * @return JsonResponse
     */
    public function callResetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', 'min:6'],
        ], $this->validationErrorMessages());

        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * @param Request $request
     * @param $response
     * @return JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response): JsonResponse
    {
        return response()->json([
            'message' => 'Password reset email sent.',
            'data' => $response
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:16',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$user = User::where('username', '=', $request->get('username'))->first()) {
            return response()->json(['status' => 'error', 'message' => 'wrong username'], 401);
        }

        if (!$token = JWTAuth::attempt($request->only('username', 'password'))) {
            return response()->json(['status' => 'error', 'message' => 'wrong password'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request): JsonResponse
    {
        $this->validate($request, [
            'username' => 'required|string|unique:users|max:16',
            'email' => 'email|nullable',
            'password' => 'required|min:6',
            'password_repeat' => 'required_with:password|same:password|min:6'
        ]);

        $user = User::query()
            ->orWhere('username', '=', $request->get('username'));

        if ($request->get('email')) {
            $user->orWhere('email', '=', $request->get('email'));
        }

        if ($user->exists()) {
            return response()->json([
                'errors' => ['email' => ['User with this email or username already exists']]
            ], 422);
        }

        $user = new User();
        $user->username = $request->get('username');
        $user->ip_address = $request->ip();
        $user->password = Hash::make($request->get('password'));

        if ($request->get('email')) {
            $user->email = $request->get('email');
        }

        $user->save();

        return response()->json(['status' => 'success']);
    }

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
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'username' => $user->username,
            'role' => $user->role,
            'favorites' => [
                'avis' => $user->favoriteAvis()->get(),
                'parties' => $user->favoriteParties()->get()
            ]
        ]);
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

    /**
     * @param Request $request
     * @throws ValidationException
     */
    public function forgotPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email'
        ]);

        Password::sendResetLink(
            $request->only('email')
        );
    }
}
