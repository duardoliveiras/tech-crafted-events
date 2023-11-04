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
        $this->middleware('has.ticket')->only('show');
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($eventId)
    {
        $event = Event::with('discussion')->findOrFail($eventId);
        $user = auth()->user();
        $discussion = $event->discussion;
        if (!$event->discussion) {
            return redirect()->route('home');
        }

        $userHasTicket = $event->ticket->contains('user_id', $user->id);
        $isOrganizer = $event->owner->id == $user->id;

        if (!$userHasTicket && !$isOrganizer) {
            abort(403, 'You do not have access to this discussion.');
        }




        $comments = $discussion->comments ?? collect();

        return view('layouts.event.discussion.show', compact('discussion', 'comments','event'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
