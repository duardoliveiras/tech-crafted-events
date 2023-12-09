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
        $events = Event::withCount('event_report')->get();
        $events = $events->sortByDesc('event_report_count');
        
        return view('layouts.admin.reports', compact('events'));
    }

    public function eventReports($eventId)
    {
        $eventReports = EventReport::where('event_id', $eventId)
            ->with('user')
            ->get();
        
        return response()->json($eventReports);
    }
}
