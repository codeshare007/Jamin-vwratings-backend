<?php

namespace App\Http\Controllers\Api\V1\Front;

use Illuminate\Support\Str;
use App\Models\{
    Avi,
    Nominations,				
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
            Nominations::create([
               'avi_id' => $avi->id
            ]);
            return response()->json(['status' => 'success']);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "This name doesn't qualify for nomination. Please check the spelling and make sure the name 
                is on the bad list {link to bad list}"
            ], 422);
        }

    }
}