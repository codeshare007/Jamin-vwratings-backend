<?php

namespace App\Http\Controllers\Api\V1\Front;

use App\Http\Controllers\Controller;
use App\Models\Avi;
use App\Models\AvisRatings;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;

class AvisController extends Controller
{
    /**
     * @return Avi[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request)
    {
        $avis = Avi::select(['id', 'name']);

        if ($request->has('query')) {
            $avis->where('name', 'LIKE', "%{$request->get('query')}%");
        }

        if ($request->has('per_page')) {
            $avis->paginate($request->get('per_page'));
        }

        if ($request->has('type')) {
            $type = $request->get('type');

            switch ($type):
                case('bad_list'):
                    $avis->with('ratings')
                        ->has('ratings')
                        ->leftJoin('avis_ratings', 'avis_ratings.avis_id', '=', 'avis.id')
                        ->select(['avis.*', DB::raw('AVG(avis_ratings.rating) as ratings_average')])
                        ->groupBy(DB::raw('`avis_ratings`.`avis_id`'))
                        ->having('ratings_average', '>=', '10')
                        ->orderBy('ratings_average', 'DESC')
                        ->limit(10);
                    break;
                case('good_list'):
                    $avis->with('ratings')
                        ->leftJoin('avis_ratings', 'avis_ratings.avis_id', '=', 'avis.id')
                        ->select(['avis.*', DB::raw('AVG(avis_ratings.rating) as ratings_average')])
                        ->groupBy(DB::raw('`avis_ratings`.`avis_id`'))
                        ->having('ratings_average', '<', '4')
                        ->orderBy('ratings_average', 'ASC')
                        ->limit(10);
                    break;
                case('recent_list'):
                    $avis->distinct()->has('ratings')
                        ->with('ratings')
                        ->leftJoin('avis_ratings', 'avis_ratings.avis_id', '=', 'avis.id')
                        ->select(['avis.*'])
                        ->groupBy(DB::raw('`avis_ratings`.`updated_at`'))
                        ->orderBy(DB::raw('`avis_ratings`.`updated_at`'), 'desc')
                        ->limit(10);
                    break;
                case('comments'):
                    $avis->has('comments')
                        ->with('comments')
                        ->leftJoin('avis_comments', 'avis_comments.avis_id', '=', 'avis.id')
                        ->select(['avis.*', DB::raw('COUNT(avis_comments.id) as avis_count')])
                        ->groupBy(DB::raw('`avis_comments`.`avis_id`'))
                        ->orderBy(DB::raw('`avis_comments`.`created_at`'), 'desc')
                        ->orderBy('avis_count', 'DESC')
                        ->limit(10);
                    break;
                case('pics'):
                    $avis->has('comments')
                        ->with('comments')
                        ->leftJoin('avis_comments', 'avis_comments.avi_id', '=', 'avis.id')
                        ->select(['avis.*', DB::raw('COUNT(avis_comments.file) as avis_count')])
                        ->groupBy(DB::raw('`avis_comments`.`avi_id`'))
                        ->orderBy(DB::raw('`avis_comments`.`created_at`'), 'desc')
                        ->having('avis_count', '>', '0')
                        ->orderBy('avis_count', 'DESC')
                        ->limit(10);
                    break;
            endswitch;
        }

        return $avis->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return Avi::with('comments')->find($id)->append(['average_rating', 'user_rating']);
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function rate($id, Request $request)
    {
        $this->validate($request, [
            'rating' => 'required'
        ]);

        $rating = AvisRatings::firstOrCreate([
            'user_id' => auth()->user()->getAuthIdentifier(),
            'avis_id' => $id
        ]);

        if ($rating) {
            $rating->update(['rating' => $request->get('rating')]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function edit(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string'
        ]);

        if ($avi = Avi::find($id)) {
            $avi->name = $request->get('name');
            $avi->save();

            return response()->json(['status' => 'success']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
        $this->validate($request, ['name' => 'required|string']);
        $avi = Avi::firstOrCreate(['name' => $request->get('name')]);
        return response()->json(['status' => 'success', 'data' => $avi]);
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function comment($id, Request $request)
    {
        $this->validate($request, [
            'comment' => 'required|string',
            'opinion' => 'required|int'
        ]);

        if ($avi = Avi::find($id)->first()) {

            $comment = $avi->comments()->create([
                'content' => $request->input('content'),
                'opinion' => $request->input('opinion'),
                'user_id' => auth()->user()->id
            ]);

            /** @var UploadedFile $file */
            foreach ($request->file('attachments') as $file) {
                $fileName = \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();;
                $filePath = $file->storeAs('uploads', $fileName, 'public');

                $comment->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => '/storage/' . $filePath,
                    'type' => $file->getMimeType()
                ]);
            }

            return response()->json(['status' => 'success']);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Avi not found'
        ], 404);
    }
}
