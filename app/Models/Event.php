<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends BaseModel
{
    use HasFactory;

    protected $table = 'event';
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'start_tickets_qty',
        'current_tickets_qty',
        'current_price',
        'address',
        'category_id',
        'city_id',
        'owner_id',
        'image_url',
        'status',
        'created_at'
    ];
    protected $casts = [
        'current_price' => 'float',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function owner()
    {
        return $this->belongsTo(EventOrganizer::class, 'owner_id');
    }

    public function ticket()
    {
        return $this->hasMany(Ticket::class);
    }

    public function discussion()
    {
        return $this->hasOne(Discussion::class);
    }

    protected static function booted()
    {
        static::created(function ($event) {
            Discussion::create([
                'event_id' => $event->id
            ]);
        });
    }

    public function isFinished(): bool
    {
        return $this['status'] === EventStatus::Finished->value;
    }

    public function eventNotifications()
    {
        return $this->hasMany(EventNotification::class, 'event_id');
    }

    public function event_report()
    {
        return $this->hasMany(EventReport::class, 'event_id');
    }

    public function userCanBuyTicket(): bool
    {
        foreach ($this->ticket as $ticket) {
            // Check if the ticket belongs to the current user
            if ($ticket->user_id === auth()->id() && !$ticket->canBuyTicket()) {
                // If the user has a ticket and they cannot buy a new one, return false
                return false;
            }
        }
        return true;
    }

}
