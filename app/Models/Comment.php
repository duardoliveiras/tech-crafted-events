<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $table = 'comment';

    protected $casts = [
        'commentedat' => 'datetime',
    ];
    protected $fillable = [
        'text',
        'commentedat',
        'user_id',
        'discussion_id'
    ];

    public function discussion(){
        return $this -> belongsTo(Discussion::class);
    }
    public function user(){
        return $this -> belongsTo(User::class);
    }
}
