<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Category;

class Event extends Model
{
    use HasFactory;

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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
