<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Hashtag;
use App\Models\Reaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        $totalUsers = User::count();
        $newUsersWeek = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $userGrowthPercent = $totalUsers ? round(($newUsersWeek / $totalUsers) * 100, 1) : 0;

        $totalPosts = Post::count();
        $postsLastMonth = Post::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $postGrowthPercent = $totalPosts ? round(($postsLastMonth / $totalPosts) * 100, 1) : 0;

        $totalComments = Comment::count();
        $commentsLastMonth = Comment::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $commentGrowthPercent = $totalComments ? round(($commentsLastMonth / $totalComments) * 100, 1) : 0;

        $activeHashtags = Hashtag::count();
        $topHashtag = Hashtag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->first()?->name ?? 'N/A';

        $stats = [
            'total_users' => $totalUsers,
            'new_users_week' => $newUsersWeek,
            'user_growth_percent' => $userGrowthPercent,
            'total_posts' => $totalPosts,
            'post_growth_percent' => $postGrowthPercent,
            'total_comments' => $totalComments,
            'comment_growth_percent' => $commentGrowthPercent,
            'active_hashtags' => $activeHashtags,
            'top_hashtag' => $topHashtag,
        ];

        $topUsers = User::withCount([
            'posts',
            'connections' => fn($query) => $query->where('status', 'accepted')
        ])
            ->orderByDesc('posts_count')
            ->take(3)
            ->get();

        $recentActivities = $this->getRecentActivities();


        $chartData = $this->getChartData();

        return view('admin.dashboard', compact('stats', 'topUsers', 'recentActivities', 'chartData'));
    }

    protected function getRecentActivities()
    {
        $activities = [];


        $newPosts = Post::with('user')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function ($post) {
                return [
                    'type' => 'post',
                    'user' => $post->user,
                    'description' => 'created a new post',
                    'timestamp' => $post->created_at,
                    'post_id' => $post->id,
                ];
            });


        $highEngagementPosts = Post::with('user')
            ->withCount(['reactions', 'comments'])
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->orderByRaw('(reactions_count + comments_count) DESC')
            ->take(3)
            ->get()
            ->map(function ($post) {
                return [
                    'type' => 'engagement',
                    'user' => $post->user,
                    'description' => "post received high engagement ({$post->reactions_count} likes, {$post->comments_count} comments)",
                    'timestamp' => $post->created_at,
                    'post_id' => $post->id,
                ];
            });


        $newComments = Comment::with('user', 'post')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function ($comment) {
                return [
                    'type' => 'comment',
                    'user' => $comment->user,
                    'description' => 'commented on a post',
                    'timestamp' => $comment->created_at,
                    'post_id' => $comment->post_id,
                ];
            });


        $activities = collect($newPosts)->merge($newComments)->merge($highEngagementPosts)
            ->sortByDesc('timestamp')
            ->take(3)
            ->values();

        return $activities;
    }

    protected function getChartData()
    {
        $ranges = ['7', '30', '90', 'year'];
        $data = [];

        foreach ($ranges as $range) {
            if ($range === 'year') {
                $start = Carbon::now()->startOfYear();
                $labels = collect(range(0, 11))->map(fn($i) => Carbon::now()->startOfYear()->addMonths($i)->format('M'));
            } else {
                $start = Carbon::now()->subDays($range);
                $labels = collect(range(0, (int)$range - 1))->map(fn($i) => $start->copy()->addDays($i)->format('M d'));
            }


            $posts = Post::selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as date, COUNT(*) as count")
                ->where('created_at', '>=', $start)
                ->groupBy('date')
                ->pluck('count', 'date')
                ->toArray();

            $comments = Comment::selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as date, COUNT(*) as count")
                ->where('created_at', '>=', $start)
                ->groupBy('date')
                ->pluck('count', 'date')
                ->toArray();

            $users = User::selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as date, COUNT(*) as count")
                ->where('created_at', '>=', $start)
                ->groupBy('date')
                ->pluck('count', 'date')
                ->toArray();

            $activity = [
                'labels' => $labels,
                'posts' => $labels->map(fn($label, $i) => $posts[$start->copy()->addDays($i)->format('Y-m-d')] ?? 0),
                'comments' => $labels->map(fn($label, $i) => $comments[$start->copy()->addDays($i)->format('Y-m-d')] ?? 0),
                'users' => $labels->map(fn($label, $i) => $users[$start->copy()->addDays($i)->format('Y-m-d')] ?? 0),
            ];


            $content = [
                Post::where('media_type', 'image')->count(),
                Post::where('media_type', 'video')->count(),
                Post::where('media_type', 'link')->count(),
                Post::where('media_type', 'none')->count(),
            ];


            $engagement = [
                Reaction::count(),
                Comment::count(),
                Post::sum('views'),
            ];


            $tags = Hashtag::withCount('posts')
                ->orderByDesc('posts_count')
                ->take(5)
                ->pluck('posts_count', 'name')
                ->toArray();

            $data[$range] = [
                'activity' => $activity,
                'content' => $content,
                'engagement' => $engagement,
                'tags' => $tags,
            ];
        }

        return $data;
    }
}