<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avi;

class AvisRatingsController extends Controller
{
    public function index()
    {
        return Avi::paginate(10);
    }
}
