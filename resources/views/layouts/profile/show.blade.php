@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h4>Perfil do Usuário</h4>
                </div>
                <div class="card-body">
                    <p><strong>Nome:</strong> {{ $user->name }}</p>
                    <p><strong>E-mail:</strong> {{ $user->email }}</p>
                    <p><strong>Data de Nascimento:</strong> {{ $user->birthdate }}</p>
                    <p><strong>Telefone:</strong> {{ $user->phone }}</p>
                    <a class="btn btn-primary">Editar Perfil</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection