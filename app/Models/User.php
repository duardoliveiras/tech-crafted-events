<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

// Added to define Eloquent relationships.

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps = false;
    protected $table = 'users';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'birthdate',
        'email',
        'password',
        'phone',
        'is_deleted',
        'is_banned',
        'university_id',
        'image_url',
        'provider',
        'provider_token'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function event()
    {
        return $this->hasMany(Event::class, 'owner_id', 'id');
    }

    public function ticket()
    {
        return $this->hasMany(Ticket::class);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function isAdmin()
    {
        return $this->admin()->exists();
    }

    public function delete()
    {
        // Mark the user as deleted
        $this->is_deleted = true;

        // Anonymize personal information
        $this->name = '[User Deleted]';
        $this->email = '';
        $this->password = bcrypt(Str::random(10)); // Reset password with a random hash
        $this->phone = '';
        $this->birthdate = now();
        $this->image_url = '';

        $this->save();
    }


    public function university()
    {
        return $this->belongsTo(University::class, 'university_id');
    }

    public function eventOrganizer()
    {
        return $this->hasMany(EventOrganizer::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(UserEventNotifications::class, 'user_id');
    }

    public function votesForDiscussion(Discussion $discussion)
    {
        return Vote::whereIn('comment_id', $discussion->comment()->pluck('id'))
            ->where('user_id', $this->id)
            ->pluck('vote_type', 'comment_id');
    }

    public function event_report()
    {
        return $this->hasMany(EventReport::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

}