@extends('layouts.app')

@section('title', 'My Events')

@section('content')
    <div class="container py-5">
        <h1 class="text-center mb-5">My Events</h1>

        <section class="mb-5">
            <h2 class="fs-3 mb-3">Events I'm Organizing</h2>
            @forelse ($organizedEvents as $event)
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="card-title">{{ $event->name }}</h3>
                        <p class="card-text">{{ $event->description }}</p>
                        <div class="d-flex justify-content-start">
                            <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary me-2">
                                View Event
                            </a>
                            @if($event->isFinished())
                                <a href="{{ route('payment.transfer', 10, 'stripe_account_id') }}" class="btn btn-secondary">
                                    Receive Tickets Cash
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p>You are not organizing any events currently.</p>
            @endforelse
        </section>

        <section>
            <h2 class="fs-3 mb-3">Events I Have Tickets For</h2>
            @forelse ($eventsWithTickets as $event)
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="card-title">{{ $event->name }}</h3>
                        <p class="card-text">{{ $event->description }}</p>
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary">
                            View Event
                        </a>
                    </div>
                </div>
            @empty
                <p>You have no tickets for any events currently.</p>
            @endforelse
        </section>
    </div>
@endsection
