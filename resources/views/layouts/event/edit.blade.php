@extends('layouts.app')

@section('title', 'Edit Event')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Edit Event
            </div>
            <div class="card-body">
                <form action="{{ route('events.update', $event->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Event Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $event->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $event->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="startdate" class="form-label">Start Date</label>
                        <input type="datetime-local" class="form-control" id="startdate" name="startdate" value="{{ old('startdate', $event->startdate) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="enddate" class="form-label">End Date</label>
                        <input type="datetime-local" class="form-control" id="enddate" name="enddate" value="{{ old('enddate', $event->enddate) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            @foreach($category as $category)
                                <option value="{{ $category->id }}" {{ $event->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="city_id" class="form-label">City</label>
                        <select class="form-select" id="city_id" name="city_id" required>
                            @foreach($city as $city)
                                <option value="{{ $city->id }}" {{ $event->city_id == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="startticketsqty" class="form-label">Starting Ticket Quantity</label>
                        <input type="number" class="form-control" id="startticketsqty" name="startticketsqty" value="{{ old('startticketsqty', $event->startticketsqty) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="currentticketsqty" class="form-label">Current Ticket Quantity</label>
                        <input type="number" class="form-control" id="currentticketsqty" name="currentticketsqty" value="{{ old('currentticketsqty', $event->currentticketsqty) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="currentprice" class="form-label">Current Price</label>
                        <input type="text" class="form-control" id="currentprice" name="currentprice" value="{{ old('currentprice', $event->currentprice) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $event->address) }}" required>
                    </div>


            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success">Update Event</button>
            </div>
            </form>
        </div>
    </div>
@endsection
