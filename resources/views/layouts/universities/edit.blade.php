@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">Edit University</div>
                    <div class="card-body">
                        <form action="{{ route('universities.update', $university->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $university->name }}" required>
                            </div>

                            <!-- Address Input Field -->
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ $university->address }}" required>
                            </div>

                            <div class="form-group">
                                <label for="city">City:</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ $university->city->name }}" required>
                            </div>


                            <button type="submit" class="btn btn-primary mt-3">Update University</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
