<?php

namespace App\Http\Controllers\Api\V1\Front;

use Illuminate\Support\Str;
use Carbon\Carbon;  
use App\Models\{
    Avi,
    Nominations,
    Votings,
    Creeps,
    Settings,
};
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\{
    Request,
    JsonResponse
};
use DB;

class CreepsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $creeps = Creeps::query();
        $creeps->leftJoin('avis', 'avis.id', '=', 'creeps.avi_id');
        $creeps->groupBy('avi_id');
        $creeps->orderBy('avi_name');
        $creeps->select([
            'avis.id as avi_id',
            'avis.name as avi_name',
	    'creeps.id as id'
        ]);
        return $creeps->paginate($request->get('per_page'));
    }

    public function show($id)
    {
        
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $vote = Votings::select('avi_id')
            ->groupBy('avi_id')
            ->orderByRaw('count(*) DESC')
            ->first();
        if ($vote) {
            Creeps::create([
                'avi_id' => $vote->avi_id
            ]);
        }
        DB::table('nominations')->delete();
        DB::table('votings')->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }
}