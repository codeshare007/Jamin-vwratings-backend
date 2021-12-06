<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\Avi;
use Illuminate\Http\Request;

class AvisController extends Controller
{

    public function index(Request $request)
    {

        $avis = Avi::query();

        $avis->rightJoin('users', 'users.id', '=', 'avis.user_id');

        $avis->select([
            'avis.id',
            'avis.name',
            'users.username as username',
            'avis.created_at']);

        if ($request->has('sortBy') && $request->has('sort')) {
            $avis->orderBy($request->get('sortBy'), $request->get('sort'));
        }

        if ($request->has('id')) {
            $avis->where('avis.id', 'LIKE', '%' . $request->get('id') . '%');
        }

        if ($request->has('username')) {
            $avis->where('username', 'LIKE', '%' . $request->get('username') . '%');
        }

        if ($request->has('name')) {
            $avis->where('name', 'LIKE', '%' . $request->get('name') . '%');
        }

        return $avis->paginate(100);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return Avi::findOrFail($id);
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

        $avi = new Avi();
        $avi->name = $request->get('name');
        if ($userId = $request->get('user_id')) {
            $avi->user_id = $userId;
        }

        $avi->save();

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

        if ($avi = Avi::findOrFail($id)) {
            $avi->name = $request->get('name');

            if ($userId = $request->get('user_id')) {
                $avi->user_id = $userId;
            }

            $avi->save();

            return response()->json(['status' => 'success']);
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy($id)
    {
        if ($user = Avi::findOrFail($id)) {
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

        Avi::whereIn('id', $request->get('ids'))->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Users Deleted successfully.'
        ]);
    }
}
