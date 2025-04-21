<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Hashtag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminPostController extends Controller
{
    public function index(Request $request)
    {
        // Stats
        $totalPosts = Post::count();
        $newPostsWeek = Post::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $postGrowthPercent = $totalPosts ? round(($newPostsWeek / $totalPosts) * 100, 1) : 0;
        $activePosts = Post::count();

        $stats = [
            'total_posts' => $totalPosts,
            'new_posts_week' => $newPostsWeek,
            'post_growth_percent' => $postGrowthPercent,
            'active_posts' => $activePosts,
            'active_posts_percent' => 100,
        ];

        // Post List with Filters
        $query = Post::with(['user', 'hashtags'])
            ->withCount(['reactions', 'comments']);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhereHas('hashtags', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('username', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->query('status')) {
            if ($status === 'active') {
                $query->whereNotNull('id');
            }
            // Note: 'reported' status not implemented in DB; placeholder
        }

        $posts = $query->orderByDesc('created_at')->paginate(6);

        // Chart Data
        $activity = $this->getActivityData();
        $engagement = $this->getEngagementData();
        $hashtag = $this->getHashtagData();
        $trends = $this->getTrendsData();

        return view('admin.posts', compact('stats', 'posts', 'activity', 'engagement', 'hashtag', 'trends'));
    }

    public function details(Post $post)
    {

        dd([
            'post' => $post->toArray(),
            'post_exists' => $post->exists,
            'post_id' => request()->route('post'),
            'user' => $post->user ? $post->user->toArray() : null,
            'hashtags' => $post->hashtags->toArray(),
            'comments' => $post->comments->toArray(),
            'reactions_count' => $post->reactions_count,
            'comments_count' => $post->comments_count,
        ]);
        
        try {
            $post->load(['user', 'hashtags', 'comments'])
                ->loadCount(['reactions', 'comments']);

            return response()->json([
                'user' => [
                    'name' => $post->user->name,
                    'username' => $post->user->username ?? 'N/A',
                    'profile_picture' => $post->user->profile_picture ? Storage::url($post->user->profile_picture) : 'https://api.dicebear.com/6.x/avataaars/svg?seed=' . ($post->user->username ?? 'user' . $post->user->id),
                ],
                'content' => $post->content ?? 'N/A',
                'media_type' => $post->media_type,
                'media_path' => $post->media_path ? Storage::url($post->media_path) : null,
                'shared_link' => $post->shared_link,
                'views' => $post->views,
                'reactions_count' => $post->reactions_count,
                'comments_count' => $post->comments_count,
                'hashtags' => $post->hashtags->pluck('name')->join(', ') ?: 'None',
                'created_at' => $post->created_at->format('M d, Y'),
                'status' => 'Active', // Placeholder; extend if status field exists
                'comments' => $post->comments->map(function ($comment) {
                    return [
                        'user' => $comment->user->name,
                        'username' => $comment->user->username ?? 'N/A',
                        'contenu' => $comment->contenu,
                        'created_at' => $comment->created_at->diffForHumans(),
                        'avatar' => $comment->user->profile_picture ? Storage::url($comment->user->profile_picture) : 'https://api.dicebear.com/6.x/avataaars/svg?seed=' . ($comment->user->username ?? 'user' . $comment->user->id),
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch post details', ['post_id' => $post->id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to load post details'], 500);
        }
    }

    public function destroy(Post $post)
    {
        try {
            $post->delete();
            return redirect()->route('admin.posts')->with('success', 'Post deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete post', ['post_id' => $post->id, 'error' => $e->getMessage()]);
            return redirect()->route('admin.posts')->with('error', 'Failed to delete post: ' . $e->getMessage());
        }
    }

    protected function getActivityData()
    {
        $start = Carbon::now()->subDays(30);
        $labels = collect(range(0, 29))->map(fn($i) => $start->copy()->addDays($i)->format('M d'));

        $posts = Post::selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as date, COUNT(*) as count")
            ->where('created_at', '>=', $start)
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        return [
            'labels' => $labels,
            'posts' => $labels->map(fn($label, $i) => $posts[$start->copy()->addDays($i)->format('Y-m-d')] ?? 0),
        ];
    }

    protected function getEngagementData()
    {
        return [
            'data' => [
                \App\Models\Reaction::count(),
                \App\Models\Comment::count(),
                \App\Models\Post::sum('views'),
            ],
        ];
    }

    protected function getHashtagData()
    {
        return \App\Models\Hashtag::withCount('posts')
            ->orderByDesc('posts_count')
            ->take(5)
            ->pluck('posts_count', 'name')
            ->toArray();
    }

    protected function getTrendsData()
    {
        $start = Carbon::now()->subDays(30);
        $labels = collect(range(0, 29))->map(fn($i) => $start->copy()->addDays($i)->format('M d'));

        $topHashtags = \App\Models\Hashtag::withCount('posts')
            ->orderByDesc('posts_count')
            ->take(5)
            ->pluck('name');

        $data = [];
        foreach ($topHashtags as $hashtag) {
            $counts = \App\Models\Post::whereHas('hashtags', fn($q) => $q->where('name', $hashtag))
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as date, COUNT(*) as count")
                ->where('created_at', '>=', $start)
                ->groupBy('date')
                ->pluck('count', 'date')
                ->toArray();

            $data[$hashtag] = $labels->map(fn($label, $i) => $counts[$start->copy()->addDays($i)->format('Y-m-d')] ?? 0);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}