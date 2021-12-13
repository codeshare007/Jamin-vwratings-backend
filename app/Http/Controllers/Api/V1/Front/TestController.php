<?php

namespace App\Http\Controllers\Api\V1\Front;

use App\Http\Controllers\Controller;
use App\Models\Avi;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{

    public function index()
    {

        return Avi::query();

        /*
        $duplicates = DB::table('avis')
            ->select('name', DB::raw('COUNT(*) as `count`'))
            ->groupBy('name',)
            ->having('count', '>', 1)
            ->get();

        return $duplicates; */
    }
}
