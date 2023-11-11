<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function discussion(): BelongsTo
    {
        return $this->belongsTo(Discussion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, 'comment_id');
    }
}
