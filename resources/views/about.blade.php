@extends('layouts.app')

@section('title', 'About Us - TechCrafted')

@section('content')
    @section('breadcrumbs')
        <li> &nbsp; / About us </li>
    <style>
        .card-hover-effect {
            transition: transform .3s, box-shadow .3s;
            border: none;
        }

        .card-hover-effect:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
    <div class="container py-5">
        <h2 class="text-center mb-4">About Us</h2>
        <p class="text-center mb-5">TechCrafted is dedicated to enhancing the faculty event management experience at
            FEUP, engineered by students for students.</p>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">
            @php
                $developers = [
                    ['name' => 'Bernardo', 'image' => 'bernardo.jpg', 'github' => 'https://github.com/brito-bernardo'],
                    ['name' => 'Eduardo', 'image' => 'eduardo.jpeg', 'github' => 'https://github.com/duardoliveiras'],
                    ['name' => 'Tomás', 'image' => 'tomas.jpeg', 'github' => 'https://github.com/TWEgit'],
                    ['name' => 'Vicente', 'image' => 'vicente.jpeg', 'github' => 'https://github.com/vicente-md']
                ];
            @endphp

            @foreach ($developers as $developer)
                <div class="col">
                    <a href="{{$developer['github']}}" class="text-decoration-none text-reset">
                        <div class="card h-100 card-hover-effect">
                            <img src="{{ asset('assets/developers/'.$developer['image']) }}" class="card-img-top"
                                 alt="{{ $developer['name'] }}">
                            <div class="card-body">
                                <h5 class="card-title text-center">{{ $developer['name'] }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="text-center">
            <p class="lead">Empowering Events at FEUP</p>
            <p>TechCrafted emerged from the heart of FEUP with a mission to revolutionize event management within our
                academic community. Our team of student developers combines expertise in various fields to deliver a
                seamless event management platform, specially designed for the vibrant campus life at FEUP.</p>
            <p>Our platform is a testament to the innovative spirit of the student body, providing tools for organizing,
                promoting, and attending faculty events with ease. With TechCrafted, we're not just managing
                events—we're fostering a connected and engaged academic community.</p>
            <p>We are committed to continuously evolving and enriching our platform to meet the needs of FEUP's students
                and staff. By creating a user-friendly and secure environment, we ensure that every event, whether
                academic, cultural, or social, is a success from start to finish.</p>
        </div>
    </div>
@endsection
