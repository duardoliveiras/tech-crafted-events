@extends('layouts.app')

@section('content')
    @section('breadcrumbs')
        <li> 
            &nbsp; / <a href="{{ route('events.show', $event->id) }}">{{$event->name}}</a> / Ticket
        </li>
    @endsection
    <div class="container my-5">
        <div class="card border-0 shadow">
            <div class="card-header bg-dark text-white text-center">
                <h3 class="mb-0">{{ $event->name }} - Event Ticket</h3>
            </div>
            <div class="card-body p-5">
                <div class="row">
                    <div class="col-lg-8 mb-4 mb-lg-0">
                        <h4 class="text-primary">Event Details:</h4>
                        <p>{{ $event->description }}</p>
                        <hr>
                        <p><strong>Start Time:</strong> {{ $event->start_date->format('d M Y, H:i') }}</p>
                        <p><strong>End Time:</strong> {{ $event->start_date->format('d M Y, H:i') }}</p>
                        <p><strong>Location:</strong> {{ $event->address }}</p>
                    </div>
                    <div class="col-lg-4 text-center">
                        <h4 class="text-primary">Your QR Code:</h4>
                        <div class="p-3 mb-3 bg-light rounded">
                            {!! $qrCode !!}
                        </div>
                        <p class="text-muted">Scan at the entrance</p>
                    </div>
                </div>
                <a href="{{ route('ticket.download', ['eventId' => $event->id, 'ticketId' => $ticket->id]) }}" class="btn btn-primary">Download Ticket</a>
            </div>
        </div>

    </div>
@endsection
