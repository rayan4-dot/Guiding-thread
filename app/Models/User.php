<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Connection;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'profile_picture',
        'email',
        'phone',
        'password',
        'cover_photo', 

        'bio',
        'is_active',
        'role_id',

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',

    ];

    public function role()
    {

        return $this->belongsTo(Role::class);

    }

    public static function getNonAdminUsers()
    {
        return self::where('role_id', 2)
                   ->where('id', '!=', auth()->id()) // Exclude the current user
                   ->take(5) // Limit to 5 users for the "Friends" section
                   ->get();
    }


    public function posts()

    {

        return $this->hasMany(Post::class);

    }
    
    public function connections()
    {
        return $this->hasMany(Connection::class, 'user_id');
    }

    public function isFollowing($user)
    {
        return $this->connections()
            ->where('friend_id', $user->id)
            ->where('status', 'accepted')
            ->exists();
    }
}

