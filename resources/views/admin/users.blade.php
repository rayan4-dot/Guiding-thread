@extends('layouts.admin-layout')

@section('title', 'Users')

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
                <li><a class="hover:bg-base-300">Logout</a></li>
            </ul>
        </div>
    </div>
</div>
<style>
        .navbar .btn-ghost {
        color: #1e40af; /* Primary color */
    }
    .navbar .btn-ghost:hover {
        background-color: #f9fafb; /* Light background on hover */
    }
    .navbar .dropdown-content {
        border: 1px solid #e2e8f0; /* Light border */
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
            <!-- Search Input -->
            <input id="searchInput"
                class="input input-bordered w-full sm:w-auto"
                placeholder="Search users...">

            <!-- Search Button -->
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
            <div class="stat-value text-primary">25.6K</div>
            <div class="stat-desc">↗︎ 340 (14%) this week</div>
          </div>
          <div class="stat bg-base-200 rounded-box shadow">
            <div class="stat-figure text-success">
              <i class="fas fa-user-check text-3xl"></i>
            </div>
            <div class="stat-title">Active Users</div>
            <div class="stat-value text-success">18.9K</div>
            <div class="stat-desc">74% of total users</div>
          </div>
          <div class="stat bg-base-200 rounded-box shadow">
            <div class="stat-figure text-warning">
              <i class="fas fa-user-clock text-3xl"></i>
            </div>
            <div class="stat-title">New This Month</div>
            <div class="stat-value text-warning">2.3K</div>
            <div class="stat-desc">↗︎ 21% from last month</div>
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
                <select class="select select-bordered w-full" id="joinDateFilter">
                  <option selected>All Time</option>
                  <option>Today</option>
                  <option>This Week</option>
                  <option>This Month</option>
                  <option>Last 3 Months</option>
                </select>
              </div>
              <div class="flex items-end">
                <button class="btn btn-primary w-full">
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
                <span class="badge badge-lg" id="userCountBadge">1-6 of 25,632</span>
                <div class="dropdown dropdown-end">
                  <label tabindex="0" class="btn btn-sm btn-ghost">
                    <i class="fas fa-ellipsis-v"></i>
                  </label>
                  <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-300 rounded-box w-52">
                    <li><a><i class="fas fa-download mr-2"></i>Export Data</a></li>
                    <li><a><i class="fas fa-user-plus mr-2"></i>Add User</a></li>
                    <li><a><i class="fas fa-envelope mr-2"></i>Email Selected</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="overflow-x-auto">
              <table class="table table-zebra">
                <thead>
                  <tr>
                    <th>User</th>
                    <th>Joined</th>
                    <th>Last Active</th>
                    <th>Posts</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody id="usersTableBody">
                  <!-- Populated by JavaScript -->
                </tbody>
              </table>
            </div>
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

        <!-- User Activity -->
        <div class="card bg-base-200 shadow-xl">
          <div class="card-body">
            <h2 class="card-title">User Activity by Time</h2>
            <div class="h-80">
              <canvas id="userActivityChart"></canvas>
            </div>
          </div>
        </div>
      </main>

       <!-- User Details Modal -->
  <dialog id="userDetailsModal" class="modal">
    <div class="modal-box bg-base-200 max-w-3xl">
      <form method="dialog">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
      </form>
      <h3 class="font-bold text-lg mb-4" id="modalUserName">User Details</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="flex flex-col items-center gap-4">
          <div class="avatar">
            <div class="w-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
              <img id="modalUserAvatar" src="https://api.dicebear.com/6.x/avataaars/svg?seed=user1" alt="User Avatar" />
            </div>
          </div>
          <div class="text-center">
            <h4 class="text-xl font-bold" id="modalUserFullName">Sarah Johnson</h4>
            <p class="text-sm opacity-70" id="modalUsername">@sjohnson</p>
          </div>
          <div class="flex gap-2 mt-2">
            <button class="btn btn-sm btn-primary">Message</button>
            <button class="btn btn-sm btn-outline">Follow</button>
          </div>
        </div>
        <div class="col-span-2">
          <div class="stats stats-vertical shadow w-full">
            <div class="stat">
              <div class="stat-title">Account Status</div>
              <div class="stat-value text-success" id="modalUserStatus">Active</div>
              <div class="stat-desc" id="modalUserStatusDesc">Since March 2, 2025</div>
            </div>
            <div class="stat">
              <div class="stat-title">Activity</div>
              <div class="stat-value" id="modalUserPosts">127 Posts</div>
              <div class="stat-desc" id="modalUserComments">342 Comments</div>
            </div>
          </div>
        </div>
      </div>
      <div class="tabs tabs-boxed bg-base-300 mt-6">
        <a class="tab tab-active">Overview</a>
        <a class="tab">Posts</a>
        <a class="tab">Comments</a>
        <a class="tab">Reports</a>
        <a class="tab">Activity Log</a>
      </div>
      <div class="mt-4">
        <div class="overflow-x-auto">
          <table class="table table-zebra">
            <thead>
              <tr>
                <th>Recent Activity</th>
                <th>Type</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Posted "How to improve your social media strategy"</td>
                <td>Post</td>
                <td>2 hours ago</td>
              </tr>
              <tr>
                <td>Commented on @mchen's post</td>
                <td>Comment</td>
                <td>5 hours ago</td>
              </tr>
              <tr>
                <td>Liked 12 posts</td>
                <td>Engagement</td>
                <td>Yesterday</td>
              </tr>
              <tr>
                <td>Updated profile picture</td>
                <td>Profile Update</td>
                <td>3 days ago</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-action">
        <form method="dialog">
          <button class="btn">Close</button>
        </form>
      </div>
    </div>
  </dialog>
@endsection

@section('scripts')
<script>
    // User data (simulating 25,632 users for pagination)
    const users = [
      { name: "Sarah Johnson", username: "@sjohnson", joined: "March 2, 2025", lastActive: "2 hours ago", posts: 127, status: "Active" },
      { name: "Michael Chen", username: "@mchen", joined: "February 28, 2025", lastActive: "5 hours ago", posts: 85, status: "Active" },
      { name: "Alex Rodriguez", username: "@arod", joined: "February 26, 2025", lastActive: "3 days ago", posts: 42, status: "Inactive" },
      { name: "Emma Wilson", username: "@ewilson", joined: "March 1, 2025", lastActive: "1 day ago", posts: 67, status: "Active" },
      { name: "David Kim", username: "@dkim", joined: "March 1, 2025", lastActive: "8 hours ago", posts: 23, status: "Active" },
      { name: "Sophia Lee", username: "@sophialee", joined: "February 24, 2025", lastActive: "5 days ago", posts: 87, status: "Reported" },
    ];

    let currentPage = 1;
    const usersPerPage = 6;
    const totalUsers = 25632;

    function displayUsers(page, filteredUsers = null) {
      const usersToDisplay = filteredUsers || users;
      const start = (page - 1) * usersPerPage;
      const end = Math.min(start + usersPerPage, usersToDisplay.length);
      const paginatedUsers = usersToDisplay.slice(start, end);
      const tableBody = document.getElementById('usersTableBody');
      tableBody.innerHTML = '';

      paginatedUsers.forEach(user => {
        const row = document.createElement('tr');
        const username = user.username.replace('@', '');
        row.innerHTML = `
          <td>
            <div class="flex items-center gap-3">
              <div class="avatar">
                <div class="w-12 rounded-full">
                  <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=${username}" alt="User Avatar" />
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
                <li><a onclick="openUserModal('${user.name}', '${username}', '${user.status}', ${user.posts}, '${user.joined}')"><i class="fas fa-eye mr-2"></i>View Profile</a></li>
                <li><a><i class="fas fa-edit mr-2"></i>Edit</a></li>
                <li><a><i class="fas fa-ban mr-2"></i>Suspend</a></li>
                <li><a class="text-error"><i class="fas fa-trash mr-2"></i>Delete</a></li>
              </ul>
            </div>
          </td>
        `;
        tableBody.appendChild(row);
      });

      updatePagination(filteredUsers);
    }

    function updatePagination(filteredUsers = null) {
      const totalItems = filteredUsers ? filteredUsers.length : totalUsers;
      const totalPages = Math.ceil(totalItems / usersPerPage);

      document.getElementById('paginationInfo').textContent = `Showing ${(currentPage - 1) * usersPerPage + 1}-${Math.min(currentPage * usersPerPage, totalItems)} of ${totalItems} users`;
      document.getElementById('userCountBadge').textContent = `${(currentPage - 1) * usersPerPage + 1}-${Math.min(currentPage * usersPerPage, totalItems)} of ${totalItems}`;

      const buttons = document.querySelectorAll('#paginationControls .btn');
      buttons.forEach((button, index) => {
        if (index === 0) button.disabled = currentPage === 1;
        else if (index === buttons.length - 1) button.disabled = currentPage === totalPages;
        else if (index === currentPage) button.classList.add('btn-active');
        else button.classList.remove('btn-active');
      });

      for (let i = 1; i <= 4; i++) {
        const pageButton = document.getElementById(`page${i}`);
        const pageNum = currentPage - 1 + i;
        if (pageNum > 0 && pageNum <= totalPages) {
          pageButton.textContent = pageNum;
          pageButton.style.display = 'inline-flex';
          pageButton.onclick = () => changePage(pageNum - currentPage);
        } else {
          pageButton.style.display = 'none';
        }
      }
    }

    function changePage(direction) {
      const totalPages = Math.ceil(totalUsers / usersPerPage);
      if (direction === -1 && currentPage > 1) currentPage--;
      else if (direction === 1 && currentPage < totalPages) currentPage++;
      else if (typeof direction === 'number') currentPage = currentPage + direction;
      displayUsers(currentPage);
    }

    function openUserModal(name, username, status, posts, joined) {
      const modal = document.getElementById('userDetailsModal');
      document.getElementById('modalUserName').textContent = `User Details: ${name}`;
      document.getElementById('modalUserAvatar').src = `https://api.dicebear.com/6.x/avataaars/svg?seed=${username}`;
      document.getElementById('modalUserFullName').textContent = name;
      document.getElementById('modalUsername').textContent = `@${username}`;
      document.getElementById('modalUserStatus').textContent = status;
      document.getElementById('modalUserStatusDesc').textContent = `Since ${joined}`;
      document.getElementById('modalUserPosts').textContent = `${posts} Posts`;
      document.getElementById('modalUserComments').textContent = `${Math.floor(posts * 2.7)} Comments`;

      modal.showModal();
    }

    // Search functionality
    document.getElementById('searchBtn').addEventListener('click', function() {
      const searchTerm = document.getElementById('searchInput').value.toLowerCase();
      const joinDateFilter = document.getElementById('joinDateFilter').value;

      let filteredUsers = users;

      if (searchTerm) {
        filteredUsers = filteredUsers.filter(user =>
          user.name.toLowerCase().includes(searchTerm) ||
          user.username.toLowerCase().includes(searchTerm)
        );
      }

      if (joinDateFilter !== 'All Time') {
        const now = new Date();
        filteredUsers = filteredUsers.filter(user => {
          const joinedDate = new Date(user.joined);
          if (joinDateFilter === 'Today') return joinedDate.toDateString() === now.toDateString();
          if (joinDateFilter === 'This Week') return now - joinedDate <= 7 * 24 * 60 * 60 * 1000;
          if (joinDateFilter === 'This Month') return joinedDate.getMonth() === now.getMonth() && joinedDate.getFullYear() === now.getFullYear();
          if (joinDateFilter === 'Last 3 Months') return now - joinedDate <= 90 * 24 * 60 * 60 * 1000;
          return true;
        });
      }

      currentPage = 1;
      displayUsers(currentPage, filteredUsers);
    });

    // Initialize with first page and charts
    document.addEventListener('DOMContentLoaded', function() {
      displayUsers(currentPage);
      initializeCharts();
    });

    function initializeCharts() {
      // User Growth Chart
      const growthCtx = document.getElementById('userGrowthChart').getContext('2d');
      new Chart(growthCtx, {
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
          labels: ['18-24', '25-34', '35-44', '45-54', '55+'],
          datasets: [{
            data: [35, 28, 18, 12, 7],
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

      // User Activity Chart
      const activityCtx = document.getElementById('userActivityChart').getContext('2d');
      new Chart(activityCtx, {
        type: 'bar',
        data: {
          labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
          datasets: [
            { label: 'Morning', data: [1200, 1900, 1700, 1500, 1400, 800, 500], backgroundColor: '#3b82f6' },
            { label: 'Afternoon', data: [1800, 2100, 1900, 2300, 2200, 1500, 1200], backgroundColor: '#4ade80' },
            { label: 'Evening', data: [2500, 2300, 2400, 2600, 3000, 2800, 2200], backgroundColor: '#f87171' }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { position: 'top', labels: { color: '#e2e8f0' } },
            title: { display: true, text: 'Active Users by Day and Time', color: '#e2e8f0', font: { size: 16 } }
          },
          scales: {
            x: { stacked: true, grid: { color: 'rgba(148, 163, 184, 0.1)' }, ticks: { color: '#94a3b8' } },
            y: { stacked: true, grid: { color: 'rgba(148, 163, 184, 0.1)' }, ticks: { color: '#94a3b8' } }
          }
        }
      });
    }
  </script>
@endsection
