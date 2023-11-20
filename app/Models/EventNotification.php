<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class EventNotification extends BaseModel
{
    use HasFactory;

    protected $table = 'eventnotifications';
    protected $keyType = 'string';

    public $timestamps = false;
    protected $fillable = [
        'event_id',
        'notification_text',
    ];

}
