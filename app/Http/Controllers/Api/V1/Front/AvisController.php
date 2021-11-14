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
        $avis = Avi::query();

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

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return Avi::with(['comments', 'comments.attachments'])->find($id)->append(['average_rating', 'user_rating']);
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
    public function store(Request $request)
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
                    $fileName = \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();;
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
