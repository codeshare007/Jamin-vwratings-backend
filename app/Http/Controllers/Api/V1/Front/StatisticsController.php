<?php

namespace App\Http\Controllers\Api\V1\Front;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Models\{Avi, AvisComments, Parties, PartiesComments, Settings, User, Messages, AdsCampaigns};
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\{Request, JsonResponse};

class StatisticsController extends Controller
{
    public function index()
    {



        return response()->json(['data' => [
            'avis' => [
                'all' => Avi::all()->count(),
                'good' => Avi::query()->averageRating('>', 9)->count(),
                'bad' => Avi::query()->averageRating('<', 4)->count(),
                'with_comments' => Avi::query()->has('comments')->count(),
                'pics' => Avi::query()->has('comments.attachments')->count(),
                'comments' => [
                    'good' => AvisComments::where(['opinion' => 1])->count(),
                    'bad' => AvisComments::where(['opinion' => 2])->count()
                ],
                'most_rated' => Avi::query()->mostRated()->limit(5)->get(),
                'most_commented' => Avi::query()->mostCommented()->limit(5)->get(),
                'top_rated' => Avi::query()->topRated()->limit(5)->get(),
                'lowest_rated' => Avi::query()->lowRated()->limit(5)->get()
            ],
            'parties' => [
                'all' => Parties::all()->count(),
                'good' => Parties::query()->averageRating('>', 9)->count(),
                'bad' => Parties::query()->averageRating('<', 4)->count(),
                'with_comments' => Parties::query()->has('comments')->count(),
                'pics' => Parties::query()->has('comments.attachments')->count(),
                'comments' => [
                    'good' => PartiesComments::where(['opinion' => 1])->count(),
                    'bad' => PartiesComments::where(['opinion' => 2])->count()
                ],
                'most_rated' => [],
                'most_commented' => [],
                'top_rated' => [],
                'lowest_rated' => []
            ],
        ]]);
    }
}
