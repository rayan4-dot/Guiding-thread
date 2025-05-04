<?php

namespace App\Http\View\Composers;

use App\Models\User;
use App\Models\Connection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PeopleToConnectComposer
{
    public function compose(View $view)
    {
        $userIdsToExclude = Auth::check()
            ? Connection::where(function ($query) {
                $query->where('user_id', Auth::id())
                      ->orWhere('friend_id', Auth::id());
            })->pluck('friend_id')
              ->merge(Connection::where('user_id', Auth::id())->pluck('friend_id'))
              ->merge([Auth::id()])
              ->unique()
            : collect([]);

        $peopleToConnect = User::whereNotIn('id', $userIdsToExclude)
            ->where('role_id', 2) // Non-admin users
            ->inRandomOrder()
            ->take(3)
            ->get();

        $view->with('peopleToConnect', $peopleToConnect);
    }
}