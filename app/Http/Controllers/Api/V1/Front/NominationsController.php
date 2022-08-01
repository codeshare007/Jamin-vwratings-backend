<?php

namespace App\Http\Controllers\Api\V1\Front;

use Illuminate\Support\Str;
use Carbon\Carbon;  
use App\Models\{
    Avi,
    Nominations,
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

class NominationsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $nominations = Nominations::query();
        $nominations->leftJoin('avis', 'avis.id', '=', 'nominations.avi_id');
        $nominations->groupBy('avi_id');
        $nominations->select([
            'nominations.id',
            'avis.name as avi_name',
            'nominations.created_at'
        ]);
        return $nominations->paginate($request->get('per_page'));
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
        $this->validate($request, [
            'creep_name' => 'required|string'
        ]);

        /** @var User $user */
        $user = auth()->user();
        $creepName = $request->get('creep_name');

							 
        $avi = Avi::query()->averageRating('<', 4, 'ASC')->where('name', '=', $creepName)->first();

		
        if ($avi) {    
            $nomination = Nominations::where('avi_id', $avi->id)->where('user_id', $user->id)->first();
            if ($nomination) {
                return response()->json([
                    "status" => "error",
                    "message" => "That name has already been entered this round."
                ], 422);
            } else {
                Nominations::create([
                    'avi_id' => $avi->id,
                    'user_id' => $user->id
                ]);
                return response()->json([
                    'status' => 'success',
                ]);
            }
        } else {
            return response()->json([
                "status" => "error",
                "message" => "This name doesn't qualify for nomination. Please check the spelling and make sure the name 
                is on the "
            ], 422);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function possible(Request $request): JsonResponse
    {
        $user = auth()->user();
        $nomination = Nominations::where('user_id', '=', $user->id)->orderBy('created_at', 'DESC')->first();
        
        $period = Settings::getSetting("timer_period_minutes") * 60;
        // $nomiation->created_at
        return response()->json([
            'possible' => abs($nomination->created_at->diffInSeconds(date('Y-m-d H:i:s'))) > $period
        ]);
    }
}