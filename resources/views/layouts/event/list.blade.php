@extends('layouts.app')

@section('content')
    <div class="container">
        <h6>Eventos</h6>
        <div class="row">
            @foreach($events as $event)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <a href="/events/{{ $event->id }}" class="text-decoration-none">
                            <img src="https://images-na.ssl-images-amazon.com/images/S/pv-target-images/ccd4c7fe64769b46c7cdefe3a8402b2ae396e9bb563cc151fbfbee9798397c21._RI_TTW_SX720_FMjpg_.jpg"
                                 class="card-img-top" alt="{{ $event->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $event->name }}</h5>
                                <p class="card-text">Preço do Ticket: R$ {{ $event->currentprice }}</p>
                                <p class="card-text">Localização: {{ $event->address }}</p>
                            </div>
                        </a>
                    </div>
                </div>
                @if ($loop->iteration % 3 == 0)
                    </div>
                    <div class="row">
                @endif
            @endforeach
        </div>
    </div>
@endsection
