<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Notification extends BaseModel
{
    use HasFactory;

    protected $table = 'notification';
    protected $keyType = 'string';

    public $timestamps = false;
    protected $fillable = [
        'id',
        'text',
        'expiresAt',
        'notificationType',
        'user_id',
    ];

    public static function getNotificationsByUserId($user_id)
    {
        return self::where('user_id', $user_id)->get();
    }
    
}
