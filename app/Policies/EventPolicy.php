<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class EventPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function create(User $user): bool
    {
        return !$user->isAdmin();
    }
    public function update(User $user, Event $event): bool
    {
        return $user->isAdmin() || $event->owner->user_id === $user->id;
    }

    public function showList(User $user, Event $event): bool
    {
        return $user->isAdmin() || $event->owner->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        $isOwner = $event->owner->user_id == $user->id;
        Log::info("Policy check for deleting event: User ID {$user->id} - Event Owner User ID: {$event->owner->user_id} - Is Owner: " . ($isOwner ? 'Yes' : 'No'));

        return $user->isAdmin() || $isOwner;
    }
}
