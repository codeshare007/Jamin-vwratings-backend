<?php
namespace App\Http\Controllers\Api\V1\Admin;

use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\AvisRatings;
use Illuminate\Http\{JsonResponse, Request};

class AvisRatingsController extends Controller
{
    public function index(Request $request)
    {
        $ratings = AvisRatings::with(['user', 'avi']);

        $ratings->leftJoin('users', 'users.id', '=', 'avis_ratings.user_id');
        $ratings->leftJoin('avis', 'avis.id', '=', 'avis_ratings.avis_id');
        $ratings->select(['avis_ratings.*', 'users.username', 'avis.name']);

        if ($request->has('sortBy') && $request->has('sort')) {
            $ratings->orderBy($request->get('sortBy'), $request->get('sort'));
        }

        if ($request->has('field') && $request->has('search')) {
            $field = $request->get('field');
            $query = $request->get('search');

            $ratings->where($field, 'LIKE', '%' . $query . '%');
        }

        return $ratings->paginate(100);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return AvisRatings::findOrFail($id);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        if ($user = AvisRatings::findOrFail($id)) {
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

        AvisRatings::whereIn('id', $request->get('ids'))->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Users Deleted successfully.'
        ]);
    }
}
