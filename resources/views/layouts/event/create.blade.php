@extends('layouts.app')

@section('content')
@php
    $hasLegalId = true;
 @endphp
    <div class="container">
        <h1>Create New Event</h1>

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

        <form action="{{ route('events.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Event Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
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
                <label for="currentticketsqty">Current Tickets Quantity:</label>
                <input type="number" class="form-control" id="currentticketsqty" name="currentticketsqty" required>
            </div>

            <div class="form-group">
                <label for="currentprice">Ticket Price:</label>
                <input type="number" step="0.01" class="form-control" id="currentprice" name="currentprice" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>

            <div class="form-group">
                <label for="legalid">Identificador Legal:</label>
                <input type="text" id="legalid" name="legalid"  class="form-control" required>
            </div>


            <button type="submit" class="btn btn-primary">Create Event</button>
        </form>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>

@endsection
