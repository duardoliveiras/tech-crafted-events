<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'event';

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
}
