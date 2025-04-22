@extends('layouts.admin-layout')

@section('title', 'Posts')

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
            <form id="filters" method="GET" action="{{ route('admin.posts') }}" class="relative flex items-center gap-2">
                <input id="searchInput" name="search" class="input input-bordered w-full sm:w-auto" placeholder="Search posts or hashtags..." value="{{ request('search') }}">
                <select id="statusFilter" name="status" class="select select-bordered">
                    <option value="" {{ !request('status') ? 'selected' : '' }}>All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="reported" {{ request('status') === 'reported' ? 'selected' : '' }}>Reported</option>
                </select>
                <button type="submit" class="btn btn-primary" id="searchBtn">Search</button>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="stat bg-base-200 rounded-box shadow">
            <div class="stat-figure text-primary">
                <i class="fas fa-file-alt text-3xl"></i>
            </div>
            <div class="stat-title">Total Posts</div>
            <div class="stat-value text-primary">{{ number_format($stats['total_posts']) }}</div>
            <div class="stat-desc">↗︎ {{ number_format($stats['new_posts_week']) }} ({{ $stats['post_growth_percent'] }}%) this week</div>
        </div>
        <div class="stat bg-base-200 rounded-box shadow">
            <div class="stat-figure text-success">
                <i class="fas fa-check-circle text-3xl"></i>
            </div>
            <div class="stat-title">Active Posts</div>
            <div class="stat-value text-success">{{ number_format($stats['active_posts']) }}</div>
            <div class="stat-desc">{{ $stats['active_posts_percent'] }}% of total posts</div>
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
                    <span class="badge badge-lg">{{ $posts->firstItem() }}-{{ $posts->lastItem() }} of {{ $posts->total() }}</span>
                    <div class="dropdown dropdown-end">
                        <label tabindex="0" class="btn btn-sm btn-ghost">
                            <i class="fas fa-ellipsis-v"></i>
                        </label>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-300 rounded-box w-52">
                            <li><a><i class="fas fa-download mr-2"></i>Export Data</a></li>
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
    @foreach($posts as $post)
        <tr>
            <td>
                <div class="max-w-xs truncate">{{ $post->content }}</div>
            </td>
            <td>
                <div class="flex items-center gap-2">
                    <div class="avatar">
                        <div class="w-8 rounded-full">
                            <img src="{{ $post->user->profile_picture ? Storage::url($post->user->profile_picture) : 'https://api.dicebear.com/6.x/avataaars/svg?seed=' . ($post->user->username ?? 'user' . $post->user->id) }}" alt="Author Avatar" />
                        </div>
                    </div>
                    <div>{{ $post->user->name }}</div>
                </div>
            </td>
            <td>{{ $post->hashtags->pluck('name')->join(', ') ?: 'None' }}</td>
            <td>{{ $post->created_at->format('M d, Y') }}</td>
            <td>{{ $post->reactions_count }} Likes, {{ $post->comments_count }} Comments</td>
            <td><div class="badge badge-success">Active</div></td>
            <td>
                <div class="flex items-center gap-2">
                    <button onclick="viewPostDetails({{ $post->id }})" class="btn btn-ghost btn-xs">
                        <i class="fas fa-eye"></i> View
                    </button>
                    <form id="delete-post-{{ $post->id }}" action="{{ route('admin.posts.destroy', $post) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-ghost btn-xs text-error" onclick="return confirm('Are you sure you want to delete this post?')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @endforeach
</tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="flex justify-between items-center mt-4">
                <div class="text-sm opacity-70">Showing {{ $posts->firstItem() }}-{{ $posts->lastItem() }} of {{ $posts->total() }} posts</div>
                <div class="join">
                    <a class="join-item btn {{ $posts->onFirstPage() ? 'disabled' : '' }}" href="{{ $posts->previousPageUrl() }}">«</a>
                    @for($i = max(1, $posts->currentPage() - 1); $i <= min($posts->lastPage(), $posts->currentPage() + 3); $i++)
                        <a class="join-item btn {{ $posts->currentPage() == $i ? 'btn-active' : '' }}" href="{{ $posts->url($i) }}">{{ $i }}</a>
                    @endfor
                    <a class="join-item btn {{ $posts->hasMorePages() ? '' : 'disabled' }}" href="{{ $posts->nextPageUrl() }}">»</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Moderation Section (Frontend-Only) -->
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


<!-- Post Details Modal -->
<input type="checkbox" id="postDetailsModal" class="modal-toggle" />
<div class="modal">
  <div class="modal-box bg-base-200 max-w-3xl p-0 overflow-hidden">
    <!-- Header with close button -->
    <div class="p-4 border-b border-base-300 flex justify-between items-center">
      <h3 class="font-bold text-lg">Post Details</h3>
      <label for="postDetailsModal" class="btn btn-sm btn-circle">✕</label>
    </div>
    
    <!-- Post content section -->
    <div class="p-4">
      <!-- Author info section -->
      <div class="flex items-center gap-3 mb-4">
        <div class="avatar">
          <div class="w-12 h-12 rounded-full ring ring-primary ring-offset-base-100 ring-offset-1">
            <img id="modalAuthorAvatar" src="" alt="Author Avatar" />
          </div>
        </div>
        <div>
          <h4 class="font-bold text-base" id="modalAuthorName"></h4>
          <p class="text-xs opacity-70" id="modalAuthorUsername"></p>
        </div>
        <div class="ml-auto text-xs opacity-70" id="modalPostDate"></div>
      </div>
      
      <!-- Post content -->
      <div class="mb-4">
        <p class="text-sm mb-3" id="modalPostContent"></p>
        <p class="text-sm text-primary" id="modalPostHashtags"></p>
      </div>
      
      <!-- Post media (if available) -->
      <div class="mb-4 bg-base-300 rounded-lg p-2" id="mediaContainer">
        <p class="text-xs mb-1"><span class="font-bold">Media Type:</span> <span id="modalMediaType"></span></p>
        <!-- Media will be inserted here dynamically -->
      </div>
      
      <!-- Engagement stats -->
      <div class="flex justify-between items-center py-3 border-t border-b border-base-300">
        <div class="flex items-center gap-2">
          <span class="badge badge-primary" id="modalPostStatus"></span>
        </div>
        <div class="flex gap-4 text-sm">
          <div class="flex items-center gap-1">
            <i class="fa fa-eye"></i>
            <span id="modalPostReach"></span> views
          </div>
          <div class="flex items-center gap-1">
            <i class="fa fa-heart"></i>
            <span id="modalPostEngagement"></span>
          </div>
          <div class="flex items-center gap-1">
            <i class="fa fa-comment"></i>
            <span id="modalPostComments"></span>
          </div>
        </div>
      </div>
      
      <!-- Comments section -->
      <div class="mt-4">
        <h4 class="font-bold text-base mb-2">Comments</h4>
        <div id="modalComments" class="space-y-3 max-h-60 overflow-y-auto p-2 bg-base-300 rounded-lg">
          <!-- Comments will be inserted here -->
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Media Zoom Modal -->
<input type="checkbox" id="mediaZoomModal" class="modal-toggle" />
<div class="modal">
  <div class="modal-box bg-base-200 p-0 max-w-4xl">
    <div class="p-2 border-b border-base-300 flex justify-between items-center">
      <h3 class="font-bold text-lg">Media Preview</h3>
      <label for="mediaZoomModal" class="btn btn-sm btn-circle">✕</label>
    </div>
    <div class="p-4 flex justify-center items-center">
      <div id="mediaZoomContent" class="max-w-full max-h-[80vh]"></div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
    // Chart data from controller
    const activityData = @json($activity ?? ['labels' => ['No data'], 'posts' => [0]]);
    const engagementData = @json($engagement ?? ['data' => [0, 0, 0]]);
    const hashtagData = @json($hashtag ?? []);
    const trendsData = @json($trends ?? ['labels' => ['No data'], 'data' => []]);

    // Frontend-only moderation data
    const posts = [
        { id: 1, content: "Excited for the weekend!", author: "Sarah Johnson", username: "@sjohnson", hashtags: ["#weekend"], date: "Mar 13, 2025", likes: 342, comments: 127, status: "Active" },
        { id: 2, content: "New project launch today!", author: "Michael Chen", username: "@mchen", hashtags: ["#project"], date: "Mar 12, 2025", likes: 156, comments: 45, status: "Active" },
        { id: 3, content: "This might be offensive...", author: "Alex Rodriguez", username: "@arod", hashtags: ["#random"], date: "Mar 11, 2025", likes: 89, comments: 23, status: "Reported", reportedDate: "Mar 12, 2025", reason: "Inappropriate content" },
        { id: 4, content: "Great day outside!", author: "Emma Wilson", username: "@ewilson", hashtags: ["#nature"], date: "Mar 10, 2025", likes: 234, comments: 67, status: "Active" },
        { id: 5, content: "Happy Monday everyone!", author: "David Kim", username: "@dkim", hashtags: ["#weekend", "#motivation"], date: "Mar 9, 2025", likes: 198, comments: 54, status: "Active" },
        { id: 6, content: "Spam advertisement", author: "Sophia Lee", username: "@sophialee", hashtags: ["#spam"], date: "Mar 8, 2025", likes: 12, comments: 5, status: "Reported", reportedDate: "Mar 9, 2025", reason: "Spam" }
    ];

    let currentPage = 1;
    const postsPerPage = 6;
    const totalPosts = 42100;

    function displayPosts(page, filteredPosts = null) {
        const postsToDisplay = filteredPosts || posts;
        const start = (page - 1) * postsPerPage;
        const end = Math.min(start + postsPerPage, postsToDisplay.length);
        const paginatedPosts = postsToDisplay.slice(start, end);
        const tableBody = document.getElementById('moderationTableBody');
        tableBody.innerHTML = '';

        paginatedPosts.forEach(post => {
            if (post.status !== 'Reported') return;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="checkbox" class="checkbox" data-post-id="${post.id}"></td>
                <td>
                    <div class="max-w-xs truncate">${post.content}</div>
                </td>
                <td>${post.author} (${post.username})</td>
                <td>${post.hashtags.join(', ')}</td>
                <td>${post.reportedDate}</td>
                <td>${post.reason}</td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="openModerationModal('${post.content}', '${post.author}', '${post.username}', '${post.reportedDate}', '${post.reason}', ${post.id}, '${post.hashtags.join(', ')}')">Moderate</button>
                </td>
            `;
            tableBody.appendChild(row);
        });

        updatePagination(filteredPosts);
    }

    function updatePagination(filteredPosts = null) {
        const totalItems = filteredPosts ? filteredPosts.length : totalPosts;
        const totalPages = Math.ceil(totalItems / postsPerPage);

        const postCountBadge = document.getElementById('postCountBadge');
        if (postCountBadge) {
            postCountBadge.textContent = `${(currentPage - 1) * postsPerPage + 1}-${Math.min(currentPage * postsPerPage, totalItems)} of ${totalItems}`;
        }

        const buttons = document.querySelectorAll('#paginationControls .btn');
        buttons.forEach((button, index) => {
            if (index === 0) button.disabled = currentPage === 1;
            else if (index === buttons.length - 1) button.disabled = currentPage === totalPages;
            else if (index === currentPage) button.classList.add('btn-active');
            else button.classList.remove('btn-active');
        });

        for (let i = 1; i <= 4; i++) {
            const pageButton = document.getElementById(`page${i}`);
            if (!pageButton) continue;
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

    let currentPostId = null;

    function submitModeration() {
        const action = document.getElementById('moderationAction').value;
        const feedback = document.getElementById('moderationFeedback').value;
        const postIndex = posts.findIndex(post => post.id === currentPostId);

        if (postIndex !== -1) {
            if (action === 'approve') {
                posts[postIndex].status = 'Active';
                alert(`Post approved. Feedback sent to user: "${feedback || 'No feedback provided'}"`);
            } else if (action === 'reject') {
                posts.splice(postIndex, 1);
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

    async function viewPostDetails(postId) {
        console.log('viewPostDetails called with postId:', postId);
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            console.log('CSRF token:', csrfToken);
            if (!csrfToken) {
                throw new Error('CSRF token not found');
            }

            const response = await fetch(`/admin/posts/${postId}/details`, {
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

            const post = await response.json();
            console.log('Post data:', post);
            console.log('Raw media_path:', post.media_path, 'Raw media_type:', post.media_type);

            if (post.error) {
                throw new Error(post.error);
            }

            // Parse media_path, handling /storage/ prefix and JSON
            let mediaPath = post.media_path;
            let mediaType = post.media_type;
            if (typeof mediaPath === 'string' && mediaPath.includes('[{')) {
                // Remove /storage/ prefix if present
                mediaPath = mediaPath.replace(/^\/storage\//, '');
                try {
                    const parsed = JSON.parse(mediaPath);
                    if (Array.isArray(parsed) && parsed[0]?.path) {
                        mediaPath = parsed[0].path;
                        mediaType = parsed[0].type === 'video' ? 'video/mp4' : parsed[0].type === 'image' ? 'image/jpeg' : mediaType;
                        console.log('Parsed media:', { path: mediaPath, type: mediaType });
                    }
                } catch (e) {
                    console.error('Failed to parse media_path JSON:', e.message);
                    mediaPath = null;
                }
            } else if (mediaType === 'video') {
                mediaType = 'video/mp4';
                console.log('Normalized media_type to:', mediaType);
            } else if (mediaType === 'image') {
                mediaType = 'image/jpeg';
                console.log('Normalized media_type to:', mediaType);
            }

            // Populate modal
            document.getElementById('modalAuthorAvatar').src = post.user.profile_picture || 'https://api.dicebear.com/6.x/avataaars/svg?seed=user';
            document.getElementById('modalAuthorName').textContent = post.user.name || 'N/A';
            document.getElementById('modalAuthorUsername').textContent = post.user.username && post.user.username !== 'N/A' ? `@${post.user.username}` : 'N/A';
            document.getElementById('modalPostStatus').textContent = post.status || 'Active';
            document.getElementById('modalPostDate').textContent = post.created_at || 'N/A';
            document.getElementById('modalPostEngagement').textContent = `${post.reactions_count || 0} Likes`;
            document.getElementById('modalPostComments').textContent = `${post.comments_count || 0} Comments`;
            document.getElementById('modalPostReach').textContent = post.views || 0;
            document.getElementById('modalPostContent').textContent = post.content || 'N/A';
            document.getElementById('modalPostHashtags').textContent = post.hashtags || 'None';
            document.getElementById('modalMediaType').textContent = mediaType || 'N/A';

            // Handle media
            const mediaContainer = document.getElementById('mediaContainer');
            mediaContainer.querySelectorAll('img, video').forEach(el => el.remove());
            if (mediaPath && mediaType) {
                console.log('Attempting to render media:', { path: mediaPath, type: mediaType });
                if (mediaType.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = mediaPath;
                    img.alt = 'Post Media';
                    img.className = 'w-[200px] h-auto rounded-lg cursor-pointer';
                    img.onerror = () => console.error('Failed to load image:', mediaPath);
                    img.onload = () => console.log('Image loaded successfully:', mediaPath);
                    img.onclick = () => {
                        console.log('Image clicked, opening zoom modal');
                        const zoomContent = document.getElementById('mediaZoomContent');
                        zoomContent.innerHTML = `<img src="${mediaPath}" alt="Post Media" class="max-w-full max-h-[80vh] object-contain" />`;
                        document.getElementById('mediaZoomModal').checked = true;
                    };
                    mediaContainer.appendChild(img);
                } else if (mediaType.startsWith('video/')) {
                    const video = document.createElement('video');
                    video.src = mediaPath;
                    video.controls = true;
                    video.className = 'w-[200px] h-auto rounded-lg cursor-pointer';
                    video.onerror = () => console.error('Failed to load video:', mediaPath);
                    video.onloadeddata = () => console.log('Video loaded successfully:', mediaPath);
                    video.onclick = () => {
                        console.log('Video clicked, opening zoom modal');
                        const zoomContent = document.getElementById('mediaZoomContent');
                        zoomContent.innerHTML = `<video src="${mediaPath}" controls class="max-w-full max-h-[80vh] object-contain"></video>`;
                        document.getElementById('mediaZoomModal').checked = true;
                    };
                    mediaContainer.appendChild(video);
                } else {
                    console.warn('Unsupported media type:', mediaType);
                    mediaContainer.innerHTML += '<p class="text-xs text-error">Unsupported media type</p>';
                }
            } else {
                console.warn('No media available:', { media_path: mediaPath, media_type: mediaType });
                mediaContainer.innerHTML += '<p class="text-xs text-warning">No media available</p>';
            }

            // Populate comments
            const commentsContainer = document.getElementById('modalComments');
            commentsContainer.innerHTML = '';
            if (!post.comments || post.comments.length === 0) {
                commentsContainer.innerHTML = '<p class="text-sm opacity-70">No comments yet.</p>';
            } else {
                post.comments.forEach(comment => {
                    const commentDiv = document.createElement('div');
                    commentDiv.className = 'flex items-start gap-4';
                    commentDiv.innerHTML = `
                        <div class="avatar">
                            <div class="w-10 rounded-full">
                                <img src="${comment.avatar || 'https://api.dicebear.com/6.x/avataaars/svg?seed=user'}" alt="User Avatar" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <p class="font-bold">${comment.user || 'Unknown'}</p>
                                <span class="text-xs opacity-70">${comment.created_at || 'N/A'}</span>
                            </div>
                            <p class="text-sm">${comment.contenu || 'N/A'}</p>
                        </div>
                    `;
                    commentsContainer.appendChild(commentDiv);
                });
            }

            // Show modal
            const modal = document.getElementById('postDetailsModal');
            console.log('Modal element:', modal, 'Checked state before:', modal.checked);
            modal.checked = true;
            console.log('Checked state after:', modal.checked);
        } catch (error) {
            console.error('Error fetching post details:', error.message);
            alert(`Failed to load post details: ${error.message}`);
        }
    }

    // Handle delete form submission
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form[id^="delete-post-"]').forEach(form => {
            const formId = form.id;
            const postId = formId.replace('delete-post-', '');
            const submitButton = form.querySelector('button[type="submit"]');

            // Debug button click
            submitButton.addEventListener('click', (e) => {
                console.log(`Delete button clicked for post ID: ${postId}`);
            });

            // Debug form submit
            form.addEventListener('submit', (e) => {
                console.log(`Submit event triggered for post ID: ${postId}`);
                if (!confirm('Are you sure you want to delete this post?')) {
                    e.preventDefault();
                    console.log(`Submission cancelled for post ID: ${postId}`);
                    return;
                }
                console.log(`Submitting delete form for post ID: ${postId}`);
                // Allow native submission
            });
        });
    });

    function initializeCharts() {
        const isValidActivityData = activityData && Array.isArray(activityData.labels) && Array.isArray(activityData.posts);
        const isValidEngagementData = engagementData && Array.isArray(engagementData.data);
        const isValidHashtagData = hashtagData && Object.keys(hashtagData).length > 0;
        const isValidTrendsData = trendsData && Array.isArray(trendsData.labels) && trendsData.data;

        const activityCtx = document.getElementById('postActivityChart')?.getContext('2d');
        if (activityCtx && isValidActivityData) {
            new Chart(activityCtx, {
                type: 'line',
                data: {
                    labels: activityData.labels,
                    datasets: [
                        {
                            label: 'Posts Created',
                            data: activityData.posts,
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
        } else {
            console.warn('Skipping Post Activity Chart: Invalid or missing data');
            activityCtx && (activityCtx.canvas.parentNode.innerHTML = '<p class="text-center text-gray-500">No activity data available</p>');
        }

        const engagementCtx = document.getElementById('engagementMetricsChart')?.getContext('2d');
        if (engagementCtx && isValidEngagementData) {
            new Chart(engagementCtx, {
                type: 'bar',
                data: {
                    labels: ['Likes', 'Comments', 'Views'],
                    datasets: [{
                        label: 'Engagement Metrics',
                        data: engagementData.data,
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
        } else {
            console.warn('Skipping Engagement Metrics Chart: Invalid or missing data');
            engagementCtx && (engagementCtx.canvas.parentNode.innerHTML = '<p class="text-center text-gray-500">No engagement data available</p>');
        }

        const hashtagCtx = document.getElementById('hashtagChart')?.getContext('2d');
        if (hashtagCtx && isValidHashtagData) {
            new Chart(hashtagCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(hashtagData),
                    datasets: [{
                        label: 'Posts by Hashtag',
                        data: Object.values(hashtagData),
                        backgroundColor: ['#3b82f6', '#4ade80', '#facc15', '#f87171', '#38bdf8'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        title: { display: true, text: 'Posts by Hashtag', color: '#e2e8f0', font: { size: 16 } }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(148, 163, 184, 0.1)' }, ticks: { color: '#94a3b8' } },
                        x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                    }
                }
            });
        } else {
            console.warn('Skipping Posts by Hashtag Chart: Invalid or missing data');
            hashtagCtx && (hashtagCtx.canvas.parentNode.innerHTML = '<p class="text-center text-gray-500">No hashtag data available</p>');
        }

        const trendsCtx = document.getElementById('hashtagTrendsChart')?.getContext('2d');
        if (trendsCtx && isValidTrendsData) {
            new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: trendsData.labels,
                    datasets: Object.keys(trendsData.data).map((hashtag, index) => ({
                        label: hashtag,
                        data: trendsData.data[hashtag],
                        borderColor: ['#3b82f6', '#4ade80', '#facc15', '#f87171', '#38bdf8'][index % 5],
                        backgroundColor: ['rgba(59, 130, 246, 0.1)', 'rgba(74, 222, 128, 0.1)', 'rgba(250, 204, 21, 0.1)', 'rgba(248, 113, 113, 0.1)', 'rgba(56, 189, 248, 0.1)'][index % 5],
                        tension: 0.4,
                        fill: true
                    }))
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
        } else {
            console.warn('Skipping Hashtag Trends Chart: Invalid or missing data');
            trendsCtx && (trendsCtx.canvas.parentNode.innerHTML = '<p class="text-center text-gray-500">No trends data available</p>');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        displayPosts(currentPage);
        initializeCharts();
    });
</script>
@endsection