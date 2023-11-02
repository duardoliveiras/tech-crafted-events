<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function __construct()
    {
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

    }

    public function store(Request $request)
    {
    }


}
