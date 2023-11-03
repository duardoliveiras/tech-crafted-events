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
                    <li class="list-group-item"><strong>Category:</strong> {{ $event->category->name }}</li>
                    <li class="list-group-item"><strong>Start Date:</strong> {{ $event->startdate instanceof \Carbon\Carbon ? $event->startdate->format('F d, Y h:i A') : \Carbon\Carbon::parse($event->startdate)->format('F d, Y h:i A') }}</li>
                    <li class="list-group-item"><strong>End Date:</strong> {{ $event->enddate instanceof \Carbon\Carbon ? $event->enddate->format('F d, Y h:i A') : \Carbon\Carbon::parse($event->enddate)->format('F d, Y h:i A') }}</li>
                    <li class="list-group-item"><strong>City:</strong> {{ $event->city->name }}</li>
                    <li class="list-group-item"><strong>Start Tickets Quantity:</strong> {{ $event->startticketsqty }}</li>
                    <li class="list-group-item"><strong>Current Tickets Quantity:</strong> {{ $event->currentticketsqty }}</li>
                    <li class="list-group-item"><strong>Ticket Price:</strong> ${{ number_format($event->currentprice, 2) }}</li>
                    <li class="list-group-item"><strong>Address:</strong> {{ $event->address }}</li>

                </ul>
                    @if(auth()->id() === $event->owner->user_id)
                        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-primary mt-3">Edit Event</a>
                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mt-3" onclick="return confirm('Do still want to cancel this event? You cannot undo this action. ')">Cancel Event</button>
                        </form>

                    @endif
            </div>
        </div>
    </div>
@endsection

