<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvisComments;
use App\Models\AvisInterviews;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AvisInterviewsController extends Controller
{

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request): LengthAwarePaginator
    {
        $comments = AvisInterviews::query();;
        $comments->leftJoin('avis', 'avis.id', '=', 'avis_interviews.avis_id');

        $comments->select([
            'avis_interviews.id',
            'avis.name',
            'avis.id as entity_id',
            'avis_interviews.order',
            'avis_interviews.content',
            'avis_interviews.created_at'
        ]);

        if ($request->has('sortBy') && $request->has('sort'))
            $comments->orderBy($request->get('sortBy'), $request->get('sort'));

        if ($request->has('id'))
            $comments->where('avis_comments.id', 'LIKE', '%' . $request->get('id') . '%');

        if ($request->has('name'))
            $comments->where('name', 'LIKE', '%' . $request->get('name') . '%');

        if ($request->has('content'))
            $comments->where('content', 'LIKE', '%' . $request->get('content') . '%');

        return $comments->paginate($request->get('per_page'));
    }
}
