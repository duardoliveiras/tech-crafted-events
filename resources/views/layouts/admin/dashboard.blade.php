@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Admin Dashboard</h2>

        <div class="row">
            <!-- Users Section -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="m-0">Users</h3>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach ($users as $user)
                            <li class="list-group-item">
                                <a href="{{ route('profile.show', $user->id) }}">{{ $user->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Events Section -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="m-0">Events</h3>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach ($events as $event)
                            <li class="list-group-item">
                                <a href="{{ route('events.show', $event->id) }}">{{ $event->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection