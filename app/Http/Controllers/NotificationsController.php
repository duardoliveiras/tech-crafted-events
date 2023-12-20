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

        return response()->json($userEventNotifications);
    }

    public function getInvites()
    {
        $user_id = Auth::id();

        $invites = Notification::with('events')
            ->where('user_id', $user_id)
            ->where('read', false)
            ->get();

        return response()->json($invites);
    }

    public function updateRead($type, $notificationId)
    {
        if ($type == 'notification') {
            $notification = UserEventNotifications::find($notificationId);
            $qt_notificaiton = Auth::user()->notifications()->where('read', false)->count();

            if ($notification) {
                $notification->update(['read' => true]);
                return response()->json(['message' => 'Read success.', 'qt_notification' => $qt_notificaiton]);
            } else {
                return response()->json(['error' => 'Notification not found.'], 404);
            }
        } else if ($type == 'invite') {
            $notification = Notification::find($notificationId);
            $notification->update(['read' => true]);
        }

    }

    public function inviteUser($userId, $eventId)
    {
        $notification = Notification::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->where('notificationtype', 'INVITE')
            ->where('read', false)
            ->get();

        if ($notification->isEmpty()) {
            $notification = new Notification([
                'text' => "You are invited to EVENT",
                'notificationtype' => 'INVITE',
                'user_id' => $userId,
                'read' => false,
                'event_id' => $eventId
            ]);
            $notification->save();
        }



        return response()->json(['message' => 'Invited success.']);
    }

}