<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventReport extends Model
{
    use HasFactory;

    protected $table = "event_report";
    public $timestamps = false;
    protected $fillable = [
        'event_id',
        'user_id',
        'reason',
        'description'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event(){
        return $this->belongsTo(Event::class, 'event_id');
    }


}
