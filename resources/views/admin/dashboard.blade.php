@extends('layouts.admin-layout')

@section('title', 'Dashboard')

@section('navbar')
    <div class="navbar bg-base-200 shadow-lg">
        <div class="flex-none lg:hidden">
            <label for="my-drawer" class="btn btn-square btn-ghost">
                <i class="fas fa-bars"></i>
            </label>
        </div>
        <div class="flex-1">
            <span class="text-xl font-bold px-2">SocialAdmin</span>
        </div>
        <div class="flex-none gap-2">
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-ghost btn-circle">
                    <div class="indicator">
                        <i class="fas fa-bell text-lg"></i>
                        <span class="badge badge-sm badge-primary indicator-item">8</span>
                    </div>
                </label>
                <div tabindex="0" class="mt-3 z-[1] card card-compact dropdown-content w-52 bg-base-200 shadow">
                    <div class="card-body">
                        <span class="font-bold text-lg">8 Notifications</span>
                        <span class="text-info">3 new user reports</span>
                        <span class="text-info">5 new posts flagged</span>
                        <div class="card-actions">
                            <button class="btn btn-primary btn-block">View all</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                    <div class="w-10 rounded-full">
                        <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=admin" alt="Admin Avatar" />
                    </div>
                </label>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-200 rounded-box w-52">
                    <li><a>Profile</a></li>
                    <li><a>Settings</a></li>
                    <li><a>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
<main class="flex-1 overflow-y-auto p-4 lg:p-6 bg-base-100">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold">Dashboard Overview</h1>
            <p class="text-sm opacity-70 mt-1">Real-time insights for {{ now()->format('F d, Y') }}</p>
        </div>
        <div class="mt-4 md:mt-0 flex gap-2">
            <div class="join">
                <select id="timeRange" class="select select-bordered join-item" onchange="updateCharts()">
                    <option value="7">Last 7 days</option>
                    <option value="30">Last 30 days</option>
                    <option value="90">Last 90 days</option>
                    <option value="year">This year</option>
                </select>
                <button class="btn btn-primary join-item">Export Data</button>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="stat bg-base-200 rounded-box shadow hover:shadow-lg transition-shadow">
            <div class="stat-figure text-primary">
                <i class="fas fa-users text-3xl"></i>
            </div>
            <div class="stat-title">Total Users</div>
            <div class="stat-value text-primary">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-desc">↗︎ {{ number_format($stats['new_users_week']) }} ({{ $stats['user_growth_percent'] }}%) this week</div>
        </div>
        <div class="stat bg-base-200 rounded-box shadow hover:shadow-lg transition-shadow">
            <div class="stat-figure text-secondary">
                <i class="fas fa-file-alt text-3xl"></i>
            </div>
            <div class="stat-title">Posts</div>
            <div class="stat-value text-secondary">{{ number_format($stats['total_posts']) }}</div>
            <div class="stat-desc">↗︎ {{ $stats['post_growth_percent'] }}% from last month</div>
        </div>
        <div class="stat bg-base-200 rounded-box shadow hover:shadow-lg transition-shadow">
            <div class="stat-figure text-accent">
                <i class="fas fa-comment text-3xl"></i>
            </div>
            <div class="stat-title">Comments</div>
            <div class="stat-value text-accent">{{ number_format($stats['total_comments']) }}</div>
            <div class="stat-desc">↗︎ {{ $stats['comment_growth_percent'] }}% from last month</div>
        </div>
        <div class="stat bg-base-200 rounded-box shadow hover:shadow-lg transition-shadow">
            <div class="stat-figure text-info">
                <i class="fas fa-hashtag text-3xl"></i>
            </div>
            <div class="stat-title">Active Tags</div>
            <div class="stat-value text-info">{{ number_format($stats['active_hashtags']) }}</div>
            <div class="stat-desc">Top: {{ $stats['top_hashtag'] }}</div>
        </div>
        <div class="stat bg-base-200 rounded-box shadow hover:shadow-lg transition-shadow">
            <div class="stat-figure text-error">
                <i class="fas fa-flag text-3xl"></i>
            </div>
            <div class="stat-title">Reports</div>
            <div class="stat-value text-error">89</div>
            <div class="stat-desc">12 new today</div>
        </div>
    </div>

    <!-- Activity Overview -->
    <div class="card bg-base-200 shadow-xl mb-6">
        <div class="card-body">
            <div class="flex justify-between items-center">
                <h2 class="card-title">Platform Activity</h2>
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-ghost btn-sm"><i class="fas fa-ellipsis-v"></i></label>
                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-300 rounded-box w-52">
                        <li><a>Export Chart</a></li>
                    </ul>
                </div>
            </div>
            <div class="h-80">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity, Top Users, and Recent Reports -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Recent Activity (Frontend-Only) -->
       <!-- Recent Activity -->
<div class="card bg-base-200 shadow-xl">
    <div class="card-body">
        <h2 class="card-title mb-4">Recent Activity</h2>
        <div class="space-y-4 max-h-96 overflow-y-auto">
            @forelse($recentActivities as $activity)
                <div class="flex items-start gap-4">
                    <div class="avatar">
                        <div class="w-10 rounded-full">
                            <img src="{{ $activity['user']->profile_picture ? Storage::url($activity['user']->profile_picture) : 'https://api.dicebear.com/6.x/avataaars/svg?seed=' . ($activity['user']->username ?? 'user' . $activity['user']->id) }}" alt="User Avatar" />
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <p class="font-bold">{{ $activity['user']->name }}</p>
                            <span class="text-xs opacity-70">{{ $activity['timestamp']->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm">{{ $activity['description'] }}</p>
                        <div class="flex justify-between items-center mt-2">
                            <div class="badge {{ $activity['type'] === 'post' ? 'badge-info' : ($activity['type'] === 'comment' ? 'badge-accent' : 'badge-success') }}">
                                {{ ucfirst($activity['type']) }}
                            </div>
                            <a href="{{ route('admin.posts.show', $activity['post_id']) }}" class="btn btn-xs btn-outline">View Post</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm opacity-70">No recent activity.</p>
            @endforelse
        </div>
        <div class="card-actions justify-end mt-4">
            <button class="btn btn-primary btn-sm">View All</button>
        </div>
    </div>
</div>

        <!-- Top Users -->
        <div class="card bg-base-200 shadow-xl">
            <div class="card-body">
                <h2 class="card-title mb-4">Top Users</h2>
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Posts</th>
                                <th>Connections</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topUsers as $user)
                                <tr class="hover">
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar">
                                                <div class="w-8 rounded-full">
                                                    <img src="{{ $user->profile_picture ? Storage::url($user->profile_picture) : 'https://api.dicebear.com/6.x/avataaars/svg?seed=' . $user->username }}" alt="User Avatar" />
                                                </div>
                                            </div>
                                            <div class="font-bold">{{ $user->name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $user->posts_count }}</td>
                                    <td>{{ number_format($user->connections_count) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-actions justify-end mt-4">
                    <a href="{{ route('admin.users') }}" class="btn btn-primary btn-sm">View All</a>
                </div>
            </div>
        </div>

        <!-- Recent Reports (Frontend-Only) -->
        <div class="card bg-base-200 shadow-xl">
            <div class="card-body">
                <h2 class="card-title mb-4">Recent Reports</h2>
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    <div class="flex items-start gap-4">
                        <div class="avatar">
                            <div class="w-10 rounded-full">
                                <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user6" alt="User Avatar" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <p class="font-bold">Sophia L.</p>
                                <span class="text-xs opacity-70">1h ago</span>
                            </div>
                            <p class="text-sm">Spam post #spam</p>
                            <div class="flex justify-between items-center mt-2">
                                <div class="badge badge-error">Spam</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="avatar">
                            <div class="w-10 rounded-full">
                                <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user2" alt="User Avatar" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <p class="font-bold">Michael C.</p>
                                <span class="text-xs opacity-70">3h ago</span>
                            </div>
                            <p class="text-sm">Inappropriate comment</p>
                            <div class="flex justify-between items-center mt-2">
                                <div class="badge badge-error">Violation</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="avatar">
                            <div class="w-10 rounded-full">
                                <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user1" alt="User Avatar" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <p class="font-bold">Sarah J.</p>
                                <span class="text-xs opacity-70">6h ago</span>
                            </div>
                            <p class="text-sm">Offensive photo</p>
                            <div class="flex justify-between items-center mt-2">
                                <div class="badge badge-error">Content</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-actions justify-end mt-4"></div>
            </div>
        </div>
    </div>

    <!-- Content Distribution and Tag Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card bg-base-200 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">Content Distribution</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="h-64">
                        <canvas id="contentTypeChart"></canvas>
                    </div>
                    <div class="h-64">
                        <canvas id="engagementChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="card bg-base-200 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">Top Hashtags</h2>
                <div class="h-64">
                    <canvas id="tagChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    // Chart data from controller
    const chartData = @json($chartData);

    let charts = {};

    function initializeCharts(range = '7') {
        const data = chartData[range];

        // Activity Chart
        if (charts.activity) charts.activity.destroy();
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        charts.activity = new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: data.activity.labels,
                datasets: [
                    {
                        label: 'Posts',
                        data: data.activity.posts,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Comments',
                        data: data.activity.comments,
                        borderColor: '#0ea5e9',
                        backgroundColor: 'rgba(14, 165, 233, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'New Users',
                        data: data.activity.users,
                        borderColor: '#4ade80',
                        backgroundColor: 'rgba(74, 222, 128, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { color: '#e2e8f0' } }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(148, 163, 184, 0.1)' }, ticks: { color: '#94a3b8' } },
                    x: { grid: { color: 'rgba(148, 163, 184, 0.1)' }, ticks: { color: '#94a3b8' } }
                }
            }
        });

        // Content Type Chart
        if (charts.contentType) charts.contentType.destroy();
        const contentTypeCtx = document.getElementById('contentTypeChart').getContext('2d');
        charts.contentType = new Chart(contentTypeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Images', 'Videos', 'Links', 'Text'],
                datasets: [{
                    data: data.content,
                    backgroundColor: ['#3b82f6', '#4ade80', '#facc15', '#f87171'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { color: '#e2e8f0' } },
                    title: { display: true, text: 'Content Types', color: '#e2e8f0', font: { size: 16 } }
                }
            }
        });

        // Engagement Chart
        if (charts.engagement) charts.engagement.destroy();
        const engagementCtx = document.getElementById('engagementChart').getContext('2d');
        charts.engagement = new Chart(engagementCtx, {
            type: 'bar',
            data: {
                labels: ['Likes', 'Comments', 'Views'],
                datasets: [{
                    label: 'Engagement Metrics',
                    data: data.engagement,
                    backgroundColor: ['#3b82f6', '#4ade80', '#facc15'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Engagement Metrics', color: '#e2e8f0', font: { size: 16 } }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(148, 163, 184, 0.1)' }, ticks: { color: '#94a3b8' } },
                    x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                }
            }
        });

        // Tag Chart
        if (charts.tag) charts.tag.destroy();
        const tagCtx = document.getElementById('tagChart').getContext('2d');
        charts.tag = new Chart(tagCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(data.tags),
                datasets: [{
                    label: 'Hashtag Usage',
                    data: Object.values(data.tags),
                    backgroundColor: ['#3b82f6', '#4ade80', '#facc15', '#38bdf8', '#f87171'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Top Hashtags', color: '#e2e8f0', font: { size: 16 } }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(148, 163, 184, 0.1)' }, ticks: { color: '#94a3b8' } },
                    x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                }
            }
        });
    }

    function updateCharts() {
        const range = document.getElementById('timeRange').value;
        initializeCharts(range);
    }

    document.addEventListener('DOMContentLoaded', () => {
        initializeCharts();
    });
</script>
@endsection