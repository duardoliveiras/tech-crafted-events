<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'event';
    protected $keyType = 'string';
    protected $dates = ['startdate', 'enddate'];

    protected $fillable = [
        'name',
        'description',
        'startdate',
        'enddate',
        'startticketsqty',
        'currentticketsqty',
        'currentprice',
        'address',
        'category_id',
        'city_id',
        'owner_id'
    ];
    public $timestamps = false;
    protected $casts = [
        'currentprice' => 'float',
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
                'event_id' => $event->id,

            ]);
        });
    }
}
