<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Hashtag;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'shared_link',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->hasMany(PostMedia::class);
    }

    public function hashtags()
{
    return $this->belongsToMany(Hashtag::class, 'hashtag_post');
}
}