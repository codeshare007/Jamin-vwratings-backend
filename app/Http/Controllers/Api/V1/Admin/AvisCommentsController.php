<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvisComments;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AvisCommentsController extends Controller
{

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request): LengthAwarePaginator
    {
        $comments = AvisComments::query();
        $comments->with('attachments');
        $comments->leftJoin('users', 'users.id', '=', 'avis_comments.user_id');
        $comments->leftJoin('avis', 'avis.id', '=', 'avis_comments.avis_id');
        $comments->leftJoin('avis_claims', 'avis_claims.avis_id', '=', 'avis_comments.avis_id');
        $comments->select([
            'avis_comments.id',
            'users.username',
            'avis.name',
            'avis.id as entity_id',
            'avis_claims.claimed_until',
            'avis_comments.content',
            'avis_comments.opinion',
            'avis_comments.created_at'
        ]);

        if ($request->has('sortBy') && $request->has('sort')) {
            $comments->orderBy($request->get('sortBy'), $request->get('sort'));
        }

        if ($request->has('id')) {
            $comments->where('avis_comments.id', 'LIKE', '%' . $request->get('id') . '%');
        }

        if ($request->has('username')) {
            $comments->where('username', 'LIKE', '%' . $request->get('username') . '%');
        }

        if ($request->has('name')) {
            $comments->where('name', 'LIKE', '%' . $request->get('name') . '%');
        }

        if ($request->has('content')) {
            $comments->where('content', 'LIKE', '%' . $request->get('content') . '%');
        }

        return $comments->paginate(100);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return AvisComments::findOrFail($id);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
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
        //
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if ($user = AvisComments::findOrFail($id)) {
            $user->delete();
            return response()->json(['status' => 'success']);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function bulkOpinion(Request $request): JsonResponse
    {
        $this->validate($request, [
            'ids' => 'array|required',
            'opinion' => 'required'
        ]);

        AvisComments::whereIn('id', $request->get('ids'))
            ->update(['opinion' => $request->get('opinion')]);

        return response()->json([
            'status' => 'success',
            'message' => 'Users Deleted successfully.'
        ]);
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

        AvisComments::whereIn('id', $request->get('ids'))
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Users Deleted successfully.'
        ]);
    }
}
