<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostMedia extends Model
{
    protected $fillable = ['post_id', 'media_path', 'media_type'];

}
