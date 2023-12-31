<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventOrganizer extends BaseModel
{
    use HasFactory;

    protected $table = 'eventorganizer';
    protected $fillable = [
        'legal_id',
        'user_id',
        'stripe_account_id'
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
