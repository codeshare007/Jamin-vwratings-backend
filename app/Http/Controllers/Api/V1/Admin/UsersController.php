<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $descOrAsc = $request->get('sort');

        $users = User::orderBy($request->get('sortBy'), $descOrAsc);

        if ($query = $request->get('search')) {
            $users->where('username', 'LIKE', '%' . $query . '%')
                ->orWhere('email', 'LIKE', '%' . $query . '%');
        }

        return $users->paginate(100);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return User::findOrFail($id);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'username' => 'required',
            'email' => 'nullable|email',
            'role' => 'required|int',
            'password' => 'required|string'
        ]);

        $user = new User();
        $user->username = $request->get('username');
        $user->role = $request->get('role');
        $user->password = Hash::make($request->get('password'));
        if ($email = $request->get('email')) $user->email = $email;

        $user->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse|void
     * @throws ValidationException
     */
    public function edit($id, Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'email' => 'email',
            'role' => 'required|int',
            'password' => 'string'
        ]);

        if ($user = User::findOrFail($id)) {
            $user->username = $request->get('username');
            $user->role = $request->get('role');

            if ($password = $request->get('password')) {
                $user->password = Hash::make($password);
            }

            if ($email = $request->get('email')) {
                $user->email = $email;
            }

            $user->save();

            return response()->json(['status' => 'success']);
        }
    }

    /**
     * @param $id
     * @return JsonResponse|void
     */
    public function destroy($id)
    {
        if ($user = User::findOrFail($id)) {
            $user->delete();
            return response()->json(['status' => 'success']);
        }
    }
}
