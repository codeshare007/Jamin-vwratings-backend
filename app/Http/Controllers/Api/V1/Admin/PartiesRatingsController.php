<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartiesRatings;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\{JsonResponse, Request};

class PartiesRatingsController extends Controller
{
    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request): LengthAwarePaginator
    {
        $ratings = PartiesRatings::with(['user', 'party']);

        $ratings->leftJoin('users', 'users.id', '=', 'parties_ratings.user_id');
        $ratings->leftJoin('parties', 'parties.id', '=', 'parties_ratings.party_id');
        $ratings->select(['parties_ratings.*', 'users.username', 'parties.name']);

        if ($request->has('sortBy') && $request->has('sort'))
            $ratings->orderBy($request->get('sortBy'), $request->get('sort'));

        if ($request->has('username'))
            $ratings->where('username', 'LIKE', '%' . $request->get('username') . '%');

        if ($request->has('name'))
            $ratings->where('name', 'LIKE', '%' . $request->get('name') . '%');

        return $ratings->paginate(100);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return PartiesRatings::findOrFail($id);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update($id, Request $request): JsonResponse
    {
        $this->validate($request, [
            'party_id' => 'required|int',
            'user_id' => 'required|int',
            'rating' => 'required'
        ]);

        if ($rating = PartiesRatings::findOrFail($id)) {

            $rating->update([
                'party_id' => $request->get('party_id') ?? null,
                'user_id' => $request->get('user_id') ?? null,
                'rating' => $request->get('rating')
            ]);

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 422);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        if ($user = PartiesRatings::findOrFail($id)) {
            $user->delete();
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error']);
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

        PartiesRatings::whereIn('id', $request->get('ids'))->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Users Deleted successfully.'
        ]);
    }
}
