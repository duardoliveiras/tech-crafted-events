<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discussion extends BaseModel
{
    use HasFactory;

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

    public function commentsOrderedByVotes()
    {
        return $this->hasMany(Comment::class)
            ->where('is_deleted', false) // Exclude deleted comments
            ->get()
            ->sortByDesc(function ($comment) {
                return $comment->votes()->sum('vote_type');
            });
    }
}
