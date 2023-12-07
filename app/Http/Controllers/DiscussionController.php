<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class DiscussionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('acess.ticket')->only('show');
    }

    public function show($eventId)
    {
        $event = Event::with('discussion', 'ticket')->findOrFail($eventId);
        $user = auth()->user();
        $discussion = $event->discussion;
        if (!$event->discussion) {
            return redirect()->route('home');
        }

        $userHasTicket = $event->ticket->contains('user_id', $user->id);
        $isOrganizer = $event->owner->id == $user->id;

        if (!($userHasTicket || $isOrganizer || $user->isAdmin())) {
            abort(403, 'You do not have access to this discussion.');
        }

        $comments = $discussion->comments ?? collect();
        $userVotes = $user->votesForDiscussion($discussion);

        return view('layouts.event.discussion.show', compact('discussion', 'comments', 'event', 'userVotes'));
    }
}
