<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $posts = $user->posts()
            ->with(['user', 'comments', 'likes'])
            ->latest()
            ->paginate(10);
        $is_following = auth()->check() && auth()->id() !== $user->id ? auth()->user()->isFollowing($user) : false;

        return view('user.public-profile', compact('user', 'posts', 'is_following'));
    }
}