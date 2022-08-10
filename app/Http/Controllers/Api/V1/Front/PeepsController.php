<?php

namespace App\Http\Controllers\Api\V1\Front;

use Illuminate\Support\Str;
use Carbon\Carbon;  
use App\Models\{
    Avi,
    NominationsPeeps,
    VotingsPeeps,
    Peeps,
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

class PeepsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $peeps = Peeps::query();
        $peeps->leftJoin('avis', 'avis.id', '=', 'peeps.avi_id');
        $peeps->groupBy('avi_id');
        $peeps->orderBy('avi_name');
        $peeps->select([
            'avis.id as avi_id',
            'avis.name as avi_name',
			'peeps.id as id'					  
        ]);
        return $peeps->paginate($request->get('per_page'));
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
        $vote = VotingsPeeps::select('avi_id')
            ->groupBy('avi_id')
            ->orderByRaw('count(*) DESC')
            ->first();
        if ($vote) {
            Peeps::create([
                'avi_id' => $vote->avi_id
            ]);
        }
        DB::table('nominations_peeps')->delete();
        DB::table('votings_peeps')->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }
}