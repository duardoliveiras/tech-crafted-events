@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{URL::asset('/assets/css/list-event.scss')}}">

@section('content')
    <div class="container">
        <h6>Upcoming Events</h6>
        <div class="row">
            @foreach($events as $event)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <a href="/events/{{ $event->id }}" class="text-decoration-none">
                            <img src="https://images-na.ssl-images-amazon.com/images/S/pv-target-images/ccd4c7fe64769b46c7cdefe3a8402b2ae396e9bb563cc151fbfbee9798397c21._RI_TTW_SX720_FMjpg_.jpg"
                                 class="card-img-top" alt="{{ $event->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $event->name }}</h5>
                                <p class="card-text">Ticket price: R$ {{ $event->currentprice }}</p>
                                <p class="card-text">Localization: {{ $event->address }}</p>
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

    <div class="banner mt-5">
        <img src="{{URL::asset('/assets/make-your-event.png')}}" class="image-mye" />
        <div class="banner-content text-center">
            <h1>Make your own Event</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            <a href="{{route('events.create')}}"><button class="btn banner-button">Create Events</button></a>
        </div>
    </div>

@endsection
