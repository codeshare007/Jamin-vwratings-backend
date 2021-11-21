<?php

namespace App\Http\Controllers\Api\V1\Front;

use App\Http\Controllers\Controller;
use App\Models\Avi;
use App\Models\Parties;
use Illuminate\Http\Request;

class ClaimsController extends Controller
{
    public function claim(Request $request)
    {
        $this->validate($request, [
           'name' => 'required|string',
           'type' => 'required|int'
        ]);

        $name = $request->get('name');
        $type = $request->get('type');

        switch ($type) {
            case('avi'):
                if ($avi = Avi::where('name', '=', $name)) {
                    $avi->claim()->create([ 'user_id' => auth()->user()->getAuthIdentifier() ]);
                }
                break;
            case('perty'):
                if ($party = Parties::where('name', '=', $name)) {
                    $party->claim()->create([ 'user_id' => auth()->user()->getAuthIdentifier() ]);
                }
                break;
        }
    }
}
