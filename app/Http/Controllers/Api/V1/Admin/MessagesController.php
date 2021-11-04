<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class MessagesController extends Controller
{
    public function index()
    {
        return User::paginate(10);
    }
}
