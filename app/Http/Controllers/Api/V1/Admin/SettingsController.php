<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Parties;
use App\Models\Settings;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        return Settings::paginate(10);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return Settings::findOrFail($id);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'value' => 'required|string'
        ]);

        if ($setting = Settings::findOrFail($id)) {
            $setting->value = $request->get('value');
            $setting->save();
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 422);
    }
}
