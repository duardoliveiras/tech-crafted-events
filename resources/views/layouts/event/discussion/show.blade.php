{{-- resources/views/discussion/show.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Discussion for "{{ $event->name }}"</h2>

        <div class="mb-5">
            @forelse($discussion->comment as $comment)
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <strong>{{ $comment->user->name }}</strong> commented on {{ $comment->commentedat->format('d M Y, H:i') }}
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $comment->text }}</p>
                </div>
            </div>
            @empty
                <div class="alert alert-info" role="alert">
                    No comments yet. Be the first to start the discussion!
                </div>
            @endforelse
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Add a Comment</h5>
                <form action="{{ route('discussion.comment', ['event' => $event->id, 'discussion' => $discussion->id]) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="content">Your Comment:</label>
                        <textarea name="content" id="content" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Submit Comment</button>
                </form>
            </div>
        </div>
    </div>
@endsection


