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
                        @foreach ($categories as $eventType)
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
                    <div class="card mb-4 shadow" style="border-width: 0;">
                        <a href="/events/{{ $event->id }}" class="text-decoration-none text-reset">
                            <div style="position: absolute; top: 10px; left: 20px; background: white; color: #7848F4; padding: 8px; border-radius: 10px;">
                                @if ($event->currentprice == 0)
                                    FREE
                                @else
                                    € {{ number_format($event->currentprice, 2) }}
                                @endif
                            </div>
                            <img src="{{('storage/' . $event->image_url) }}"
                                 class="card-img-top" alt="{{ $event->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $event->name }}</h5>
                                <p class="card-text mb-1" style="color: #7848F4;">{{ \Carbon\Carbon::parse($event->startdate)->format('l, F j, g:i A') }}</p>
                                <p class="card-text" style="color: #7E7E7E;">{{ $event->address }}, {{ $event->city->name }}</p>
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
            <a href="{{route('events.create')}}"><button class="btn banner-button">Create Events</button></a>
        </div>
    </div>

    <div class="container mt-5">
        <h2 class="title-events black">Trending</h2>
        <h2 class="title-events purple"> Colleges</h2>
        <div class="row mt-3">
            @foreach($universities as $index => $university)
                <div class="col-md-4">
                    <div class="card mb-4">
                        @php
                            $imageName = 'university' . ($index + 1) . '.jpeg';
                            $imageUrl = asset("assets/universities/{$imageName}");
                        @endphp
                        <img src="{{ $imageUrl }}" class="card-img-top" alt="Imagem de {{ $university->name }}">
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

