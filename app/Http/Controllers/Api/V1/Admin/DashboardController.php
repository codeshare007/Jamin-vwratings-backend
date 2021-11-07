<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avi;
use App\Models\AvisComments;
use App\Models\AvisRatings;
use App\Models\User;

class DashboardController extends Controller
{
    public function information()
    {

        $users = User::all()->count();

        $avis = Avi::all()->count();

        $avisComments = AvisComments::all()->count();

        $avisRatings = AvisRatings::all()->count();

        return response()->json([
            'users' => $users,
            'avis' => $avis,
            'comments' => $avisComments,
            'ratings' => $avisRatings
        ]);
    }
}
