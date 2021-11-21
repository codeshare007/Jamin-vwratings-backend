<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parties;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PartiesController extends Controller
{
    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $parties = Parties::query();
        $parties->leftJoin('users', 'users.id', '=', 'parties.user_id');
        $parties->select(['parties.id', 'parties.name', 'users.username', 'parties.created_at']);

        if ($request->has('sortBy') && $request->has('sort')) {
            $parties->orderBy($request->get('sortBy'), $request->get('sort'));
        }

        if ($query = $request->get('search')) {
            $parties->where('users.username', 'LIKE', '%' . $query . '%')
                ->orWhere('avis.name', 'LIKE', '%' . $query . '%');
        }

        return $parties->paginate(100);
    }
}
