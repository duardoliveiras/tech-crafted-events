@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height: 50vh;">
            <div class="col-md-8 text-center">
                <h1 class="display-4">Oops! Page Not Found</h1>
                <p class="lead">Sorry, the page you are looking for might be unavailable or does not exist.</p>
                <a href="{{ route('home') }}" class="btn btn-primary next-step w-50 custom-button mt-3 mb-3">Go back to the home page</a>
            </div>
        </div>
    </div>
@endsection

<style>
    .custom-button {
        background: linear-gradient(to left, #7848F4, #5827D8);
        font-size: 1.3rem !important;
        font-weight: bolder !important;
        border: none !important;
    }
</style>

