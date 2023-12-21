<div class="d-flex flex-start mt-3">

    @if($comment->user->provider == null)
    <img class="rounded-circle shadow-1-strong me-3" src="{{ $comment->user->image_url ? asset('user/' . $comment->user->image_url) : 'https://static-00.iconduck.com/assets.00/user-icon-2048x2048-ihoxz4vq.png' }}" alt="avatar" width="60" height="60" />
    @else
    <img class="rounded-circle shadow-1-strong me-3" src="{{ asset($comment->user->image_url)}}" alt="avatar" width="60" height="60" />
    @endif
    <div class="w-100">
        <h6 class="fw-bold mb-0" style="font-size: 1.05rem;">
            {{ $comment->user->name }}
            @if($comment->isOwner())
            <span class=" badge rounded-pill bg-success ms-2"><strong>Event Organizer</strong></span>
            @endif
            @if($comment->isAdmin())
            <span class="badge rounded-pill bg-secondary ms-2"><strong>Admin</strong></span>
            @endif
            @if($comment->user->id === auth()->id())
            <a href="javascript:void(0);" class="link-muted text-decoration-none text-reset ms-2 button-edit" id="button-edit" onclick="toggleEdit('{{ $comment->id }}')" title="Edit comment">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#d3d7cf" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                </svg>
            </a>

            <a href="javascript:void(0);" class="link-muted text-decoration-none text-reset ms-2 button-delete" id="button-edit" onclick="confirmDelete('{{ $comment->id }}')" title="Delete comment">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#d3d7cf" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                    <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                </svg>
            </a>
            @endif
            <a href="javascript:void(0);" class="link-muted text-decoration-none text-reset ms-2 button-delete" id="button-report" data-toggle="modal" data-target="#reportModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#d3d7cf" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                    <path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12.435 12.435 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A19.626 19.626 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a19.587 19.587 0 0 0 1.349-.476l.019-.007.004-.002h.001" />
                </svg>
            </a>
        </h6>
        <div class="d-flex align-items-center mb-2">
            <p class="mb-0" style="color: #7E7E7E;">
                {{ $comment->commented_at->format('d M Y, H:i') }}
            </p>

            <div class="d-flex align-items-center ms-auto">
                <a href="javascript:void(0);" class="link-muted text-decoration-none text-reset upvote-btn" data-comment-id="{{ $comment->id }}" data-vote-type="1" data-bs-toggle="tooltip" data-bs-placement="top" title="Upvote">
                    @if($userVotes == 1)
                    <svg id="upvote-filled" xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 512 512">
                        <style>
                            #upvote-filled {
                                fill: #49a835
                            }

                        </style>
                        <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM135.1 217.4l107.1-99.9c3.8-3.5 8.7-5.5 13.8-5.5s10.1 2 13.8 5.5l107.1 99.9c4.5 4.2 7.1 10.1 7.1 16.3c0 12.3-10 22.3-22.3 22.3H304v96c0 17.7-14.3 32-32 32H240c-17.7 0-32-14.3-32-32V256H150.3C138 256 128 246 128 233.7c0-6.2 2.6-12.1 7.1-16.3z" />
                    </svg>
                    @else
                    <svg id="upvote-not-filled" xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 512 512">
                        <style>
                            #upvote-not-filled {
                                fill: #d3d7cf
                            }

                        </style>
                        <path d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM135.1 217.4c-4.5 4.2-7.1 10.1-7.1 16.3c0 12.3 10 22.3 22.3 22.3H208v96c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32V256h57.7c12.3 0 22.3-10 22.3-22.3c0-6.2-2.6-12.1-7.1-16.3L269.8 117.5c-3.8-3.5-8.7-5.5-13.8-5.5s-10.1 2-13.8 5.5L135.1 217.4z" />
                    </svg>
                    @endif
                </a>

                <span id="vote-count-{{ $comment->id }}" class="mx-2" style="font-size: 1.2em; color: #555;">
                    {{ $comment->votes()->sum('vote_type') }}
                </span>
                <a href="javascript:void(0);" class="link-muted text-decoration-none text-reset downvote-btn" data-comment-id="{{ $comment->id }}" data-vote-type="-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Downvote">
                    @if($userVotes == -1)
                    <svg id="downvote-filled" xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 512 512">
                        <style>
                            #downvote-filled {
                                fill: #ef2929
                            }

                        </style>
                        <path d="M256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6L269.8 394.5c-3.8 3.5-8.7 5.5-13.8 5.5s-10.1-2-13.8-5.5L135.1 294.6c-4.5-4.2-7.1-10.1-7.1-16.3c0-12.3 10-22.3 22.3-22.3l57.7 0 0-96c0-17.7 14.3-32 32-32l32 0c17.7 0 32 14.3 32 32l0 96 57.7 0c12.3 0 22.3 10 22.3 22.3c0 6.2-2.6 12.1-7.1 16.3z" />
                    </svg>
                    @else
                    <svg id="downvote-not-filled" xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 512 512">
                        <style>
                            #downvote-not-filled {
                                fill: #d3d7cf
                            }

                        </style>
                        <path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z" />
                    </svg>
                    @endif
                </a>
            </div>
        </div>
        <p class="mb-0" style="font-size: 1.2em;" id="commentText-{{$comment->id}}">
            {{ $comment->text }} &ensp;
            @if($comment->attachment_path)
            <a href="{{ Storage::url($comment->attachment_path) }}" class="btn btn-outline-secondary py-0 align-self-end" download>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-download" viewBox="0 0 16 16">
                    <path d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z" />
                    <path d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708z" />
                </svg> Download Attachment
            </a>
            @endif
        </p>

        @if($comment->user->id === auth()->id())
        <input type="text" class="form-control mb-2" id="editCommentInput-{{$comment->id}}" style="display: none;" value="{{ $comment->text }}">
        <button id="button-save-{{$comment->id}}" class="btn btn-primary mt-2" style="display: none;" onclick="saveChanges('{{ $comment->id }}')">Save
            Changes
        </button>
        @endif
    </div>
</div>



<style>
    .button-edit:hover svg,
    .button-delete:hover svg,
    #downvote-not-filled:hover,
    #upvote-not-filled:hover {
        transition: .5s;
        fill: #666666;
    }

</style>



<!-- Report Modal -->
@include('partials.report', ['comment' => $comment, 'report' => "comment"])

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
