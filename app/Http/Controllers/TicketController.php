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
        $pricepaid = $event->current_price > 0 ? $event->current_price : 0.0;

        if ($event->current_tickets_qty <= 0) {
            return back()->withError('Sorry, there are no more tickets available for this event.');
        }

        if (Ticket::where('user_id', Auth::id())->where('event_id', $eventId)->exists()) {
            return back()->withError('You already have a ticket for this event.');
        }

        // Cast current_price to float and use strict comparison
        if ((float)$event->current_price === 0.0) {
            $ticket = new Ticket([
                'user_id' => Auth::id(),
                'event_id' => $eventId,
                'price_paid' => $pricepaid,
            ]);

            $event->decrement('current_tickets_qty');
            $ticket->save();

            return redirect()->route('events.show', $eventId)->with('success', 'Your free ticket has been acquired!');
        } else {
            $ticket = new Ticket([
                'user_id' => Auth::id(),
                'event_id' => $eventId,
            ]);

            $event->decrement('current_tickets_qty');
            dd($event->current_price);
            $ticket->save();

            // Add the ticket ID to the redirect if you need to reference it later
            return redirect()->route('payment', ['ticketId' => $ticket->id])->with('success', 'Your ticket has been reserved. Please proceed with payment.');
        }
    }

}
