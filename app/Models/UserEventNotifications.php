<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEventNotifications extends Model
{
    use HasFactory;

    protected $table = 'userseventnotifications';

    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'read',
        'notification_id',
    ];

    public function eventNotification()
    {
        return $this->belongsTo(EventNotification::class);
    }
}