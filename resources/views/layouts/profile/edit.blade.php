@extends('layouts.app')

@section('content')
       @section('breadcrumbs')
        <li> 
            &nbsp; / <a href="{{ route('profile.show', $user->id) }}">{{$user->name}}</a>
        </li>
        <li>
            &nbsp; / Edit    
        </li>
        @endsection
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit user profile</h4>
                    </div>
                    <div class="card-body">
                        {{-- Eventually erros --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Edition forms --}}
                        <form action="{{ route('profile.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                            </div>

                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                            </div>

                            <div class="form-group">
                                <label for="birthdate">Birth date:</label>
                                <input type="date" class="form-control" id="birthdate" name="birthdate" value="{{ $user->birthdate }}" required>
                            </div>

                            <div class="form-group">
                                <label for="phone_number">Phone number:</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $user->phone }}" required>
                            </div>

                            <button type="submit" class="btn btn-success">Save editions</button>

                        </form>
                        @isset($mensagemSucesso)
                            <div class="alert alert-success">
                                {{  $mensagemSucesso }}
                            </div>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
