<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow(Request $request, User $user)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to follow users.');
        }

        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Cannot follow yourself.');
        }

        $existingConnection = Auth::user()->connections()->where('friend_id', $user->id)->first();

        if ($existingConnection) {
            return redirect()->back()->with('error', 'Already following.');
        }

        Auth::user()->connections()->create([
            'friend_id' => $user->id,
            'status' => 'accepted', // Change to 'pending' if approval needed
        ]);

        return redirect()->route('public-profile.show', $user->username)->with('success', 'Followed successfully.');
    }

    public function unfollow(Request $request, User $user)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to unfollow users.');
        }

        $connection = Auth::user()->connections()->where('friend_id', $user->id)->first();

        if (!$connection) {
            return redirect()->back()->with('error', 'Not following.');
        }

        $connection->delete();

        return redirect()->route('public-profile.show', $user->username)->with('success', 'Unfollowed successfully.');
    }
}