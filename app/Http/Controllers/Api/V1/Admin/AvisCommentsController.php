<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvisComments;
use Illuminate\Http\Request;

class AvisCommentsController extends Controller
{
    public function index()
    {
        return AvisComments::with(['user', 'avi'])->paginate(100);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => ''
        ]);
    }
}
