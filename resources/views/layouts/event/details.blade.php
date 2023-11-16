@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                Event Details
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <h5 class="card-title">{{ $event->name }}</h5>
                <p class="card-text">{{ $event->description }}</p>
                <ul class="list-group list-group-flush">
                    <!-- ... other list items ... -->
                    <li class="list-group-item"><strong>Current Tickets Quantity:</strong> {{ $event->current_tickets_qty }}</li>
                    <li class="list-group-item"><strong>Ticket Price:</strong> ${{ number_format($event->current_price, 2) }}</li>
                    <!-- ... other list items ... -->
                </ul>
                @if(auth()->check())
                    @php
                        $userHasTicket = $event->ticket->contains('user_id', auth()->id());
                        $ticketsAvailable = $event->current_tickets_qty > 0;
                        $isOwnerOrAdmin = auth()->user()->isAdmin() || auth()->id() === $event->owner_id;
                    @endphp
                    @if(!$userHasTicket && $ticketsAvailable)
                        <a href="{{ route('ticket.buy', ['event' => $event->id]) }}" class="btn btn-success mt-3">Buy Ticket</a>
                    @endif
                    @if($userHasTicket || $isOwnerOrAdmin)
                        <a href="{{ route('discussion.show', ['event' => $event->id]) }}" class="btn btn-primary mt-3">Access Discussion</a>
                    @endif
                @endif
                    @if(auth()->check())
                        @if(auth()->user()->isAdmin() || auth()->id() === $event->owner->user_id)
                        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-primary mt-3">Edit Event</a>
                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mt-3" onclick="return confirm('Are you sure you want to cancel this event? This action cannot be undone.')">Cancel Event</button>
                        </form>
                        @endif
                    @endif
            </div>
        </div>
    </div>
@endsection
