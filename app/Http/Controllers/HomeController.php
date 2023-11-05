<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //VERY IMPORTANT !!!!!
    //public function __construct()
    //{
   //     $this->middleware('auth');
   // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $events = Event::orderBy('startdate', 'desc')->take(3)->get();
        return view('home', compact('events'));
    }
}
