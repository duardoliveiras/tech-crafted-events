<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends BaseModel
{
    protected $table = 'vote';
    public $timestamps = false;

    use HasFactory;
    protected $fillable = [
        'vote_type',
        'voted_at',
        'user_id',
        'comment_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
