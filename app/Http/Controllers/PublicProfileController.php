<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PublicProfileController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        //  mutual friends
        $friends = User::whereIn('id', function ($query) use ($user) {
            $query->select('friend_id')
                ->from('connections')
                ->where('user_id', $user->id)
                ->where('status', 'accepted')
                ->union(
                    Connection::select('user_id')
                        ->where('friend_id', $user->id)
                        ->where('status', 'accepted')
                );
        })->where('role_id', 2)->take(8)->get();

        // count total friends
        $friendsCount = Connection::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)->orWhere('friend_id', $user->id);
        })->where('status', 'accepted')->count();

        // display posts
        $posts = $user->posts()
            ->with(['user', 'comments', 'likes'])
            ->latest()
            ->paginate(10);

        $isOwnProfile = Auth::check() && Auth::user()->id === $user->id;
        $isFriend = Auth::check() && Connection::where(function ($query) use ($user) {
            $query->where('user_id', Auth::id())->where('friend_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('friend_id', Auth::id());
        })->where('status', 'accepted')->exists();

        Log::info('PublicProfileController::show', [
            'user_id' => $user->id,
            'username' => $username,
            'friends_count' => $friends->count(),
            'total_friends' => $friendsCount,
            'posts_count' => $posts->count(),
            'is_own_profile' => $isOwnProfile,
            'is_friend' => $isFriend,
        ]);

        return view('user.public-profile', compact('user', 'friends', 'friendsCount', 'posts', 'isOwnProfile', 'isFriend'));
    }
}