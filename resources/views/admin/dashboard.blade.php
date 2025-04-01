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
                    <li><a>Logout </a></li>
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
            <p class="text-sm opacity-70 mt-1">Real-time insights for March 13, 2025</p>
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
            <div class="stat-value text-primary">25.6K</div>
            <div class="stat-desc">↗︎ 340 (14%) this week</div>
          </div>
          <div class="stat bg-base-200 rounded-box shadow hover:shadow-lg transition-shadow">
            <div class="stat-figure text-secondary">
              <i class="fas fa-file-alt text-3xl"></i>
            </div>
            <div class="stat-title">Posts</div>
            <div class="stat-value text-secondary">142K</div>
            <div class="stat-desc">↗︎ 12% from last month</div>
          </div>
          <div class="stat bg-base-200 rounded-box shadow hover:shadow-lg transition-shadow">
            <div class="stat-figure text-accent">
              <i class="fas fa-comment text-3xl"></i>
            </div>
            <div class="stat-title">Comments</div>
            <div class="stat-value text-accent">856K</div>
            <div class="stat-desc">↗︎ 7% from last month</div>
          </div>
          <div class="stat bg-base-200 rounded-box shadow hover:shadow-lg transition-shadow">
            <div class="stat-figure text-info">
              <i class="fas fa-hashtag text-3xl"></i>
            </div>
            <div class="stat-title">Active Tags</div>
            <div class="stat-value text-info">3.2K</div>
            <div class="stat-desc">Top: #weekend</div>
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
          <!-- Recent Activity -->
          <div class="card bg-base-200 shadow-xl">
            <div class="card-body">
              <h2 class="card-title mb-4">Recent Activity</h2>
              <div class="space-y-4 max-h-96 overflow-y-auto">
                <div class="flex items-start gap-4">
                  <div class="avatar">
                    <div class="w-10 rounded-full">
                      <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user1" alt="User Avatar" />
                    </div>
                  </div>
                  <div class="flex-1">
                    <div class="flex justify-between">
                      <p class="font-bold">Sarah Johnson</p>
                      <span class="text-xs opacity-70">2h ago</span>
                    </div>
                    <p class="text-sm">Posted a photo flagged by 3 users</p>
                    <div class="flex justify-between items-center mt-2">
                      <div class="badge badge-warning">Needs Review</div>
                      <button class="btn btn-xs btn-outline">View</button>
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
                      <p class="font-bold">Michael Chen</p>
                      <span class="text-xs opacity-70">5h ago</span>
                    </div>
                    <p class="text-sm">Reported a comment</p>
                    <div class="flex justify-between items-center mt-2">
                      <div class="badge badge-error">Report</div>
                      <button class="btn btn-xs btn-outline">View</button>
                    </div>
                  </div>
                </div>
                <div class="flex items-start gap-4">
                  <div class="avatar">
                    <div class="w-10 rounded-full">
                      <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user3" alt="User Avatar" />
                    </div>
                  </div>
                  <div class="flex-1">
                    <div class="flex justify-between">
                      <p class="font-bold">Alex Rodriguez</p>
                      <span class="text-xs opacity-70">1d ago</span>
                    </div>
                    <p class="text-sm">New account created</p>
                    <div class="flex justify-between items-center mt-2">
                      <div class="badge badge-info">New User</div>
                      <button class="btn btn-xs btn-outline">View</button>
                    </div>
                  </div>
                </div>
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
                        <th>Followers</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover">
                        <td>
                            <div class="flex items-center gap-3">
                            <div class="avatar">
                                <div class="w-8 rounded-full">
                                <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user1" alt="User Avatar" />
                                </div>
                            </div>
                            <div class="font-bold">Sarah J.</div>
                            </div>
                        </td>
                        <td>127</td>
                        <td>45.2K</td>
                        </tr>
                        <tr class="hover">
                        <td>
                            <div class="flex items-center gap-3">
                            <div class="avatar">
                                <div class="w-8 rounded-full">
                                <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user5" alt="User Avatar" />
                                </div>
                            </div>
                            <div class="font-bold">David K.</div>
                            </div>
                        </td>
                        <td>98</td>
                        <td>38.7K</td>

                        </tr>
                        <tr class="hover">
                        <td>
                            <div class="flex items-center gap-3">
                            <div class="avatar">
                                <div class="w-8 rounded-full">
                                <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user4" alt="User Avatar" />
                                </div>
                            </div>
                            <div class="font-bold">Emma W.</div>
                            </div>
                        </td>
                        <td>67</td>
                        <td>29.3K</td>

                        </tr>
                    </tbody>
                    </table>
                </div>
                <div class="card-actions justify-end mt-4">
                    <button class="btn btn-primary btn-sm">View All</button>
                </div>
                </div>
            </div>

          <!-- Recent Reports -->
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
                      <button class="btn btn-xs btn-outline">Review</button>
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
                      <button class="btn btn-xs btn-outline">Review</button>
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
                      <button class="btn btn-xs btn-outline">Review</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-actions justify-end mt-4">
                <button class="btn btn-primary btn-sm">View All</button>
              </div>
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
    // Chart data based on time range
    const chartData = {
      '7': {
        activity: { posts: [120, 190, 150, 170, 180, 210, 250], comments: [450, 550, 400, 480, 520, 630, 720], users: [45, 60, 55, 70, 65, 85, 90] },
        content: [45, 25, 15, 10, 5],
        engagement: [65000, 42000, 18000, 9500, 1200],
        tags: { '#weekend': 1200, '#project': 850, '#nature': 600, '#motivation': 450, '#spam': 300 }
      },
      '30': {
        activity: { posts: [500, 620, 580, 700], comments: [1800, 2200, 2000, 2500], users: [200, 250, 230, 280] },
        content: [48, 28, 12, 8, 4],
        engagement: [260000, 168000, 72000, 38000, 4800],
        tags: { '#weekend': 4800, '#project': 3400, '#nature': 2400, '#motivation': 1800, '#spam': 1200 }
      },
      '90': {
        activity: { posts: [1500, 1700, 1600], comments: [5400, 6000, 5700], users: [600, 680, 650] },
        content: [50, 30, 10, 7, 3],
        engagement: [780000, 504000, 216000, 114000, 14400],
        tags: { '#weekend': 14400, '#project': 10200, '#nature': 7200, '#motivation': 5400, '#spam': 3600 }
      },
      'year': {
        activity: { posts: [6000, 6200, 6500, 6800], comments: [21600, 22400, 23000, 24000], users: [2400, 2500, 2600, 2700] },
        content: [52, 32, 9, 5, 2],
        engagement: [3120000, 2016000, 864000, 456000, 57600],
        tags: { '#weekend': 57600, '#project': 40800, '#nature': 28800, '#motivation': 21600, '#spam': 14400 }
      }
    };

    let charts = {};

    function initializeCharts(range = '7') {
      const data = chartData[range];

      // Activity Chart
      if (charts.activity) charts.activity.destroy();
      const activityCtx = document.getElementById('activityChart').getContext('2d');
      charts.activity = new Chart(activityCtx, {
        type: 'line',
        data: {
          labels: range === '7' ? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] : range === '30' ? ['Week 1', 'Week 2', 'Week 3', 'Week 4'] : range === '90' ? ['Month 1', 'Month 2', 'Month 3'] : ['Q1', 'Q2', 'Q3', 'Q4'],
          datasets: [
            { label: 'Posts', data: data.activity.posts, borderColor: '#3b82f6', backgroundColor: 'rgba(59, 130, 246, 0.1)', tension: 0.4, fill: true },
            { label: 'Comments', data: data.activity.comments, borderColor: '#0ea5e9', backgroundColor: 'rgba(14, 165, 233, 0.1)', tension: 0.4, fill: true },
            { label: 'New Users', data: data.activity.users, borderColor: '#4ade80', backgroundColor: 'rgba(74, 222, 128, 0.1)', tension: 0.4, fill: true }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { position: 'top', labels: { color: '#e2e8f0' } } },
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
          labels: ['Photos', 'Videos', 'Text', 'Links', 'Other'],
          datasets: [{ data: data.content, backgroundColor: ['#3b82f6', '#4ade80', '#facc15', '#f87171', '#38bdf8'], borderWidth: 0 }]
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
          labels: ['Likes', 'Comments', 'Shares', 'Saves', 'Reports'],
          datasets: [{
            label: 'Engagement Metrics',
            data: data.engagement,
            backgroundColor: ['#3b82f6', '#4ade80', '#facc15', '#38bdf8', '#f87171'],
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