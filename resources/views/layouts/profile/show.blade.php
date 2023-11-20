@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">User Profile</h4>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                <img src="{{ $user->image_url ? asset('storage/' . $user->image_url) : 'https://static-00.iconduck.com/assets.00/user-icon-2048x2048-ihoxz4vq.png' }}"
                                     alt="avatar" class="img-fluid rounded" style="max-width: 150px; height: auto;"/>
                            </div>

                            <div class="col-md-8">
                                <!-- Início da Estilização dos Dados do Usuário -->
                                <div class="user-data">
                                    <h5><i class="fas fa-user"></i> <strong>Name:</strong> {{ $user->name }}</h5>
                                    <p><i class="fas fa-envelope"></i> <strong>E-mail:</strong> {{ $user->email }}</p>
                                    <p><i class="fas fa-birthday-cake"></i> <strong>Birthdate:</strong> {{ $user->birthdate }}</p>
                                    <p><i class="fas fa-phone"></i> <strong>Phone number:</strong> {{ $user->phone }}</p>
                                </div>
                                <!-- Fim da Estilização dos Dados do Usuário -->

                                <div class="d-flex justify-content-start">
                                    <a class="btn btn-primary me-2" href="{{route('profile.edit', ['profile' => Auth::user()->id])}}">Edit Profile</a>
                                    <form action="{{ route('profile.destroy', Auth::user()->id) }}" method="POST" onsubmit="return confirm('Do you still want to cancel this account? You cannot undo this action.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete Account</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
