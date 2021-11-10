<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Messages;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class MessagesController extends Controller
{
    public function index(Request $request)
    {
        $messages = Messages::query();

        $messages->select(['id', 'name', 'email', 'content', 'created_at']);

        if ($request->has('sortBy') && $request->has('sort')) {
            $messages->orderBy($request->get('sortBy'), $request->get('sort'));
        }

        if ($query = $request->get('search')) {
            $messages->where('name', 'LIKE', '%' . $query . '%')
                ->orWhere('email', 'LIKE', '%' . $query . '%')
                ->orWhere('content', 'LIKE', '%' . $query . '%');
        }

        return $messages->paginate(100);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $message = Messages::find($id);
        $message->delete();
        return response()->json(['Message Deleted successfully.']);
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

        Messages::whereIn('id', $request->get('ids'))->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Users Deleted successfully.'
        ]);
    }
}
