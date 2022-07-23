<?php

namespace App\Http\Controllers\Api\V1\Front;

use App\Http\Controllers\Controller;
use App\Models\Notifications;
use App\Models\UsersReadGlobalNotifications;
use App\Models\User;
use App\Models\UsersNotifications;
use App\Models\Messages;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'username' => $user->username,
            'role' => $user->role,
            'favorites' => [
                'avis' => $user->favoriteAvis()->get(),
                'parties' => $user->favoriteParties()->get()
            ]
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function favoriteAvis(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        return response()->json($user->favoriteAvis()->get());
    }

    public function getNotifications(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $isAll = $request->input('all');
        $notifications = collect();
        $readIds = collect();

        if ($isAll) {
            $deletedGlobalIds = collect();
            foreach ($user->globalNotifications()->where('status', UsersReadGlobalNotifications::STATUS_DELECTED)->get() as $globalNotification) {
                $deletedGlobalIds->push($globalNotification->notification_id);
            }
            $globalNotifications = Notifications::whereNotIn('id', $deletedGlobalIds)->get();
            foreach ($globalNotifications as $notification) {
                $notifications->push($notification);
            }
            foreach ($user->notifications()->get() as $notification) {
                $notifications->push($notification);
            }
        } else {
            // Get unread notifications
            $readIds = collect();
            foreach ($user->globalNotifications()->get() as $readNotification) {
                $readIds->push($readNotification->notification_id);
            }

            $globalNotifications = Notifications::whereNotIn('id', $readIds)->get();
            foreach ($globalNotifications as $notification) {
                $notifications->push($notification);
            }

            $userUnreadNotifications = $user->notifications()->where('status', UsersNotifications::STATUS_UNREAD)->get();
            foreach ($userUnreadNotifications as $notification) {
                $notifications->push($notification);
            }
        }
        
        return response()->json([
            'data' => $notifications->sortBy('created_at', SORT_ASC)
        ]);
    }


    /**
     * @return JsonResponse
     */
    public function readAllNotifications(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        foreach ($user->notifications()->get() as $notification) {
            $notification->update([
                'status' => UsersNotifications::STATUS_READ
            ]);
        }

        $readOrDeletedNotificationIds = collect();
        foreach ($user->globalNotifications()->get() as $notification) {
            $readOrDeletedNotificationIds->push($notification->notification_id);
        }
        $unreadGlobalNotifications = Notifications::whereNotIn('id', $readOrDeletedNotificationIds)->get();
        
        foreach ($unreadGlobalNotifications as $notification) {
            UsersReadGlobalNotifications::create([
                'user_id' => $user->id,
                'notification_id' => $notification->id,
                'status' => UsersReadGlobalNotifications::STATUS_READ
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function readNotification($id, Request $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $isGlobal = $request->input('global');

        if ($isGlobal) {
            $globalNotification = $user->globalNotifications()->where('notification_id', $id)->first();
            if (!$globalNotification) {
                UsersReadGlobalNotifications::create([
                    'user_id' => $user->id,
                    'notification_id' => $id,
                    'status' => UsersReadGlobalNotifications::STATUS_READ
                ]);
            }
        } else {
            if ($notification = UserNotification::findOrFail($id)) {
                $notification->update(['status' => UsersNotifications::STATUS_READ]);
            }
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * @return JsonResponse
     */
    public function favoriteParties(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        return response()->json($user->favoriteParties()->get());
    }

    public function commentsAvis(): Collection
    {
        /** @var User $user */
        $user = auth()->user();
        return $user->avisComments()->with(['attachments', 'avi'])->limit(5)->latest()->get();
    }

    public function commentsParties(): Collection
    {
        /** @var User $user */
        $user = auth()->user();
        return $user->partiesCOmments()->with(['attachments', 'party'])->limit(5)->latest()->get();
    }

    public function statsAvis(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        return response()->json([
            'rated' => $user->avisRated()->count(),
            'comments' => $user->avisComments()->count(),
            'added' => $user->avis()->count()
        ]);
    }

    public function statsParties(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        return response()->json([
            'rated' => $user->partiesRated()->count(),
            'comments' => $user->partiesComments()->count(),
            'added' => $user->parties()->count()
        ]);
    }

    /**
     * @param Request $request
     * @throws ValidationException
     */
    public function changePassword(Request $request): JsonResponse
    {
        $this->validate($request, [
            'old_password' => 'required|string',
            'password' => 'required|min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6'
        ]);

        if ($request->get('old_password') === $request->get('password')) {
            return response()->json([
                'errors' => ['password' => ['Passwords are same.']]
            ], 422);
        }

        /** @var User $user */
        $user = auth()->user();
        $oldPassword = Hash::make($request->get('old_password'));

        if (Hash::check($oldPassword, $user->password)) {
            return response()->json([
                'errors' => ['password' => ['Old password is wrong.']]
            ], 422);
        }

        $user->password = Hash::make($request->get('password'));
        $user->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'nullable|email'
        ]);

        /** @var User $user */
        $user = auth()->user();
        $old_email = $user->email;
        $email = $request->get('email');

        if ($email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
                $user->update(['email' => $request->get('email') ]);
            } else {
                return response()->json([
                    'errors' => ['email' => ['Email is not valid.']]
                ], 422);
            }
        } else {
            $user->update(['email' => null]);
        }

        if ($email != $old_email) {
            Messages::create([
                'name' => $user->username,
                'email' => $email,
                'content' => $user->username. ' modified the email.'
            ]);
        }
        
        return response()->json(['status' => 'success']);
    }
}
