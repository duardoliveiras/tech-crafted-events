<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discussion extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $timestamps = false;
    protected $table = 'discussion';
    protected $fillable = ['event_id'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function comment(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function commentsWithUserVotes($userId)
    {
        return Comment::whereHas('votes', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->where('discussion_id', $this->id) // Assuming 'id' is the primary key of Discussion
            ->get();
    }
}
