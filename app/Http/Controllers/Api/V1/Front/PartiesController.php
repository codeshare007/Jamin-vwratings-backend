<?php

namespace App\Http\Controllers\Api\V1\Front;

use App\Http\Controllers\Controller;
use App\Models\{Parties, PartiesComments, PartiesRatings};
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\{Collection, Builder};
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PartiesController extends Controller
{
    /**
     * @param Request $request
     * @return Builder[]|Collection
     */
    public function index(Request $request)
    {
        $parties = Parties::query();

        if ($request->has('search')) {
            $parties->where('name', 'LIKE', "%{$request->get('search')}%");
        }

        if ($request->has('per_page')) {
            $parties->paginate($request->get('per_page'));
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

        return $parties->get();
    }

    /**
     * @param $id
     * @return Builder|Builder[]|Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function show($id)
    {
        return Parties::with(['comments', 'comments.attachments'])->find($id)
            ->append(['average_rating', 'user_rating']);
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
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, ['name' => 'required|string']);
        $party = Parties::firstOrCreate(['name' => $request->get('name')]);
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
