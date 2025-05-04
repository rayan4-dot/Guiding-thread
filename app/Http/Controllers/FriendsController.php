<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class FriendsController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

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
        })->where('role_id', 2)->get();

        Log::error('FriendsController::index', [
            'user_id' => $user->id,
            'friends_count' => $friends->count(),
        ]);

        return view('user.friends', compact('friends'));
    }

    public function requests(Request $request)
    {
        $user = Auth::user();

        //  pending requests
        $requests = User::whereIn('id', function ($query) use ($user) {
            $query->select('user_id')
                ->from('connections')
                ->where('friend_id', $user->id)
                ->where('status', 'pending');
        })->where('role_id', 2)->get();

        Log::error('FriendsController::requests', [
            'user_id' => $user->id,
            'requests_count' => $requests->count(),
        ]);

        return view('user.requests', compact('requests'));
    }

    public function acceptRequest(Request $request, User $user)
    {
        $connection = Connection::where('user_id', $user->id)
            ->where('friend_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($connection) {
            $connection->update(['status' => 'accepted']);
            return redirect()->back()->with('success', 'Connection request accepted.');
        }

        return redirect()->back()->with('error', 'Request not found.');
    }

    public function rejectRequest(Request $request, User $user)
    {
        $connection = Connection::where('user_id', $user->id)
            ->where('friend_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($connection) {
            $connection->delete();
            return redirect()->back()->with('success', 'Connection request rejected.');
        }

        return redirect()->back()->with('error', 'Request not found.');
    }
}