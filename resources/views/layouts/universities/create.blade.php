@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">Create University</div>
                    <div class="card-body">
                        <form action="{{ route('universities.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="name">Name:</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Enter the name of the university that you want to add">
                                    <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
                                </span>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="image_url">Image:</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Enter the picture of the university that you want to add">
                                    <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
                                </span>
                                <input type="file" class="form-control-file" id="image_url" name="image_url" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="address_search">Search Address (City, State, Country):</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Enter the address of the university that you want to add">
                                    <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
                                </span>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="address_search" name="address_search" placeholder="Type address..." required>
                                    <div class="input-group-append">
                                        <button id="searchBtn" class="btn btn-outline-secondary" type="button" onclick="searchInMap()">
                                            Search in Map
                                        </button>
                                    </div>
                                </div>
                                <div id="preview-map" style="height: 400px; margin-top: 20px;"></div>
                                <input type="hidden" id="lat" name="lat">
                                <input type="hidden" id="lon" name="lon">
                            </div>
                            <div class="form-group mb-3">
                                <label for="address">Address Description (Number, Neighborhood, Reference):</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Enter the address description of the university that you want to add">
                                    <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
                                </span>
                                <input type="text" class="form-control" id="address" name="address"
                                       placeholder="Type address's number, neighborhood, reference..." required>
                            </div>

                            <button type="submit" class="btn btn-primary">Create University</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{ asset('js/event/create-event.js') }}"></script>
@endsection
