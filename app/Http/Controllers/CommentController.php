<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Discussion;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Event $event, Discussion $discussion): RedirectResponse
    {
        $request->validate([
            'content' => 'required|string',
            'attachment' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,txt|max:2048'
        ]);

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('attachments', 'public');
        }

        $comment = new Comment([
            'text' => $request->input('content'),
            'user_id' => auth()->id(),
            'discussion_id' => $discussion->id,
            'commented_at' => now(),
            'attachment_path' => $filePath,
        ]);

        $comment->save();

        return redirect()
            ->route('discussion.show', ['event' => $event->id])
            ->with('success', 'Comment added successfully.');
    }

    public function toggleVote(Comment $comment, int $voteType): JsonResponse
    {
        $userId = auth()->id();

        $existingVote = $comment->votes()->where('user_id', $userId)->first();

        if ($existingVote && $existingVote->vote_type === $voteType) {
            $existingVote->delete();
            $newVoteType = 0; // vote removed
        } else {
            $comment->votes()->updateOrCreate(
                ['user_id' => $userId],
                ['vote_type' => $voteType]
            );
            $newVoteType = $voteType;
        }

        $voteCount = $comment->votes()->sum('vote_type');

        return response()->json([
            'success' => true,
            'newVoteType' => $newVoteType,
            'voteCount' => $voteCount,
        ]);
    }

    public function update(Request $request, Comment $comment): JsonResponse
    {
        $request->validate([
            'text' => 'required|string',
        ]);

        // Check if the authenticated user is the owner of the comment
        if (auth()->id() !== $comment->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this comment.',
            ], 403);
        }

        // Update the comment text
        $comment->text = $request->input('text');
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully.',
            'updatedComment' => $comment,
        ]);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        // Check if the authenticated user is the owner of the comment
        if (auth()->id() !== $comment->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this comment.',
            ], 403);
        }

        // Perform logical deletion by updating the is_deleted field
        $comment->update(['is_deleted' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.',
        ]);
    }
}
