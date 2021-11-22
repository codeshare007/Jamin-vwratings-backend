<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvisClaims;
use App\Models\AvisComments;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AvisClaimsController extends Controller
{
    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $comments = AvisClaims::query()->with('user');

        if ($request->has('sortBy') && $request->has('sort')) {
            $comments->orderBy($request->get('sortBy'), $request->get('sort'));
        }

        if ($query = $request->get('search')) {

        }

        return $comments->paginate(100);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return AvisClaims::findOrFail($id);
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
            'user_id' => 'nullable|int',
            'avis_id' => 'required|int',
            'claimed_until' => 'nullable|date'
        ]);

        if ($claim = AvisClaims::find($id)) {
            $claim->avis_id = $request->get('avis_id');

            if ($request->get('user_id')) {
                $claim->user_id = $request->get('user_id');
            }

            $claim->claimed_until = $request->get('claimed_until');
            $claim->save();

            return response()->json(['status' => 'success']);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'nullable|int',
            'avis_id' => 'required|int',
            'claimed_until' => 'nullable|date'
        ]);

        $claim = new AvisClaims();

        $claim->avis_id = $request->get('avis_id');

        if ($request->get('user_id')) {
            $claim->user_id = $request->get('user_id');
        }

        $claim->claimed_until = $request->get('claimed_until');
        $claim->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * @param $id
     * @return JsonResponse|void
     */
    public function destroy($id)
    {
        if ($user = AvisClaims::findOrFail($id)) {
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

        AvisClaims::whereIn('id', $request->get('ids'))->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Users Deleted successfully.'
        ]);
    }
}
