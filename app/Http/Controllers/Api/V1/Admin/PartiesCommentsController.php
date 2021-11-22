<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartiesComments;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PartiesCommentsController extends Controller
{

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request)
    {

        $comments = PartiesComments::query();
        $comments->with('attachments');
        $comments->leftJoin('users', 'users.id', '=', 'parties_comments.user_id');
        $comments->leftJoin('parties', 'parties.id', '=', 'parties_comments.party_id');
        $comments->select([
            'parties_comments.id',
            'users.username',
            'parties.name',
            'parties_comments.content',
            'parties_comments.opinion',
            'parties_comments.created_at'
        ]);

        if ($request->has('sortBy') && $request->has('sort')) {
            $comments->orderBy($request->get('sortBy'), $request->get('sort'));
        }

        if ($query = $request->get('search')) {
            $comments->where('users.username', 'LIKE', '%' . $query . '%')
                ->orWhere('parties.name', 'LIKE', '%' . $query . '%')
                ->orWhere('parties_comments.content', 'LIKE', '%' . $query . '%');
        }

        return $comments->paginate(100);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return PartiesComments::findOrFail($id);
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
            'party_id' => 'required|int',
            'opinion' => 'required',
            'user_id' => 'nullable|int',
            'content' => 'nullable|string',
        ]);

        if ($partyComment = PartiesComments::findOrFail($id)) {

            $partyComment->party_id = $request->get('party_id');
            $partyComment->content = $request->get('content');
            $partyComment->opinion = $request->get('opinion');

            if ($userId = $request->get('user_id')) {
                $partyComment->user_id = $userId;
            }

            $partyComment->save();

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
        if ($user = PartiesComments::findOrFail($id)) {
            $user->delete();
            return response()->json(['status' => 'success']);
        }
    }

    public function bulkOpinion(Request $request)
    {
        $this->validate($request, [
            'ids' => 'array|required',
            'opinion' => 'required'
        ]);

        PartiesComments::whereIn('id', $request->get('ids'))->update(['opinion' => $request->get('opinion')]);

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

        PartiesComments::whereIn('id', $request->get('ids'))->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Users Deleted successfully.'
        ]);
    }
}
