<?php

namespace App\Http\Controllers\Api\V1\Front;

use App\Http\Controllers\Controller;
use App\Models\Avi;
use App\Models\AvisClaims;
use App\Models\Parties;
use App\Models\PartiesClaims;
use App\Models\User;
use App\Models\Creeps;
use App\Models\Nominations;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClaimsController extends Controller
{

    public function index()
    {
        /** @var User $user */
        $user = auth()->user();

        $avis = $user->avisClaimed;
        $parties = $user->partiesClaimed;

        return response()->json([
            'status' => 'success',
            'data' => [
                'avis' => $avis,
                'parties' => $parties
            ]
        ]);
    }

    public function stayClaimed(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $this->validate($request, [
            'type' => 'required'
        ]);

        $type = $request->get('type');

        if ($type === 'avi') {
            foreach ($user->avisClaimed as $claim) {
                $claim->claimed_until = Carbon::now()->addDays(3)->toDateTimeString();
                $claim->save();
            }
        }

        if ($type === 'party') {
            foreach ($user->partiesClaimed as $claim) {
                $claim->claimed_until = Carbon::now()->addDays(3)->toDateTimeString();
                $claim->save();
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function claim(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'type' => 'required|string'
        ]);

        $name = $request->get('name');
        $type = $request->get('type');

        /** @var User $user */
        $user = auth()->user();
        switch ($type) {
            case('avi'):
                if ($avi = Avi::where('name', '=', $name)->first()) {
		    $creeps = Creeps::where('avi_id', '=', $avi->id)->first();
            $nomination = Nominations::where('avi_id', $avi->id)->first();			
		    if ( $creeps ) {
                        return response()->json([
                            'status' => 'error', 'errors' => ['avi' => ['Names on the Creeps List can not be claimed']]
                        ], 422);

		    }
                    else if ( $nomination ) {
                        return response()->json([
                            'status' => 'error', 'errors' => ['avi' => ['Names can not be claimed while on the nomination or voting lists']]
                        ], 422);			
					}  else if (AvisClaims::where('avis_id', '=', $avi->id)->first()) {
                        return response()->json([
                            'status' => 'error', 'errors' => ['avi' => ['Player already claimed']]
                        ], 422);
                    } else {
                        $avi->claim()->create([
                            'user_id' => $user->id,
                            'claimed_until' => Carbon::now()->addDays(3)->toDateTimeString()
                        ]);

                        return response()->json(['status' => 'success']);
                    }
                } else {
                    return response()->json([
                        'status' => 'error', 'errors' => ['avi' => ['Player not found']]
                    ], 422);
                }
                break;
            case('party'):
                if ($party = Parties::where('name', '=', $name)->first()) {
                    if (PartiesClaims::where('party_id', '=', $party->id)->first()) {
                        return response()->json([
                            'status' => 'error', 'errors' => ['avi' => ['Party already claimed']]
                        ], 422);

                    } else {
                        $party->claim()->create([
                            'user_id' => $user->id,
                            'claimed_until' => Carbon::now()->addDays(3)->toDateTimeString()
                        ]);

                        return response()->json(['status' => 'success']);
                    }
                } else {
                    return response()->json([
                        'status' => 'error', 'errors' => ['avi' => ['Party not found']]
                    ], 422);
                }
                break;
        }

        return false;
    }
}
