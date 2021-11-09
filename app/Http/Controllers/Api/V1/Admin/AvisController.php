<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avi;

class AvisController extends Controller
{
    public function index()
    {
        $avis = Avi::with(['ratings', 'user'])->paginate(100);
        $avis->each(function ($reply) {
            $reply->append('average_rating');
        });
        return $avis;
    }
}
