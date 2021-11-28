<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $users = User::query();
        $users->select(['id', 'username', 'email', 'role', 'created_at']);

        if ($request->has('sortBy') && $request->has('sort')) {
            $users->orderBy($request->get('sortBy'), $request->get('sort'));
        }

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
    public function update($id, Request $request)
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

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $this->validate($request, [
            'ids' => 'array|required'
        ]);

        User::whereIn('id', $request->get('ids'))->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Users Deleted successfully.'
        ]);
    }
}
