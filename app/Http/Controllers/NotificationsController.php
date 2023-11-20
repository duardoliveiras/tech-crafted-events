<?php

namespace App\Http\Controllers;

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

        $notifications = UserEventNotifications::where('user_id', $user_id)
            ->where('read', false)
            ->get();


        $eventNotifications = [];

        $eventNotifications = [];

        foreach ($notifications as $notification) {

            $eventNotification = EventNotification::find($notification->notification_id);

            if ($eventNotification) {

                $event = Event::find($eventNotification->event_id);

                if ($event) {
                    $eventNotifications[] = [
                        'eventNotification' => $eventNotification,
                        'eventName' => $event->name,
                    ];
                }
            }
        }

        return view('layouts.notifications.index', compact('eventNotifications'));
    }

    public function markRead($notificationId)
  
  
    {

        $notification = UserEventNotifications::find($notificationId);

        if($notification) {
            
            $notification->update([
                'read' => true,
            ]);
            error_log($notification);
            echo "Nome: " . $notification . "<br>";

            return response()->json([
                'success' => true,
            ]);
        }
            return response()->json([
                'success' => false,
            ]);
            
    }
}
