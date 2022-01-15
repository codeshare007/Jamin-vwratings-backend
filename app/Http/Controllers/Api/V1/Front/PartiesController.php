<?php

namespace App\Http\Controllers\Api\V1\Front;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Parties,
    PartiesClaims,
    PartiesCommentsAttachments,
    PartiesRatings,
    User,
    UsersFavoriteParties};
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\{Collection, Builder};
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class PartiesController extends Controller
{

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request): LengthAwarePaginator
    {
        $parties = Parties::query();

        if ($request->has('search')) {
            $parties->where('name', 'LIKE', "%{$request->get('search')}%");
        }

        if ($request->has('type')) {
            switch ($request->get('type')):
                case('full_list'):
                    $parties->select(['id', 'name'])->latest();
                    break;
                case('good_list'):
                    $parties->averageRating('>', 9);
                    break;
                case('bad_list'):
                    $parties->averageRating('<', 4, 'ASC');
                    break;
                case('recent_list'):
                    $parties->recentRated();
                    break;
                case('comments'):
                    $parties->latestComments();
                    break;
                case('pics'):
                    $parties->latestAttachments();
                    break;
            endswitch;
        }

        return $parties->paginate($request->get('per_page'));
    }

    public function attachments(Request $request): LengthAwarePaginator
    {
        $comments = PartiesCommentsAttachments::query();
        $comments->with(['comment', 'comment.party']);

        return $comments->paginate($request->get('per_page'));
    }

    public function show($id)
    {
        if ($party = Parties::with(['comments.attachments', 'comments', 'claim', 'ratings'])->find($id)) {

            $party->append(['average_rating', 'user_rating']);

            $ratingsCount = $party->ratings()->count();

            $partyClaimed = false;
            // remove negative comments if avi claimed
            if (PartiesClaims::where('party_id', '=', $id)->count()) {
                $partyClaimed = true;
                $comments = array_values($party->comments->filter(function ($item) {
                    if ($item->attachments->count() || $item->opinion !== 2) return $item;
                    return false;
                })->toArray());

                $party->unsetRelation('comments');
                $party['comments'] = $comments;
            }

            /** @var User $user */
            if ($user = auth()->user()) {
                if ($user->favoriteParties()->where('party_id', '=', $id)->exists()) {
                    $party['is_favorite'] = true;
                }
            }

            if (!$partyClaimed) {
                $partiesComments = $party['comments']->toArray();
            } else {
                $partiesComments = [];
            }

            $party['statistics'] = [
                'comments' => count($party['comments']),
                'rated' => $ratingsCount,
                'positive' => count(array_filter($partiesComments, function($item) {
                    return $item['opinion'] == 1 ? $item : null;
                })),
                'negative' => count(array_filter($partiesComments, function($item) {
                    return $item['opinion'] == 2 ? $item : null;
                })),
                'watchers' => UsersFavoriteParties::where(['party_id' => $id])->count()
            ];

            return $party;
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Party not found'
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

        if ($favorite = $user->favoriteParties()->where('party_id', '=', $id)->first()) {
            $favorite->delete();
        } else {
            $user->favoriteParties()->create(['party_id' => $id]);
        }

        return response()->json(['status' => 'success']);
    }

    public function removeFavorite($id): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $user->favoriteParties()->where('party_id', '=', $id)->delete();
        return response()->json(['status' => 'success']);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function rate($id, Request $request): JsonResponse
    {
        $this->validate($request, [
            'rating' => 'required'
        ]);

        $rating = PartiesRatings::firstOrCreate([
            'user_id' => auth()->user()->getAuthIdentifier(),
            'party_id' => $id
        ]);

        if ($rating) {
            $rating->update(['rating' => $request->get('rating')]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse|void
     * @throws ValidationException
     */
    public function edit(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string'
        ]);

        if ($party = Parties::find($id)) {
            $party->name = $request->get('name');
            $party->save();

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
            $party = Parties::firstOrCreate(['name' => $name, 'user_id' => $user->id]);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == '1062') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Party already exists'
                ], 422);
            }
        }

        return response()->json(['status' => 'success', 'data' => $party]);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function comment($id, Request $request): JsonResponse
    {
        $this->validate($request, [
            'comment' => 'required_if:files,""',
            'files' => 'required_if:comment,""',
            'opinion' => 'required|int'
        ]);

        if ($party = Parties::findOrFail($id)) {

            $comment = $party->comments()->create([
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
            'message' => 'Party not found.'
        ], 404);
    }
}
