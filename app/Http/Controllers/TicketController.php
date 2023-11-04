<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        return redirect()->route('event.show', $request->event_id)->with('success', 'Ticket purchased successfully!');
    }
    public function showBuyTicketForm(Event $event)
    {
        return view('layouts.event.ticket.buy', compact('event'));
    }

    public function acquireTicket(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $pricepaid = $event->currentprice > 0 ? $event->currentprice : 0.0;

        if ($event->currentticketsqty <= 0) {
            return back()->withError('Sorry, there are no more tickets available for this event.');
        }

        if (Ticket::where('user_id', Auth::id())->where('event_id', $eventId)->exists()) {
            return back()->withError('You already have a ticket for this event.');
        }

        // Cast currentprice to float and use strict comparison
        if ((float)$event->currentprice === 0.0) {
            $ticket = new Ticket([
                'user_id' => Auth::id(),
                'event_id' => $eventId,
                'pricepaid'=> $pricepaid,
            ]);

            $event->decrement('currentticketsqty');
            $ticket->save();

            return redirect()->route('events.show', $eventId)->with('success', 'Your free ticket has been acquired!');
        } else {
            $ticket = new Ticket([
                'user_id' => Auth::id(),
                'event_id' => $eventId,
            ]);

            $event->decrement('currentticketsqty');
            dd($event->currentprice);
            $ticket->save();

            // Add the ticket ID to the redirect if you need to reference it later
            return redirect()->route('payment', ['ticketId' => $ticket->id])->with('success', 'Your ticket has been reserved. Please proceed with payment.');
        }
    }


    // ... include other methods as needed ...
}
