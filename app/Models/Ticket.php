<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends BaseModel
{
    use HasFactory;

    protected $table = 'ticket';
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'event_id',
        'user_id',
        'price_paid',
        'is_used',
    ];
    protected $attributes = [
        'is_used' => false,
    ];


    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
