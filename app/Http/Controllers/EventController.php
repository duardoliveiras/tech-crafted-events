<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(): View
    {
        $events = Event::all();
        return view('layouts.event.list', ['events' => $events]);
    }

    public function show($id): View
    {
        $event = Event::find($id);
        return view('layouts.event.details', compact('event'));
    }


    public function create()
    {
        return view('layouts.event.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'startdate' => 'required|date',
            'enddate' => 'required|date',
            'startticketsqty' => 'required|integer',
            'currentticketsqty' => 'required|integer',
            'currentprice' => 'required|numeric',
            'address' => 'required|string|max:255',
        ]);

        $event = new Event([
            'name' => $request->name,
            'description' => $request->description,
            'startdate' => $request->startdate,
            'enddate' => $request->enddate,
            'startticketsqty' => $request->startticketsqty,
            'currentticketsqty' => $request->currentticketsqty,
            'currentprice' => $request->currentprice,
            'address' => $request->address,
            'owner_id' => Auth::id(),
        ]);

        $event->save();

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }


}
