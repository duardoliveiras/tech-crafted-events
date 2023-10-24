<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Card;

use Illuminate\Support\Facades\Auth;

class CardPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if a given card can be shown to a user.
     */
    public function show(User $user, Card $card): bool
    {
        // Only a card owner can see a card.
        return $user->id === $card->user_id;
    }

    /**
     * Determine if all cards can be listed by a user.
     */
    public function list(User $user): bool
    {
        // Any (authenticated) user can list its own cards.
        return Auth::check();
    }

    /**
     * Determine if a card can be created by a user.
     */
    public function create(User $user): bool
    {
        // Any user can create a new card.
        return Auth::check();
    }

    /**
     * Determine if a card can be deleted by a user.
     */
    public function delete(User $user, Card $card): bool
    {
      // Only a card owner can delete it.
      return $user->id === $card->user_id;
    }
}
