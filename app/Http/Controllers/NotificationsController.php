<?php

namespace App\Http\Controllers;

use App\Events\NotificationReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\EventNotification;
use App\Models\Event;
use App\Models\UserEventNotifications;

class NotificationsController extends Controller
{
    // public function index()
    // {
    //     $user = Auth::user();
    //     $notifications = Notification::getNotificationsByUserId($user->id);

    //     return view('layouts.notifications.index', compact('user', 'notifications'));
    // }

    public function index()
    {
        $user_id = Auth::id();

        $userEventNotifications = UserEventNotifications::with('eventNotification.event')
            ->where('user_id', $user_id)
            ->where('read', false)
            ->get();
        $userNotifications = Notification::where('user_id', $user_id)->get();

        $allNotifications = $userEventNotifications->concat($userNotifications);
        error_log($allNotifications);

        return response()->json($userEventNotifications);
    }

    public function updateRead($notificationId)
    {
        $notification = UserEventNotifications::find($notificationId);
        $qt_notificaiton = Auth::user()->notifications()->where('read', false)->count();

        if ($notification) {
            $notification->update(['read' => true]);
            return response()->json(['message' => 'Read success.', 'qt_notification' => $qt_notificaiton]);
        } else {
            return response()->json(['error' => 'Notification not found.'], 404);
        }
    }

    public function inviteUser($userId, $eventId)
    {
        $notification = new Notification([
            'text' => "You are invited to EVENT",
            'notificationtype' => 'INVITE',
            'user_id' => $userId,
            'event_id' => $eventId
        ]);

        $notification->save();
        return response()->json(['message' => 'Invited success.']);
    }

}