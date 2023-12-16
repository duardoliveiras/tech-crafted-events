<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\CommentReport;
use Illuminate\Support\Facades\Auth;

class CommentReportController extends Controller
{
    public function postReport(Request $request, $eventId, $commentId){
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
}
