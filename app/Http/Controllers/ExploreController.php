<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Hashtag;
use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExploreController extends Controller
{
    public function index()
    {
        //  trending posts (last 7 days
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

        //  trending hashtags (top 5 by post count
        $trendingHashtags = Hashtag::withCount(['posts' => function ($query) {
            $query->where('posts.created_at', '>=', now()->subDays(7));
        }])
            ->orderByDesc('posts_count')
            ->take(5)
            ->get();

        //  people to connect 
        $userIdsToExclude = Auth::check()
            ? Connection::where(function ($query) {
                $query->where('user_id', Auth::id())
                      ->orWhere('friend_id', Auth::id());
            })->pluck('friend_id')
              ->merge(Connection::where('user_id', Auth::id())->pluck('friend_id'))
              ->merge([Auth::id()])
              ->unique()
            : collect([]);

        $peopleToFollow = User::whereNotIn('id', $userIdsToExclude)
            ->where('role_id', 2) 
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('user.explore', compact('trendingPosts', 'trendingHashtags', 'peopleToFollow'));
    }
}