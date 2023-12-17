<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Ticket extends BaseModel
{
    use HasFactory;

    protected $table = 'ticket';
    protected $fillable = [
        'event_id',
        'user_id',
        'price_paid',
        'status',
        'created_at',
        'updated_at'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markTicketAsPending()
    {
        $this->status = 'PENDING';
        $this->save();
    }

    public function markTicketAsPaid()
    {
        $this->status = 'PAID';
        $this->save();
    }

    public function markTicketAsRead()
    {
        $this->status = 'READ';
        $this->save();
    }

    public function markTicketAsCanceled()
    {
        $this->status = 'CANCELED';
        $this->save();
    }

    public function markTicketAsError()
    {
        $this->status = 'ERROR';
        $this->save();
    }

    public function isValidTicket(): bool
    {
        return $this->status === 'PAID' || $this->status === 'READ';
    }

    public function canBuyTicket(): bool
    {
        // Check if the current user already has a ticket
        if ($this->user_id === Auth::id()) {
            // If the user already has a ticket, check its status
            $status = $this->status;
            // If the status is 'ERROR' or 'CANCELED', the user can buy a new ticket
            return $status === 'ERROR' || $status === 'CANCELED' || $status === 'PENDING';
        }
        // If the current user does not have a ticket, they can buy one
        return true;
    }

}
