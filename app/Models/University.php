<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class University extends BaseModel
{
    protected $table = 'university';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['name', 'address', 'city_id', 'image_url'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
