@extends('layouts.app')

@section('content')
    @section('breadcrumbs')
        <li> 
            &nbsp; / <a href="{{ route('events.show', $event->id) }}">{{$event->name}}</a>
        </li>
        <li> &nbsp; / Buys </li>
    @endsection
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                Purchase Ticket
            </div>
            <div class="card-body">
                <form action="{{ $event->current_price != 0 ? route('payment.session', ['eventId' => $event->id, 'amount' => $event->current_price, 'eventName' => $event->name]) : route('ticket.acquire', ['event' => $event->id]) }}"
                      method="POST">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if ($event->current_price == 0)
                        <div class="alert alert-info">This event is free! Just confirm your attendance below.</div>
                        <button type="submit" class="btn btn-success">Get Free Ticket</button>
                    @else
                        <p class="card-text">Price: ${{ number_format($event->current_price, 2) }}</p>
                        <button type="submit" class="btn btn-primary" id="checkout-live-button">Buy Ticket for
                            €{{ number_format($event->current_price, 2) }}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
