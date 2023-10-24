<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Item;

class ItemController extends Controller
{
    /**
     * Creates a new item.
     */
    public function create(Request $request, $card_id)
    {
        // Create a blank new item.
        $item = new Item();

        // Set item's card.
        $item->card_id = $card_id;

        // Check if the current user is authorized to create this item.
        $this->authorize('create', $item);

        // Set item details.
        $item->done = false;
        $item->description = $request->input('description');

        // Save the item and return it as JSON.
        $item->save();
        return response()->json($item);
    }

    /**
     * Updates the state of an individual item.
     */
    public function update(Request $request, $id)
    {
        // Find the item.
        $item = Item::find($id);

        // Check if the current user is authorized to update this item.
        $this->authorize('update', $item);

        // Update the done property of the item.
        $item->done = $request->input('done');

        // Save the item and return it as JSON.
        $item->save();
        return response()->json($item);
    }

    /**
     * Deletes a specific item.
     */
    public function delete(Request $request, $id)
    {
        // Find the item.
        $item = Item::find($id);

        // Check if the current user is authorized to delete this item.
        $this->authorize('delete', $item);

        // Delete the item and return it as JSON.
        $item->delete();
        return response()->json($item);
    }
}
