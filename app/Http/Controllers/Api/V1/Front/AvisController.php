<?php

namespace App\Http\Controllers\Api\V1\Front;

use Illuminate\Support\Str;
use App\Models\{Avi, AvisClaims, AvisCommentsAttachments, AvisInterviews, AvisRatings, User, UsersFavoriteAvis};
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\{Request, UploadedFile, JsonResponse};

class AvisController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $avis = Avi::query();

        if ($request->has('search')) {
            $avis->where('name', 'LIKE', "%{$request->get('search')}%");
        }

        if ($request->has('type')) {
            switch ($request->get('type')):
                case('full_list'):
                    $avis->select(['id', 'name'])->latest();
                    break;
                case('good_list'):
                    $avis->averageRating('>', 9);
                    break;
                case('bad_list'):
                    $avis->averageRating('<', 4, 'ASC');
                    break;
                case('recent_list'):
                    $avis->recentRated();
                    break;
                case('comments'):
                    $avis->latestComments();
                    break;
                case('pics'):
                    $avis->latestAttachments();
                    break;
            endswitch;
        }

        return $avis->paginate($request->get('per_page'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        if ($avi = Avi::with(['comments.attachments', 'comments', 'claim', 'ratings'])->find($id)) {

            $avi->append(['average_rating', 'user_rating']);

            $ratingsCount = $avi->ratings()->count();

            // remove negative comments if avi claimed
            if (AvisClaims::where('avis_id', '=', $id)->count()) {
                $comments = array_values($avi->comments->filter(function ($item) {
                    if ($item->attachments->count() || $item->opinion !== 2) return $item;
                    return false;
                })->toArray());

                $avi->unsetRelation('comments');
                $avi['comments'] = $comments;
            }

            /** @var User $user */
            if ($user = auth()->user()) {
                if ($user->favoriteAvis()->where('avis_id', '=', $id)->exists()) {
                    $avi['is_favorite'] = true;
                }
            }

            $avi['statistics'] = [
                'comments' => count($avi['comments']),
                'rated' => $ratingsCount,
                'positive' => count(array_filter($avi['comments']->toArray(), function($item) {
                    return $item['opinion'] == 1 ? $item : null;
                })),
                'negative' => count(array_filter($avi['comments']->toArray(), function($item) {
                    return $item['opinion'] == 2 ? $item : null;
                })),
                'watchers' => UsersFavoriteAvis::where(['avis_id' => $id])->count()
            ];

            return $avi;
        }

        return response()->json([
            'status' => 'error',
            'message' => 'avi not found'
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function favorite($id): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if ($favorite = $user->favoriteAvis()->where('avis_id', '=', $id)->first()) {
            $favorite->delete();
        } else {
            $user->favoriteAvis()->create(['avis_id' => $id]);
        }

        return response()->json(['status' => 'success']);
    }

    public function removeFavorite($id): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $user->favoriteAvis()->where('avis_id', '=', $id)->delete();
        return response()->json(['status' => 'success']);
    }

    public function getInterview($id)
    {
        return AvisInterviews::where('avis_id', '=', $id)->first();
    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function attachments(Request $request): LengthAwarePaginator
    {
        $comments = AvisCommentsAttachments::query();
        $comments->with(['comment', 'comment.avi']);

        return $comments->paginate($request->get('per_page'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function rate($id, Request $request)
    {
        $this->validate($request, [
            'rating' => 'required'
        ]);

        /** @var User $user */
        $user = auth()->user();

        $rating = AvisRatings::firstOrCreate([
            'user_id' => $user->id,
            'avis_id' => $id
        ]);

        if ($rating) $rating->update([
            'rating' => $request->get('rating')
        ]);


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
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|string'
        ]);

        /** @var User $user */
        $user = auth()->user();
        $name = $request->get('name');
        $party = null;

        try {
            $party = Avi::firstOrCreate(['name' => $name, 'user_id' => $user->id]);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == '1062') {
                return response()->json([
                    'status' => 'error', 'message' => 'Avi already exists'
                ], 422);
            }
        }

        return response()->json(['status' => 'success', 'data' => $party]);
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
            'comment' => 'required_if:files,""',
            'files' => 'required_if:comment,""',
            'opinion' => 'required|int'
        ]);

        if ($avi = Avi::findOrFail($id)) {

            $comment = $avi->comments()->create([
                'content' => $request->get('comment'),
                'opinion' => $request->get('opinion'),
                'user_id' => auth()->user()->getAuthIdentifier()
            ]);

            if ($request->has('attachments')) {
                /** @var UploadedFile $file */
                foreach ($request->file('attachments') as $file) {
                    $fileName = Str::random(10) . '.' . $file->getClientOriginalExtension();;
                    $filePath = $file->storeAs('uploads', $fileName, 'public');

                    $comment->attachments()->create([
                        'filename' => $file->getClientOriginalName(),
                        'path' => '/storage/' . $filePath,
                        'type' => $file->getMimeType()
                    ]);
                }
            }

            return response()->json(['status' => 'success']);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Avi not found'
        ], 404);
    }
}
