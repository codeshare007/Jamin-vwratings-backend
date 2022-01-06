<?php

namespace App\Http\Controllers\Api\V1\Front;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(): JsonResponse
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
     * @return JsonResponse
     */
    public function favoriteAvis(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        return response()->json($user->favoriteAvis()->get());
    }

    /**
     * @return JsonResponse
     */
    public function favoriteParties(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        return response()->json($user->favoriteParties()->get());
    }

    public function commentsAvis(): Collection
    {
        /** @var User $user */
        $user = auth()->user();
        return $user->avisComments()->with(['attachments', 'avi'])->limit(5)->latest()->get();
    }

    public function commentsParties(): Collection
    {
        /** @var User $user */
        $user = auth()->user();
        return $user->partiesCOmments()->with(['attachments', 'party'])->limit(5)->latest()->get();
    }

    public function statsAvis(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        return response()->json([
            'rated' => $user->avisRated()->count(),
            'comments' => $user->avisComments()->count(),
            'added' => $user->avis()->count()
        ]);
    }

    public function statsParties(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        return response()->json([
            'rated' => $user->partiesRated()->count(),
            'comments' => $user->partiesComments()->count(),
            'added' => $user->parties()->count()
        ]);
    }

    /**
     * @param Request $request
     * @throws ValidationException
     */
    public function changePassword(Request $request): JsonResponse
    {
        $this->validate($request, [
            'old_password' => 'required|string',
            'password' => 'required|min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6'
        ]);

        if ($request->get('old_password') === $request->get('password')) {
            return response()->json([
                'errors' => ['password' => ['Passwords are same.']]
            ], 422);
        }

        /** @var User $user */
        $user = auth()->user();
        $oldPassword = Hash::make($request->get('old_password'));

        if (Hash::check($oldPassword, $user->password)) {
            return response()->json([
                'errors' => ['password' => ['Old password is wrong.']]
            ], 422);
        }

        $user->password = Hash::make($request->get('password'));
        $user->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email'
        ]);

        /** @var User $user */
        $user = auth()->user();
        $user->email = $request->get('email');
        $user->save();

        return response()->json(['status' => 'success']);
    }
}
