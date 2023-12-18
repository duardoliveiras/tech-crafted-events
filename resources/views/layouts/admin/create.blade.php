@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow">
                    <div class="card-header text-white bg-gradient bg-primary">
                        <h2 class="text-center mb-0">Create Admin Account</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Enter the name of admin account that you want to create">
                                    <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
                                </span>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="birthdate" class="form-label">Birthdate:</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Enter the birth date of the admin account that you want to create ">
                                    <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
                                </span>
                                <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Enter the email of the admin account that you want to create ">
                                    <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
                                </span>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Enter the password of the admin account that you want to create ">
                                    <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
                                </span>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="phone" class="form-label">Phone:</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Enter the phone number of the admin account that you want to create ">
                                    <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
                                </span>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="university_id" class="form-label">University:</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Select the university related to the admin account that you want to create ">
                                    <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
                                </span>
                                <select class="form-select" id="university_id" name="university_id">
                                    @foreach ($universities as $university)
                                        <option value="{{ $university->id }}">{{ $university->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-4">
                                <label for="image_url" class="form-label">Profile Image:</label>
                                <span class="ms-1 custom-label" data-toggle="tooltip" data-placement="top"
                                      title="Enter the image for the profile of the admin account that you want to create ">
                                    <img src="{{ asset('assets/img/info.svg') }}" alt="Info">
                                </span>
                                <input type="file" class="form-control" id="image_url" name="image_url">
                            </div>

                            <button type="submit" class="btn btn-primary">Create Admin</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
