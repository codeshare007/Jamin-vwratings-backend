<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvisComments;
use App\Models\AvisInterviews;
use App\Models\Notifications;
use App\Models\UsersNotifications;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UsersNotificationsController extends Controller
{
    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request): LengthAwarePaginator
    {
        $ratings = UsersNotifications::with(['user']);

        $ratings->leftJoin('users', 'users.id', '=', 'users_notifications.user_id');
        $ratings->select([
            'users_notifications.*',
            'users.username',
            'users_notifications.content'
        ]);

        if ($request->has('sortBy') && $request->has('sort')) {
            $ratings->orderBy($request->get('sortBy'), $request->get('sort'));
        }

        if ($request->has('username')) {
            $ratings->where('username', 'LIKE', '%' . $request->get('username') . '%');
        }

        if ($request->has('name')) {
            $ratings->where('name', 'LIKE', '%' . $request->get('name') . '%');
        }

        return $ratings->paginate(100);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return UsersNotifications::findOrFail($id);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
           'user_id' => 'required|int',
           'content' => 'required|string'
        ]);

        UsersNotifications::create([
           'user_id' => $request->get('user_id'),
           'content' => $request->get('content')
        ]);

        return response()->json(['status' => 'success']);
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
            'user_id' => 'required|int',
            'content' => 'required|string'
        ]);

        if ($notification = UsersNotifications::findOrFail($id)) {

            $notification->update([
               'user_id' => $request->get("user_id"),
               'content' => $request->get('content')
            ]);

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 422);
    }
}
