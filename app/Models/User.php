<?php

namespace App\Models;

use App\Models\Connection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
                   ->where('id', '!=', Auth::user()->id) 
                   ->take(5) 
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

    public function isFriend(User $user)
    {
        return Connection::where(function ($query) use ($user) {
            $query->where('user_id', $this->id)->where('friend_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('friend_id', $this->id);
        })->where('status', 'accepted')->exists();
    }

    public function hasPendingConnection(User $user)
    {
        return $this->connections()
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

}

