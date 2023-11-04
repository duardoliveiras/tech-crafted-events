<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Discussion;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request,Event $event,Discussion $discussion )
    {
        $request->validate([
            'content' => 'required|string',
        ]);


        $comment = new Comment([
            'text' => $request->input('content'),
            'user_id' => auth()->id(),
            'discussion_id' => $discussion->id,
            'commentedat' => now(),
        ]);

        $comment->save();


        return redirect()->route('discussion.show', ['event' => $event->id])
            ->with('success', 'Comment added successfully.');

    }
}
