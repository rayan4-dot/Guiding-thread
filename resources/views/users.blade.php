@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
    <!-- Users Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Users Management</h1>
            <p class="text-sm opacity-70">Monitor and manage user accounts</p>
        </div>
        <div class="mt-4 md:mt-0 flex flex-col sm:flex-row gap-2">
            <div class="join">
                <div>
                    <div>
                        <input class="input input-bordered join-item" placeholder="Search users..."/>
                    </div>
                </div>
                <select class="select select-bordered join-item">
                    <option disabled selected>Filter</option>
                    <option>Active</option>
                    <option>Inactive</option>
                    <option>Reported</option>
                    <option>New</option>
                </select>
                <button class="btn join-item">Search</button>
            </div>
        </div>
    </div>
    
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-stats-card color="primary" icon="fa-users" title="Total Users" value="25.6K" description="↗︎ 340 (14%) this week" />
        <x-stats-card color="success" icon="fa-user-check" title="Active Users" value="18.9K" description="74% of total users" />
        <x-stats-card color="warning" icon="fa-user-clock" title="New This Month" value="2.3K" description="↗︎ 21% from last month" />
        <x-stats-card color="error" icon="fa-user-slash" title="Reported Users" value="142" description="Requires moderation" />
    </div>

    <!-- Users Table -->
    <x-card>
        <h2 class="card-title mb-4">All Users</h2>
        <x-table :headers="['User', 'Joined', 'Last Active', 'Posts', 'Status', 'Actions']">
            <tr>
                <td>
                        <div class="flex items-center gap-3">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user1" alt="User Avatar" />
                                </div>
                            </div>
                            <div>
                                <div class="font-bold">Sarah Johnson</div>
                                <div class="text-sm opacity-70">@sjohnson</div>
                            </div>
                        </div>
                    </td>
                    <td>Mar 2, 2025</td>
                    <td>2 hours ago</td>
                    <td>127</td>
                    <td><div class="badge badge-success">Active</div></td>
                    <td>
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-ghost btn-xs">
                                <i class="fas fa-ellipsis-v"></i>
                            </label>
                            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-200 rounded-box w-52">
                                <li><a><i class="fas fa-eye mr-2"></i>View Profile</a></li>
                                <li><a><i class="fas fa-edit mr-2"></i>Edit</a></li>
                                <li><a><i class="fas fa-ban mr-2"></i>Suspend</a></li>
                                <li><a class="text-error"><i class="fas fa-trash mr-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user2" alt="User Avatar" />
                                </div>
                            </div>
                            <div>
                                <div class="font-bold">Michael Chen</div>
                                <div class="text-sm opacity-70">@mchen</div>
                            </div>
                        </div>
                    </td>
                    <td>Feb 28, 2025</td>
                    <td>5 hours ago</td>
                    <td>85</td>
                    <td><div class="badge badge-success">Active</div></td>
                    <td>
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-ghost btn-xs">
                                <i class="fas fa-ellipsis-v"></i>
                            </label>
                            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-200 rounded-box w-52">
                                <li><a><i class="fas fa-eye mr-2"></i>View Profile</a></li>
                                <li><a><i class="fas fa-edit mr-2"></i>Edit</a></li>
                                <li><a><i class="fas fa-ban mr-2"></i>Suspend</a></li>
                                <li><a class="text-error"><i class="fas fa-trash mr-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user3" alt="User Avatar" />
                                </div>
                            </div>
                            <div>
                                <div class="font-bold">Alex Rodriguez</div>
                                <div class="text-sm opacity-70">@arod</div>
                            </div>
                        </div>
                    </td>
                    <td>Feb 26, 2025</td>
                    <td>3 days ago</td>
                    <td>42</td>
                    <td><div class="badge badge-warning">Inactive</div></td>
                    <td>
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-ghost btn-xs">
                                <i class="fas fa-ellipsis-v"></i>
                            </label>
                            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-200 rounded-box w-52">
                                <li><a><i class="fas fa-eye mr-2"></i>View Profile</a></li>
                                <li><a><i class="fas fa-edit mr-2"></i>Edit</a></li>
                                <li><a><i class="fas fa-ban mr-2"></i>Suspend</a></li>
                                <li><a class="text-error"><i class="fas fa-trash mr-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user4" alt="User Avatar" />
                                </div>
                            </div>
                            <div>
                                <div class="font-bold">Emma Wilson</div>
                                <div class="text-sm opacity-70">@ewilson</div>
                            </div>
                        </div>
                    </td>
                    <td>Mar 1, 2025</td>
                    <td>1 day ago</td>
                    <td>67</td>
                    <td><div class="badge badge-success">Active</div></td>
                    <td>
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-ghost btn-xs">
                                <i class="fas fa-ellipsis-v"></i>
                            </label>
                            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-200 rounded-box w-52">
                                <li><a><i class="fas fa-eye mr-2"></i>View Profile</a></li>
                                <li><a><i class="fas fa-edit mr-2"></i>Edit</a></li>
                                <li><a><i class="fas fa-ban mr-2"></i>Suspend</a></li>
                                <li><a class="text-error"><i class="fas fa-trash mr-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user5" alt="User Avatar" />
                                </div>
                            </div>
                            <div>
                                <div class="font-bold">David Kim</div>
                                <div class="text-sm opacity-70">@dkim</div>
                            </div>
                        </div>
                    </td>
                    <td>Mar 1, 2025</td>
                    <td>8 hours ago</td>
                    <td>23</td>
                    <td><div class="badge badge-success">Active</div></td>
                    <td>
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-ghost btn-xs">
                                <i class="fas fa-ellipsis-v"></i>
                            </label>
                            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-200 rounded-box w-52">
                                <li><a><i class="fas fa-eye mr-2"></i>View Profile</a></li>
                                <li><a><i class="fas fa-edit mr-2"></i>Edit</a></li>
                                <li><a><i class="fas fa-ban mr-2"></i>Suspend</a></li>
                                <li><a class="text-error"><i class="fas fa-trash mr-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=user6" alt="User Avatar" />
                                </div>
                            </div>
                            <div>
                                <div class="font-bold">Sophia Lee</div>
                                <div class="text-sm opacity-70">@sophialee</div>
                            </div>
                        </div>
                    </td>
                    <td>Feb 24, 2025</td>
                    <td>5 days ago</td>
                    <td>87</td>
                    <td><div class="badge badge-error">Reported</div></td>
                    <td>
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-ghost btn-xs">
                                <i class="fas fa-ellipsis-v"></i>
                            </label>
                            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-200 rounded-box w-52">
                                <li><a><i class="fas fa-eye mr-2"></i>View Profile</a></li>
                                <li><a><i class="fas fa-edit mr-2"></i>Edit</a></li>
                                <li><a><i class="fas fa-ban mr-2"></i>Suspend</a></li>
                                <li><a class="text-error"><i class="fas fa-trash mr-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
        </x-table>
        
        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4">
            <div class="text-sm opacity-70" id="paginationInfo">Showing 1-6 of 25,632 users</div>
            <div class="join" id="paginationControls">
                <button class="join-item btn" onclick="changePage(-1)">«</button>
                <button class="join-item btn btn-active" id="page1">1</button>
                <button class="join-item btn" id="page2">2</button>
                <button class="join-item btn" id="page3">3</button>
                <button class="join-item btn" id="page4">4</button>
                <button class="join-item btn" onclick="changePage(1)">»</button>
            </div>
        </div>
    </x-card>
    
    <!-- User Demographics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Growth -->
        <x-card>
            <h2 class="card-title">User Growth</h2>
            <div class="h-80">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </x-card>
        
        <!-- Demographics -->
        <x-card>
            <h2 class="card-title">User Demographics</h2>
            <div class="h-80">
                <canvas id="userDemographicsChart"></canvas>
            </div>
        </x-card>
    </div>

    <script>
        // User data (simulating 25,632 users for pagination)
        const users = [
            { name: "Sarah Johnson", username: "@sjohnson", joined: "Mar 2, 2025", lastActive: "2 hours ago", posts: 127, status: "Active" },
            { name: "Michael Chen", username: "@mchen", joined: "Feb 28, 2025", lastActive: "5 hours ago", posts: 85, status: "Active" },
            { name: "Alex Rodriguez", username: "@arod", joined: "Feb 26, 2025", lastActive: "3 days ago", posts: 42, status: "Inactive" },
            { name: "Emma Wilson", username: "@ewilson", joined: "Mar 1, 2025", lastActive: "1 day ago", posts: 67, status: "Active" },
            { name: "David Kim", username: "@dkim", joined: "Mar 1, 2025", lastActive: "8 hours ago", posts: 23, status: "Active" },
            { name: "Sophia Lee", username: "@sophialee", joined: "Feb 24, 2025", lastActive: "5 days ago", posts: 87, status: "Reported" },
            // Add more users to reach 25,632 if needed, but for simplicity, we'll use these 6 for demonstration
        ];

        let currentPage = 1;
        const usersPerPage = 6;

        function displayUsers(page) {
            const start = (page - 1) * usersPerPage;
            const end = start + usersPerPage;
            const paginatedUsers = users.slice(start, end);
            const tableBody = document.getElementById('usersTableBody');
            tableBody.innerHTML = '';

            paginatedUsers.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=${user.username.replace('@', '')}" alt="User Avatar" />
                                </div>
                            </div>
                            <div>
                                <div class="font-bold">${user.name}</div>
                                <div class="text-sm opacity-70">${user.username}</div>
                            </div>
                        </div>
                    </td>
                    <td>${user.joined}</td>
                    <td>${user.lastActive}</td>
                    <td>${user.posts}</td>
                    <td><div class="badge ${user.status === 'Active' ? 'badge-success' : user.status === 'Inactive' ? 'badge-warning' : 'badge-error'}">${user.status}</div></td>
                    <td>
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-ghost btn-xs">
                                <i class="fas fa-ellipsis-v"></i>
                            </label>
                            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-200 rounded-box w-52">
                                <li><a><i class="fas fa-eye mr-2"></i>View Profile</a></li>
                                <li><a><i class="fas fa-edit mr-2"></i>Edit</a></li>
                                <li><a><i class="fas fa-ban mr-2"></i>Suspend</a></li>
                                <li><a class="text-error"><i class="fas fa-trash mr-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            updatePagination();
        }

        function updatePagination() {
            const totalPages = Math.ceil(users.length / usersPerPage);
            document.getElementById('paginationInfo').textContent = `Showing ${(currentPage - 1) * usersPerPage + 1}-${Math.min(currentPage * usersPerPage, users.length)} of ${users.length} users`;

            const buttons = document.querySelectorAll('#paginationControls .btn');
            buttons.forEach((button, index) => {
                if (index === 0) button.disabled = currentPage === 1;
                else if (index === buttons.length - 1) button.disabled = currentPage === totalPages;
                else if (index - 1 === currentPage) button.classList.add('btn-active');
                else button.classList.remove('btn-active');
            });

            // Update page button numbers
            for (let i = 1; i <= 4; i++) {
                const pageButton = document.getElementById(`page${i}`);
                const pageNum = currentPage - 2 + i; // Center the current page
                if (pageNum > 0 && pageNum <= totalPages) {
                    pageButton.textContent = pageNum;
                    pageButton.style.display = 'block';
                    pageButton.onclick = () => changePage(pageNum - currentPage);
                } else {
                    pageButton.style.display = 'none';
                }
            }
        }

        function changePage(direction) {
            const totalPages = Math.ceil(users.length / usersPerPage);
            if (direction === -1 && currentPage > 1) currentPage--;
            else if (direction === 1 && currentPage < totalPages) currentPage++;
            else if (typeof direction === 'number') currentPage = direction;
            displayUsers(currentPage);
        }

        // Initialize with first page
        displayUsers(currentPage);

        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            // User Growth Chart
            const growthCtx = document.getElementById('userGrowthChart').getContext('2d');
            const growthChart = new Chart(growthCtx, {
                type: 'line',
                data: {
                    labels: ['Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
                    datasets: [
                        {
                            label: 'New Users',
                            data: [1200, 1500, 1800, 2100, 1900, 2200, 2300],
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Active Users',
                            data: [8000, 9500, 11000, 14000, 16000, 17500, 18900],
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
            
            // Demographics Chart
            const demoCtx = document.getElementById('userDemographicsChart').getContext('2d');
            const demoChart = new Chart(demoCtx, {
                type: 'doughnut',
                data: {
                    labels: ['18-24', '25-34', '35-44', '45-54', '55+'],
                    datasets: [
                        {
                            data: [35, 28, 18, 12, 7],
                            backgroundColor: [
                                '#3b82f6',
                                '#4ade80',
                                '#facc15',
                                '#f87171',
                                '#38bdf8'
                            ],
                            borderWidth: 0
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                color: '#e2e8f0'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection