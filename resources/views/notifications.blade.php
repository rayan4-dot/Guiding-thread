<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifications - Social Media Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'primary': '#1e40af',
            'primary-focus': '#1e3a8a',
            'primary-content': '#ffffff',
            'secondary': '#3b82f6',
            'accent': '#0ea5e9',
            'neutral': '#1f2937',
            'base-100': '#0f172a',
            'base-200': '#1e293b',
            'base-300': '#334155',
            'info': '#38bdf8',
            'success': '#4ade80',
            'warning': '#facc15',
            'error': '#f87171',
          }
        }
      },
      daisyui: {
        themes: [
          {
            dark: {
              'primary': '#1e40af',
              'primary-focus': '#1e3a8a',
              'primary-content': '#ffffff',
              'secondary': '#3b82f6',
              'accent': '#0ea5e9',
              'neutral': '#1f2937',
              'base-100': '#0f172a',
              'base-200': '#1e293b',
              'base-300': '#334155',
              'info': '#38bdf8',
              'success': '#4ade80',
              'warning': '#facc15',
              'error': '#f87171',
            },
          },
        ],
      },
    }
  </script>
</head>
<body class="bg-base-100 text-base-content">
  <div class="drawer lg:drawer-open">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col">
      <!-- Navbar -->
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

      <!-- Main Content -->
      <main class="flex-1 overflow-y-auto p-4 lg:p-6 bg-base-100">
        <!-- Page Header -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6">
          <div>
            <h1 class="text-2xl font-bold">Notifications</h1>
            <p class="text-sm opacity-70">Manage and review platform notifications</p>
          </div>
          <div class="mt-4 lg:mt-0 flex flex-col sm:flex-row gap-2">
            <div class="join">
              <div class="form-control">
                <div class="input-group">
                  <input type="text" placeholder="Search notifications..." class="input input-sm input-bordered" />
                  <button class="btn btn-sm btn-square">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
          <div class="stat bg-base-200 rounded-box shadow">
            <div class="stat-figure text-info">
              <i class="fas fa-bell text-3xl"></i>
            </div>
            <div class="stat-title">Total Notifications</div>
            <div class="stat-value text-info">8,452</div>
            <div class="stat-desc">↗︎ 1,234 (17%) this week</div>
          </div>
          
          <div class="stat bg-base-200 rounded-box shadow">
            <div class="stat-figure text-success">
              <i class="fas fa-check-circle text-3xl"></i>
            </div>
            <div class="stat-title">Read Notifications</div>
            <div class="stat-value text-success">6,789</div>
            <div class="stat-desc">80% of total</div>
          </div>
          
          <div class="stat bg-base-200 rounded-box shadow">
            <div class="stat-figure text-warning">
              <i class="fas fa-exclamation-circle text-3xl"></i>
            </div>
            <div class="stat-title">Unread Notifications</div>
            <div class="stat-value text-warning">1,663</div>
            <div class="stat-desc">↗︎ 85 (6%) today</div>
          </div>
          
          <div class="stat bg-base-200 rounded-box shadow">
            <div class="stat-figure text-error">
              <i class="fas fa-times-circle text-3xl"></i>
            </div>
            <div class="stat-title">Critical Alerts</div>
            <div class="stat-value text-error">42</div>
            <div class="stat-desc">Requires immediate action</div>
          </div>
        </div>

        <!-- Notification Management Tools -->
        <div class="card bg-base-200 shadow-xl mb-6">
          <div class="card-body">
            <h2 class="card-title">Notification Filters</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-2">
              <div>
                <label class="label">
                  <span class="label-text">Notification Type</span>
                </label>
                <select class="select select-bordered w-full">
                  <option selected>All Types</option>
                  <option>User Reports</option>
                  <option>Post Flags</option>
                  <option>System Alerts</option>
                  <option>Engagement Alerts</option>
                </select>
              </div>
              <div>
                <label class="label">
                  <span class="label-text">Status</span>
                </label>
                <select class="select select-bordered w-full">
                  <option selected>All Statuses</option>
                  <option>Read</option>
                  <option>Unread</option>
                  <option>Archived</option>
                  <option>Critical</option>
                </select>
              </div>
              <div>
                <label class="label">
                  <span class="label-text">Date Range</span>
                </label>
                <select class="select select-bordered w-full">
                  <option selected>Last 7 Days</option>
                  <option>Today</option>
                  <option>Last 30 Days</option>
                  <option>This Month</option>
                  <option>Custom Range</option>
                </select>
              </div>
              <div>
                <label class="label">
                  <span class="label-text">Priority</span>
                </label>
                <select class="select select-bordered w-full">
                  <option selected>All Priorities</option>
                  <option>Low</option>
                  <option>Medium</option>
                  <option>High</option>
                  <option>Critical</option>
                </select>
              </div>
            </div>
            <div class="flex justify-end mt-4">
              <button class="btn btn-primary">
                <i class="fas fa-filter mr-1"></i> Apply Filters
              </button>
            </div>
          </div>
        </div>

        <!-- Notifications List -->
        <div class="card bg-base-200 shadow-xl mb-6">
          <div class="card-body">
            <div class="flex justify-between items-center mb-4">
              <h2 class="card-title">All Notifications</h2>
              <div class="flex gap-2">
                <span class="badge badge-lg">1-20 of 8,452</span>
                <div class="dropdown dropdown-end">
                  <label tabindex="0" class="btn btn-sm btn-ghost">
                    <i class="fas fa-ellipsis-v"></i>
                  </label>
                  <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-300 rounded-box w-52">
                    <li><a><i class="fas fa-download mr-2"></i>Export Data</a></li>
                    <li><a><i class="fas fa-trash mr-2"></i>Bulk Delete</a></li>
                    <li><a><i class="fas fa-archive mr-2"></i>Bulk Archive</a></li>
                  </ul>
                </div>
              </div>
            </div>
            
            <div class="overflow-x-auto">
              <table class="table table-zebra">
                <thead>
                  <tr>
                    <th>Notification</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Date</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody id="notificationsTableBody">
                  <!-- Notification 1 -->
                  <tr>
                    <td>
                      <div class="flex items-center gap-3">
                        <div class="avatar">
                          <div class="w-12 h-12 mask mask-squircle bg-base-300 flex items-center justify-center">
                            <i class="fas fa-user text-xl"></i>
                          </div>
                        </div>
                        <div>
                          <div class="font-bold">New user report submitted</div>
                          <div class="text-sm opacity-70 truncate w-48">User @sophialee reported for harassment</div>
                        </div>
                      </div>
                    </td>
                    <td>User Reports</td>
                    <td><div class="badge badge-warning">Unread</div></td>
                    <td><div class="badge badge-error">Critical</div></td>
                    <td>Mar 2, 2025</td>
                    <td>
                      <div class="flex gap-1">
                        <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-check"></i></button>
                        <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-trash"></i></button>
                      </div>
                    </td>
                  </tr>
                  
                  <!-- Notification 2 -->
                  <tr>
                    <td>
                      <div class="flex items-center gap-3">
                        <div class="avatar">
                          <div class="w-12 h-12 mask mask-squircle bg-base-300 flex items-center justify-center">
                            <i class="fas fa-flag text-xl"></i>
                          </div>
                        </div>
                        <div>
                          <div class="font-bold">Post flagged by users</div>
                          <div class="text-sm opacity-70 truncate w-48">Post by @mchen flagged for hate speech</div>
                        </div>
                      </div>
                    </td>
                    <td>Post Flags</td>
                    <td><div class="badge badge-success">Read</div></td>
                    <td><div class="badge badge-warning">High</div></td>
                    <td>Mar 2, 2025</td>
                    <td>
                      <div class="flex gap-1">
                        <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-check"></i></button>
                        <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-trash"></i></button>
                      </div>
                    </td>
                  </tr>
                  
                  <!-- Notification 3 -->
                  <tr>
                    <td>
                      <div class="flex items-center gap-3">
                        <div class="avatar">
                          <div class="w-12 h-12 mask mask-squircle bg-base-300 flex items-center justify-center">
                            <i class="fas fa-exclamation-circle text-xl"></i>
                          </div>
                        </div>
                        <div>
                          <div class="font-bold">Server overload warning</div>
                          <div class="text-sm opacity-70 truncate w-48">System performance degraded</div>
                        </div>
                      </div>
                    </td>
                    <td>System Alerts</td>
                    <td><div class="badge badge-error">Critical</div></td>
                    <td><div class="badge badge-error">Critical</div></td>
                    <td>Mar 1, 2025</td>
                    <td>
                      <div class="flex gap-1">
                        <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-check"></i></button>
                        <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-trash"></i></button>
                      </div>
                    </td>
                  </tr>
                  
                  <!-- Notification 4 -->
                  <tr>
                    <td>
                      <div class="flex items-center gap-3">
                        <div class="avatar">
                          <div class="w-12 h-12 mask mask-squircle bg-base-300 flex items-center justify-center">
                            <i class="fas fa-heart text-xl"></i>
                          </div>
                        </div>
                        <div>
                          <div class="font-bold">High engagement on post</div>
                          <div class="text-sm opacity-70 truncate w-48">Post by @jtaylor reached 10K likes</div>
                        </div>
                      </div>
                    </td>
                    <td>Engagement Alerts</td>
                    <td><div class="badge badge-success">Read</div></td>
                    <td><div class="badge badge-success">Low</div></td>
                    <td>Mar 1, 2025</td>
                    <td>
                      <div class="flex gap-1">
                        <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-check"></i></button>
                        <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-trash"></i></button>
                      </div>
                    </td>
                  </tr>
                  
                  <!-- Notification 5 -->
                  <tr>
                    <td>
                      <div class="flex items-center gap-3">
                        <div class="avatar">
                          <div class="w-12 h-12 mask mask-squircle bg-base-300 flex items-center justify-center">
                            <i class="fas fa-user text-xl"></i>
                          </div>
                        </div>
                        <div>
                          <div class="font-bold">New user signup alert</div>
                          <div class="text-sm opacity-70 truncate w-48">User @newuser joined the platform</div>
                        </div>
                      </div>
                    </td>
                    <td>User Reports</td>
                    <td><div class="badge badge-warning">Unread</div></td>
                    <td><div class="badge badge-info">Medium</div></td>
                    <td>Feb 28, 2025</td>
                    <td>
                      <div class="flex gap-1">
                        <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-check"></i></button>
                        <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-trash"></i></button>
                      </div>
                    </td>
                  </tr>
                  
                  <!-- Notification 6 -->
                  <tr>
                    <td>
                      <div class="flex items-center gap-3">
                        <div class="avatar">
                          <div class="w-12 h-12 mask mask-squircle bg-base-300 flex items-center justify-center">
                            <i class="fas fa-flag text-xl"></i>
                          </div>
                        </div>
                        <div>
                          <div class="font-bold">Comment flagged by moderator</div>
                          <div class="text-sm opacity-70 truncate w-48">Comment on post by @ewilson flagged</div>
                        </div>
                      </div>
                    </td>
                    <td>Post Flags</td>
                    <td><div class="badge badge-success">Read</div></td>
                    <td><div class="badge badge-warning">High</div></td>
                    <td>Feb 28, 2025</td>
                    <td>
                      <div class="flex gap-1">
                        <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-check"></i></button>
                        <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-trash"></i></button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            
            <!-- Pagination -->
            <div class="flex justify-center mt-4">
              <div class="join">
                <button class="join-item btn btn-sm" onclick="changePage(-1)">«</button>
                <button class="join-item btn btn-sm" onclick="changePage(-1)">‹</button>
                <button class="join-item btn btn-sm btn-active" id="page1">1</button>
                <button class="join-item btn btn-sm" id="page2">2</button>
                <button class="join-item btn btn-sm" id="page3">3</button>
                <button class="join-item btn btn-sm" id="page4">4</button>
                <button class="join-item btn btn-sm" onclick="changePage(1)">›</button>
                <button class="join-item btn btn-sm" onclick="changePage(1)">»</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Notification Analytics -->
        <div class="card bg-base-200 shadow-xl mb-6">
          <div class="card-body">
            <h2 class="card-title mb-4">Notification Analytics</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <!-- Notification Volume Chart -->
              <div class="h-80">
                <canvas id="notificationVolumeChart"></canvas>
              </div>
              
              <!-- Notification Type Distribution -->
              <div class="h-80">
                <canvas id="notificationTypeChart"></canvas>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
    
    <!-- Sidebar (Updated) -->
    <div class="drawer-side">
      <label for="my-drawer" class="drawer-overlay"></label>
      <aside class="bg-base-200 w-64 h-screen">
        <div class="px-4 py-8 border-b border-base-300">
          <div class="flex flex-col items-center gap-2 mb-8 text-center">
            <div class="avatar">
              <div class="w-16 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=admin" alt="Admin Avatar" />
              </div>
            </div>
            <div class="space-y-2">
              <div class="font-bold text-2xl tracking-wide">Admin Panel</div>
              <div class="text-lg opacity-70">Social Media Manager</div>
            </div>
          </div>
        </div>
        <div class="px-4 py-6 flex-1">
          <ul class="menu menu-md bg-base-200 rounded-none space-y-2">
            <li><a href="index.html" class="${window.location.pathname === '/index.html' ? 'active' : ''}"><i class="fas fa-chart-line mr-2"></i>Dashboard</a></li>
            <li><a href="users.html" class="${window.location.pathname === '/users.html' ? 'active' : ''}"><i class="fas fa-users mr-2"></i>Users</a></li>
            <li><a href="posts.html" class="${window.location.pathname === '/posts.html' ? 'active' : ''}"><i class="fas fa-file-alt mr-2"></i>Posts</a></li>
            <li><a href="notifications.html" class="${window.location.pathname === '/notifications.html' ? 'active' : ''}"><i class="fas fa-bell mr-2"></i>Notifications</a></li>
          </ul>
        </div>
        <div class="px-4 py-6 border-t border-base-300">
          <div class="flex flex-col items-start gap-4">
            <div class="flex items-center justify-between w-full">
              <div>
                <div class="text-sm font-bold">System Status</div>
                <div class="text-xs opacity-70">All systems operational</div>
              </div>
              <div class="badge badge-success">Online</div>
            </div>
            <button class="btn btn-outline btn-error btn-sm w-full"><i class="fas fa-sign-out-alt mr-2"></i>Logout</button>
          </div>
        </div>
      </aside>
    </div>
  </div>

  <script>
    // Notification data (simulating 8,452 notifications for pagination)
    const notifications = [
      { message: "New user report submitted", details: "User @sophialee reported for harassment", type: "User Reports", status: "Unread", priority: "Critical", date: "Mar 2, 2025" },
      { message: "Post flagged by users", details: "Post by @mchen flagged for hate speech", type: "Post Flags", status: "Read", priority: "High", date: "Mar 2, 2025" },
      { message: "Server overload warning", details: "System performance degraded", type: "System Alerts", status: "Critical", priority: "Critical", date: "Mar 1, 2025" },
      { message: "High engagement on post", details: "Post by @jtaylor reached 10K likes", type: "Engagement Alerts", status: "Read", priority: "Low", date: "Mar 1, 2025" },
      { message: "New user signup alert", details: "User @newuser joined the platform", type: "User Reports", status: "Unread", priority: "Medium", date: "Feb 28, 2025" },
      { message: "Comment flagged by moderator", details: "Comment on post by @ewilson flagged", type: "Post Flags", status: "Read", priority: "High", date: "Feb 28, 2025" },
      // Add more notifications to reach 8,452 if needed, but for simplicity, we'll use these 6 for demonstration
    ];

    let currentPage = 1;
    const notificationsPerPage = 6;

    function displayNotifications(page) {
      const start = (page - 1) * notificationsPerPage;
      const end = start + notificationsPerPage;
      const paginatedNotifications = notifications.slice(start, end);
      const tableBody = document.getElementById('notificationsTableBody');
      tableBody.innerHTML = '';

      paginatedNotifications.forEach(notification => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>
            <div class="flex items-center gap-3">
              <div class="avatar">
                <div class="w-12 h-12 mask mask-squircle bg-base-300 flex items-center justify-center">
                  <i class="fas fa-${notification.type === 'User Reports' ? 'user' : notification.type === 'Post Flags' ? 'flag' : notification.type === 'System Alerts' ? 'exclamation-circle' : 'heart'} text-xl"></i>
                </div>
              </div>
              <div>
                <div class="font-bold">${notification.message}</div>
                <div class="text-sm opacity-70 truncate w-48">${notification.details}</div>
              </div>
            </div>
          </td>
          <td>${notification.type}</td>
          <td><div class="badge ${notification.status === 'Read' ? 'badge-success' : notification.status === 'Unread' ? 'badge-warning' : 'badge-error'}">${notification.status}</div></td>
          <td><div class="badge ${notification.priority === 'Low' ? 'badge-success' : notification.priority === 'Medium' ? 'badge-info' : notification.priority === 'High' ? 'badge-warning' : 'badge-error'}">${notification.priority}</div></td>
          <td>${notification.date}</td>
          <td>
            <div class="flex gap-1">
              <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-eye"></i></button>
              <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-check"></i></button>
              <button class="btn btn-xs btn-ghost btn-square"><i class="fas fa-trash"></i></button>
            </div>
          </td>
        `;
        tableBody.appendChild(row);
      });

      updatePagination();
    }

    function updatePagination() {
      const totalPages = Math.ceil(notifications.length / notificationsPerPage);
      const badge = document.querySelector('.badge-lg');
      badge.textContent = `${(currentPage - 1) * notificationsPerPage + 1}-${Math.min(currentPage * notificationsPerPage, notifications.length)} of ${notifications.length}`;

      const buttons = document.querySelectorAll('.join-item.btn');
      buttons.forEach((button, index) => {
        if (index === 0) button.disabled = currentPage === 1; // «
        else if (index === 1) button.disabled = currentPage === 1; // ‹
        else if (index === buttons.length - 2) button.disabled = currentPage === totalPages; // ›
        else if (index === buttons.length - 1) button.disabled = currentPage === totalPages; // »
        else if (index - 2 === currentPage) button.classList.add('btn-active');
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
      const totalPages = Math.ceil(notifications.length / notificationsPerPage);
      if (direction === -1 && currentPage > 1) currentPage--;
      else if (direction === 1 && currentPage < totalPages) currentPage++;
      else if (typeof direction === 'number') currentPage = direction;
      displayNotifications(currentPage);
    }

    // Initialize with first page
    displayNotifications(currentPage);

    // Notification Analytics Charts
    document.addEventListener('DOMContentLoaded', function() {
      // Notification Volume Chart
      const volumeCtx = document.getElementById('notificationVolumeChart');
      new Chart(volumeCtx, {
        type: 'line',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
          datasets: [
            {
              label: 'Notifications',
              data: [600, 750, 850, 900, 1,200, 1,450],
              borderColor: '#3b82f6',
              backgroundColor: 'rgba(59, 130, 246, 0.1)',
              tension: 0.4,
              fill: true
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                color: 'rgba(255, 255, 255, 0.1)'
              }
            },
            x: {
              grid: {
                color: 'rgba(255, 255, 255, 0.1)'
              }
            }
          },
          plugins: {
            title: {
              display: true,
              text: 'Monthly Notification Volume',
              color: '#ffffff',
              font: {
                size: 16
              }
            },
            legend: {
              display: false
            }
          }
        }
      });

      // Notification Type Distribution Chart
      const typeCtx = document.getElementById('notificationTypeChart');
      new Chart(typeCtx, {
        type: 'doughnut',
        data: {
          labels: ['User Reports', 'Post Flags', 'System Alerts', 'Engagement Alerts'],
          datasets: [{
            data: [35, 30, 20, 15],
            backgroundColor: [
              '#3b82f6',
              '#0ea5e9',
              '#4ade80',
              '#f87171'
            ],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            title: {
              display: true,
              text: 'Notification Type Distribution',
              color: '#ffffff',
               font: {
                size: 16
              }
            },
            legend: {
              position: 'bottom'
            }
          }
        }
      });
    });
  </script>
</body>
</html>