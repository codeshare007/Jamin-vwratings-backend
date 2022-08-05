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