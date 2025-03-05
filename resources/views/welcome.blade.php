@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <p class="text-sm opacity-70">Social Media Management Overview</p>
        </div>
        <div class="mt-4 md:mt-0">
            <div class="join">
                <button class="btn btn-sm join-item">Today</button>
                <button class="btn btn-sm join-item btn-active">Week</button>
                <button class="btn btn-sm join-item">Month</button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stats-card color="primary" icon="fa-users" title="Total Users" value="25.6K" description="↗︎ 340 (14%) this week" />
        <x-stats-card color="secondary" icon="fa-file-alt" title="Total Posts" value="142.5K" description="↗︎ 5.2K (22%) this week" />
        <x-stats-card color="accent" icon="fa-heart" title="Total Likes" value="2.4M" description="↗︎ 98K (8%) this week" />
        <x-stats-card color="info" icon="fa-user-shield" title="Banned Users" value="42" description="↗︎ 4 (10%) this month" />
    </div>

    <!-- Chart and Recent Users -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Chart -->
        <x-card>
            <h2 class="card-title">Post Activity</h2>
            <p class="text-sm opacity-70 mb-4">Posts and engagement over time</p>
            <div class="h-80">
                <canvas id="postActivityChart"></canvas>
            </div>
        </x-card>

        <!-- Recent Users -->
        <x-card>
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">Recent Users</h2>
                <button class="btn btn-sm btn-ghost">View All</button>
            </div>
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="avatar">
                        <div class="w-12 rounded-full">
                            <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user1" alt="User Avatar" />
                        </div>
                    </div>
                    <div>
                        <div class="font-bold">Sarah Johnson</div>
                        <div class="text-sm opacity-70">Joined 2 hours ago</div>
                    </div>
                    <div class="badge badge-primary ml-auto">User</div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="avatar">
                        <div class="w-12 rounded-full">
                            <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user2" alt="User Avatar" />
                        </div>
                    </div>
                    <div>
                        <div class="font-bold">Michael Chen</div>
                        <div class="text-sm opacity-70">Joined 5 hours ago</div>
                    </div>
                    <div class="badge badge-primary ml-auto">User</div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="avatar">
                        <div class="w-12 rounded-full">
                            <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user3" alt="User Avatar" />
                        </div>
                    </div>
                    <div>
                        <div class="font-bold">Alex Rodriguez</div>
                        <div class="text-sm opacity-70">Joined 8 hours ago</div>
                    </div>
                    <div class="badge badge-secondary ml-auto">Moderator</div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="avatar">
                        <div class="w-12 rounded-full">
                            <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user4" alt="User Avatar" />
                        </div>
                    </div>
                    <div>
                        <div class="font-bold">Emma Wilson</div>
                        <div class="text-sm opacity-70">Joined 1 day ago</div>
                    </div>
                    <div class="badge badge-primary ml-auto">User</div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="avatar">
                        <div class="w-12 rounded-full">
                            <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user5" alt="User Avatar" />
                        </div>
                    </div>
                    <div>
                        <div class="font-bold">David Kim</div>
                        <div class="text-sm opacity-70">Joined 1 day ago</div>
                    </div>
                    <div class="badge badge-primary ml-auto">User</div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Top Users and Recent Posts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Users -->
        <x-card>
            <h2 class="card-title mb-4">Top Users by Likes</h2>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Posts</th>
                            <th>Likes</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="w-10 rounded-full">
                                            <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=top1" alt="User Avatar" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">Jessica Taylor</div>
                                        <div class="text-sm opacity-70">@jtaylor</div>
                                    </div>
                                </div>
                            </td>
                            <td>142</td>
                            <td class="text-secondary font-bold">24.5K</td>
                            <td><div class="badge badge-success">Active</div></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="w-10 rounded-full">
                                            <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=top2" alt="User Avatar" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">Ryan Martinez</div>
                                        <div class="text-sm opacity-70">@rmartinez</div>
                                    </div>
                                </div>
                            </td>
                            <td>98</td>
                            <td class="text-secondary font-bold">18.2K</td>
                            <td><div class="badge badge-success">Active</div></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="w-10 rounded-full">
                                            <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=top3" alt="User Avatar" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">Sophia Lee</div>
                                        <div class="text-sm opacity-70">@sophialee</div>
                                    </div>
                                </div>
                            </td>
                            <td>87</td>
                            <td class="text-secondary font-bold">15.7K</td>
                            <td><div class="badge badge-success">Active</div></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="w-10 rounded-full">
                                            <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=top4" alt="User Avatar" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">James Wilson</div>
                                        <div class="text-sm opacity-70">@jwilson</div>
                                    </div>
                                </div>
                            </td>
                            <td>76</td>
                            <td class="text-secondary font-bold">12.3K</td>
                            <td><div class="badge badge-warning">Away</div></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="w-10 rounded-full">
                                            <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=top5" alt="User Avatar" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">Olivia Brown</div>
                                        <div class="text-sm opacity-70">@obrown</div>
                                    </div>
                                </div>
                            </td>
                            <td>64</td>
                            <td class="text-secondary font-bold">10.8K</td>
                            <td><div class="badge badge-success">Active</div></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Trending Hashtags Section -->
                <div class="mt-4 pt-4 border-t border-base-300">
                    <h3 class="text-sm font-bold mb-3 opacity-70">Trending Hashtags</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="badge badge-primary badge-outline badge-sm cursor-pointer hover:bg-primary hover:text-white">#Tech</span>
                        <span class="badge badge-secondary badge-outline badge-sm cursor-pointer hover:bg-secondary hover:text-white">#Photography</span>
                        <span class="badge badge-accent badge-outline badge-sm cursor-pointer hover:bg-accent hover:text-white">#Travel</span>
                        <span class="badge badge-info badge-outline badge-sm cursor-pointer hover:bg-info hover:text-white">#Marketing</span>
                        <span class="badge badge-success badge-outline badge-sm cursor-pointer hover:bg-success hover:text-white">#Entrepreneurship</span>
                        <span class="badge badge-warning badge-outline badge-sm cursor-pointer hover:bg-warning hover:text-white">#Fitness</span>
                        <span class="badge badge-error badge-outline badge-sm cursor-pointer hover:bg-error hover:text-white">#Programming</span>
                        <span class="badge badge-primary badge-outline badge-sm cursor-pointer hover:bg-primary hover:text-white">#ArtificialIntelligence</span>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Recent Posts -->
        <x-card>
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">Recent Posts</h2>
                <button class="btn btn-sm btn-ghost">View All</button>
            </div>
            <div class="space-y-4">
                <div class="card bg-base-300">
                    <div class="card-body p-4">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                    <div class="w-10 rounded-full">
                                        <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=post1" alt="User Avatar" />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">Emma Wilson</div>
                                    <div class="text-xs opacity-70">2 hours ago</div>
                                </div>
                            </div>
                            <div class="dropdown dropdown-end">
                                <label tabindex="0" class="btn btn-ghost btn-xs">
                                    <i class="fas fa-ellipsis-v"></i>
                                </label>
                                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-200 rounded-box w-52">
                                    <li><a>Edit</a></li>
                                    <li><a>Delete</a></li>
                                    <li><a>Flag</a></li>
                                </ul>
                            </div>
                        </div>
                        <p class="mt-2">Just launched my new photography portfolio! Check it out and let me know what you think. #photography #portfolio</p>
                        <div class="flex justify-between mt-3">
                            <div class="flex gap-3">
                                <span class="flex items-center gap-1"><i class="fas fa-heart text-error"></i> 245</span>
                                <span class="flex items-center gap-1"><i class="fas fa-comment"></i> 32</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-300">
                    <div class="card-body p-4">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                    <div class="w-10 rounded-full">
                                        <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=post2" alt="User Avatar" />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">Michael Chen</div>
                                    <div class="text-xs opacity-70">4 hours ago</div>
                                </div>
                            </div>
                            <div class="dropdown dropdown-end">
                                <label tabindex="0" class="btn btn-ghost btn-xs">
                                    <i class="fas fa-ellipsis-v"></i>
                                </label>
                                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-200 rounded-box w-52">
                                    <li><a>Edit</a></li>
                                    <li><a>Delete</a></li>
                                    <li><a>Flag</a></li>
                                </ul>
                            </div>
                        </div>
                        <p class="mt-2">My thoughts on the latest tech trends and how they're shaping our future. Thread 1/5 #tech #future</p>
                        <div class="flex justify-between mt-3">
                            <div class="flex gap-3">
                                <span class="flex items-center gap-1"><i class="fas fa-heart text-error"></i> 189</span>
                                <span class="flex items-center gap-1"><i class="fas fa-comment"></i> 45</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-300">
                    <div class="card-body p-4">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                    <div class="w-10 rounded-full">
                                        <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=post3" alt="User Avatar" />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">Jessica Taylor</div>
                                    <div class="text-xs opacity-70">6 hours ago</div>
                                </div>
                            </div>
                            <div class="dropdown dropdown-end">
                                <label tabindex="0" class="btn btn-ghost btn-xs">
                                    <i class="fas fa-ellipsis-v"></i>
                                </label>
                                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-200 rounded-box w-52">
                                    <li><a>Edit</a></li>
                                    <li><a>Delete</a></li>
                                    <li><a>Flag</a></li>
                                </ul>
                            </div>
                        </div>
                        <p class="mt-2">Excited to announce I'll be speaking at the Digital Marketing Summit next month! Who else is attending? #marketing #conference</p>
                        <div class="flex justify-between mt-3">
                            <div class="flex gap-3">
                                <span class="flex items-center gap-1"><i class="fas fa-heart text-error"></i> 312</span>
                                <span class="flex items-center gap-1"><i class="fas fa-comment"></i> 67</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <script>
        // Initialize chart
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('postActivityChart').getContext('2d');
            
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [
                        {
                            label: 'Posts',
                            data: [1200, 1900, 3000, 5000, 4000, 6000, 7000],
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Likes',
                            data: [4000, 6000, 10000, 15000, 12000, 20000, 25000],
                            borderColor: '#0ea5e9',
                            backgroundColor: 'rgba(14, 165, 233, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#e2e8f0'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)'
                            },
                            ticks: {
                                color: '#94a3b8'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)'
                            },
                            ticks: {
                                color: '#94a3b8'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection