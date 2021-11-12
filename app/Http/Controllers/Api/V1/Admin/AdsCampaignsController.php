<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdsCampaigns;
use Illuminate\Http\Request;

class AdsCampaignsController extends Controller
{
    public function index()
    {
        return AdsCampaigns::paginate(10);
    }

    public function store(Request $request)
    {
        $this->validate($request, [

        ]);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [

        ]);
    }

    public function destroy($id)
    {

    }
}
