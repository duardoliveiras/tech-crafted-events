<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Event;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function __construct()
    {
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

        if ($eventType && is_numeric($eventType)) {
            $query->where('category_id', $eventType);
        }
        if ($location && is_numeric($location)) {
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

    }

    public function store(Request $request)
    {
    }


}
