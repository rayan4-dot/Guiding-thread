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
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(10);

        return view('user.public-profile', compact('user', 'posts'));
    }
}