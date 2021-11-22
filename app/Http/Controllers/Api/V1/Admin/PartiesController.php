<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Parties;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PartiesController extends Controller
{

    public function index(Request $request)
    {

        $parties = Parties::query();
        $parties->leftJoin('users', 'users.id', '=', 'parties.user_id');
        $parties->select(['parties.id', 'parties.name', 'users.username', 'parties.created_at']);

        if ($request->has('sortBy') && $request->has('sort')) {
            $parties->orderBy($request->get('sortBy'), $request->get('sort'));
        }

        if ($query = $request->get('search')) {
            $parties->where('users.username', 'LIKE', '%' . $query . '%')
                ->orWhere('parties.name', 'LIKE', '%' . $query . '%');
        }

        return $parties->paginate(100);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return Parties::findOrFail($id);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'nullable|int',
            'name' => 'required|string',
        ]);

        $party = new Parties();
        $party->name = $request->get('name');
        if ($userId = $request->get('user_id')) {
            $party->user_id = $userId;
        }

        $party->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'user_id' => 'nullable|int',
            'name' => 'required|string',
        ]);

        if ($party = Parties::findOrFail($id)) {
            $party->name = $request->get('name');

            if ($userId = $request->get('user_id')) {
                $party->user_id = $userId;
            }

            $party->save();

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error']);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id)
    {
        if ($user = Parties::findOrFail($id)) {
            $user->delete();
            return response()->json(['status' => 'success']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function bulkDelete(Request $request)
    {
        $this->validate($request, [
            'ids' => 'array|required'
        ]);

        Parties::whereIn('id', $request->get('ids'))->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Users Deleted successfully.'
        ]);
    }
}
