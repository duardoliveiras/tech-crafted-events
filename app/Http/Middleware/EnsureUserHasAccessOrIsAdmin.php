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
        $event = Event::with('ticket')->findOrFail($eventId); // Certifique-se de que a relação tickets está correta.

        // Verifica se o usuário é o dono do evento
        if ($event->owner_id == Auth::id()) {
            return $next($request);
        }

        // Verifica se o usuário é um admin
        if (Auth::user()->isAdmin()) {
            return $next($request);
        }

        // Verifica se o usuário tem um ticket
        if ($event->ticket->contains('user_id', Auth::id())) {
            return $next($request);
        }

        // Se nenhuma das condições acima for verdadeira, redireciona para a home com erro
        return redirect('home')->withErrors('You do not have access to this discussion.');
    }
}
