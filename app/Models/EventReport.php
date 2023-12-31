<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventReport extends BaseModel
{
    use HasFactory;

    protected $table = "event_report";
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'event_id',
        'user_id',
        'reason',
        'description',
        'analyzed'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event(){
        return $this->belongsTo(Event::class, 'event_id');
    }


}
