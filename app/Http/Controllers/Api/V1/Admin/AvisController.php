<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avi;

class AvisController extends Controller
{
    public function index()
    {
        return Avi::with('user')->paginate(10);
    }
}
