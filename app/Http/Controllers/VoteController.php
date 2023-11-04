<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Vote;

class VoteController extends Controller
{

        public function store(Request $request, $commentId)
    {
        $voteType = $request->has('upvote') ? 'up' : 'down';

        $vote = Vote::updateOrCreate(
            ['user_id' => auth()->id(), 'comment_id' => $commentId],
            ['voteType' => $voteType, 'votedAt' => now()]
        );

        return back()->with('success', 'Thank you for voting!');
    }

}
