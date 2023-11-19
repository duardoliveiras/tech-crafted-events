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
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $event->start_date) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $event->end_date) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $event->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="city_id" class="form-label">City</label>
                        <select class="form-select" id="city_id" name="city_id" required>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ $event->city_id == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="start_tickets_qty" class="form-label">Starting Ticket Quantity</label>
                        <input type="number" class="form-control" id="start_tickets_qty" name="start_tickets_qty" value="{{ old('start_tickets_qty', $event->start_tickets_qty) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="current_tickets_qty" class="form-label">Current Ticket Quantity</label>
                        <input type="number" class="form-control" id="current_tickets_qty" name="current_tickets_qty" value="{{ old('current_tickets_qty', $event->current_tickets_qty) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="current_price" class="form-label">Current Price</label>
                        <input type="text" class="form-control" id="current_price" name="current_price" value="{{ old('current_price', $event->current_price) }}" required>
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
