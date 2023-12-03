<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends BaseModel
{
    use HasFactory;

    protected $table = 'event';
    protected $keyType = 'string';
    public $timestamps = false;
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
        'status'
    ];
    protected $casts = [
        'current_price' => 'float',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
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
}
