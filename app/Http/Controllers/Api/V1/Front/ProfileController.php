<?php

namespace App\Http\Controllers\Api\V1\Front;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

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
    public function favoriteAvis()
    {
        $user = auth()->user();
        return response()->json($user->favoriteAvis()->get());
    }

    /**
     * @return JsonResponse
     */
    public function favoriteParties()
    {
        $user = auth()->user();
        return response()->json($user->favoriteParties()->get());
    }
}
