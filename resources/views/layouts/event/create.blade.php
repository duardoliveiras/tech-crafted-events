@extends('layouts.app')

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

            <div class="form-group">
                <label for="name">Event Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="image_url">Event Image:</label>
                <input type="file" class="form-control" id="image_url" name="image_url" required>
            </div>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select class="form-control" id="category_id" name="category_id">
                    <option value="">Select a Category</option>
                    @foreach ($category as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="startdate">Start Date:</label>
                <input type="datetime-local" class="form-control" id="startdate" name="startdate" required>
            </div>

            <div class="form-group">
                <label for="enddate">End Date:</label>
                <input type="datetime-local" class="form-control" id="enddate" name="enddate" required>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <select class="form-control" id="city_id" name="city_id">
                    <option value="">Select a Category</option>
                    @foreach ($city as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="startticketsqty">Start Tickets Quantity:</label>
                <input type="number" class="form-control" id="startticketsqty" name="startticketsqty" required>
            </div>

            <div class="form-group">
                <label for="currentprice">Ticket Price:</label>
                <input type="number" step="0.01" class="form-control" id="currentprice" name="currentprice" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>

            @if(!$hasLegalId)
                <div class="form-group">
                    <label for="legalid">Legal ID:</label>
                    <input type="text" class="form-control" id="legalid" name="legalid" placeholder="Enter your legal ID">
                </div>
            @endif

            <button type="submit" class="btn btn-primary">Create Event</button>
        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

@endsection