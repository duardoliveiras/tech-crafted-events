@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{URL::asset('/assets/css/list-event.css')}}">

@section('title', 'Tech Crafted')

@section('content')
    <!-- Success Message -->
    @if(session('success'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="liveToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header" style="background-color: #308329;color: white;">
                    <strong class="me-auto" style="font-size: 1.2rem;font-weight: bolder;">Success</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body" style="font-size: 1rem;font-weight: bolder;">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="liveToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header" style="background-color: #f06f6f;color: white;">
                    <strong class="me-auto" style="font-size: 1.2rem;font-weight: bolder;">Error</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body" style="font-size: 1rem;font-weight: bolder;">
                    {{ session('error') }}
                </div>
            </div>
        </div>
    @endif

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
                                    <label for="event-type" class="text-white label-filter">Looking for</label>
                                    <select id="event-type" name="event-type" class="form-control">
                                        <option value="">Choose event type</option>
                                        @foreach ($categories as $eventType)
                                            <option value="{{ $eventType->id }}" {{ request('event-type') == $eventType->id ? 'selected' : '' }}>{{ $eventType->name }}</option>
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

                                <div class="col mx-2">
                                    <label for="university" class="text-white label-filter">University</label>
                                    <select id="university" name="university" class="form-control">
                                        <option value="">Choose university</option>
                                        @foreach ($universities as $university)
                                            <option value="{{ $university->id }}" {{ request('university') == $university->id ? 'selected' : '' }}>{{ $university->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="sort" id="sort">

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
                <div class="dropdown">
                    <button id="dropdown-sort" class="btn btn-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        Sort By
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdown-sort">
                        <li><a class="dropdown-item dropdown-sort" style="cursor:pointer" data-sort="start-date">Start Date</a></li>
                        <li><a class="dropdown-item dropdown-sort" style="cursor:pointer" data-sort="name">Name</a></li>
                        <li><a class="dropdown-item dropdown-sort" style="cursor:pointer" data-sort="price-lowest">Price (lowest
                                first)</a></li>
                        <li><a class="dropdown-item dropdown-sort" style="cursor:pointer" data-sort="price-greater">Price (greater
                                first)</a></li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="row mt-3">
            @include('partials.event', ['events' => $events])
            <div id="anchor"></div>
            @if($events->hasMorePages())
                <button id="loadMore" class="btn btn-primary custom-button mt-3 mb-3">Load More</button>
            @endif
        </div>
    </div>

    <div class="banner mt-5">
        <img src="{{URL::asset('/assets/make-your-event.png')}}" class="image-mye" alt="Banner image"/>
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
        <h2 class="title-events purple">Colleges</h2>
        <div class="row mt-3" id="container-events">
            @foreach($universities as $university)
                <div class="col-md-4">
                    <a href="{{ route('universities.show', $university->id) }}" class="text-decoration-none">
                        <div class="card mb-4 card-hover-effect">
                            <img src="{{ Storage::url($university->image_url) }}" class="card-img-top"
                                 alt="Image of {{ $university->name }}" style="width: 100%; height: 300px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $university->name }}</h5>
                                <p class="card-text">Localization: {{ $university->address }}</p>
                            </div>
                        </div>
                    </a>
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

