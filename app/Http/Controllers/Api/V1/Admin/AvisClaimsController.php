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
            $comments->where('users.username', 'LIKE', '%' . $query . '%')
                ->orWhere('avis.name', 'LIKE', '%' . $query . '%')
                ->orWhere('avis_comments.content', 'LIKE', '%' . $query . '%');
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
            'avis_id' => 'required|int',
            'opinion' => 'required',
            'user_id' => 'nullable|int',
            'content' => 'nullable|string',
        ]);

        if ($aviComment = AvisComments::findOrFail($id)) {

            $aviComment->avis_id = $request->get('avis_id');
            $aviComment->content = $request->get('content');
            $aviComment->opinion = $request->get('opinion');

            if ($userId = $request->get('user_id')) {
                $aviComment->user_id = $userId;
            }

            $aviComment->save();

            return response()->json(['status' => 'success']);
        }
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => ''
        ]);
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
