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
        try {
            // Parse media_path if it's JSON
            $mediaPath = $post->media_path;
            $mediaType = $post->media_type;
            if (is_string($mediaPath) && str_starts_with($mediaPath, '[')) {
                $parsed = json_decode($mediaPath, true);
                if (is_array($parsed) && isset($parsed[0]['path'])) {
                    $mediaPath = ltrim($parsed[0]['path'], '/storage/');
                    $mediaType = $parsed[0]['type'] === 'video' ? 'video/mp4' : ($parsed[0]['type'] === 'image' ? 'image/jpeg' : $mediaType);
                }
            }

            return response()->json([
                'id' => $post->id,
                'content' => $post->content,
                'user' => [
                    'name' => $post->user->name ?? 'N/A',
                    'username' => $post->user->username ?? 'N/A',
                    'profile_picture' => $post->user->profile_picture ? Storage::url($post->user->profile_picture) : null,
                ],
                'hashtags' => $post->hashtags->pluck('name')->join(', ') ?: 'None',
                'created_at' => $post->created_at->format('M d, Y H:i'),
                'reactions_count' => $post->reactions_count ?? 0,
                'comments_count' => $post->comments_count ?? 0,
                'views' => $post->views ?? 0,
                'status' => $post->status ?? 'Active',
                'media_type' => $mediaType ?? 'N/A',
                'media_path' => $mediaPath ? Storage::url($mediaPath) : null,
                'comments' => $post->comments->map(function ($comment) {
                    return [
                        'user' => $comment->user->name ?? 'Unknown',
                        'avatar' => $comment->user->profile_picture ? Storage::url($comment->user->profile_picture) : null,
                        'contenu' => $comment->content ?? 'N/A',
                        'created_at' => $comment->created_at->format('M d, Y H:i') ?? 'N/A',
                    ];
                })->toArray(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch post details', ['post_id' => $post->id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch post details'], 500);
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