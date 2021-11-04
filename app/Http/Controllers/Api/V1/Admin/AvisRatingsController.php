<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvisRatings;

class AvisRatingsController extends Controller
{
    public function index()
    {
        return AvisRatings::with(['user', 'avi'])->paginate(10);
    }
}
