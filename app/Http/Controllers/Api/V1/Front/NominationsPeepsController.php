<?php

namespace App\Http\Controllers\Api\V1\Front;

use Illuminate\Support\Str;
use Carbon\Carbon;  
use App\Models\{
    Avi,
    NominationsPeeps,
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

class NominationsPeepsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $nominations_peeps = NominationsPeeps::query();
        $nominations_peeps->leftJoin('avis', 'avis.id', '=', 'nominations_peeps.avi_id');
        $nominations_peeps->groupBy('avi_id');
        $nominations_peeps->select([
            'nominations_peeps.id',
            'nominations_peeps.avi_id',
            'avis.name as avi_name',
            'nominations_peeps.created_at'
        ]);
        return $nominations_peeps->paginate($request->get('per_page'));
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
            'peep_name' => 'required|string'
        ]);

        /** @var User $user */
        $user = auth()->user();
        $peepName = $request->get('peep_name');

							 
        $avi = Avi::query()->averageRating('>', 9, 'ASC')->where('name', '=', $peepName)->first();

		
        if ($avi) {    
            $nomination = NominationsPeeps::where('avi_id', $avi->id)->first();
            if ($nomination) {
                return response()->json([
                    "status" => "error",
                    "message" => "That name has already been entered this round."
                ], 422);
            } else {
                NominationsPeeps::create([
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
                is on the Good List"
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
        $nomination = NominationsPeeps::where('user_id', '=', $user->id)->orderBy('created_at', 'DESC')->first();
        
        $period = Settings::getSetting("timer_period_minutes") * 60;
        // $nomiation->created_at
        return response()->json([
            'possible' => abs($nomination->created_at->diffInSeconds(date('Y-m-d H:i:s'))) > $period
        ]);
    }
}