@extends('layouts.admin-layout')

@section('title', 'Posts')

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
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold">Posts Management</h1>
        <p class="text-sm text-gray-600">Monitor and moderate social media posts</p>
    </div>
    
    <div class="mt-4 md:mt-0 flex flex-col sm:flex-row gap-2">
        <div class="relative flex items-center gap-2">
            <!-- Search Input -->
            <div class="relative w-full sm:w-auto">
                <input id="searchInput" 
                    class="input input-bordered w-full sm:w-auto" 
                    placeholder="Search posts or hashtags..." 
                    oninput="showHashtagSuggestions(this.value)">
                
                <!-- Hashtag Suggestions Dropdown -->
                <div id="hashtagSuggestions" 
                    class="absolute z-10 top-full left-0 w-full bg-white shadow-md rounded-md hidden">
                </div>
            </div>

            <!-- Status Filter -->
            <select id="statusFilter" class="select select-bordered">
                <option value="" disabled selected>Select Status</option>
                <option value="Active">Active</option>
                <option value="Reported">Reported</option>
            </select>

            <!-- Search Button -->
            <button class="btn btn-primary" id="searchBtn">Search</button>
        </div>
    </div>
</div>


        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <div class="stat bg-base-200 rounded-box shadow">
                <div class="stat-figure text-primary">
                    <i class="fas fa-file-alt text-3xl"></i>
                </div>
                <div class="stat-title">Total Posts</div>
                <div class="stat-value text-primary">42.1K</div>
                <div class="stat-desc">↗︎ 560 (12%) this week</div>
            </div>
            <div class="stat bg-base-200 rounded-box shadow">
                <div class="stat-figure text-success">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
                <div class="stat-title">Active Posts</div>
                <div class="stat-value text-success">41.9K</div>
                <div class="stat-desc">99% of total posts</div>
            </div>
            <div class="stat bg-base-200 rounded-box shadow">
                <div class="stat-figure text-error">
                    <i class="fas fa-flag text-3xl"></i>
                </div>
                <div class="stat-title">Reported Posts</div>
                <div class="stat-value text-error">87</div>
                <div class="stat-desc">Awaiting moderation</div>
            </div>
        </div>

        <!-- Posts List -->
        <div class="card bg-base-200 shadow-xl mb-6">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="card-title">All Posts</h2>
                    <div class="flex gap-2">
                        <span class="badge badge-lg" id="postCountBadge">1-6 of 42,100</span>
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-sm btn-ghost">
                                <i class="fas fa-ellipsis-v"></i>
                            </label>
                            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-300 rounded-box w-52">
                                <li><a><i class="fas fa-download mr-2"></i>Export Data</a></li>
                                <li><a><i class="fas fa-trash mr-2"></i>Delete Selected</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Post</th>
                                <th>Author</th>
                                <th>Hashtags</th>
                                <th>Date</th>
                                <th>Engagement</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="postsTableBody">
                            <!-- Populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="flex justify-between items-center mt-4">
                    <div class="text-sm opacity-70" id="paginationInfo">Showing 1-6 of 42,100 posts</div>
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

        <!-- Moderation Section -->
        <div class="card bg-base-200 shadow-xl mb-6">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="card-title">Moderation Queue</h2>
                    <div class="flex gap-2">
                        <button class="btn btn-sm btn-success" onclick="bulkModerate('approve')">Approve Selected</button>
                        <button class="btn btn-sm btn-error" onclick="bulkModerate('reject')">Reject Selected</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll" onchange="toggleAllCheckboxes(this)"></th>
                                <th>Post</th>
                                <th>Author</th>
                                <th>Hashtags</th>
                                <th>Reported Date</th>
                                <th>Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="moderationTableBody">
                            <!-- Populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Hashtag Trends Section -->
        <div class="card bg-base-200 shadow-xl mb-6">
            <div class="card-body">
                <h2 class="card-title">Hashtag Trends</h2>
                <div class="h-80">
                    <canvas id="hashtagTrendsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Post Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="card bg-base-200 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Post Activity</h2>
                    <div class="h-80">
                        <canvas id="postActivityChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="card bg-base-200 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Engagement Metrics</h2>
                    <div class="h-80">
                        <canvas id="engagementMetricsChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="card bg-base-200 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Posts by Hashtag</h2>
                    <div class="h-80">
                        <canvas id="hashtagChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <!-- Post Details Modal -->
    <dialog id="postDetailsModal" class="modal">
        <div class="modal-box bg-base-200 max-w-3xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg mb-4" id="modalPostTitle">Post Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex flex-col items-center gap-4">
                    <div class="avatar">
                        <div class="w-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                            <img id="modalAuthorAvatar" src="https://api.dicebear.com/6.x/avataaars/svg?seed=sjohnson" alt="Author Avatar" />
                        </div>
                    </div>
                    <div class="text-center">
                        <h4 class="text-xl font-bold" id="modalAuthorName">Sarah Johnson</h4>
                        <p class="text-sm opacity-70" id="modalAuthorUsername">@sjohnson</p>
                    </div>
                </div>
                <div class="col-span-2">
                    <div class="stats stats-vertical shadow w-full">
                        <div class="stat">
                            <div class="stat-title">Status</div>
                            <div class="stat-value text-success" id="modalPostStatus">Active</div>
                            <div class="stat-desc" id="modalPostDate">Mar 13, 2025</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Engagement</div>
                            <div class="stat-value" id="modalPostEngagement">342 Likes</div>
                            <div class="stat-desc" id="modalPostComments">127 Comments</div>
                        </div>
                        <div class="stat">
                            <div class="stat-title">Reach</div>
                            <div class="stat-value" id="modalPostReach">15.6K</div>
                            <div class="stat-desc" id="modalPostShares">89 Shares</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6">
                <h4 class="font-bold text-lg">Content</h4>
                <p class="mt-2 text-sm" id="modalPostContent">Loading...</p>
                <p class="mt-2 text-sm"><strong>Hashtags:</strong> <span id="modalPostHashtags"></span></p>
            </div>
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn">Close</button>
                </form>
            </div>
        </div>
    </dialog>

    <!-- Moderation Modal -->
    <dialog id="moderationModal" class="modal">
        <div class="modal-box bg-base-200 max-w-3xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg mb-4">Moderate Reported Post</h3>
            <div class="mb-4">
                <p><strong>Author:</strong> <span id="modAuthorName"></span> (<span id="modAuthorUsername"></span>)</p>
                <p><strong>Content:</strong> <span id="modPostContent"></span></p>
                <p><strong>Hashtags:</strong> <span id="modPostHashtags"></span></p>
                <p><strong>Reported Date:</strong> <span id="modReportedDate"></span></p>
                <p><strong>Reason:</strong> <span id="modReason"></span></p>
            </div>
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Moderation Action</span>
                </label>
                <select id="moderationAction" class="select select-bordered w-full">
                    <option value="approve">Approve (Keep Active)</option>
                    <option value="reject">Reject (Remove Post)</option>
                </select>
            </div>
            <div class="form-control mt-4">
                <label class="label">
                    <span class="label-text">Feedback to User (Optional)</span>
                </label>
                <textarea id="moderationFeedback" class="textarea textarea-bordered" placeholder="Enter feedback for the user..."></textarea>
            </div>
            <div class="modal-action">
                <button class="btn btn-primary" onclick="submitModeration()">Submit</button>
                <form method="dialog">
                    <button class="btn">Cancel</button>
                </form>
            </div>
        </div>
    </dialog>

    <script>
        // Your JavaScript from the original posts.blade.php goes here
        const posts = [
            { id: 1, content: "Excited for the weekend!", author: "Sarah Johnson", username: "@sjohnson", hashtags: ["#weekend"], date: "Mar 13, 2025", likes: 342, comments: 127, status: "Active" },
            { id: 2, content: "New project launch today!", author: "Michael Chen", username: "@mchen", hashtags: ["#project"], date: "Mar 12, 2025", likes: 156, comments: 45, status: "Active" },
            { id: 3, content: "This might be offensive...", author: "Alex Rodriguez", username: "@arod", hashtags: ["#random"], date: "Mar 11, 2025", likes: 89, comments: 23, status: "Reported", reportedDate: "Mar 12, 2025", reason: "Inappropriate content" },
            { id: 4, content: "Great day outside!", author: "Emma Wilson", username: "@ewilson", hashtags: ["#nature"], date: "Mar 10, 2025", likes: 234, comments: 67, status: "Active" },
            { id: 5, content: "Happy Monday everyone!", author: "David Kim", username: "@dkim", hashtags: ["#weekend", "#motivation"], date: "Mar 9, 2025", likes: 198, comments: 54, status: "Active" },
            { id: 6, content: "Spam advertisement", author: "Sophia Lee", username: "@sophialee", hashtags: ["#spam"], date: "Mar 8, 2025", likes: 12, comments: 5, status: "Reported", reportedDate: "Mar 9, 2025", reason: "Spam" },
        ];

        let currentPage = 1;
        const postsPerPage = 6;
        const totalPosts = 42100;

        function displayPosts(page, filteredPosts = null) {
            const postsToDisplay = filteredPosts || posts;
            const start = (page - 1) * postsPerPage;
            const end = Math.min(start + postsPerPage, postsToDisplay.length);
            const paginatedPosts = postsToDisplay.slice(start, end);
            const tableBody = document.getElementById('postsTableBody');
            tableBody.innerHTML = '';

            paginatedPosts.forEach(post => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="max-w-xs truncate">${post.content}</div>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="avatar">
                                <div class="w-8 rounded-full">
                                    <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=${post.username.replace('@', '')}" alt="Author Avatar" />
                                </div>
                            </div>
                            <div>${post.author}</div>
                        </div>
                    </td>
                    <td>${post.hashtags.join(', ')}</td>
                    <td>${post.date}</td>
                    <td>${post.likes} Likes, ${post.comments} Comments</td>
                    <td><div class="badge ${post.status === 'Active' ? 'badge-success' : 'badge-error'}">${post.status}</div></td>
                    <td>
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-ghost btn-xs">
                                <i class="fas fa-ellipsis-v"></i>
                            </label>
                            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-200 rounded-box w-52">
                                <li><a onclick="openPostModal('${post.content}', '${post.author}', '${post.username}', '${post.status}', ${post.likes}, ${post.comments}, '${post.date}', '${post.hashtags.join(', ')}')"><i class="fas fa-eye mr-2"></i>View Details</a></li>
                                ${post.status === 'Reported' ? `<li><a onclick="openModerationModal('${post.content}', '${post.author}', '${post.username}', '${post.reportedDate}', '${post.reason}', ${post.id}, '${post.hashtags.join(', ')}')"><i class="fas fa-gavel mr-2"></i>Moderate</a></li>` : ''}
                                <li><a><i class="fas fa-trash mr-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            updatePagination(filteredPosts);
            displayModerationQueue();
        }

        // Include all remaining JavaScript functions from your original posts.blade.php here
        function updatePagination(filteredPosts = null) {
            const totalItems = filteredPosts ? filteredPosts.length : totalPosts;
            const totalPages = Math.ceil(totalItems / postsPerPage);
            
            document.getElementById('paginationInfo').textContent = `Showing ${(currentPage - 1) * postsPerPage + 1}-${Math.min(currentPage * postsPerPage, totalItems)} of ${totalItems} posts`;
            document.getElementById('postCountBadge').textContent = `${(currentPage - 1) * postsPerPage + 1}-${Math.min(currentPage * postsPerPage, totalItems)} of ${totalItems}`;

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
            const totalPages = Math.ceil(totalPosts / postsPerPage);
            if (direction === -1 && currentPage > 1) currentPage--;
            else if (direction === 1 && currentPage < totalPages) currentPage++;
            else if (typeof direction === 'number') currentPage = currentPage + direction;
            displayPosts(currentPage);
        }

        function openPostModal(content, author, username, status, likes, comments, date, hashtags) {
            const modal = document.getElementById('postDetailsModal');
            document.getElementById('modalPostTitle').textContent = `Post Details`;
            document.getElementById('modalAuthorAvatar').src = `https://api.dicebear.com/6.x/avataaars/svg?seed=${username.replace('@', '')}`;
            document.getElementById('modalAuthorName').textContent = author;
            document.getElementById('modalAuthorUsername').textContent = username;
            document.getElementById('modalPostStatus').textContent = status;
            document.getElementById('modalPostDate').textContent = date;
            document.getElementById('modalPostEngagement').textContent = `${likes} Likes`;
            document.getElementById('modalPostComments').textContent = `${comments} Comments`;
            document.getElementById('modalPostReach').textContent = `${Math.floor(likes * 45.6)}`;
            document.getElementById('modalPostShares').textContent = `${Math.floor(comments * 0.7)} Shares`;
            document.getElementById('modalPostContent').textContent = content;
            document.getElementById('modalPostHashtags').textContent = hashtags;
            
            modal.showModal();
        }

        function displayModerationQueue() {
            const reportedPosts = posts.filter(post => post.status === 'Reported');
            const tableBody = document.getElementById('moderationTableBody');
            tableBody.innerHTML = '';

            reportedPosts.forEach(post => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><input type="checkbox" class="checkbox" data-post-id="${post.id}"></td>
                    <td>
                        <div class="max-w-xs truncate">${post.content}</div>
                    </td>
                    <niejs

td>${post.author} (${post.username})</td>
                    <td>${post.hashtags.join(', ')}</td>
                    <td>${post.reportedDate}</td>
                    <td>${post.reason}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="openModerationModal('${post.content}', '${post.author}', '${post.username}', '${post.reportedDate}', '${post.reason}', ${post.id}, '${post.hashtags.join(', ')}')">Moderate</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        let currentPostId = null;

        function openModerationModal(content, author, username, reportedDate, reason, postId, hashtags) {
            const modal = document.getElementById('moderationModal');
            document.getElementById('modAuthorName').textContent = author;
            document.getElementById('modAuthorUsername').textContent = username;
            document.getElementById('modPostContent').textContent = content;
            document.getElementById('modPostHashtags').textContent = hashtags;
            document.getElementById('modReportedDate').textContent = reportedDate;
            document.getElementById('modReason').textContent = reason;
            document.getElementById('moderationAction').value = 'approve';
            document.getElementById('moderationFeedback').value = '';
            currentPostId = postId;
            
            modal.showModal();
        }

        function submitModeration() {
            const action = document.getElementById('moderationAction').value;
            const feedback = document.getElementById('moderationFeedback').value;
            const postIndex = posts.findIndex(post => post.id === currentPostId);

            if (postIndex !== -1) {
                if (action === 'approve') {
                    posts[postIndex].status = 'Active';
                    alert(`Post approved. Feedback sent to user: "${feedback || 'No feedback provided'}"`);
                } else if (action === 'reject') {
                    posts.splice(postIndex, 1); // Remove post
                    alert(`Post removed. Feedback sent to user: "${feedback || 'No feedback provided'}"`);
                }
                displayPosts(currentPage);
                document.getElementById('moderationModal').close();
            }
        }

        function toggleAllCheckboxes(source) {
            const checkboxes = document.querySelectorAll('#moderationTableBody .checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = source.checked);
        }

        function bulkModerate(action) {
            const checkboxes = document.querySelectorAll('#moderationTableBody .checkbox:checked');
            if (checkboxes.length === 0) {
                alert('Please select at least one post to moderate.');
                return;
            }

            const postIds = Array.from(checkboxes).map(cb => parseInt(cb.getAttribute('data-post-id')));
            postIds.forEach(postId => {
                const postIndex = posts.findIndex(post => post.id === postId);
                if (postIndex !== -1) {
                    if (action === 'approve') {
                        posts[postIndex].status = 'Active';
                    } else if (action === 'reject') {
                        posts.splice(postIndex, 1);
                    }
                }
            });
            alert(`${action === 'approve' ? 'Approved' : 'Rejected'} ${checkboxes.length} post(s).`);
            displayPosts(currentPage);
        }

        function showHashtagSuggestions(input) {
            const suggestionsDiv = document.getElementById('hashtagSuggestions');
            const hashtagList = ['#weekend', '#project', '#nature', '#spam', '#motivation', '#random'];
            const filteredHashtags = hashtagList.filter(tag => tag.toLowerCase().includes(input.toLowerCase()) && input.length > 0);

            if (filteredHashtags.length > 0) {
                suggestionsDiv.innerHTML = filteredHashtags.map(tag => `
                    <div class="p-2 hover:bg-base-300 cursor-pointer" onclick="document.getElementById('searchInput').value = '${tag}'; this.parentElement.classList.add('hidden'); searchPosts();">${tag}</div>
                `).join('');
                suggestionsDiv.classList.remove('hidden');
            } else {
                suggestionsDiv.classList.add('hidden');
            }
        }

        function searchPosts() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            
            let filteredPosts = posts;
            
            if (searchTerm) {
                filteredPosts = filteredPosts.filter(post => 
                    post.content.toLowerCase().includes(searchTerm) || 
                    post.author.toLowerCase().includes(searchTerm) || 
                    post.username.toLowerCase().includes(searchTerm) || 
                    post.hashtags.some(tag => tag.toLowerCase().includes(searchTerm))
                );
            }
            
            if (statusFilter && statusFilter !== 'Status') {
                filteredPosts = filteredPosts.filter(post => post.status === statusFilter);
            }
            
            currentPage = 1;
            displayPosts(currentPage, filteredPosts);
        }

        document.getElementById('searchBtn').addEventListener('click', searchPosts);

        // Initialize with first page and charts
        document.addEventListener('DOMContentLoaded', function() {
            displayPosts(currentPage);
            initializeCharts();
        });

        function initializeCharts() {
            // Post Activity Chart
            const activityCtx = document.getElementById('postActivityChart').getContext('2d');
            new Chart(activityCtx, {
                type: 'line',
                data: {
                    labels: ['Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
                    datasets: [
                        {
                            label: 'Posts Created',
                            data: [3200, 3500, 3800, 4100, 3900, 4200, 4500],
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Posts Reported',
                            data: [50, 60, 55, 70, 65, 80, 87],
                            borderColor: '#f87171',
                            backgroundColor: 'rgba(248, 113, 113, 0.1)',
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
                        title: { display: true, text: 'Post Activity Over Time', color: '#e2e8f0', font: { size: 16 } }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(148, 163, 184, 0.1)' }, ticks: { color: '#94a3b8' } },
                        x: { grid: { color: 'rgba(148, 163, 184, 0.1)' }, ticks: { color: '#94a3b8' } }
                    }
                }
            });

            // Engagement Metrics Chart
            const engagementCtx = document.getElementById('engagementMetricsChart').getContext('2d');
            new Chart(engagementCtx, {
                type: 'bar',
                data: {
                    labels: ['Likes', 'Comments', 'Shares', 'Views'],
                    datasets: [
                        {
                            label: 'Active Posts',
                            data: [12500, 3400, 1800, 45600],
                            backgroundColor: '#3b82f6'
                        },
                        {
                            label: 'Reported Posts',
                            data: [300, 150, 50, 1200],
                            backgroundColor: '#f87171'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { color: '#e2e8f0' } },
                        title: { display: true, text: 'Engagement Metrics', color: '#e2e8f0', font: { size: 16 } }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(148, 163, 184, 0.1)' }, ticks: { color: '#94a3b8' } },
                        x: { grid: { color: 'rgba(148, 163, 184, 0.1)' }, ticks: { color: '#94a3b8' } }
                    }
                }
            });

            // Posts by Hashtag Chart
            const hashtagCtx = document.getElementById('hashtagChart').getContext('2d');
            const hashtagCounts = {};
            posts.forEach(post => {
                post.hashtags.forEach(tag => {
                    hashtagCounts[tag] = (hashtagCounts[tag] || 0) + 1;
                });
            });
            new Chart(hashtagCtx, {
                type: 'pie',
                data: {
                    labels: Object.keys(hashtagCounts),
                    datasets: [{
                        data: Object.values(hashtagCounts),
                        backgroundColor: ['#3b82f6', '#4ade80', '#facc15', '#f87171', '#38bdf8'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { color: '#e2e8f0' } },
                        title: { display: true, text: 'Posts by Hashtag', color: '#e2e8f0', font: { size: 16 } }
                    }
                }
            });

            // Hashtag Trends Chart
            const trendsCtx = document.getElementById('hashtagTrendsChart').getContext('2d');
            new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: ['Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
                    datasets: [
                        {
                            label: '#weekend',
                            data: [500, 550, 600, 620, 580, 610, 650],
                            borderColor: '#3b82f6',
                            tension: 0.4
                        },
                        {
                            label: '#project',
                            data: [300, 320, 350, 380, 400, 420, 450],
                            borderColor: '#4ade80',
                            tension: 0.4
                        },
                        {
                            label: '#nature',
                            data: [200, 210, 230, 250, 270, 290, 310],
                            borderColor: '#facc15',
                            tension: 0.4
                        },
                        {
                            label: '#spam',
                            data: [20, 25, 30, 35, 40, 45, 50],
                            borderColor: '#f87171',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { color: '#e2e8f0' } },
                        title: { display: true, text: 'Hashtag Trends Over Time', color: '#e2e8f0', font: { size: 16 } }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(148, 163, 184, 0.1)' }, ticks: { color: '#94a3b8' } },
                        x: { grid: { color: 'rgba(148, 163, 184, 0.1)' }, ticks: { color: '#94a3b8' } }
                    }
                }
            });
        }
    </script>
@endsection