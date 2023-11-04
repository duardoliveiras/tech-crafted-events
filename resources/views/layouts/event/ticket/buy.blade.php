@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                Purchase Ticket
            </div>
            <div class="card-body">
                <form  action="{{ route('ticket.acquire', ['event' => $event->id]) }}" method="POST">
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
                    @if ($event->currentprice == 0)
                        <div class="alert alert-info">This event is free! Just confirm your attendance below.</div>
                        <button type="submit" class="btn btn-success">Get Free Ticket</button>
                    @else
                        <p class="card-text">Price: ${{ number_format($event->currentprice, 2) }}</p>
                        <button type="submit" class="btn btn-primary">Buy Ticket for ${{ number_format($event->currentprice, 2) }}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
