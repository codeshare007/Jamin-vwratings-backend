<?php
namespace App\Http\Controllers\Api\V1\Front;

use App\Http\Controllers\Controller;
use App\Models\AvisComments;
use App\Models\Messages;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function comments()
    {
        return AvisComments::where('user_id', '=', auth()->user()->getAuthIdentifier())->get();
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

    public function promo()
    {
        return view('promo');
    }
}
