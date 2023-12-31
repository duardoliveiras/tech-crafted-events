<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventReport;
use Illuminate\Http\Request;
use App\Models\CommentReport;
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

    public function checkOneReportEvent($reportId)
    {
        $report = EventReport::find($reportId);

        if($report){
            $report->update(['analyzed' => true]);
            return response()->json(['message' => 'Check report success.']);
        }else{
            return response()->json(['error' => 'Report not found'], 404);
        }


    }

    public function banEvent($eventId)
    {
        $event = Event::find($eventId);

        if($event){
            $event->update(['status' => 'BANNED']);
            return response()->json(['message' => 'Event successfully banned.']);
        }else{
            return response()->json(['error' => 'Event not found'], 404);
        }
    }

    public function check_all_event($eventId){
        $reports = EventReport::where('event_id', $eventId)->get();

        if($reports){
            foreach($reports as $report){
                $report->analyzed = true;
                $report->save();
            }
            return response()->json(['message' => 'Success check all'], 200);
                                
        }else{
            return response()->json(['error' => 'Event not found'], 404);
        }
        
    }
}


