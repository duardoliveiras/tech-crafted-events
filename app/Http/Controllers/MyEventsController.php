<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyEventsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Buscando os EventOrganizers e seus eventos
        $eventOrganizers = $user->eventOrganizer()->with('events')->get();
        $organizedEvents = $eventOrganizers->flatMap(function ($organizer) {
            return $organizer->events;
        });

        $tickets = $user->ticket()->with('event')->where('status', 'PAID')->get();
        $eventsWithTickets = $tickets->map(function ($ticket) {
            return $ticket->event;
        })->unique('id');

        return view('layouts.my_events.index', compact('organizedEvents', 'eventsWithTickets'));
    }

}
