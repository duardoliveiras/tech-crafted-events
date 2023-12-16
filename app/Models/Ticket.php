<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
