<?php

namespace App\Http\Controllers\Api\V1\Front;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Models\{Settings, User, Messages, AdsCampaigns};
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\{Request, JsonResponse};

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
     * @return JsonResponse
     * @throws ValidationException
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

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function promo(Request $request)
    {
        $campaign = (object)[
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
     * @return JsonResponse
     * @throws ValidationException
     */
    public function sendTracker(Request $request)
    {
        $this->validate($request, [
            'last_page' => 'required',
            'route' => 'required'
        ]);

        $settings = Settings::all()->pluck('value', 'key');
        $session = $request->session();

        $config = [
            'amount_of_hits' => $settings['ads_hits'] ? $settings['ads_hits'] + 1 : 10,
            'promo_routes' => [
                'ratings.avis.view'
            ]
        ];

        $lastPage = $request->get('last_page') ?? '/';
        $session->put('last_page', $lastPage);

        if ($session->has('hits') === false) {
            $session->put('hits', 1);
        }

        $hits = $request->session()->get('hits');

        if ($route = $request->get('route')) {
            if (in_array($route, $config['promo_routes'])) {
                $session->put('hits');
                $hits = $request->session()->get('hits');
            }

            $response = [
                'start_promo' => $hits > $config['amount_of_hits'],
                'show_announcement' => $session->has('timout') ? 0 : 1
            ];

            if ((int)$settings['announcement_enabled']) {
                if ($session->get('timout') == null) {
                    $session->put('timout', Carbon::now()->addHours($settings['announcement_timeout']));
                    $response['show_announcement'] = 1;
                } else {
                    $timout = Carbon::parse($session->get('timout'));
                    if (Carbon::now()->greaterThanOrEqualTo($timout) ) {
                        $request->session()->remove('timout');
                    }
                }

                $response['modal_content'] = $settings['announcement_html'];
            }

            $response['session'] = $request->session()->all();

            return response()->json($response);
        }

        return response()->json(['status' => 'error']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function endPromo(Request $request): JsonResponse
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
