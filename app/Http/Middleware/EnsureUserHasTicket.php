<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class EnsureUserHasTicket
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $eventId = $request->route('event');
        $event = Event::findOrFail($eventId);

        if ($event->owner->user_id == auth()->id()) {
            return $next($request);
        }

        if ($event->ticket->contains('user_id', auth()->id())) {
            return $next($request);
        }

        return redirect('home')->withErrors('You do not have access to this discussion.');

    }
}
