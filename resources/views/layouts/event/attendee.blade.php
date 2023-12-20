@extends('layouts.app')

@section('content')
    @section('breadcrumbs')
        <li>
            &nbsp; / <a href="{{ route('events.show', $event->id) }}">{{$event->name}}</a>
        </li>
        <li> &nbsp; / View Attendees </li>
    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h1 class="text-center m-0">Attendees for {{ $event->name }}</h1>
            </div>

            <div class="card-body">
                @if ($event->ticket && count($event->ticket) > 0)
                    <h2 class="text-center mt-3 mb-4">Attendee List</h2>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Price paid</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($event->ticket as $ticket)
                                @if($ticket->user)
                                    <tr>
                                        <td>{{ $ticket->user->name }}</td>
                                        <td>{{ $ticket->user->email }}</td>
                                        <td>{{ $ticket->price_paid }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center mt-4">No attendees found for this event.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
