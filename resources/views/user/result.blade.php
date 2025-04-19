@extends('layouts.user-layout')

@section('title', 'Search Results')

@section('content')
    <section class="p-4 bg-black min-h-screen">
        <h1 class="text-2xl font-bold text-white mb-6">
            Search Results for "{{ $query ?? 'No query' }}"
        </h1>

        <!-- Hashtags Section -->
        <div class="mb-8">
            <h2 class="text-lg font-bold text-white mb-4">Hashtags</h2>
            @if ($results['hashtags']->isEmpty())
                <p class="text-gray-400">No hashtags found.</p>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach ($results['hashtags'] as $hashtag)
                        <a 
                            href="{{ route('search.index', ['query' => '#' . $hashtag]) }}"
                            class="bg-zinc-800 text-primary hover:bg-zinc-700 rounded-full px-4 py-2 text-sm transition-colors"
                        >
                            #{{ $hashtag }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Users Section -->
        <div class="mb-8">
            <h2 class="text-lg font-bold text-white mb-4">Users</h2>
            @if ($results['users']->isEmpty())
                <p class="text-gray-400">No users found.</p>
            @else
                <div class="grid gap-4">
                    @foreach ($results['users'] as $user)
                        <a 
                            href="/profile/{{ $user->username }}"
                            class="flex items-center gap-3 p-3 bg-zinc-900 border border-zinc-700 rounded-lg hover:bg-zinc-800 transition-colors"
                        >
                            <img 
                                src="{{ $user->profile_picture ? Storage::url($user->profile_picture) : asset('images/default-profile.png') }}"
                                alt="{{ $user->name }}"
                                class="w-10 h-10 rounded-full object-cover"
                            >
                            <div>
                                <p class="font-semibold text-white">{{ $user->name }}</p>
                                <p class="text-gray-400 text-sm">{{ '@' . $user->username }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $results['users']->links() }}
                </div>
            @endif
        </div>

        <!-- Posts Section -->
        <div>
            <h2 class="text-lg font-bold text-white mb-4">Posts</h2>
            @if ($results['posts']->isEmpty())
                <p class="text-gray-400">No posts found.</p>
            @else
                <div class="divide-y divide-dark-border">
                    @foreach ($results['posts'] as $post)
                        <article class="p-4" id="post-{{ $post->id }}">
                            <div class="flex gap-4">
                                <a href="/profile/{{ $post->user->username }}" class="flex-shrink-0">
                                    <img 
                                        src="{{ $post->user->profile_picture ? Storage::url($post->user->profile_picture) : asset('images/default-profile.png') }}"
                                        alt="{{ $post->user->name }}"
                                        class="w-12 h-12 rounded-full object-cover hover:opacity-90 transition-opacity"
                                    >
                                </a>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                                        <a href="/profile/{{ $post->user->username }}" class="font-bold hover:underline text-white">
                                            {{ $post->user->name }}
                                        </a>
                                        <span class="text-gray-500">{{ '@' . $post->user->username }}</span>
                                        <span class="text-gray-500">·</span>
                                        <time class="text-gray-500 hover:underline">
                                            {{ $post->created_at->diffForHumans() }}
                                        </time>
                                        @if (Auth::check() && Auth::id() === $post->user_id)
                                            <div class="relative ml-auto">
                                                <button 
                                                    onclick="document.getElementById('options-{{ $post->id }}').classList.toggle('hidden')"
                                                    class="p-2 rounded-full hover:bg-zinc-800 transition-colors"
                                                >
                                                    <i class="fa-solid fa-ellipsis"></i>
                                                </button>
                                                <div 
                                                    id="options-{{ $post->id }}"
                                                    class="hidden absolute right-0 mt-1 w-48 bg-zinc-900 border border-zinc-700 rounded-lg shadow-xl z-10"
                                                >
                                                    <button 
                                                        onclick="deletePost({{ $post->id }}); document.getElementById('options-{{ $post->id }}').classList.add('hidden')"
                                                        class="flex items-center gap-2 w-full text-left px-4 py-2.5 text-sm text-red-500 hover:bg-zinc-800 rounded-b-lg"
                                                    >
                                                        <i class="fa-solid fa-trash-can w-5"></i>
                                                        <span>Delete Post</span>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="mb-3 text-[15px] text-gray-200 leading-relaxed">
                                        {{ nl2br(e($post->content)) }}
                                    </p>
                                    <!-- Media Section -->
                                    @if ($post->media_path)
                                        @php
                                            $mediaItems = json_decode($post->media_path, true);
                                        @endphp
                                        @if (is_array($mediaItems))
                                            <div class="mb-3 grid gap-2">
                                                @foreach ($mediaItems as $media)
                                                    @if ($media['type'] === 'image')
                                                        <img 
                                                            src="{{ $media['path'] }}"
                                                            alt="Post media"
                                                            class="max-w-full h-auto rounded-lg object-cover"
                                                            loading="lazy"
                                                        >
                                                    @elseif ($media['type'] === 'video')
                                                        <video 
                                                            src="{{ $media['path'] }}"
                                                            controls
                                                            class="max-w-full h-auto rounded-lg"
                                                            loading="lazy"
                                                        ></video>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                                             <!-- Views -->
                                                             <a href="{{ route('post.show', $post->id) }}" class="view flex items-center gap-2 hover:text-primary transition-colors group" aria-label="Views">
                                    <div class="p-2 rounded-full group-hover:bg-primary/10 transition-colors">
                                        <i class="fa-regular fa-eye"></i>
                                    </div>
                                    <span>{{ $post->views ?? 0 }}</span>
                                </a>
                                    <div class="flex justify-start gap-8 mt-2">
                                        <a 
                                            href="{{ route('post.show', $post->id) }}#comments"
                                            class="flex items-center gap-2 hover:text-blue-500 transition-colors group"
                                            aria-label="Comments"
                                        >
                                            <div class="p-2 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                                <i class="fa-regular fa-comment"></i>
                                            </div>
                                            <span>{{ $post->comments()->count() }}</span>
                                        </a>
                                        <form action="{{ route('posts.like', $post) }}" method="POST" class="like-form">
                                            @csrf
                                            <button 
                                                class="like-btn flex items-center gap-2 hover:text-red-500 transition-colors group"
                                                aria-label="Like"
                                                data-post-id="{{ $post->id }}"
                                                data-liked="{{ $post->isLikedBy(auth()->user()) ? 'true' : 'false' }}"
                                            >
                                                <div class="p-2 rounded-full group-hover:bg-red-500/10 transition-colors">
                                                    <i class="fa{{ $post->isLikedBy(auth()->user()) ? 's' : 'r' }} fa-heart {{ $post->isLikedBy(auth()->user()) ? 'text-red-500' : '' }}"></i>
                                                </div>
                                                <span class="like-count">{{ $post->likes()->count() }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $results['posts']->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
<style>
    .view{
        width: 187px;
    position: relative;
    left: 81%;
    bottom: -36px;
    }
    .like-btn {
    position: absolute;
    gap: 0.5rem;
}
</style>
@section('scripts')
    <script src="{{ asset('js/fetch.js') }}"></script>
@endsection