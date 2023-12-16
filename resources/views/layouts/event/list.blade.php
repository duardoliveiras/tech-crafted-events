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
                        <li><a class="dropdown-item" style="cursor:pointer" data-sort="start-date">Start Date</a></li>
                        <li><a class="dropdown-item" style="cursor:pointer" data-sort="name">Name</a></li>
                        <li><a class="dropdown-item" style="cursor:pointer" data-sort="price-lowest">Price (lowest
                                first)</a></li>
                        <li><a class="dropdown-item" style="cursor:pointer" data-sort="price-greater">Price (greater
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
        <h2 class="title-events purple">Colleges</h2>
        <div class="row mt-3" id="container-events">
            @foreach($universities as $university)
                <div class="col-md-4">
                    <a href="{{ route('universities.show', $university->id) }}" class="text-decoration-none">
                        <div class="card mb-4 card-hover-effect">
                            <img src="{{ Storage::url($university->image_url) }}" class="card-img-top"
                                 alt="Image of {{ $university->name }}">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            let page = 1; // Track the current page

            function getUrlParameter(name) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                let regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                let results = regex.exec(location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            }

            // Function to load more data with filters
            function loadMoreData(page, sortType, eventType, location, dateFilter, nameFilter) {
                $.ajax({
                    url: `?page=${page}&sort=${sortType}&eventType=${eventType}&location=${location}&date-filter=${dateFilter}&full-text-search=${nameFilter}`,
                    type: "get",
                    beforeSend: function () {
                        $('#loadMore').text('Loading...');
                    }
                })
                    .done(function (data) {
                        if (data.html === "") {
                            $('#loadMore').text('No more records').prop('disabled', true);
                            return;
                        }
                        $('#loadMore').text('Load More');
                        $("#anchor").before(data.html); // Insert the new data before the anchor
                    })
                    .fail(function (jqXHR, ajaxOptions, thrownError) {
                        alert('server not responding...');
                    });
            }

            // Load more button click event
            $('#loadMore').click(function () {
                page++; // Increase the page number
                let sortType = getUrlParameter('sort');
                // Get filter values from URL
                let eventType = getUrlParameter('eventType');
                let location = getUrlParameter('location');
                let dateFilter = getUrlParameter('date-filter');
                let nameFilter = getUrlParameter('full-text-search');

                loadMoreData(page, sortType, eventType, location, dateFilter, nameFilter); // Load data with filters
            });

        });
    </script>

@endsection

