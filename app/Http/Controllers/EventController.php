<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\City;
use App\Models\EventOrganizer;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request): View
    {
        $categories = Category::all();
        $locations = City::all();
        $universities = University::all();

        $eventType = $request->query('eventType');
        $location = $request->query('location');
        $dateFilter = $request->query('date-filter');

        $query = Event::query();

        if ($eventType) {
            $query->where('category_id', $eventType);
        }
        if ($location) {
            $query->where('city_id', $location);
        }
        if ($dateFilter) {
            $query->where('startdate', $dateFilter);
        }

        $events = $query->get();

        return view('layouts.event.list', [
            'events' => $events,
            'universities' => $universities,
            'eventTypes' => $categories,
            'locations' => $locations]);
    }

    public function show($id): View
    {
        $event = Event::find($id);
        return view('layouts.event.details', compact('event'));
    }


    public function create()
    {
        $category = Category::all();
        $city = City::all();
        $eventOrganizer = EventOrganizer::where('user_id', Auth::id())->first();
        $hasLegalId = $eventOrganizer && !is_null($eventOrganizer->legalid);

        return view('layouts.event.create', compact('category', 'city', 'hasLegalId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'startdate' => 'required|date',
            'category_id' => 'required|exists:category,id',
            'enddate' => 'required|date',
            'city_id' => 'required|exists:city,id',
            'startticketsqty' => 'required|integer',
            'currentticketsqty' => 'required|integer',
            'currentprice' => 'required|numeric',
            'address' => 'required|string|max:255',
        ]);




        $eventOrganizer = EventOrganizer::firstOrCreate([
            'user_id' => Auth::id(),
        ], [
            'legalid' => $request->input('legalid'),
        ]);


        if (!$eventOrganizer->legalid) {
            return redirect()->back()->withInput()->withErrors([
                'legalid' => 'O campo Identificador Legal é obrigatório.'
            ]);
        }


        $event = new Event([
            'name' => $request->name,
            'description' => $request->description,
            'startdate' => $request->startdate,
            'category_id' => $request->category_id,
            'enddate' => $request->enddate,
            'startticketsqty' => $request->startticketsqty,
            'currentticketsqty' => $request->currentticketsqty,
            'currentprice' => $request->currentprice,
            'address' => $request->address,
            'city_id' => $request->city_id,
            'owner_id' => $eventOrganizer-> id,

        ]);
        if ($event->save()) {
            return redirect()->route('events.index')->with('success', 'Event created successfully.');
        } else {

            return redirect()->back()->withInput()->withErrors(['msg' => 'Error saving the event.']);
        }

    }
    public function edit($id)
    {
        $event = Event::findOrFail($id);

        $this->authorize('update', $event);

        $category = Category::all();
        $city = City::all();

        return view('layouts.event.edit', compact('event', 'category', 'city'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $this->authorize('update', $event);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'startdate' => 'required|date',
            'category_id' => 'required|exists:category,id',
            'enddate' => 'required|date',
            'city_id' => 'required|exists:city,id',
            'startticketsqty' => 'required|integer',
            'currentticketsqty' => 'required|integer',
            'currentprice' => 'required|numeric',
            'address' => 'required|string|max:255',
        ]);

        $event->update($request->all());

        return redirect()->route('events.show', $event->id)->with('success', 'Event updated successfully!');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        $this->authorize('delete', $event);

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }


}
