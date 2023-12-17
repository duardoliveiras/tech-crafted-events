@extends('layouts.app')

@section('content')
@section('breadcrumbs')
<li>
    <a href="{{ route('events.show', $event->id) }} "> &nbsp; / {{ $event->name }} </a>
</li>

<li>
    &nbsp; / Discussion
</li>
@endsection

<div class="container mt-4">
    <h2 class="mb-4">Discussion for "{{ $event->name }}"</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Add a Comment</h5>
            <form action="{{ route('discussion.comment', ['event' => $event->id, 'discussion' => $discussion->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label for="content">Your Comment:</label>
                    <textarea name="content" id="content" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="custom-label" for="attachment">Attach image to comment:</label>
                    <input type="file" class="form-control custom-input" id="attachment" name="attachment" required>
                    <small class="form-text text-muted">Image must be in JPEG, PNG, JPG, GIF, or SVG format and have
                        a maximum size of 2MB.</small>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Submit Comment</button>
            </form>
        </div>
    </div>

    <div class="container my-5 py-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-12 p-0">
                <div class="card text-dark">
                    <div class="card-body p-4">
                        <h4 class="mb-0">Comments</h4>
                        <p class="fw-light mb-4 pb-2">Comments section by users registered in the event</p>

                        @forelse($discussion->commentsOrderedByVotes() as $comment)

                        @include('partials.comment', ['comment' => $comment, 'userVotes' => optional($userVotes)[$comment->id]])
                        @if(!$loop->last)
                        <hr class="my-3" />
                        @endif
                        @empty
                        <div class="alert alert-info" role="alert">
                            No comments yet. Be the first to start the discussion!
                        </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="{{ URL::asset ('js/event/discussion.js') }}"></script>
</div>

@endsection
