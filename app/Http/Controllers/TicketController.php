<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

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
        $price_paid = $event->current_price > 0 ? $event->current_price : 0.0;

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
                'price_paid' => $price_paid,
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
    public function showTicket($eventId,$ticketId)
    {
        $event = Event::findOrFail($eventId);
        $ticket = Ticket::where('event_id', $eventId)->findOrFail($ticketId);

        // Verify if the ticket belongs to the event
        if ($ticket->event_id != $event->id) {
            abort(404);
        }

        $userId = $ticket->user_id;

        // QRcode Event
        $qrContent = "{$eventId},{$ticket->id},{$userId}";

        // Generate QRcode
        $qrCode = QrCode::size(400)->generate($qrContent);

        return view('layouts.event.ticket.show', [
            'ticket' => $ticket,
            'event' => $event,
            'qrCode' => $qrCode
        ]);
    }

    public function authorizeTicket(Event $event)
    {
        return view('layouts.event.ticket.authenticate',compact('event'));
    }
    public function authenticateTicket(Request $request, $eventId)
    {
        if (!Str::isUuid($eventId)) {
            return redirect()->back()->with('error', 'Invalid event ID.');
        }

        $request->validate([
            'ticket_id' => 'required|uuid',
            'user_id' => 'required|uuid',
        ]);

        $ticketId = $request->input('ticket_id');
        $userId = $request->input('user_id');

        $ticket = Ticket::where('event_id', $eventId)
            ->where('id', $ticketId)
            ->where('user_id', $userId)
            ->first();

        if (!$ticket) {
            return redirect()->back()->with('error', 'Invalid ticket.');
        }

        if ($ticket->is_used) {
            return redirect()->back()->with('error', 'Ticket already used.');
        }

        $ticket->is_used = true;
        $ticket->save();
        return redirect()->route('events.show', ['event' => $eventId])->with('success', 'Ticket authenticated successfully!');
    }
}
