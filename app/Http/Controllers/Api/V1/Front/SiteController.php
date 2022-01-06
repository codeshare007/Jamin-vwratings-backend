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
    public function sendTracker(Request $request): JsonResponse
    {
        $this->validate($request, [
            'last_page' => 'required',
            'route' => 'required'
        ]);

        $session = $request->session();

        $settings = Settings::all()->pluck('value', 'key');

        $isAnnouncementEnabled = (int) $settings['announcement_enabled'];
        $announcementTimeout = (int) $settings['announcement_timeout'];
        $announcementContent = $settings['announcement_html'];
        $hitsLimit = (int) $settings['ads_hits'] ?? 10;

        // set last visited page
        $session->put('last_page', $request->get('last_page') ?? DIRECTORY_SEPARATOR);

        // initialize hits
        if ($session->has('hits') == false) {
            $session->put('hits', 1);
        }

        if ($route = $request->get('route')) {

            // increase hits if page in array visited
            if (in_array($route, ['ratings.avis.view'])) {
                $session->increment('hits');
            }

            $response = [
                'start_promo' => $session->get('hits') > $hitsLimit,
                'show_modal' => false
            ];

            $shoModal = false;
            if ($isAnnouncementEnabled) {

                $modalVersion = md5($announcementContent);

                if ($session->has('timeout')) {
                    $sessionTimeout = Carbon::parse($session->get('timeout'));
                    if (Carbon::now()->greaterThan($sessionTimeout)) {
                        $session->remove('timeout');
                    }
                } else {
                    $session->put('timeout', Carbon::now()->addMinutes($announcementTimeout));
                    $shoModal = true;
                }

                if ($session->has('modal_version')) {
                    if ($session->get('modal_version') !== $modalVersion) {
                        $session->put('modal_version', $modalVersion);
                        $shoModal = true;
                    }
                } else {
                    $session->put('modal_version', $modalVersion);
                }

                if ($shoModal) {
                    $response['modal_content'] = $announcementContent;
                    $response['show_modal'] = true;
                }
            }

            $response['session'] = $request->session()->all();

            return response()->json($response);
        }

        return response()->json(['status' => 'error'], 422);
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
