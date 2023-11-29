<div class="d-flex flex-start mt-3">
    <img class="rounded-circle shadow-1-strong me-3" src="{{ $comment->user->image_url ? asset('storage/' . $comment->user->image_url) : 'https://static-00.iconduck.com/assets.00/user-icon-2048x2048-ihoxz4vq.png' }}" alt="avatar" width="60" height="60"/>
    <div class="w-100">
        <h6 class="fw-bold mb-0" style="font-size: 1.05rem;">
            {{ $comment->user->name }}
            @if($comment->isOwner())
                <span class="badge rounded-pill bg-success ms-2"><strong>Event Organizer</strong></span>
            @endif
            @if($comment->isAdmin())
                <span class="badge rounded-pill bg-secondary ms-2"><strong>Admin</strong></span>
            @endif
        </h6>
        <div class="d-flex align-items-center mb-2">
            <p class="mb-0" style="color: #7E7E7E;">
                {{ $comment->commented_at->format('d M Y, H:i') }}
            </p>
            <div class="d-flex align-items-center ms-auto">
                <a href="javascript:void(0);" class="link-muted text-decoration-none text-reset upvote-btn"
                   data-comment-id="{{ $comment->id }}" data-vote-type="1"
                   data-bs-toggle="tooltip" data-bs-placement="top" title="Upvote">
                    @include('partials.vote-icon', ['comment' => $comment, 'voteType' => 1])
                </a>
                <span id="vote-count-{{ $comment->id }}" class="mx-2" style="font-size: 1.2em; color: #555;">
                    {{ $comment->votes()->sum('vote_type') }}
                </span>
                <a href="javascript:void(0);" class="link-muted text-decoration-none text-reset downvote-btn"
                   data-comment-id="{{ $comment->id }}" data-vote-type="-1"
                   data-bs-toggle="tooltip" data-bs-placement="top" title="Downvote">
                    @include('partials.vote-icon', ['comment' => $comment, 'voteType' => -1])
                </a>
            </div>
        </div>
        <p class="mb-0" style="font-size: 1.2em;">
            {{ $comment->text }}
        </p>
    </div>
</div>
