@extends('layouts.user-layout')

@section('title', 'Explore')

@section('content')
    <header class="sticky top-0 z-10 bg-black/80 backdrop-blur-md border-b border-dark-border">
        <div class="px-4 py-3 flex justify-between items-center">
            <h2 class="text-xl font-bold">Explore</h2>
            <button class="text-white hover:bg-dark-hover p-2 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Settings">
                <i class="fa-solid fa-gear text-xl"></i>
            </button>
        </div>
        <div class="relative group mx-4 my-2">
            <input type="search" placeholder="Search" class="w-full bg-dark-lighter text-white rounded-full py-3 pl-12 pr-4 outline-none focus:bg-black focus:ring-1 focus:ring-primary transition-all duration-200" aria-label="Search platform">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-500 group-focus-within:text-primary transition-colors duration-200"></i>
        </div>
        <div class="flex">
            <button class="flex-1 text-center py-4 font-bold hover:bg-dark-hover transition-colors duration-200 relative focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Trending">
                Trending
                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-16 h-1 bg-primary rounded-full"></div>
            </button>
            <button class="flex-1 text-center py-4 text-gray-500 hover:bg-dark-hover transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary" aria-label="News">
                News
            </button>
            <button class="flex-1 text-center py-4 text-gray-500 hover:bg-dark-hover transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Sports">
                Sports
            </button>
            <button class="flex-1 text-center py-4 text-gray-500 hover:bg-dark-hover transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Entertainment">
                Entertainment
            </button>
        </div>
    </header>
    
    <!-- Trending News Section -->
    <div class="p-4 space-y-6">
        <div class="bg-dark-lighter rounded-2xl overflow-hidden">
            <h2 class="text-xl font-bold p-4 border-b border-dark-border">Trending News</h2>
            <div class="space-y-4">
                <div class="cursor-pointer hover:bg-dark-hover p-4 transition-colors duration-200 border-b border-dark-border">
                    <h3 class="font-bold text-lg">Breaking: Major Event in Technology</h3>
                    <p class="text-gray-500 text-sm">A major technological breakthrough has been announced today...</p>
                </div>
                <div class="cursor-pointer hover:bg-dark-hover p-4 transition-colors duration-200 border-b border-dark-border">
                    <h3 class="font-bold text-lg">Sports Update: Championship Results</h3>
                    <p class="text-gray-500 text-sm">The latest championship results are in, with surprising outcomes...</p>
                </div>
                <div class="cursor-pointer hover:bg-dark-hover p-4 transition-colors duration-200">
                    <h3 class="font-bold text-lg">Entertainment: New Movie Release</h3>
                    <p class="text-gray-500 text-sm">A highly anticipated movie has just been released...</p>
                </div>
            </div>
        </div>
    </div>

  <!-- Trending Section -->
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-4 text-sm text-gray-400">
                    Aucune tendance trouvée.
                </div>
            @endforelse

            <a href="#" class="block p-4 text-primary hover:bg-dark-hover transition-colors duration-200">
                Voir plus
            </a>
        </div>
    </div>
</div>

    <!-- People to Follow Section -->
    <div class="p-4 space-y-6">
        <div class="bg-dark-lighter rounded-2xl overflow-hidden">
            <h2 class="text-xl font-bold p-4 border-b border-dark-border">Who to follow</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/150?img=1" alt="Emma Smith" class="w-12 h-12 rounded-full border border-dark-border">
                        <div>
                            <p class="font-bold text-[15px] hover:underline">Emma Smith</p>
                            <p class="text-gray-500 text-sm">@emmasmith</p>
                        </div>
                    </div>
                    <button class="rounded-full bg-white text-black hover:bg-gray-200 text-sm font-bold px-4 py-1.5 transition-colors duration-200">
                        Follow
                    </button>
                </div>
                
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/150?img=2" alt="James Brown" class="w-12 h-12 rounded-full border border-dark-border">
                        <div>
                            <p class="font-bold text-[15px] hover:underline">James Brown</p>
                            <p class="text-gray-500 text-sm">@jamesbrown</p>
                        </div>
                    </div>
                    <button class="rounded-full bg-white text-black hover:bg-gray-200 text-sm font-bold px-4 py-1.5 transition-colors duration-200">
                        Follow
                    </button>
                </div>
                
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/150?img=3" alt="Olivia Johnson" class="w-12 h-12 rounded-full border border-dark-border">
                        <div>
                            <p class="font-bold text-[15px] hover:underline">Olivia Johnson</p>
                            <p class="text-gray-500 text-sm">@oliviaj</p>
                        </div>
                    </div>
                    <button class="rounded-full bg-white text-black hover:bg-gray-200 text-sm font-bold px-4 py-1.5 transition-colors duration-200">
                        Follow
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Swipable Posts Sections -->
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
        <!-- Right sidebar content -->
        @include('admin.components.right-sidebar')
    </aside>
@endsection