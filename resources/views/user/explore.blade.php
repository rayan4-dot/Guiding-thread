@extends('layouts.user-layout')

@section('title', 'Explore')

@section('content')
    <header class="sticky top-0 z-10 bg-black/80 backdrop-blur-md border-b border-dark-border">
        <div class="px-4 py-3 flex justify-between items-center">
            <h2 class="text-xl font-bold">Explore</h2>
        </div>
        <div class="p-4 border-b border-dark-border">
            <form action="{{ route('search.index') }}" method="GET" class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 group-focus-within:text-primary transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="query" 
                    placeholder="Search posts, users, or #hashtags..." 
                    value="{{ request('query') }}"
                    class="pl-12 pr-4 py-3 bg-dark-lighter text-white rounded-full w-full focus:outline-none focus:ring-1 focus:ring-primary focus:bg-black transition-all duration-200" 
                    aria-label="Search"
                >
            </form>
        </div>
        <div class="flex">
            <button class="flex-1 text-center py-4 font-bold hover:bg-dark-hover transition-colors duration-200 relative focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Trending">
                Trending
                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-16 h-1 bg-primary rounded-full"></div>
            </button>
        </div>
    </header>

    <!-- Trending News Section -->
    <div class="p-4 space-y-6">
        <div class="bg-dark-lighter rounded-2xl overflow-hidden">
            <h2 class="text-xl font-bold p-4 border-b border-dark-border">Trending News</h2>
            <div class="space-y-4">
                @forelse ($trendingPosts as $post)
                <a href="{{ route('post.show', $post->id) }}" class="block cursor-pointer hover:bg-dark-hover p-4 transition-colors duration-200 border-b border-dark-border">
    <h3 class="font-bold text-lg">
        {{ optional($post->user)->name }}
        <span class="text-sm text-gray-500">
            · {{ '@' . optional($post->user)->username }}
        </span>
    </h3>

    <p class="text-gray-500 text-sm mt-1">
        {{ Str::limit($post->content, 100) }}
    </p>

    <p class="text-gray-500 text-sm mt-1">
        {{ $post->likes->count() }} Likes · 
        {{ $post->comments->count() }} Comments · 
        {{ $post->views ?? 0 }} Views
    </p>
</a>

                @empty
                    <div class="p-4 text-gray-400">
                        No trending posts found.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Trending Hashtags Section (from Composer) -->
    <div class="p-4 space-y-6">
        <div class="bg-dark-lighter rounded-2xl overflow-hidden">
            <h2 class="text-xl font-bold p-4 border-b border-dark-border">Trending for you</h2>
            <div>
                @forelse ($trendingHashtags as $hashtag)
                    <div class="cursor-pointer hover:bg-dark-hover p-4 transition-colors duration-200 border-b border-dark-border">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs text-gray-500">Tendance · Hashtag</p>
                                <p class="font-bold text-[15px] mt-0.5">#{{ $hashtag->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ number_format($hashtag->posts_count) }} Posts</p>
                            </div>
                            <button class="text-gray-500 hover:text-primary hover:bg-primary/10 rounded-full p-2 transition-colors duration-200" aria-label="More options">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-sm text-gray-400">
                        Aucune tendance trouvée.
                    </div>
                @endforelse
           
            </div>
        </div>
    </div>

    <!-- People to Follow Section -->
    <div class="p-4 space-y-6">
        <div class="bg-dark-lighter rounded-2xl overflow-hidden">
            <h2 class="text-xl font-bold p-4 border-b border-dark-border">Who to follow</h2>
            <div class="space-y-4">
                @forelse ($peopleToFollow as $user)
                    @if ($user->username)
                        <div class="flex items-center justify-between p-4">
                            <a href="{{ route('public-profile.show', $user->username) }}" class="flex items-center space-x-3">
                                <img src="{{ $user->profile_picture ? Storage::url($user->profile_picture) : 'https://via.placeholder.com/150' }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full border border-dark-border">
                                <div>
                                    <p class="font-bold text-[15px] hover:underline">{{ $user->name }}</p>
                                    <p class="text-gray-500 text-sm">{{'@' . $user->username }}</p>
                                </div>
                            </a>
                            @auth
                                <form action="{{ route('follow', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="rounded-full bg-white text-black hover:bg-gray-200 text-sm font-bold px-4 py-1.5 transition-colors duration-200" title="Follow">
                                        Follow
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="rounded-full bg-white text-black hover:bg-gray-200 text-sm font-bold px-4 py-1.5 transition-colors duration-200" title="Log in to follow">
                                    Follow
                                </a>
                            @endauth
                        </div>
                    @endif
                @empty
                    <div class="p-4 text-gray-400">
                        No suggestions found.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Swipable Posts Sections (Static) -->
    @foreach(['Entertainment', 'Politics', 'Sports', 'Technology'] as $category)
        <div class="p-4 space-y-6">
            <div class="bg-dark-lighter rounded-2xl overflow-hidden">
                <h2 class="text-xl font-bold p-4 border-b border-dark-border">{{ $category }}</h2>
                <div class="flex overflow-x-auto space-x-4 p-4">
                    @foreach(range(1, 5) as $index)
                        <article class="min-w-[250px] bg-dark rounded-xl p-4 border border-dark-border" id="{{ $category }}Post{{ $index }}">
                            <div class="flex gap-4">
                                <img src="https://source.unsplash.com/random/100x100?sig={{ $index }}" alt="User profile" class="w-12 h-12 rounded-full object-cover">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                                        <span class="font-bold hover:underline cursor-pointer">User {{ $index }}</span>
                                        <span class="text-gray-500">@user{{ $index }}</span>
                                        <span class="text-gray-500">·</span>
                                        <time class="text-gray-500 hover:underline cursor-pointer">{{ rand(1, 24) }}h</time>
                                    </div>
                                    <p class="mb-3 text-[15px] leading-normal">
                                        Sample post content for {{ strtolower($category) }} category. This is post number {{ $index }}.
                                    </p>
                                    <div class="rounded-2xl overflow-hidden mb-3 relative group cursor-pointer">
                                        <img src="https://source.unsplash.com/random/600x400?sig={{ $index }}" alt="Post image" class="w-full object-cover group-hover:brightness-90 transition-all duration-200">
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('right-sidebar')
    <aside class="hidden lg:block w-[350px] px-4 py-3 sticky top-0 h-screen overflow-y-auto custom-scrollbar" aria-label="Additional content">
        @include('admin.components.right-sidebar')
    </aside>
@endsection