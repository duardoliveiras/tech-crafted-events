<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends BaseModel
{
    use HasFactory;
    protected $table = 'country';
    protected $keyType = 'string';
    protected $fillable = ['name', 'initials'];
    public $timestamps = false;

    public function states()
    {
        return $this->hasMany(State::class);
    }
}
