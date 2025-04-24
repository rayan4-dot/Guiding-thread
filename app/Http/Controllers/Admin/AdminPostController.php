<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Hashtag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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

        // post list with Filters
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
        }

        $posts = $query->orderByDesc('created_at')->paginate(6);

        // chart data
        $activity = $this->getActivityData();
        $engagement = $this->getEngagementData();
        $hashtag = $this->getHashtagData();
        $trends = $this->getTrendsData();

        return view('admin.posts', compact('stats', 'posts', 'activity', 'engagement', 'hashtag', 'trends'));
    }

    public function details(Post $post)
{

    try {

        $mediaItems = [];
        if (is_string($post->media_path) && str_starts_with($post->media_path, '[')) {
            $parsed = json_decode($post->media_path, true);
            if (is_array($parsed)) {
                foreach ($parsed as $item) {
                    if (isset($item['path'])) {
                        $path = ltrim($item['path'], '/storage/');
                        $fullPath = storage_path('app/public/' . $path);
                        Log::error('Checking media file', ['path' => $path, 'exists' => file_exists($fullPath)]);
                        $mediaItems[] = [
                            'path' => Storage::url($path), 
                            'type' => $item['type'] === 'video' ? 'video/mp4' : ($item['type'] === 'image' ? 'image/jpeg' : $post->media_type)
                        ];
                    }
                }
            }
        } else if ($post->media_path) {
            $path = ltrim($post->media_path, '/storage/');
            $fullPath = storage_path('app/public/' . $path);
            log::error('Checking media file', ['path' => $path, 'exists' => file_exists($fullPath)]);
            $mediaItems[] = [
                'path' => Storage::url($path),
                'type' => $post->media_type === 'video' ? 'video/mp4' : ($post->media_type === 'image' ? 'image/jpeg' : $post->media_type)
            ];
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
            'media' => $mediaItems,
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

    // public function details(Post $post)
    // {
        
    //     try {
    //         $post->load(['user', 'hashtags', 'comments'])
    //             ->loadCount(['reactions', 'comments']);

    //         return response()->json([
    //             'user' => [
    //                 'name' => $post->user->name,
    //                 'username' => $post->user->username ?? 'N/A',
    //                 'profile_picture' => $post->user->profile_picture ? Storage::url($post->user->profile_picture) : 'https://api.dicebear.com/6.x/avataaars/svg?seed=' . ($post->user->username ?? 'user' . $post->user->id),
    //             ],
    //             'content' => $post->content ?? 'N/A',
    //             'media_type' => $post->media_type,
    //             'media_path' => $post->media_path ? Storage::url($post->media_path) : null,
    //             'views' => $post->views,
    //             'reactions_count' => $post->reactions_count,
    //             'comments_count' => $post->comments_count,
    //             'hashtags' => $post->hashtags->pluck('name')->join(', ') ?: 'None',
    //             'created_at' => $post->created_at->format('M d, Y'),
    //             'status' => 'Active', // Placeholder; extend if status field exists
    //             'comments' => $post->comments->map(function ($comment) {
    //                 return [
    //                     'user' => $comment->user->name,
    //                     'username' => $comment->user->username ?? 'N/A',
    //                     'contenu' => $comment->contenu,
    //                     'created_at' => $comment->created_at->diffForHumans(),
    //                     'avatar' => $comment->user->profile_picture ? Storage::url($comment->user->profile_picture) : 'https://api.dicebear.com/6.x/avataaars/svg?seed=' . ($comment->user->username ?? 'user' . $comment->user->id),
    //                 ];
    //             }),
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error('Failed to fetch post details', ['post_id' => $post->id, 'error' => $e->getMessage()]);
    //         return response()->json(['error' => 'Failed to load post details'], 500);
    //     }
    // }

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