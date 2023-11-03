@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{URL::asset('/assets/css/list-event.scss')}}">

@section('content')
    <div class="container my-3 filters-container">
        <form class="mb-0">
            <div class="form-row d-flex flex-row justify-content-around mx-3">
                <div class="col mx-2">
                    <label for="eventType" class="text-white label-filter">Looking for</label>
                    <select id="eventType" name="eventType" class="form-control">
                        <option value="">Choose event type</option>
                        @foreach ($eventTypes as $eventType)
                            <option value="{{ $eventType->id }}" {{ request('eventType') == $eventType->id ? 'selected' : '' }}>{{ $eventType->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col mx-2">
                    <label for="location" class="text-white label-filter">Location</label>
                    <select id="location" name="location" class="form-control">
                        <option value="">Choose location</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col mx-2">
                    <label for="dateTime" class="text-white label-filter">When</label>
                    <input type="date" class="form-control" id="date-filter" name="date-filter"
                           value="{{ request('date-filter') }}">
                </div>
                <div class="col-1 align-self-end mx-2">
                    <button type="submit" class="btn button-search p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor"
                             class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="container mt-5">
        <h2 class="title-events black">Upcoming</h2>
        <h2 class="title-events purple"> Events</h2>
        <div class="row mt-3">
            @foreach($events as $event)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <a href="/events/{{ $event->id }}" class="text-decoration-none text-reset">
                            <img src="https://images-na.ssl-images-amazon.com/images/S/pv-target-images/ccd4c7fe64769b46c7cdefe3a8402b2ae396e9bb563cc151fbfbee9798397c21._RI_TTW_SX720_FMjpg_.jpg"
                                 class="card-img-top" alt="{{ $event->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $event->name }}</h5>
                                <p class="card-text">Ticket price: R$ {{ $event->currentprice }}</p>
                                <p class="card-text">Localization: {{ $event->address }}</p>
                                <p class="card-text">When: {{ $event->startdate }}</p>
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
        <img src="{{URL::asset('/assets/make-your-event.png')}}" class="image-mye"/>
        <div class="banner-content text-center">
            <h1>Make your own Event</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            <button class="btn banner-button">Create Events</button>
        </div>
    </div>

    <div class="container mt-5">
        <h2 class="title-events black">Trending</h2>
        <h2 class="title-events purple"> Colleges</h2>
        <div class="row mt-3">
            @foreach($universities as $university)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="https://4.bp.blogspot.com/-mUQETPUdsgw/T75cjv545FI/AAAAAAAAAhs/SlutqeCKR2Y/s1600/Wallpaper-1-1024-768.jpg"
                             class="card-img-top" alt="{{ $university->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $university->name }}</h5>
                            <p class="card-text">Localization: {{ $university->address }}</p>
                        </div>
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
