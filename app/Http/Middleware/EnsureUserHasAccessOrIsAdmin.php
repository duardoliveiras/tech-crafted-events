<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasAccessOrIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $eventId = $request->route('event');
        $event = Event::with('ticket')->findOrFail($eventId); // Ensure that the tickets relation is correct.

        // Check if the user is the owner of the event
        if ($event->owner_id == Auth::id()) {
            return $next($request);
        }

        // Check if the user is an admin
        if (Auth::user()->isAdmin()) {
            return $next($request);
        }

        // Check if the user has a ticket with status "PAID" or "READ"
        $userTickets = $event->ticket->where('user_id', Auth::id());
        foreach($userTickets as $userTicket) {
            if ($userTicket && in_array($userTicket->status, ['PAID', 'READ'])) {
                return $next($request);
            }
        }

        // If none of the above conditions are true, redirect to home with an error
        return redirect('home')->withErrors('You do not have access to this discussion.');
    }
}
