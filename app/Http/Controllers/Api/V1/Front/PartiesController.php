<?php

namespace App\Http\Controllers\Api\V1\Front;

use App\Http\Controllers\Controller;
use App\Models\Parties;
use Illuminate\Http\Request;

class PartiesController extends Controller
{
    public function index(Request $request)
    {
        $avis = Parties::query();

        if ($request->has('search')) {
            $avis->where('name', 'LIKE', "%{$request->get('search')}%");
        }

        if ($request->has('per_page')) {
            $avis->paginate($request->get('per_page'));
        }

        if ($request->has('type')) {
            switch ($request->get('type')):
                case('full_list'): $avis->select(['id', 'name'])->latest();
                    break;
                case('good_list'): $avis->averageRating('>', 9);
                    break;
                case('bad_list'): $avis->averageRating('<',  4, 'ASC');
                    break;
                case('recent_list'): $avis->recentRated();
                    break;
                case('comments'): $avis->latestComments();
                    break;
                case('pics'): $avis->latestAttachments();
                    break;
            endswitch;
        }

        return $avis->get();
    }
}
