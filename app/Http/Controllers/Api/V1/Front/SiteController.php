<?php

namespace App\Http\Controllers\Api\V1\Front;

use App\Http\Controllers\Controller;
use App\Models\AdsCampaigns;
use App\Models\AvisComments;
use App\Models\Messages;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * @return Collection
     */
    public function comments(): Collection
    {
        /** @var User $user */
        $user = auth()->user();
        return $user
            ->comments()
            ->with(['attachments', 'avi'])
            ->limit(5)
            ->latest()
            ->get();
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function message(Request $request)
    {
        $this->validate($request, [
            'name' => 'nullable|string',
            'email' => 'nullable|string',
            'content' => 'required|max:255'
        ]);

        $message = new Messages();

        if ($name = $request->get('name'))
            $message->name = $name;

        if ($email = $request->get('email'))
            $message->email = $email;

        $message->content = $request->get('content');
        $message->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Message successfully sent'
        ]);
    }

    public function promo(Request $request)
    {
        $campaign = (object) [
            'timer' => 0,
            'content' => 'Ads campaigns not set'
        ];

        if ($id = $request->get('campaign')) {
            if ($requestedCampaigns = AdsCampaigns::findOrFail($id)) {
                $campaign = $requestedCampaigns;
                $campaign->timer = 100;
            }
        } else {
            if ($requestedCampaigns = AdsCampaigns::where('active', '=', 1)->first()) {
                $campaign = $requestedCampaigns;
            }
        }

        return view('promo', [
            'campaign' => $campaign
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendTracker(Request $request)
    {
        $this->validate($request, [
            'last_page' => 'required',
            'route' => 'required'
        ]);

        $promo = false;

        $amountOfHits = Settings::where('key', 'ads_hits')->first();
        $announcementHtml = Settings::where('key', 'announcement_html')->first();
        $announcementEnabled = Settings::where('key', 'announcement_enabled')->first();


        $config = [
            'amount_of_hits' => $amountOfHits ? $amountOfHits->value + 1 : 10,
            'promo_routes' => [
                'ratings.avis.view'
            ]
        ];

        if (!($lastPage = $request->get('last_page'))) $lastPage = '/';
        $request->session()->put('last_page', $lastPage);

        if (!($hits = $request->session()->get('hits'))) {
            $request->session()->put('hits', 1);
            $hits = $request->session()->get('hits');
        }

        if ($route = $request->get('route')) {
            if (in_array($route, $config['promo_routes'])) {
                $request->session()->put('hits', $hits = $hits + 1);
            }

            if ($hits > $config['amount_of_hits']) $promo = true;

            $response = [
                'start_promo' => $promo,
                'announcement_enabled' => (int) $announcementEnabled->value
            ];

            if ($response['announcement_enabled']) {
                $response['announcement_content'] = $announcementHtml->value;
            }

            return response()->json($response);
        }

        return response()->json(['status' => 'error']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function endPromo(Request $request)
    {
        if ($request->get('csrf') === csrf_token()) {

            $request->session()->put('hits', 0);

            return response()->json([
                'status' => 'success',
                'last_page' => $request->session()->get('last_page') ?? '/'
            ]);
        } else {
            return response()->json(['status' => 'error', 'csrf' => csrf_token()]);
        }
    }
}
