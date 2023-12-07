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

        $userEventNotifications = UserEventNotifications::with('eventNotification.event')
        ->where('user_id', $user_id)
        ->where('read', false)
        ->get();
                                 
        return response()->json($userEventNotifications);
    }

    public function updateRead($notificationId)
    {
        $notification = UserEventNotifications::find($notificationId);

        if($notification) {     
            $notification->update(['read' => true]);
            return response()->json(['message' => 'Marcado como lido com sucesso.']);
        }   
        else{
            return response()->json(['error' => 'Notificação não encontrada.'], 404);
        }
    }
} 