<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends BaseModel
{
    use HasFactory;

    protected $keyType = 'string';
    public $timestamps = false;
    protected $table = 'comment';

    protected $casts = [
        'commented_at' => 'datetime',
    ];
    protected $fillable = [
        'text',
        'commented_at',
        'user_id',
        'discussion_id'
    ];

    public function discussion(): BelongsTo
    {
        return $this->belongsTo(Discussion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isOwner(): bool
    {
        return $this->discussion->event->owner->user->id === $this->user->id;
    }

    public function isAdmin(): bool
    {
        return $this->user->isAdmin();
    }


    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, 'comment_id');
    }
}