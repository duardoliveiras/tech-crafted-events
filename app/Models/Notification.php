<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Notification extends BaseModel
{
    use HasFactory;

    protected $table = 'notification';
    protected $fillable = [
        'text',
        'notificationtype',
        'user_id',
        'creadted_at',
        'read',
        'event_id'
    ];

    public static function getNotificationsByUserId($user_id)
    {
        return self::where('user_id', $user_id)->get();
    }

    public function events()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

}
