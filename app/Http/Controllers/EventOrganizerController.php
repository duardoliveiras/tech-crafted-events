<?php

namespace App\Http\Controllers;

use App\Models\EventOrganizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventOrganizerController extends Controller
{
    public function show(Request $request)
    {
        return view('layouts.event_organizer.create');
    }

    public function create($legalId, $stripeId)
    {
        if($legalId && $stripeId) {
            EventOrganizer::create([
                'user_id' => Auth::id(),
                'legal_id' => $legalId,
                'stripe_account_id' => $stripeId,
            ]);
        }
     }
}
