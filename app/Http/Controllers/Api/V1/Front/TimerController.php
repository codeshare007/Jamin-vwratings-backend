<?php

namespace App\Http\Controllers\Api\V1\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;

class TimerController extends Controller
{

    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' =>  [Settings::getSetting("timer_start"), date('Y-m-d H:i:s'), Settings::getSetting("timer_period_minutes"), ]
        ]);
    }
}
