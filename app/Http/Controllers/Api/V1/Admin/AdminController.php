<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Parties,
    Messages,
    User,
    Settings,
    PartiesClaims,
    AvisClaims,
    PartiesRatings,
    PartiesComments,
    AvisRatings,
    AvisComments,
    Avi
};
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function dashboard(): JsonResponse
    {
        $users = User::all()->count();
        $avis = Avi::all()->count();
        $avisComments = AvisComments::all()->count();
        $avisRatings = AvisRatings::all()->count();
        $avisClaims = AvisClaims::all()->count();
        $messages = Messages::all()->count();
        $parties = Parties::all()->count();
        $partiesComments = PartiesComments::all()->count();
        $partiesRatings = PartiesRatings::all()->count();
        $partiesClaims = PartiesClaims::all()->count();
        $ttl = auth('api')->factory()->getTTL();

        return response()->json([
            'users' => $users,
            'avis' => $avis,
            'comments' => $avisComments,
            'ratings' => $avisRatings,
            'claims' => $avisClaims,
            'messages' => $messages,
            'parties' => $parties,
            'pcomments' => $partiesComments,
            'pratings' => $partiesRatings,
            'pclaims' => $partiesClaims,
            'ttl' => $ttl
        ]);
    }

    /**
     * @return mixed
     */
    public function getHits()
    {
        return Settings::where('key', '=', 'ads_hits')->first()->value;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function changeHits(Request $request): JsonResponse
    {
        $this->validate($request, [
            'value' => 'required'
        ]);

        if ($setting = Settings::where('key', '=','ads_hits')->first()) {
            $setting->value = $request->get('value');
            $setting->save();

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error']);
    }
}
