<?php
namespace App\Http\Controllers\Api\V1\Front;

use Illuminate\Support\Str;
use App\Models\{Avi, AvisClaims, AvisCommentsAttachments, AvisInterviews, AvisRatings, User};
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\{Request, UploadedFile, JsonResponse};

class AvisInterviewsController extends Controller
{
    public function index()
    {
        return AvisInterviews::all();
    }
}
