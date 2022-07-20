<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Messages;						
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
        $users->select(['id', 'username', 'email', 'ip_address', 'role', 'created_at']);

        if ($request->has('sortBy') && $request->has('sort'))
            $users->orderBy($request->get('sortBy'), $request->get('sort'));

        if ($request->has('id'))
            $users->where('id', 'LIKE', '%' . $request->get('id') . '%');

        if ($request->has('username'))
            $users->where('username', 'LIKE', '%' . $request->get('username') . '%');

        if ($request->has('email'))
            $users->where('email', 'LIKE', '%' . $request->get('email') . '%');

        if ($request->has('ip'))
            $users->where('ip_address', 'LIKE', '%' . $request->get('ip') . '%');

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
        $user->email = $request->get('email') ?? null;

        $user->save();

        Messages::create([
            'name' => $user->username,
            'email' => $user->email,
            'content' => 'Email was created.'
        ]);
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
            'email' => 'nullable|email',
            'role' => 'required|int',
            'password' => 'string'
        ]);

        if ($user = User::findOrFail($id)) {
            $user->username = $request->get('username');
            $user->role = $request->get('role');

            if ($password = $request->get('password')) {
                $user->password = Hash::make($password);
            }

            $user->email = $request->get('email') ?? null;

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
