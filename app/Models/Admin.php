<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends BaseModel
{
    use HasFactory;

    protected $table = 'admin';
    protected $keyType = 'string';
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
