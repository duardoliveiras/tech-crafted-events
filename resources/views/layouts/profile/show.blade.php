@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h4>User profile</h4>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>E-mail:</strong> {{ $user->email }}</p>
                    <p><strong>Birthdate:</strong> {{ $user->birthdate }}</p>
                    <p><strong>Phone number:</strong> {{ $user->phone }}</p>
                    <a class="btn btn-primary" href="{{route('profile.edit', ['profile' => Auth::user()->id])}}">Edit profile</a>
                    <form class="d-inline" action="{{ route('profile.destroy', Auth::user()->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Do still want to cancel this account? You cannot undo this action. ')">Delete Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection