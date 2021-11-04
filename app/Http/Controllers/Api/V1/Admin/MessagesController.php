<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Messages;

class MessagesController extends Controller
{
    public function index()
    {
        return Messages::paginate(10);
    }
}
