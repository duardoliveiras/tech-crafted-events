@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{URL::asset('/assets/css/list-event.css')}}">

@section('content')
    <div class="container my-3 filters-container">
        <form class="mb-0" id="filter-form">
            <div class="form-row d-flex flex-row justify-content-around mx-4">
                <div class="col">
                    <label for="full-text-search" class="text-white label-filter">Search for event</label>
                    <input type="text" class="form-control" id="full-text-search" name="full-text-search"
                           placeholder="Type name of a event..."
                           value="{{ request('full-text-search') }}">
                </div>
                <div class="col-1 align-self-end d-flex justify-content-center ms-3">
                    <button type="submit" class="btn button-search p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor"
                             class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="accordion accordion-flush" id="fullTextSearch">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingOne" style="width: 15% !important;">
                        <button class="accordion-button collapsed ms-2" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapseOne" aria-expanded="false"
                                aria-controls="flush-collapseOne">
                            Advanced search
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                         data-bs-parent="#fullTextSearch">
                        <div class="accordion-body pt-0">
                            <div class="form-row d-flex flex-row justify-content-around mx-2">
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
                                    <label for="date-filter" class="text-white label-filter">When</label>
                                    <input type="date" class="form-control" id="date-filter" name="date-filter"
                                           value="{{ request('date-filter') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a onclick="clearForm()" id="clear-filters" class="text-decoration-none text-white ms-4"
               style="cursor: pointer;"><u>Clear filter options</u></a>
        </form>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="title col">
                <h2 class="title-events black">Upcoming</h2>
                <h2 class="title-events purple"> Events</h2>
            </div>
            <div class="sort-options col justify-content-end d-flex flex-row align-items-center">
                {{--                <div class="event-type-select">--}}
                {{--                    <select id="eventType" name="eventType" class="form-control">--}}
                {{--                        <option value="">Event Type</option>--}}
                {{--                        @foreach ($categories as $eventType)--}}
                {{--                            <option value="{{ $eventType->id }}" {{ request('eventType') == $eventType->id ? 'selected' : '' }}>{{ $eventType->name }}</option>--}}
                {{--                        @endforeach--}}
                {{--                    </select>--}}
                {{--                </div>--}}

                {{--                <div class="sort-by-select">--}}

                {{--                </div>--}}
            </div>
        </div>

        <div class="row mt-3">
            @forelse($events as $event)
                <div class="col-md-4">
                    <div class="card mb-4 shadow card-hover-effect" style="border-width: 0;">
                        <a href="/events/{{ $event->id }}" class="text-decoration-none text-reset">
                            <div style="position: absolute; top: 10px; left: 20px; background: white; color: #7848F4; padding: 8px; border-radius: 10px;">
                                @if ($event->current_price == 0)
                                    FREE
                                @else
                                    € {{ number_format($event->current_price, 2) }}
                                @endif
                            </div>
                            <img src="{{ asset('storage/' . $event->image_url) }}"
                                 class="card-img-top" alt="{{ $event->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $event->name }}</h5>
                                <p class="card-text mb-1"
                                   style="color: #7848F4;">{{ \Carbon\Carbon::parse($event->start_date)->format('l, F j, g:i A') }}</p>
                                <p class="card-text" style="color: #7E7E7E;">{{ $event->address }}, {{ $event->city->name }}</p>
                            </div>
                        </a>
                    </div>
                </div>
                @if ($loop->iteration % 3 == 0)
        </div>
        <div class="row">
            @endif
            @empty
                <div class="col-12 text-center">
                    <p class="lead">No upcoming events found.</p>
                </div>
            @endforelse
        </div>
    </div>


    <div class="banner mt-5">
        <img src="{{URL::asset('/assets/make-your-event.png')}}" class="image-mye"/>
        <div class="banner-content text-center">
            <h1>Make your own Event</h1>
            <p>Publish your own event here!</p>
            <a href="{{route('events.create')}}">
                <button class="btn btn-primary banner-button px-5">Create Events</button>
            </a>
        </div>
    </div>

    <div class="container mt-5">
        <h2 class="title-events black">Trending</h2>
        <h2 class="title-events purple"> Colleges</h2>
        <div class="row mt-3">
            @foreach($universities as $index => $university)
                <div class="col-md-4">
                    <div class="card mb-4 card-hover-effect">
                        @php
                            $imageName = 'university' . ($index + 1) . '.jpeg';
                            $imageUrl = asset("assets/universities/{$imageName}");
                        @endphp
                        <img src="{{ $imageUrl }}" class="card-img-top" alt="Imagem de {{ $university->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $university->name }}</h5>
                            <p class="card-text">Location: {{ $university->address }}</p>
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

    <script type="text/javascript" src="{{ URL::asset ('js/event/list-event.js') }}"></script>

@endsection

