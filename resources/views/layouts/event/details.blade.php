@extends('layouts.app')




@section('content')
    @section('breadcrumbs')
        <li> &nbsp; / {{ $event->name }} </li>
    @endsection

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4 shadow-sm" style="transition: transform .2s;">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            @if($event->image_url)
                                <img src="{{asset('storage/' . $event->image_url) }}" class="card-img" alt="Event Image"
                                     style="height: 100%; object-fit: cover;">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">{{ $event->name }}</h5>
                                <p class="card-text">{{ $event->description }}</p>
                                <p class="card-text"><small class="text-muted">Tickets
                                        Available: {{ $event->current_tickets_qty }}</small></p>
                                <p class="card-text"><small class="text-muted">Price:
                                        ${{ number_format($event->current_price, 2) }}</small></p>
                                @if(auth()->check() && $event->current_tickets_qty > 0 && !$event->ticket->contains('user_id', auth()->id()))
                                    <a href="{{ route('ticket.buy', ['event' => $event->id]) }}"
                                       class="btn btn-success">Buy Ticket</a>
                                @endif
                                @if(auth()->check())
                                    @php
                                        $userTicket = $event->ticket->firstWhere('user_id', auth()->id());
                                        $user = auth()->user();
                                        $isOwner = $user->id == $event->owner->user_id;
                                        $isAdmin = $user->isAdmin();
                                        $isOwnerOrAdmin = $isOwner || $isAdmin;
                                    @endphp
                                    @if($userTicket || $isOwnerOrAdmin)
                                        <a href="{{ route('discussion.show', ['event' => $event->id]) }}"
                                           class="btn btn-primary m-1">Access Discussion</a>
                                        @if($userTicket)
                                            <a href="{{ route('ticket.show', ['event' => $event->id, 'ticket' => $userTicket->id]) }}"
                                               class="btn btn-info m-1">Access Ticket</a>
                                        @endif
                                    @endif
                                    @if($isOwnerOrAdmin)
                                        <a href="{{ route('ticket.authorize', $event->id) }}"
                                           class="btn btn-warning m-1">Authenticate Ticket</a>
                                        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-secondary m-1">Edit
                                            Event</a>
                                        <form action="{{ route('events.destroy', $event->id) }}" method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger m-1"
                                                    onclick="return confirm('Are you sure you want to cancel this event? This action cannot be undone.')">
                                                Cancel Event
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{ URL::asset ('js/event/details-event.js') }}"></script>
@endsection
