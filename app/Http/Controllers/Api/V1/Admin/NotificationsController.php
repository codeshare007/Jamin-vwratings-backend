<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvisComments;
use App\Models\AvisInterviews;
use App\Models\Notifications;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationsController extends Controller
{

    /**
     * @return mixed
     */
    public function index()
    {
        return Notifications::paginate(10);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return Notifications::findOrFail($id);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'content' => 'required|string'
        ]);

        Notifications::create([
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
           'content' => 'required|string'
        ]);

        $content = $request->get('content');

        if ($notification = Notifications::findOrFail($id)) {
            $notification->update(['content' => $content]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        if ($notification = Notifications::findOrFail($id)) {
            $notification->delete();

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 422);
    }
}
