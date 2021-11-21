<?php

namespace App\Http\Controllers\Api\V1\Front;

use App\Http\Controllers\Controller;
use App\Models\Avi;
use App\Models\AvisClaims;
use App\Models\Parties;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClaimsController extends Controller
{

    public function index()
    {
        /** @var User $user */
        $user = auth()->user();

        $avis = $user->avisClaimed;
        $parties = $user->partiesClamied;

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

        foreach ($user->avisClaimed as $claim) {
            $claim->claimed_until = Carbon::now()->addDays(5)->toDateTimeString();
            $claim->save();
        }

        foreach ($user->partiesClaimed as $claim) {
            $claim->claimed_until = Carbon::now()->addDays(5)->toDateTimeString();
            $claim->save();
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

        switch ($type) {
            case('avi'):
                if ($avi = Avi::where('name', '=', $name)->first()) {
                    if ($claim = AvisClaims::where('avis_id', '=', $avi->id)->first()) {
                        return response()->json([
                            'status' => 'error',
                            'errors' => [
                                'avi' => ['Avi already claimed']
                            ]
                        ], 422);

                    } else {
                        $avi->claim()->create([
                            'user_id' => auth()->user()->getAuthIdentifier(),
                            'claimed_until '=> Carbon::now()->addDays(5)
                        ]);

                        return response()->json([
                            'status' => 'success'
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'errors' => [
                            'avi' => ['Avi not Found']
                        ]
                    ], 422);
                }
                break;
            case('party'):
                if ($party = Parties::where('name', '=', $name)->first()) {
                    $party->claim()->create(['user_id' => auth()->user()->getAuthIdentifier()]);
                }
                break;
        }
    }
}
