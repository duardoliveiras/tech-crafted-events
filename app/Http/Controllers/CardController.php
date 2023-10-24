<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\Card;

class CardController extends Controller
{
    /**
     * Show the card for a given id.
     */
    public function show(string $id): View
    {
        // Get the card.
        $card = Card::findOrFail($id);

        // Check if the current user can see (show) the card.
        $this->authorize('show', $card);  

        // Use the pages.card template to display the card.
        return view('pages.card', [
            'card' => $card
        ]);
    }

    /**
     * Shows all cards.
     */
    public function list()
    {
        // Check if the user is logged in.
        if (!Auth::check()) {
            // Not logged in, redirect to login.
            return redirect('/login');

        } else {
            // The user is logged in.

            // Get cards for user ordered by id.
            $cards = Auth::user()->cards()->orderBy('id')->get();

            // Check if the current user can list the cards.
            $this->authorize('list', Card::class);

            // The current user is authorized to list cards.

            // Use the pages.cards template to display all cards.
            return view('pages.cards', [
                'cards' => $cards
            ]);
        }
    }

    /**
     * Creates a new card.
     */
    public function create(Request $request)
    {
        // Create a blank new Card.
        $card = new Card();

        // Check if the current user is authorized to create this card.
        $this->authorize('create', $card);

        // Set card details.
        $card->name = $request->input('name');
        $card->user_id = Auth::user()->id;

        // Save the card and return it as JSON.
        $card->save();
        return response()->json($card);
    }

    /**
     * Delete a card.
     */
    public function delete(Request $request, $id)
    {
        // Find the card.
        $card = Card::find($id);

        // Check if the current user is authorized to delete this card.
        $this->authorize('delete', $card);

        // Delete the card and return it as JSON.
        $card->delete();
        return response()->json($card);
    }
}
