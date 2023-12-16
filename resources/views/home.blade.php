@extends('layouts.app')

<style>
    .carousel-item img {
        object-fit: cover;
        width: 100%;
        height: 400px;
    }
    .carousel {
        width: 100%;
        max-height: 300px;
    }
</style>

@section('content')
    <div class="container">
        <div class="row justify-content-center vh-100" style="background: url('{{ asset('assets/backgroundicon.svg') }}') no-repeat center center fixed; background-size: cover;">
            <div class="col-12 col-md-8 col-lg-6"> <!-- Ajuste as classes de grid aqui -->
                <div class="mt-5 text-center">
                    <h1 class="mt-5">Stay connected with Tech Crafted events</h1>
                    <p class="mt-2">Don't miss the opportunity to be at the forefront of the technological revolution - come be part of Tech Crafted</p>
                    <div class="justify-content-center">
                        @guest
                            <a href="{{ route('login') }}"><button class="btn btn-outline-dark m-2">Get started</button></a>
                        @endguest
                        <a href="/events"><button class="btn btn-primary m-2">View all events</button></a>
                    </div>
                </div>

                <!-- Carrossel start here -->
                <div id="carouselExampleCaptions" class="carousel slide mt-5" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach($events as $index => $event)
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{ $index }}" class="{{ $loop->first ? 'active' : '' }}" aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach($events as $event)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <img src="{{ Storage::url($event->image_url) }}" class="d-block w-100 img-fluid" alt="{{ $event->name }}">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>{{ $event->name }}</h5>
                                    <p>{{ $event->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
