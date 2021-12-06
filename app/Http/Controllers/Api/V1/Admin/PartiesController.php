<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Parties;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PartiesController extends Controller
{

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request): LengthAwarePaginator
    {
        $parties = Parties::query();

        $parties->rightJoin('users', 'users.id', '=', 'parties.user_id');

        $parties->select([
            'parties.id',
            'parties.name',
            'users.username as username',
            'parties.created_at']);

        if ($request->has('sortBy') && $request->has('sort'))
            $parties->orderBy($request->get('sortBy'), $request->get('sort'));

        if ($request->has('id'))
            $parties->where('parties.id', 'LIKE', '%' . $request->get('id') . '%');

        if ($request->has('username'))
            $parties->where('username', 'LIKE', '%' . $request->get('username') . '%');

        if ($request->has('name'))
            $parties->where('name', 'LIKE', '%' . $request->get('name') . '%');

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
