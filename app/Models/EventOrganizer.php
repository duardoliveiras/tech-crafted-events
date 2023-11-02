<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventOrganizer extends Model
{
    use HasFactory;
    protected $fillable = [
        'legal_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'owner_id');
    }
}
