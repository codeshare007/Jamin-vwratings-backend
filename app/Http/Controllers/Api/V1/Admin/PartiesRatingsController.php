<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartiesRatings;

class PartiesRatingsController extends Controller
{
    public function index()
    {
        return PartiesRatings::with(['user', 'party'])->paginate(100);
    }
}
