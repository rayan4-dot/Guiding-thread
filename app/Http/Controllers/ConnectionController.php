<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Connection;
use App\Models\User;

class ConnectionController extends Controller
{
  
    public function sendRequest(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot send a connection request to yourself.');
        }

        $existing = Connection::where('user_id', Auth::id())
            ->where('friend_id', $user->id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Connection request already sent or you are already connected.');
        }

        Connection::create([
            'user_id' => Auth::id(),
            'friend_id' => $user->id,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Connection request sent.');
    }

    public function removeConnection(Request $request, User $user)
    {
        $connection = Connection::where(function ($query) use ($user) {
            $query->where('user_id', Auth::id())->where('friend_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('friend_id', Auth::id());
        })->where('status', 'accepted')->first();

        if (!$connection) {
            return redirect()->back()->with('error', 'No connection found.');
        }

        $connection->delete();

        return redirect()->back()->with('success', 'Connection removed.');
    }
}