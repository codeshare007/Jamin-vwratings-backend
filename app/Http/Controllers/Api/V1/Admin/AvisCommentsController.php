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
    public function index(Request $request)
    {

        $comments = AvisComments::query();
        $comments->leftJoin('users', 'users.id', '=', 'avis_comments.user_id');
        $comments->leftJoin('avis', 'avis.id', '=', 'avis_comments.avis_id');
        $comments->select([
            'avis_comments.id',
            'users.username',
            'avis.name',
            'avis_comments.content',
            'avis_comments.opinion',
            'avis_comments.created_at'
        ]);

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
        return AvisComments::findOrFail($id);
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
            'user_id' => 'nullable|int',
            'opinion' => 'nullable',
            'content' => 'required|string',
        ]);

        if ($aviComment = AvisComments::findOrFail($id)) {

            $aviComment->avis_id = $request->get('avis_id');
            $aviComment->content = $request->get('content');

            if ($userId = $request->get('user_id')) {
                $aviComment->user_id = $userId;
            }

            if ($opinion = $request->get('opinion')) {
                $aviComment->opinion = $opinion;
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
        if ($user = AvisComments::findOrFail($id)) {
            $user->delete();
            return response()->json(['status' => 'success']);
        }
    }

    public function bulkOpinion()
    {

    }

    public function changeOpinion()
    {

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

        AvisComments::whereIn('id', $request->get('ids'))->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Users Deleted successfully.'
        ]);
    }
}
