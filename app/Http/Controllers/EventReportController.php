<?php

namespace App\Http\Controllers;

use App\Models\EventReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventReportController extends Controller
{
    public function postReport(Request $request, $eventId)
    {   
        $request->validate([
            'reportReason' => 'required'
        ]);

        $report = new EventReport([
            'event_id' => $eventId,
            'user_id' => Auth::id(),
            'reason' => $request->reportReason,
            'description' => $request->reportDescription
            
        ]);

        $report->save();

        return redirect()->to(route('events.show', $eventId))->with(
            "success", "Your report will be analyzed by the team"
        );
    }

    public function checkOneReport($reportId)
    {
        $report = EventReport::find($reportId);
        

        if($report){
            $report->update(['analyzed' => true]);
            return response()->json(['message' => 'Check report success.']);
        }else{
            return response()->json(['error' => 'Report not found'], 404);
        }


    }
}
