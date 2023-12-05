@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{URL::asset('/assets/css/create-event.css')}}">

@section('content')

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">Create New Event</div>
                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group mb-3">
                                <label class="custom-label" for="name">Event Name:</label>
                                <input type="text" class="form-control custom-input" id="name" name="name" required>
                            </div>

                            <div class="form-group mb-3">
                                <label class="custom-label" for="image_url">Event Image:</label>
                                <input type="file" class="form-control custom-input" id="image_url" name="image_url"
                                       accept="image/jpeg, image/png, image/jpg, image/gif, image/svg+xml" required>
                                <small class="form-text text-muted">Image must be in JPEG, PNG, JPG, GIF, or SVG format
                                    and have a maximum size of 2MB.</small>
                            </div>

                            <div class="form-group mb-3">
                                <label class="custom-label" for="category_id">Category</label>
                                <select class="form-control custom-input" id="category_id" name="category_id">
                                    <option value="">Select a Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="custom-label" for="description">Description:</label>
                                <textarea class="form-control custom-input" id="description" name="description"
                                          required></textarea>
                            </div>

                            <div class="form-row d-flex justify-content-around mb-3">
                                <div class="form-group col-md-5 me-1">
                                    <label class="custom-label" for="start_date">Start Date:</label>
                                    <input type="datetime-local" class="form-control custom-input" id="start_date"
                                           name="start_date"
                                           required>
                                </div>
                                <div class="form-group col-md-5 ms-1">
                                    <label class="custom-label" for="end_date">End Date:</label>
                                    <input type="datetime-local" class="form-control custom-input" id="end_date"
                                           name="end_date"
                                           required>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="custom-label" for="start_tickets_qty">Start Tickets Quantity:</label>
                                <input type="number" class="form-control custom-input" id="start_tickets_qty"
                                       name="start_tickets_qty"
                                       required>
                            </div>

                            <div class="form-group mb-3">
                                <label class="custom-label" for="current_price">Ticket Price:</label>
                                <input type="number" step="0.01" class="form-control custom-input" id="current_price"
                                       name="current_price" required>
                            </div>

                            <div class="form-group mb-3">
                                <label class="custom-label" for="address_search">Search Address (City, State,
                                    Country): </label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control custom-input" id="address_search"
                                           name="address_search"
                                           placeholder="Type address..." required>
                                    <div class="input-group-append">
                                        <button id="searchBtn"
                                                class="btn btn-outline-secondary h-100 search-city-button" type="button"
                                                onclick="searchInMap()">Search in Map
                                        </button>
                                    </div>
                                </div>

                                <div id="preview-map" style="height: 400px; margin-top: 20px;"></div>

                                <!-- Hidden input for latitude -->
                                <input type="hidden" id="lat" name="lat">

                                <!-- Hidden input for longitude -->
                                <input type="hidden" id="lon" name="lon">
                            </div>

                            <div class="form-group mb-3">
                                <label class="custom-label" for="address">Address Description (Number, Neighborhood,
                                    Reference):</label>
                                <input type="text" class="form-control custom-input" id="address" name="address"
                                       placeholder="Type address's number, neighborhood, reference..." required>
                            </div>

                            @if(!$hasLegalId)
                                <div class="form-group mb-3">
                                    <label class="custom-label" for="legal_id">Legal ID:</label>
                                    <input type="text" class="form-control custom-input" id="legal_id" name="legal_id"
                                           placeholder="Enter your legal ID">
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary next-step w-100 custom-button mt-3 mb-3">Create
                                Event
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- toast alert -->
            <div class="toast" id="errorToast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
                <div class="toast-header">
                    <strong class="mr-auto">Error</strong>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    <span id="errorToastMessage"></span>
                </div>
            </div>
        </div>

        <script type="text/javascript" src="{{ URL::asset('js/event/create-event.js') }}"></script>

    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

@endsection
