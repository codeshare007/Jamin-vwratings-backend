<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdsCampaigns;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\{Request, JsonResponse};

class AdsCampaignsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('type')) {
            $type = $request->get('type');
            return AdsCampaigns::where('type', $type)->paginate(10);
        } else {
            return AdsCampaigns::paginate(10);
        }
    }

    public function show($id)
    {
        return AdsCampaigns::findOrFail($id);
    }

    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'timer' => 'required|integer',
            'active' => 'required|integer',
            'type' => 'required|integer',
        ]);

        $campaign = new AdsCampaigns();
        $campaign->name = $request->get('name');
        if ($description = $request->get('description'))
            $campaign->description = $description;

        if ($content = $request->get('content'))
            $campaign->content = $content;

        $campaign->timer = $request->get('timer');
        $campaign->active = $request->get('active');
        $campaign->type = $request->get('type');
        $campaign->save();

        return response()->json(['status' => 'success']);

    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update($id, Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'timer' => 'required|integer',
            'active' => 'required|integer',
        ]);

        if ($campaign = AdsCampaigns::find($id)) {
            $campaign->name = $request->get('name');
            if ($description = $request->get('description'))
                $campaign->description = $description;

            if ($content = $request->get('content'))
                $campaign->content = $content;

            $campaign->timer = $request->get('timer');
            $campaign->active = $request->get('active');

            $campaign->save();

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error']);
    }

    public function destroy($id)
    {
        if ($campaign = AdsCampaigns::findOrFail($id)) {
            $campaign->delete();
            return response()->json(['status' => 'error']);
        }
    }
}
