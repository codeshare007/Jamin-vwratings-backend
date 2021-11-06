<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {

        $descOrAsc = $request->get('sort');

        $users = User::orderBy($request->get('sortBy'), $descOrAsc);

        if ($query = $request->get('search')) {
            $users->where('username', 'LIKE', '%' . $query . '%')
                ->orWhere('email', 'LIKE', '%' . $query . '%');
        }

        return $users->paginate(10);
    }

    public function delete($id)
    {
        if ($user = User::findOrFail($id)) {
            $user->delete();
            return response()->json(['status' => 'success']);
        }
    }
}
