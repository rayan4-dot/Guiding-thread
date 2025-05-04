@extends('layouts.admin-layout')

@section('title', 'Users')

@section('navbar')
    <!-- Unchanged from your provided code -->
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
                <div tabindex="0" class="mt-3 z-[1] card card-compact dropdown-content w-72 bg-base-200 shadow">
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
                    <li><a class="hover:bg-base-300">Profile</a></li>
                    <li><a class="hover:bg-base-300">Settings</a></li>
                    <li><a class="hover:bg-300">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
    <style>
        .navbar .btn-ghost {
            color: #1e40af;
        }
        .navbar .btn-ghost:hover {
            background-color: #f9fafb;
        }
        .navbar .dropdown-content {
            border: 1px solid #e2e8f0;
        }
        .navbar .card-body span {
            display: block;
            margin-bottom: 0.5rem;
        }
    </style>
@endsection

@section('content')
<main class="flex-1 overflow-y-auto p-4 lg:p-6 bg-base-100">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Users Management</h1>
            <p class="text-sm text-gray-600">Monitor and manage user accounts</p>
        </div>
        <div class="mt-4 md:mt-0 flex flex-col sm:flex-row gap-2">
            <div class="relative flex items-center gap-2">
                <input id="searchInput" class="input input-bordered w-full sm:w-auto" placeholder="Search users..." value="{{ request('search') }}">
                <button class="btn btn-primary" id="searchBtn">Search</button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="stat bg-base-200 rounded-box shadow">
            <div class="stat-figure text-primary">
                <i class="fas fa-users text-3xl"></i>
            </div>
            <div class="stat-title">Total Users</div>
            <div class="stat-value text-primary">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-desc">↗︎ {{ number_format($stats['new_users_week']) }} ({{ $stats['user_growth_percent'] }}%) this week</div>
        </div>
        <div class="stat bg-base-200 rounded-box shadow">
            <div class="stat-figure text-success">
                <i class="fas fa-user-check text-3xl"></i>
            </div>
            <div class="stat-title">Active Users</div>
            <div class="stat-value text-success">{{ number_format($stats['active_users']) }}</div>
            <div class="stat-desc">{{ $stats['active_users_percent'] }}% of total users</div>
        </div>
        <div class="stat bg-base-200 rounded-box shadow">
            <div class="stat-figure text-warning">
                <i class="fas fa-user-clock text-3xl"></i>
            </div>
            <div class="stat-title">New This Month</div>
            <div class="stat-value text-warning">{{ number_format($stats['new_users_month']) }}</div>
            <div class="stat-desc">↗︎ {{ $stats['new_users_growth_percent'] }}% from last month</div>
        </div>
        <div class="stat bg-base-200 rounded-box shadow">
            <div class="stat-figure text-error">
                <i class="fas fa-user-slash text-3xl"></i>
            </div>
            <div class="stat-title">Reported Users</div>
            <div class="stat-value text-error">142</div>
            <div class="stat-desc">Requires moderation</div>
        </div>
    </div>

    <!-- User Filters -->
    <div class="card bg-base-200 shadow-xl mb-6">
        <div class="card-body">
            <h2 class="card-title">User Filters</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                <div>
                    <label class="label">
                        <span class="label-text">Join Date</span>
                    </label>
                    <select class="select select-bordered w-full" id="joinDateFilter" name="join_date">
                        <option value="" {{ !request('join_date') ? 'selected' : '' }}>All Time</option>
                        <option value="today" {{ request('join_date') === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('join_date') === 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('join_date') === 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="3months" {{ request('join_date') === '3months' ? 'selected' : '' }}>Last 3 Months</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button class="btn btn-primary w-full" onclick="applyFilters()">
                        <i class="fas fa-filter mr-1"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Users List -->
    <div class="card bg-base-200 shadow-xl mb-6">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">All Users</h2>
                <div class="flex gap-2">
                    <span class="badge badge-lg">{{ $users->firstItem() }}-{{ $users->lastItem() }} of {{ $users->total() }}</span>
                    <div class="dropdown dropdown-end">
                        <label tabindex="0" class="btn btn-sm btn-ghost">
                            <i class="fas fa-ellipsis-v"></i>
                        </label>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-300 rounded-box w-52">
                            <li><a href="{{ route('admin.users.export', request()->query()) }}"><i class="fas fa-download mr-2"></i>Export Data</a></li>
                        </ul>
                    </div>
                </div>
            </div>


       <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Posts</th>
                        <th>Connections</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="hover">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="w-8 rounded-full">
                                            <img src="{{ $user->profile_picture ? Storage::url($user->profile_picture) : 'https://api.dicebear.com/6.x/avataaars/svg?seed=' . ($user->username ?? 'user' . $user->id) }}" alt="User Avatar" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">{{ $user->name }}</div>
                                        <div class="text-sm opacity-50">{{ $user->username ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->posts_count }}</td>
                            <td>{{ $user->connections_count }}</td>
                            <td>
                                <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-error' }}">
                                    {{ $user->is_active ? 'Active' : 'Suspended' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-ghost btn-xs" onclick="viewUserDetails({{ $user->id }})">
                                    <i class="fas fa-eye"></i> View Details
                                </button>
                                <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-ghost btn-xs">
                                        <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                        {{ $user->is_active ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-xs" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

            <!-- Pagination -->
            <div class="flex justify-between items-center mt-4">
                <div class="text-sm opacity-70">Showing {{ $users->firstItem() }}-{{ $users->lastItem() }} of {{ $users->total() }} users</div>
                <div class="join">
                    <a class="join-item btn {{ $users->onFirstPage() ? 'disabled' : '' }}" href="{{ $users->previousPageUrl() }}">«</a>
                    @for($i = max(1, $users->currentPage() - 1); $i <= min($users->lastPage(), $users->currentPage() + 3); $i++)
                        <a class="join-item btn {{ $users->currentPage() == $i ? 'btn-active' : '' }}" href="{{ $users->url($i) }}">{{ $i }}</a>
                    @endfor
                    <a class="join-item btn {{ $users->hasMorePages() ? '' : 'disabled' }}" href="{{ $users->nextPageUrl() }}">»</a>
                </div>
            </div>
        </div>
    </div>

    <!-- User Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="card bg-base-200 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">User Growth</h2>
                <div class="h-80">
                    <canvas id="userGrowthChart"></canvas>
                </div>
            </div>
        </div>
        <div class="card bg-base-200 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">User Demographics</h2>
                <div class="h-80">
                    <canvas id="userDemographicsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

   <!-- User Details Modal -->
   <input type="checkbox" id="userDetailsModal" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">User Details</h3>
            <div class="py-4">
                <div class="flex items-center gap-4 mb-4">
                    <div class="avatar">
                        <div class="w-16 rounded-full">
                            <img id="userAvatar" src="" alt="User Avatar" />
                        </div>
                    </div>
                    <div>
                        <h4 id="userName" class="font-bold"></h4>
                        <p id="userUsername" class="text-sm opacity-70"></p>
                    </div>
                </div>
                <p><strong>Email:</strong> <span id="userEmail"></span></p>
                <p><strong>Bio:</strong> <span id="userBio"></span></p>
                <p><strong>Status:</strong> <span id="userStatus"></span></p>
                <p><strong>Posts:</strong> <span id="userPosts"></span></p>
                <p><strong>Connections:</strong> <span id="userConnections"></span></p>
                <p><strong>Joined:</strong> <span id="userJoined"></span></p>
            </div>
            <div class="modal-action">
                <label for="userDetailsModal" class="btn">Close</label>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    // Chart data from controller
    const growthData = @json($growth);
    const demographicsData = @json($demographics);

    function initializeCharts() {
        // User Growth Chart
        const growthCtx = document.getElementById('userGrowthChart').getContext('2d');
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: growthData.labels,
                datasets: [
                    {
                        label: 'New Users',
                        data: growthData.new_users,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Active Users',
                        data: growthData.active_users,
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
                    legend: { position: 'top', labels: { color: '#e2e8f0' } },
                    title: { display: true, text: 'User Growth', color: '#e2e8f0', font: { size: 16 } }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(148, 163, 184, 0.1)' }, ticks: { color: '#94a3b8' } },
                    x: { grid: { color: 'rgba(148, 163, 184, 0.1)' }, ticks: { color: '#94a3b8' } }
                }
            }
        });

        // User Demographics Chart
        const demoCtx = document.getElementById('userDemographicsChart').getContext('2d');
        new Chart(demoCtx, {
            type: 'doughnut',
            data: {
                labels: demographicsData.labels,
                datasets: [{
                    data: demographicsData.data,
                    backgroundColor: ['#3b82f6', '#4ade80', '#facc15', '#f87171', '#38bdf8'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { color: '#e2e8f0' } },
                    title: { display: true, text: 'User Demographics', color: '#e2e8f0', font: { size: 16 } }
                }
            }
        });
    }


  
    async function viewUserDetails(userId) {
        console.log('viewUserDetails called with userId:', userId);
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            console.log('CSRF token:', csrfToken);
            if (!csrfToken) {
                throw new Error('CSRF token not found');
            }

            const response = await fetch(`/admin/users/${userId}/details`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const user = await response.json();
            console.log('User data:', user);

            if (user.error) {
                throw new Error(user.error);
            }

            // Populate modal
            document.getElementById('userAvatar').src = user.profile_picture || 'https://api.dicebear.com/6.x/avataaars/svg?seed=user';
            document.getElementById('userName').textContent = user.name || 'N/A';
            document.getElementById('userUsername').textContent = user.username && user.username !== 'N/A' ? `@${user.username}` : 'N/A';
            document.getElementById('userEmail').textContent = user.email || 'N/A';
            document.getElementById('userBio').textContent = user.bio || 'N/A';
            document.getElementById('userStatus').textContent = user.is_active ? 'Active' : 'Suspended';
            document.getElementById('userPosts').textContent = user.posts_count || 0;
            document.getElementById('userConnections').textContent = user.connections_count || 0;
            document.getElementById('userJoined').textContent = user.created_at || 'N/A';

            // Show modal
            const modal = document.getElementById('userDetailsModal');
            console.log('Modal element:', modal, 'Checked state before:', modal.checked);
            modal.checked = true;
            console.log('Checked state after:', modal.checked);
        } catch (error) {
            console.error('Error fetching user details:', error.message);
            alert(`Failed to load user details: ${error.message}`);
        }
    }

    function applyFilters() {
        const search = document.getElementById('searchInput').value;
        const joinDate = document.getElementById('joinDateFilter').value;
        const query = new URLSearchParams();
        if (search) query.set('search', search);
        if (joinDate) query.set('join_date', joinDate);
        window.location.href = `/admin/users?${query.toString()}`;
    }

    document.getElementById('searchBtn').addEventListener('click', applyFilters);
    document.getElementById('searchInput').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') applyFilters();
    });

    document.addEventListener('DOMContentLoaded', () => {
        initializeCharts();
    });
</script>
@endsection