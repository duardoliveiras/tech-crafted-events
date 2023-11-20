@extends('layouts.app')

@section('title', 'My Events')

@section('content')
    <div class="container py-5">
        <h1 class="text-center mb-5 display-4">My Events</h1>

        <div class="row mb-5">
            <div class="col-12">
                <h2 class="mb-4">Events I'm Organizing</h2>
                @forelse ($organizedEvents as $event)
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title h4">{{ $event->name }}</h3>
                            <p class="card-text text-muted mb-4">{{ $event->description }}</p>
                            <a href="{{ route('events.show', $event->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> View Event
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">You are not organizing any events currently.</p>
                @endforelse
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Events I Have Tickets For</h2>
                @forelse ($eventsWithTickets as $event)
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title h4">{{ $event->name }}</h3>
                            <p class="card-text text-muted mb-4">{{ $event->description }}</p>
                            <a href="{{ route('events.show', $event->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> View Event
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">You have no tickets for any events currently.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
