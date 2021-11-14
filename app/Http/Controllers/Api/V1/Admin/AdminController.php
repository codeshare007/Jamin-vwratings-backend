<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avi;
use App\Models\AvisComments;
use App\Models\AvisRatings;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::all()->count();
        $avis = Avi::all()->count();
        $avisComments = AvisComments::all()->count();
        $avisRatings = AvisRatings::all()->count();
        $ttl = auth('api')->factory()->getTTL();

        return response()->json([
            'users' => $users,
            'avis' => $avis,
            'comments' => $avisComments,
            'ratings' => $avisRatings,
            'ttl' => $ttl
        ]);
    }

    /**
     * @return mixed
     */
    public function getHits()
    {
        return Settings::where('key', 'ads_hits')->first()->value;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeHits(Request $request)
    {
        $this->validate($request, [
           'value' => 'required'
        ]);

        if ($setting = Settings::where('key', 'ads_hits')->first()) {
            $setting->value = $request->get('value');
            $setting->save();

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error']);
    }
}
