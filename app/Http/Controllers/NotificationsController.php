<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Comment;
use App\Models\Discussion;
use App\Models\EventReport;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\CommentReport;
use App\Models\EventNotification;
use App\Events\NotificationReceived;
use Illuminate\Support\Facades\Auth;
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

    public function getInvites($type)
    {
        $user_id = Auth::id();

        $invites = Notification::with('events')
            ->where('user_id', $user_id)
            ->where('read', false)
            ->where('notificationtype', $type)
            ->get();

        return response()->json($invites);
    }

    public function updateRead($type, $notificationId)
    {
        $qt_notificaiton = Auth::user()->allNotifications();

        if ($type == 'notification') {
            $notification = UserEventNotifications::find($notificationId);
            if ($notification) {
                $notification->update(['read' => true]);
                return response()->json(['message' => 'Read success.', 'qt_notification' => $qt_notificaiton]);
            } else {
                return response()->json(['error' => 'Notification not found.'], 404);
            }
        } else {
            $notification = Notification::find($notificationId);
            $notification->update(['read' => true]);
            return response()->json(['message' => 'Read success.', 'qt_notification' => $qt_notificaiton]);
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
            $users = array($userId);
            event(new NotificationReceived($users));
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

    public function notifyUsersComment($commentId, $type)
    {

        $comment = Comment::find($commentId);
        $reports = CommentReport::where('comment_id', $commentId)->get();
        $event = Discussion::find($comment->discussion_id)->event;

        if ($type === "check") {
            $text = "Your comment report in the " . $event->name . " discussion has been successfully analyzed. The comment was maintained as our team believes it did not violate any company rules.";
        } else {
            $text = "Your comment report in the " . $event->name . " discussion has been successfully analyzed. The comment was removed from the site and the writer's account was banned. Thank you for contributing to a safe community.";
        }

        $users = [];
        foreach ($reports as $report) {
            $notification = new Notification([
                'text' => $text,
                'notificationtype' => 'REPORT',
                'user_id' => $report->user_id,
                'read' => false,
                'event_id' => null
            ]);
            $notification->save();
            array_push($users, $report->user_id);
        }
        event(new NotificationReceived($users));

        return response()->json(['message' => 'Read success.']);
    }

    public function notifyUsersEvent($eventId, $type)
    {
        $reports = EventReport::where('event_id', $eventId)->get();
        $event = Event::find($eventId);

        if ($type === "check") {
            $text = "Your " . $event->name . " event report has been successfully analyzed. The event was maintained as our team believes it did not violate any company rules.";
        } else {
            $text = "Your " . $event->name . " event report has been successfully analyzed. The event was removed from the site. Thank you for contributing to a safe community.";
        }

        $users = [];
        foreach ($reports as $report) {
            $notification = new Notification([
                'text' => $text,
                'notificationtype' => 'REPORT',
                'user_id' => $report->user_id,
                'read' => false,
                'event_id' => null
            ]);
            $notification->save();
            array_push($users, $report->user_id);
        }
        event(new NotificationReceived($users));

        return response()->json(['message' => 'Read success.']);
    }

}