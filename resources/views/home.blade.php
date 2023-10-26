@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center vh-100" style="background: url('{{ asset('assets/backgroundicon.svg')}}') no-repeat center center fixed; background-size: cover;">
        <div class="mt-5 text-center">
            <h1 class="mt-5">Stay connected with Tech Crafted events</h1>
            <p class="mt-2">Don't miss the opportunity to be at the forefront of the technological revolution - come be part of Tech Crafted</p>
            <div class="justify-content-center">

                <a href="{{ route('login') }}"><button class="btn btn-outline-dark m-2">Get started</button></a>
                <a href="/events"><button class="btn btn-primary m-2">View all events</button></a>
            </div>
        </div>
        <div id="carouselExampleCaptions" class="carousel slide w-50" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{URL::asset('/assets/c_exemplo_1.jpeg')}}" class="d-block w-100 img-fluid" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>First slide label</h5>
                        <p>Some representative placeholder content for the first slide.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{URL::asset('/assets/c_exemplo_2.jpeg')}}" class="d-block w-100 img-fluid" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Second slide label</h5>
                        <p>Some representative placeholder content for the second slide.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{URL::asset('/assets/c_exemplo_3.jpeg')}}" class="d-block w-100 img-fluid" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Third slide label</h5>
                        <p>Some representative placeholder content for the third slide.</p>
                    </div>
                </div>
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
@endsection
