@extends('layouts.app')

@section('title', $event->name)

@section('content')
    @section('breadcrumbs')
        <li> &nbsp; / {{ $event->name }} </li>
    @endsection

    <div class="container">
        @if(session('success'))
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 999">
                <div id="liveToast" class="toast show" role="alert" aria-live="assertive"
                     aria-atomic="true">
                    <div class="toast-header" style="background-color: #308329;color: white;">
                        <strong class="me-auto"
                                style="font-size: 1.2rem;font-weight: bolder;">Success</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                    </div>
                    <div class="toast-body" style="font-size: 1rem;font-weight: bolder;">
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 999">
                <div id="liveToast" class="toast show" role="alert" aria-live="assertive"
                     aria-atomic="true">
                    <div class="toast-header" style="background-color: #f06f6f;color: white;">
                        <strong class="me-auto"
                                style="font-size: 1.2rem;font-weight: bolder;">Error</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                    </div>
                    <div class="toast-body" style="font-size: 1rem;font-weight: bolder;">
                        {{ session('error') }}
                    </div>
                </div>
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-8 w-100">
                <div class="d-flex justify-content-center w-100 image-container">
                    @if($event->image_url)
                        <div class="blurred-background"></div>
                        <img src="{{asset('event/' . $event->image_url)}}" alt="Event Image">
                    @endif
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-8 col-12 box-left">
                <h1 class="event-name">{{ $event->name }}</h1>
                <p class="event-description ms-2 mb-2">{{ $event->description }}</p>
                <h3 class="mt-5">When?</h3>
                <p class="ms-1"><i class="far fa-calendar-check me-2"></i> Event takes place
                    between <b>{{\Carbon\Carbon::parse($event->start_date)->format('l, F j, Y, g:i A')}}</b>
                    and <b>{{\Carbon\Carbon::parse($event->end_date)->format('l, F j, Y, g:i A')}}</b>!</p>
                <h3>Where?</h3>
                <p class="ms-1">
                    <i class="fas fa-map-marker-alt me-2"></i> It will be hosted at
                    <a class="link-muted text-decoration-none text-reset"
                       href="https://www.google.com/maps/search/?api=1&query={{ urlencode($event->address . ', ' . $event->city->name) }}">
                        <b><u>{{ $event->address }}, {{ $event->city->name }}</u></b>
                    </a>
                </p>
            </div>
            <div class="col-1 d-none d-sm-block" style="width: 30px;">
                <div class="vl"></div>
            </div>
            <div class="col-md-3 col-12 box-right ms-md-2 me-md-0 pe-md-0">
                @if(auth()->check())
                    @php
                        $userTickets = $event->ticket->where('user_id', auth()->id());
                        $userTicket = null; // Initialize $userTicket as null
                        foreach ($userTickets as $ticket) {
                            if ($ticket && $ticket->isValidTicket()) {
                                $userTicket = $ticket;
                                break;
                            }
                        }
                        $user = auth()->user();
                        $isOwner = $user->id == $event->owner->user_id;
                        $isAdmin = $user->isAdmin();
                        $isOwnerOrAdmin = $isOwner || $isAdmin;

                        $canBuyTicket = auth()->check() && $event->current_tickets_qty > 0 && $event->userCanBuyTicket() && !$isOwnerOrAdmin;
                        $isTicketPending = $event->ticket->where('user_id', auth()->id())->where('status', 'PENDING')->count() > 0;
                    @endphp
                    <div class="box-actions ms-md-5 px-4 py-4 d-flex justify-content-center align-items-center flex-column w-100">
                        @if($canBuyTicket)
                            @if($isTicketPending)
                                <span class="mb-2">You have a ticket pending for payment. If already paid, wait until we detect.</span>
                                <button class="btn btn-secondary custom-button disabled">Ticket Purchase Pending
                                </button>
                            @else
                                <span class="mb-2">Tickets for {{ $event->current_price == 0? 'free' : '€ ' . number_format($event->current_price, 2) }}</span>
                                <a href="{{ route('ticket.buy', ['event' => $event->id]) }}"
                                   class="btn btn-primary w-100 custom-button"><i class="fas fa-money-bill-wave"></i>
                                    Buy now!</a>
                            @endif
                        @endif

                        @if($userTicket || $isOwnerOrAdmin)
                            <h3 style="font-family: 'Raleway', sans-serif;font-weight: bold">Actions</h3>
                            <a href="{{ route('discussion.show', ['event' => $event->id]) }}"
                               class="btn btn-primary my-2 custom-button discussion"><i class="far fa-comment-dots"></i>
                                Access Discussion</a>
                            @if($userTicket && !$isOwnerOrAdmin)
                                <a href="{{ route('ticket.show', ['event' => $event->id, 'ticket' => $userTicket->id]) }}"
                                   class="btn btn-primary my-2 custom-button access-ticket"><i
                                            class="fas fa-ticket-alt"></i> Access Ticket</a>

                                <form action="{{ route('events.leave', ['event_id' => $event->id, 'ticket_id' => $userTicket->id]) }}"
                                      method="POST" class="d-inline-block w-100">
                                    @csrf
                                    <button type="submit" class="btn btn-danger my-2 custom-button leave-event"><i
                                                class="fas fa-times-circle"></i> Leave
                                        Event
                                    </button>
                                </form>

                            @endif
                        @endif

                        @if($isOwnerOrAdmin)
                            <a href="{{ route('ticket.authorize', $event->id) }}"
                               class="btn btn-primary m-1 custom-button authenticate-ticket"><i class="fas fa-key"></i>
                                Authenticate Ticket</a>
                            <a href="{{ route('events.edit', $event->id) }}"
                               class="btn btn-primary my-2 custom-button edit-event"><i class="far fa-edit"></i> Edit
                                Event</a>
                            <a href="{{ route('events.attendees', ['event' => $event->id]) }}"
                               class="btn btn-primary my-2 custom-button"><i class="fas fa-users"></i> View
                                attendees</a>

                            <button class="btn btn-primary my-2 custom-button edit-event"
                                    data-toggle="modal" data-target="#inviteModal"><i class="fas fa-share-alt"></i>
                                Invite User
                            </button>

                            <form action="{{ route('events.destroy', $event->id) }}" method="POST"
                                  class="d-inline w-100 m-0 p-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger custom-button cancel-event my-2"
                                        onclick="return confirm('Are you sure you want to cancel this event? This action cannot be undone.')">
                                    <i class="fas fa-power-off"></i> Cancel Event
                                </button>
                            </form>
                        @endif
                    </div>
                @else
                    <div class="box-actions ms-md-5 px-4 py-4 d-flex justify-content-center align-items-center flex-column w-100">
                        <span class="mb-2">Tickets for € {{ number_format($event->current_price, 2) }}</span>
                        <a href="{{ route('login') }}"
                           class="btn btn-primary mt-3 custom-button">Login to purchase</a>
                    </div>
                @endif
            </div>
        </div>
        @if(auth()->check())
            <div class="row mt-5">
                <p class="report-paragraph">
                    <em>Something wrong with "{{ $event->name }}" event?
                        <a
                                role="button"
                                class="link-muted text-decoration-none text-reset"
                                data-toggle="modal"
                                data-target="#reportModal"
                                aria-label="Report this event for moderation"
                        >
                            <u><strong>Report this event</strong></u>
                        </a>
                    </em>
                </p>
            </div>
        @endif

    </div>

    <!-- Report Modal -->
    @include('partials.report', ['event' => $event, 'report' => "event"])

    <div class="modal fade" id="inviteModal" tabindex="-1" role="dialog" aria-labelledby="inviteModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="inviteModalLabel"> Invite User </h3>
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" aria-label="Close"
                            onclick="window.location=route_reports">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="userSearch" name="userSearch"
                                           placeholder="Type email of a user...">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary"
                                                onclick="getUsers('{{ $event->id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                 fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3" style="width: 100%; height: 100%;">
                            <ul class="list-group list-group-flush" id="listUser">
                                <!-- insert by js -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/event/invite.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('/assets/css/details-event.css')}}">

    <script>
        let eventId = "{{ $event->id }}";
    </script>

    <style>
        .blurred-background {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url({{asset('event/' . $event->image_url) }});
            background-size: cover;
            filter: blur(10px);
            z-index: -1;
        }
    </style>
@endsection
