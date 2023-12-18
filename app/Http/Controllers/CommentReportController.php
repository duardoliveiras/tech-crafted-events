<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\CommentReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CommentReportController extends Controller
{
    public function postReport(Request $request, $eventId, $commentId)
    {
        $request->validate([
            'description' => 'reportRequired'
        ]);

        $report = new CommentReport([
            'comment_id' => $commentId,
            'user_id' => Auth::id(),
            'reason' => $request->reportReason,
            'description' => $request->reportDescription
        ]);

        $report->save();

        return redirect()->to(route('discussion.show', $eventId))->with(
            "success", "Your report will be analyzed by the team"
        );
    }

    public function checkOneReportComment($reportId)
    {
        $report = CommentReport::find($reportId);

        if ($report) {
            $report->update(['analyzed' => true]);
            return response()->json(['message' => 'Check report success.']);
        } else {
            return response()->json(['error' => 'Report not found'], 404);
        }


    }
    public function check_all_comment($userId)
    {
        $reports = CommentReport::where('user_id', $userId)->get();

        if ($reports) {
            foreach ($reports as $report) {
                $report->analyzed = true;
                $report->save();
            }
            return response()->json(['message' => 'Success check all'], 200);

        } else {
            return response()->json(['error' => 'Event not found'], 404);
        }

    }

    public function banComment($commentId)
    {
        $comment = Comment::find($commentId);
        $user = User::find($comment->user_id);

        Mail::send('mail.banned', [], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject("Your account are banned");
        });

        if ($user && $comment) {
            $user->update([
                'is_banned' => true
            ]);
            $comment->update([
                'is_deleted' => true
            ]);
            return response()->json(['message' => 'Banned with success.']);
        } else {
            return response()->json(['error' => 'User not found.'], 404);
        }
    }
}
