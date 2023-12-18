@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Admin Dashboard</h2>

    <div class="mb-3">
        <a href="{{ route('universities.create') }}" class="btn btn-primary">Add New University</a>
        <a href="{{ route('admin.create') }}" class="btn btn-secondary">Create a new admin account</a>

    </div>
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
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('profile.show', $user->id) }}">{{ $user->name }}</a>
                            </div>
                            <div>
                                @if($user->is_banned)
                                <a href="{{ route('ban.user', $user->id) }}" class="btn btn-secondary">Unban</a>
                                @else
                                <a href="{{ route('ban.user', $user->id) }}" class="btn btn-danger">Ban</a>
                                @endif
                            </div>

                        </div>
                    </li>
                    @endforeach
                    <div>
                        {{$users->links()}}
                    </div>
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
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('events.show', $event->id) }}">{{ $event->name }}</a>
                            </div>
                            <div>
                                @if($event->status == 'BANNED')
                                <a href="{{ route('ban.event', $event->id) }}" class="btn btn-secondary">Unban</a>
                                @else
                                <a href="{{ route('ban.event', $event->id) }}" class="btn btn-danger">Ban</a>
                                @endif
                            </div>
                        </div>
                    </li>
                    @endforeach
                    <div>
                        {{$events->links()}}
                    </div>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
