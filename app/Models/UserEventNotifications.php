<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserEventNotifications extends BaseModel
{
    use HasFactory;

    protected $table = 'userseventnotifications';
    protected $keyType = 'string';

    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'read',
        'notification_id',
    ];

    public static function getNotificationsByUserId($user_id)
    {
        return self::where('user_id', $user_id)->get();
    }
}