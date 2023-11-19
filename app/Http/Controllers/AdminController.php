<?php

namespace App\Http\Controllers;

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
}
