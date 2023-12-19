@extends('layouts.app')

@section('title', $university->name)

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $university->name }}</div>
                    <div class="card-body text-center">
                        <img src="{{ Storage::url($university->image_url) }}" alt="{{ $university->name }}" class="img-fluid mb-3" style="max-height: 300px;">
                        <p class="card-text"><strong>City:</strong> {{ $university->city->name }}</p>

                        @if(auth()->user() && auth()->user()->isAdmin())
                            <a href="{{ route('universities.edit', $university->id) }}" class="btn btn-primary">Edit</a>
                            <button type="button" class="btn btn-danger" onclick="if (confirm('Do you still want to delete this university? You cannot undo this action.')) { document.getElementById('delete-university-form').submit(); }">
                                Delete
                            </button>

                            <form id="delete-university-form" action="{{ route('universities.destroy', $university->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
