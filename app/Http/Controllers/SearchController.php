<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Hashtag;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $results = ['posts' => [], 'users' => [], 'hashtags' => []];

        if ($query) {
            // Normalize query for hashtag search
            $hashtagQuery = ltrim($query, '#');

            // Find users matching the query
            $matchingUsers = User::where('name', 'like', "%{$query}%")
                ->orWhere('username', 'like', "%{$query}%")
                ->pluck('id');

            // Find hashtags matching the query
            $matchingHashtags = Hashtag::where('name', 'like', "%{$hashtagQuery}%")
                ->pluck('id');

            // Search posts by content, hashtags, or user
            $results['posts'] = Post::with(['user', 'hashtags'])
                ->where(function ($q) use ($query, $matchingUsers, $matchingHashtags) {
                    $q->where('content', 'like', "%{$query}%")
                      ->orWhereIn('user_id', $matchingUsers)
                      ->orWhereHas('hashtags', function ($q) use ($matchingHashtags) {
                          $q->whereIn('hashtags.id', $matchingHashtags);
                      });
                })
                ->latest()
                ->paginate(10, ['*'], 'posts_page');

            // Search users by name or username
            $results['users'] = User::where('name', 'like', "%{$query}%")
                ->orWhere('username', 'like', "%{$query}%")
                ->paginate(10, ['*'], 'users_page');

            // Search hashtags
            $results['hashtags'] = Hashtag::where('name', 'like', "%{$hashtagQuery}%")
                ->pluck('name')
                ->take(10)
                ->values();
        }

        return view('user.result', compact('results', 'query'));
    }
}