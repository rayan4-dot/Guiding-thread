@extends('layouts.user-layout')

@section('title', $user->name . "'s Profile")

@section('content')
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Fixed Header -->
<header class="sticky top-0 z-50 backdrop-blur-xl bg-black/80 border-b border-dark-border">
    <div class="max-w-screen-xl mx-auto px-4 py-3 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <a href="/home" class="text-white hover:bg-dark-hover p-2 rounded-full transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-lg font-bold text-white">{{ $user->name }}</h2>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="max-w-screen-xl mx-auto">
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="bg-green-500 text-white px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-500 text-white px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Profile Header -->
    <section class="relative mb-6">
        <!-- Cover Photo -->
        <div class="h-48 w-full bg-gradient-to-r from-dark-lighter to-dark overflow-hidden sm:h-56 md:h-64">
            <button onclick="document.getElementById('coverModal').classList.remove('hidden')" class="w-full h-full">
                <img src="{{ $user->cover_photo ? Storage::url($user->cover_photo) : 'https://source.unsplash.com/random/1200x400?dark' }}" alt="Cover Photo" class="w-full h-full object-cover opacity-80 hover:opacity-100 transition-opacity">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            </button>
        </div>
        
        <!-- Profile Info -->
        <div class="relative px-4 pb-4">
            <!-- Profile Picture -->
            <div class="absolute -top-16 left-4 ring-4 ring-black rounded-full sm:-top-20 md:-top-24">
                <button onclick="document.getElementById('pictureModal').classList.remove('hidden')" class="focus:outline-none">
                    <img src="{{ $user->profile_picture ? Storage::url($user->profile_picture) : (asset('images/default-profile.png') ?: 'https://via.placeholder.com/150') }}" alt="Profile Picture" class="w-24 h-24 rounded-full border-4 border-black hover:opacity-90 transition-opacity sm:w-28 sm:h-28 md:w-32 md:h-32">
                </button>
            </div>
            
            <!-- Profile Details -->
            <div class="flex justify-between items-center pt-16 sm:pt-20 md:pt-24">
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                    <p class="text-gray-400 text-sm">{{ '@' . $user->username }}</p>
                </div>
                @auth
                    @if(auth()->id() !== $user->id)
                        @if($is_following)
                            <form action="{{ route('unfollow', $user) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-white text-black font-bold px-4 py-2 rounded-full hover:bg-gray-200 transition-colors text-sm" title="Unfollow">
                                    Unfollow
                                </button>
                            </form>
                        @elseif(auth()->user()->hasPendingFollow($user))
                            <button class="bg-gray-500 text-white font-bold px-4 py-2 rounded-full cursor-not-allowed text-sm" disabled title="Follow request pending">
                                Pending
                            </button>
                        @else
                            <form action="{{ route('follow', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-white text-black font-bold px-4 py-2 rounded-full hover:bg-gray-200 transition-colors text-sm" title="Follow">
                                    Follow
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('user.profile') }}" class="bg-white text-black font-bold px-4 py-2 rounded-full hover:bg-gray-200 transition-colors text-sm">
                            Edit Profile
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="bg-white text-black font-bold px-4 py-2 rounded-full hover:bg-gray-200 transition-colors text-sm" title="Log in to follow">
                        Follow
                    </a>
                @endauth
            </div>
            
            <!-- Bio -->
            <div class="mt-3">
                <p class="text-gray-300 text-sm">{{ $user->bio ?? 'No bio defined' }}</p>
            </div>
            
            <!-- Stats -->
            <div class="flex space-x-4 mt-3">
                <div class="flex items-center space-x-1">
                    <i class="fa-regular fa-calendar text-gray-500"></i>
                    <span class="text-gray-400 text-sm">Joined {{ $user->created_at->format('F Y') }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Tabs -->
    <section class="border-b border-dark-border mb-2">
        <div class="flex px-4">
            <button class="py-4 px-6 text-center text-white font-semibold border-b-2 border-primary hover:bg-dark-hover transition-colors" aria-selected="true">
                Posts
            </button>
            <button class="py-4 px-6 text-center text-gray-500 font-semibold border-b-2 border-transparent hover:bg-dark-hover hover:text-gray-300 transition-colors">
                Media
            </button>
            <button class="py-4 px-6 text-center text-gray-500 font-semibold border-b-2 border-transparent hover:bg-dark-hover hover:text-gray-300 transition-colors">
                Likes
            </button>
        </div>
    </section>

    <!-- Posts Section -->
    <section class="divide-y divide-dark-border" id="posts-container">
        @if(isset($posts))
            @forelse($posts as $post)
                <article class="p-4 hover:bg-dark-hover transition-colors" id="post-{{ $post->id }}">
                    <div class="flex gap-3">
                        <a href="/profile/{{ $post->user->username }}" class="flex-shrink-0">
                            <img src="{{ $post->user->profile_picture ? Storage::url($post->user->profile_picture) : (asset('images/default-profile.png') ?: 'https://via.placeholder.com/150') }}" alt="{{ $post->user->name }} profile" class="w-12 h-12 rounded-full hover:opacity-90 transition-opacity">
                        </a>
                        <div class="flex-1">
                            <div class="flex items-center gap-1.5 mb-1">
                                <a href="/profile/{{ $post->user->username }}" class="font-bold text-white hover:underline">{{ $post->user->name }}</a>
                                <span class="text-gray-500">{{ '@' . $post->user->username }}</span>
                                <span class="text-gray-500">·</span>
                                <time class="text-gray-500 hover:underline">{{ $post->created_at->diffForHumans() }}</time>
                            </div>
                            <p class="text-white text-base leading-relaxed mt-2">{{ nl2br(e($post->content)) }}</p>
                            @if($post->media_path)
                                @php
                                    $mediaItems = is_string($post->media_path) ? json_decode($post->media_path, true) : $post->media_path;
                                @endphp
                                @if(is_array($mediaItems) && count($mediaItems) > 0)
                                    <div class="mt-3 grid {{ count($mediaItems) === 1 ? 'grid-cols-1' : 'grid-cols-2' }} gap-2 rounded-xl overflow-hidden border border-dark-border">
                                        @foreach($mediaItems as $media)
                                            @if($media['type'] === 'image')
                                                <img src="{{ $media['path'] }}" alt="Post image" class="w-full h-auto max-h-[500px] object-cover hover:opacity-90 transition-opacity cursor-pointer" onclick="openMediaModal('{{ $media['path'] }}', 'image')">
                                            @elseif($media['type'] === 'video')
                                                <video controls class="w-full h-auto max-h-[500px] object-cover">
                                                    <source src="{{ $media['path'] }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                            <div class="flex justify-start gap-8 mt-3 text-gray-500">
                                <!-- Comments -->
                                <a href="{{ route('post.show', $post->id) }}#comments" class="flex items-center gap-2 hover:text-primary transition-colors group" aria-label="Comments" title="View comments">
                                    <div class="p-2 rounded-full group-hover:bg-primary/10 transition-colors">
                                        <i class="fa-regular fa-comment"></i>
                                    </div>
                                    <span>{{ $post->comments()->count() }}</span>
                                </a>
                                <!-- Likes -->
                                @auth
                                    <button class="like-btn flex items-center gap-2 hover:text-red-500 transition-colors group" aria-label="Like" data-post-id="{{ $post->id }}" data-liked="{{ $post->isLikedBy(auth()->user()) ? 'true' : 'false' }}" title="{{ $post->isLikedBy(auth()->user()) ? 'Unlike' : 'Like' }}">
                                        <div class="p-2 rounded-full group-hover:bg-red-500/10 transition-colors">
                                            <i class="fa{{ $post->isLikedBy(auth()->user()) ? 's' : 'r' }} fa-heart {{ $post->isLikedBy(auth()->user()) ? 'text-red-500' : '' }}"></i>
                                        </div>
                                        <span class="like-count">{{ $post->likes()->count() }}</span>
                                    </button>
                                @else
                                    <div class="flex items-center gap-2 text-gray-500" aria-label="Likes" title="Log in to like">
                                        <div class="p-2 rounded-full">
                                            <i class="far fa-heart"></i>
                                        </div>
                                        <span>{{ $post->likes()->count() }}</span>
                                    </div>
                                @endauth
                                <!-- Views -->
                                <a href="{{ route('post.show', $post->id) }}" class="flex items-center gap-2 hover:text-primary transition-colors group" aria-label="Views" title="View post">
                                    <div class="p-2 rounded-full group-hover:bg-primary/10 transition-colors">
                                        <i class="fa-regular fa-eye"></i>
                                    </div>
                                    <span>{{ $post->views ?? 0 }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="px-4 py-6 text-center text-gray-400">
                    <p class="text-lg font-medium">No posts yet</p>
                </div>
            @endforelse
        @else
            <div class="px-4 py-6 text-center text-red-500">
                <p class="text-lg font-medium">Error: Posts could not be loaded</p>
            </div>
        @endif
    </section>

    <!-- Pagination -->
    @if(isset($posts) && $posts->hasPages())
        <div class="flex justify-center py-6">
            {{ $posts->links('vendor.pagination.tailwind') }}
        </div>
    @endif

    <!-- Profile Picture Modal -->
    <div id="pictureModal" class="fixed inset-0 z-50 overflow-auto flex items-center justify-center bg-black/70 hidden">
        <div class="relative bg-black w-full max-w-md rounded-xl border border-dark-border shadow-lg">
            <div class="flex items-center justify-between px-4 py-3 border-b border-dark-border">
                <h3 class="text-xl font-bold text-white">Profile Picture</h3>
                <button onclick="document.getElementById('pictureModal').classList.add('hidden')" class="text-white hover:bg-dark-hover p-2 rounded-full transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-4 flex flex-col items-center">
                <img src="{{ $user->profile_picture ? Storage::url($user->profile_picture) : (asset('images/default-profile.png') ?: 'https://via.placeholder.com/150') }}" alt="Profile Picture" class="w-64 h-64 object-cover rounded-lg">
            </div>
        </div>
    </div>

    <!-- Cover Photo Modal -->
    <div id="coverModal" class="fixed inset-0 z-50 overflow-auto flex items-center justify-center bg-black/70 hidden">
        <div class="relative bg-black w-full max-w-3xl rounded-xl border border-dark-border shadow-lg">
            <div class="flex items-center justify-between px-4 py-3 border-b border-dark-border">
                <h3 class="text-xl font-bold text-white">Cover Photo</h3>
                <button onclick="document.getElementById('coverModal').classList.add('hidden')" class="text-white hover:bg-dark-hover p-2 rounded-full transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-4 flex flex-col items-center">
                <img src="{{ $user->cover_photo ? Storage::url($user->cover_photo) : 'https://source.unsplash.com/random/1200x400?dark' }}" alt="Cover Photo" class="w-full h-96 object-cover rounded-lg">
            </div>
        </div>
    </div>

    <!-- Media Modal -->
    <div id="mediaModal" class="fixed inset-0 z-50 overflow-auto bg-black/90 backdrop-blur-sm hidden">
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="absolute top-4 right-4 z-10">
                <button onclick="document.getElementById('mediaModal').classList.add('hidden')" class="text-white hover:bg-white/10 p-3 rounded-full transition-colors" aria-label="Close modal">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div class="max-w-5xl w-full">
                <img id="mediaImage" src="" alt="Selected Media" class="w-full h-auto max-h-[85vh] object-contain rounded-lg shadow-2xl hidden">
                <video id="mediaVideo" controls class="w-full h-auto max-h-[85vh] object-contain rounded-lg shadow-2xl hidden">
                    <source src="" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>
</main>

@endsection

@section('right-sidebar')
    @include('admin.components.right-sidebar')
@endsection

@section('scripts')
<script>
    function openMediaModal(src, type) {
        const mediaModal = document.getElementById('mediaModal');
        const mediaImage = document.getElementById('mediaImage');
        const mediaVideo = document.getElementById('mediaVideo');
        const videoSource = mediaVideo.querySelector('source');

        if (type === 'image') {
            mediaImage.src = src;
            mediaImage.classList.remove('hidden');
            mediaVideo.classList.add('hidden');
        } else {
            videoSource.src = src;
            mediaVideo.load();
            mediaVideo.classList.remove('hidden');
            mediaImage.classList.add('hidden');
        }
        mediaModal.classList.remove('hidden');
    }

    document.getElementById('pictureModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    document.getElementById('coverModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    document.getElementById('mediaModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('pictureModal')?.classList.add('hidden');
            document.getElementById('coverModal')?.classList.add('hidden');
            document.getElementById('mediaModal')?.classList.add('hidden');
        }
    });

    // AJAX Like Functionality
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const postId = this.dataset.postId;
            const isLiked = this.dataset.liked === 'true';
            const likeCountSpan = this.querySelector('.like-count');
            const heartIcon = this.querySelector('i');

            try {
                const response = await fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });

                const data = await response.json();

                if (response.ok) {
                    likeCountSpan.textContent = data.likes_count;
                    this.dataset.liked = !isLiked;
                    heartIcon.classList.toggle('far', isLiked);
                    heartIcon.classList.toggle('fas', !isLiked);
                    heartIcon.classList.toggle('text-red-500', !isLiked);
                    this.title = isLiked ? 'Like' : 'Unlike';
                } else {
                    if (data.error === 'Unauthenticated') {
                        alert('Please log in to like posts.');
                    } else {
                        alert(data.error || 'Failed to like post.');
                    }
                }
            } catch (error) {
                console.error('Like error:', error);
                alert('An error occurred while liking the post.');
            }
        });
    });
</script>
@endsection