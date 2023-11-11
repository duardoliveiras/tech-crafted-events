{{-- resources/views/discussion/show.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Discussion for "{{ $event->name }}"</h2>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Add a Comment</h5>
                <form action="{{ route('discussion.comment', ['event' => $event->id, 'discussion' => $discussion->id]) }}"
                      method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="content">Your Comment:</label>
                        <textarea name="content" id="content" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Submit Comment</button>
                </form>
            </div>
        </div>

        <div class="container my-5 py-5">
            <div class="row d-flex justify-content-center">
                <div class="col-md-12 col-lg-10">
                    <div class="card text-dark">
                        <div class="card-body p-4">
                            <h4 class="mb-0">Recent comments</h4>
                            <p class="fw-light mb-4 pb-2">Latest Comments section by users</p>

                            {{-- Iterar sobre os comentários --}}
                            @forelse($discussion->comment as $comment)
                                <div class="d-flex flex-start mt-4">
                                    <img class="rounded-circle shadow-1-strong me-3"
                                         src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(23).webp"
                                         alt="avatar" width="60"
                                         height="60"/>
                                    <div class="w-100">
                                        <h6 class="fw-bold mb-1" style="font-size: 1.05rem;">{{ $comment->user->name }}</h6>
                                        <div class="d-flex align-items-center mb-3">
                                            <p class="mb-0" style="color: #7E7E7E;">
                                                {{ $comment->commentedat->format('d M Y, H:i') }}
                                                {{--                                                    <span class="badge bg-{{ $comment->status_color }}">{{ $comment->status }}</span>--}}
                                            </p>
                                            <a href="javascript:void(0);" class="link-muted text-decoration-none text-reset ms-4 upvote-btn"
                                               data-comment-id="{{ $comment->id }}" data-vote-type="1">
                                                <img src="{{ URL::asset('/assets/img/arrow-up.png') }}" alt="Upvote" width="20" height="20"
                                                     @if(optional($userVotes)[$comment->id] == 1) style="color: blue;" @endif>
                                            </a>

                                            <a href="javascript:void(0);" class="link-muted text-decoration-none text-reset downvote-btn"
                                               data-comment-id="{{ $comment->id }}" data-vote-type="-1">
                                                <img src="{{ URL::asset('/assets/img/arrow-down.png') }}" alt="Downvote" width="20" height="20"
                                                     @if(optional($userVotes)[$comment->id] == -1) style="color: red;" @endif>
                                            </a>
                                        </div>
                                        <p class="mb-0">
                                            {{ $comment->text }}
                                        </p>
                                    </div>
                                </div>

                                <hr class="my-2"/>
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

        {{--        <div class="mb-5">--}}
        {{--            @forelse($discussion->comment as $comment)--}}
        {{--            <div class="card mb-4 shadow-sm">--}}
        {{--                <div class="card-header bg-primary text-white">--}}
        {{--                    <strong>{{ $comment->user->name }}</strong> commented on {{ $comment->commentedat->format('d M Y, H:i') }}--}}
        {{--                </div>--}}
        {{--                <div class="card-body">--}}
        {{--                    <p class="card-text">{{ $comment->text }}</p>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--                    @empty--}}
        {{--                        <div class="alert alert-info" role="alert">--}}
        {{--                            No comments yet. Be the first to start the discussion!--}}
        {{--                        </div>--}}
        {{--                    @endforelse--}}
        {{--        </div>--}}


    </div>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.upvote-btn, .downvote-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                let commentId = this.getAttribute('data-comment-id');
                let voteType = this.getAttribute('data-vote-type');

                axios.post(`/comments/${commentId}/toggle-vote/${voteType}`)
                    .then(function (response) {
                        if (voteType === '1') {
                            console.log('altera o botão upvote')
                        } else {
                            console.log('altera o botão downvote')
                        }
                    })
                    .catch(function (error) {
                        console.error('Erro ao processar a solicitação:', error);
                    });
            });
        });
    });
</script>

