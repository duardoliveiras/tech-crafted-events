<?php

namespace App\Http\Services;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketService
{
    public function createTicket($eventId, $amount)
    {
        $ticket = new Ticket([
            'user_id' => Auth::id(),
            'event_id' => $eventId,
            'price_paid' => $amount
        ]);

        $event = $ticket->event;
        $event->decrement('current_tickets_qty');
        $ticket->save();
        $ticket->markTicketAsPending();

        return $ticket;
    }

    public function createFreeTicket($eventId)
    {
        $ticket = new Ticket([
            'user_id' => Auth::id(),
            'event_id' => $eventId,
            'price_paid' => 0.0,
        ]);

        $event = $ticket->event;
        $ticket->markTicketAsPaid();
        $event->decrement('current_tickets_qty');
        $ticket->save();

        return $ticket;
    }
}
