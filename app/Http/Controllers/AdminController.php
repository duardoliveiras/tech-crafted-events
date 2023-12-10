<?php

namespace App\Http\Controllers;

use App\Models\EventReport;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get();

        $events = Event::all();
        return view('layouts.admin.dashboard',compact('users', 'events'));
    }

    public function reports()
    {
        $events = Event::whereHas('event_report', fn($query) => $query->where('analyzed', false))
        ->withCount([
            'event_report as event_report_count' => function ($query) {
                $query->where('analyzed', false);
            }
        ])->get();

        $events = $events->sortByDesc('event_report_count');
    
        return view('layouts.admin.reports', compact('events'));
        
    }

    public function eventReports($eventId, $reason)
    {
        if($reason == "All"){
            $eventReports = EventReport::where('event_id', $eventId)
            ->where('analyzed', false)
            ->with('user')
            ->get();
        }else{
            $eventReports = EventReport::where('event_id', $eventId)
            ->where('reason', $reason)
            ->where('analyzed', false)
            ->with('user')
            ->get();
        }

        
        
        return response()->json($eventReports);
    }
}
