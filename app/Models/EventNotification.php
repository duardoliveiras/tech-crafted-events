<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventNotification extends Model
{
    use HasFactory;

    protected $table = 'eventnotifications';

    public $timestamps = false;
    protected $fillable = [
        'event_id',
        'notification_text',
    ];

    public function userEventNotifications()
    {
        return $this->hasMany(UserEventNotifications::class);
    }

}
