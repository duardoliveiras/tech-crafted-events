<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $table = 'discussion';
    protected $fillable = ['event_id'];
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }
}
