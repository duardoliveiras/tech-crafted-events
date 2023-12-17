<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReport extends BaseModel
{
    use HasFactory;

    protected $table = "comment_report";
    protected $keyType = "string";
    public $timestamps = false;

    public $fillable = [
        'comment_id',
        'user_id',
        'reason',
        'description',
        'analyzed'
    ];


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function comment(){
        return $this->belongsTo(Comment::class, 'comment_id');
    }

}
