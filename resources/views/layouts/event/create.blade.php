@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{URL::asset('/assets/css/create-event.css')}}">

@section('title', 'Create Event')

@section('content')
    @section('breadcrumbs')
        <li>
            &nbsp; / Create Event
        </li>
    @endsection
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

                        @if(!$hasLegalId)
                            @php
                                return redirect()->route('event-organizer.show');
                            @endphp
                        @endif

                        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group mb-3">
                                <label class="custom-label" for="name">Event Name</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Enter the name of the event as you want it to appear on promotional materials and event listings.">
        <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
    </span>
                                <input type="text" class="form-control custom-input @error('name') is-invalid @enderror"
                                       id="name" name="name"
                                       value="{{ old('name') }}" required>
                                @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="custom-label" for="image_url">Event Image</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Upload an eye-catching image for your event. This will be displayed on the event's preview card.">
        <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
    </span>
                                <input type="file"
                                       class="form-control custom-input @error('image_url') is-invalid @enderror"
                                       id="image_url"
                                       value="{{ old('image_url') }}" name="image_url"
                                       accept="image/jpeg, image/png, image/jpg, image/gif, image/svg+xml" required>
                                <small class="form-text text-muted">Image must be in JPEG, PNG, JPG, GIF, or SVG format
                                    and have a maximum size of 2MB.</small>
                                @error('image_url')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="custom-label" for="category_id">Category</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Choose the category that best describes your event, helping attendees find it more easily.">
        <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
    </span>
                                <select class="form-control custom-input @error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id">
                                    <option value="">Select a Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="custom-label" for="description">Description</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Provide a detailed description of what attendees can expect at your event. Be clear and engaging!">
        <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
    </span>
                                <textarea class="form-control custom-input @error('description') is-invalid @enderror"
                                          id="description" name="description"
                                          required>{{old('description')}}</textarea>
                                @error('description')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="d-flex flex-column flex-md-row justify-content-around mb-3">
                                <div class="form-group col-md-5 col-12 me-1">
                                    <label class="custom-label" for="start_date">Start Date</label>
                                    <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                          title="Set the date and time when your event will begin.">
        <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
    </span>
                                    <input type="datetime-local"
                                           class="form-control custom-input @error('start_date') is-invalid @enderror"
                                           id="start_date"
                                           name="start_date" value="{{old('start_date')}}"
                                           required>
                                    @error('start_date' || 'msg'.contains('start date'))
                                    <span class="invalid-feedback" role="alert"><strong>opa{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-5 ms-1">
                                    <label class="custom-label" for="end_date">End Date</label>
                                    <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                          title="Specify the date and time when your event will conclude. Note: Events should last no more than 5 days.">
        <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
    </span>
                                    <input type="datetime-local"
                                           class="form-control custom-input @error('end_date') is-invalid @enderror"
                                           id="end_date"
                                           value="{{old('end_date')}}"
                                           name="end_date"
                                           required>
                                    @error('end_date')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="custom-label" for="start_tickets_qty">Start Tickets Quantity</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Specify the initial quantity of tickets available for your event. Adjustments can be made later if needed.">
        <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
    </span>
                                <input type="number"
                                       class="form-control custom-input @error('start_tickets_qty') is-invalid @enderror"
                                       id="start_tickets_qty"
                                       name="start_tickets_qty" value="{{old('start_tickets_qty')}}"
                                       required>
                                @error('start_tickets_qty')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>


                            <div class="form-group mb-3">
                                <label class="custom-label" for="current_price">Ticket Price</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Enter the price for each ticket. Set the price as 0 for free events. Ensure the price reflects the value of the event experience.">
        <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
    </span>
                                <input type="number" step="0.01"
                                       class="form-control custom-input @error('current_price') is-invalid @enderror"
                                       id="current_price"
                                       value="{{old('current_price')}}"
                                       name="current_price" required>
                                @error('current_price')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="custom-label" for="address_search">Search Address (City, State,
                                    Country)</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Type the event's location details such as city, state or province, and country to ensure accurate mapping and easy location by attendees.">
        <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
    </span>
                                <div class="input-group mb-3">
                                    <input type="text"
                                           class="form-control custom-input @error('address_search' || 'lat' || 'lon') is-invalid @enderror"
                                           id="address_search"
                                           name="address_search"
                                           placeholder="Type address..." required>
                                    @error('address_search' || 'lat' || 'lon')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
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
                                    Reference)</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Type more information about event's address.">
        <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
    </span>
                                <input type="text"
                                       class="form-control custom-input @error('address') is-invalid @enderror"
                                       id="address" name="address"
                                       placeholder="Type address's number, neighborhood, reference..." required>
                                @error('address')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

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
