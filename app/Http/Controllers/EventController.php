<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\City;
use App\Models\EventOrganizer;
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
        $category = Category::all();
        $city = City::all();


        return view('layouts.event.create', compact('category', 'city'));
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
            'user_id' => Auth::id(), // Usuário atualmente autenticado
        ], [
            'legalid' => $request->input('legalid'), // O campo legalid vindo do formulário
        ]);

        // Caso o legal_id seja necessário e não esteja presente, retorne com erro
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


}
