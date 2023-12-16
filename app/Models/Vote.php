<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends BaseModel
{
    use HasFactory;

    protected $table = 'vote';
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
