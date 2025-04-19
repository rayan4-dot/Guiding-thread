<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExploreController extends Controller
{
    public function index()
    {
        // Trending Posts (Top 3 by engagement)
        $trendingPosts = Post::with(['user', 'likes', 'comments'])
            ->withCount(['hashtags'])
            ->where('created_at', '>=', now()->subDays(7))
            ->get()
            ->map(function ($post) {
                $engagement = ($post->views ?? 0) * 1 +
                              $post->likes->count() * 2 +
                              $post->comments->count() * 3 +
                              $post->hashtags_count * 1;
                return ['post' => $post, 'engagement' => $engagement];
            })
            ->sortByDesc('engagement')
            ->take(3)
            ->pluck('post');

        // People to Follow (3 users not connected)
        $userIdsToExclude = Auth::check()
            ? Auth::user()->connections()->pluck('friend_id')->merge([Auth::id()])
            : collect([]);
        $peopleToFollow = User::whereNotIn('id', $userIdsToExclude)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('user.explore', compact('trendingPosts', 'peopleToFollow'));
    }
}


