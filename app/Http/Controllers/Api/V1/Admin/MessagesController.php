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
        $users = Messages::orderBy(
            $request->get('sortBy'),
            $request->get('sort')
        );

        if ($query = $request->get('search')) {
            $users->where('name', 'LIKE', '%' . $query . '%')
                ->orWhere('email', 'LIKE', '%' . $query . '%')
                ->orWhere('content', 'LIKE', '%' . $query . '%');
        }

        return $users->paginate(100);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
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
            'ids' => 'array'
        ]);

        Messages::whereIn('id', $request->get('ids'))->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Messages Deleted successfully.'
        ]);
    }
}
