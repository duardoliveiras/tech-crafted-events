<?php

namespace App\Http\Controllers;

use App\Http\Services\TicketService;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    protected TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->middleware('auth');
        $this->ticketService = $ticketService;
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

        if ($event->current_tickets_qty <= 0) {
            return back()->withError('Sorry, there are no more tickets available for this event.');
        }

        if (Ticket::where('user_id', Auth::id())->where('event_id', $eventId)->exists()) {
            return back()->withError('You already have a ticket for this event.');
        }

        if ((float) $event->current_price === 0.0) {
            $this->ticketService->createFreeTicket($eventId);

            return redirect()->route('events.show', $eventId)->with('success', 'Your free ticket has been acquired!');
        } else {
            return route('payment.session', ['eventId' => $event->id, 'amount' => $event->current_price, 'eventName' => $event->name]);
        }
    }

    public function showTicket($eventId, $ticketId)
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
        return view('layouts.event.ticket.authenticate', compact('event'));
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
        if ($ticket->status === 'READ') {
            return redirect()->back()->with('error', 'Ticket already used.');
        }

        if ($ticket->status !== 'PAID') {
            return redirect()->back()->with('error', 'Ticket not valid for use.');
        }

        $ticket->markTicketAsRead();
        $ticket->save();

        return redirect()->route('events.show', ['event' => $eventId])->with('success', 'Ticket authenticated successfully!');
    }
}
