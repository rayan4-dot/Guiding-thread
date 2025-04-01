@extends('layouts.user-layout')

@section('title', 'Home')

@section('content')
    <header class="sticky top-0 z-10 bg-black/80 backdrop-blur-md border-b border-dark-border">
        <div class="px-4 py-3 flex justify-between items-center">
            <h2 class="text-xl font-bold">Home</h2>
            <button class="text-white hover:bg-dark-hover p-2 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Settings">
                <i class="fa-solid fa-gear text-xl"></i>
            </button>
        </div>
        <div class="flex">
            <button class="flex-1 text-center py-4 font-bold hover:bg-dark-hover transition-colors duration-200 relative focus:outline-none focus:ring-2 focus:ring-primary" aria-label="For you feed">
                For you
                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-16 h-1 bg-primary rounded-full"></div>
            </button>
            <button class="flex-1 text-center py-4 text-gray-500 hover:bg-dark-hover transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Following feed">
                Following
            </button>
        </div>
    </header>
    
    <!-- Post Creation -->
    <div class="border-b border-dark-border p-4 post-transition">
        <div class="flex gap-4">
            <img data-src="https://source.unsplash.com/random/100x100?face" alt="Your profile" class="w-12 h-12 rounded-full object-cover">
            <div class="flex-1">
                <textarea placeholder="What's happening?" class="w-full bg-transparent text-xl outline-none placeholder-gray-500 mb-4 resize-none min-h-[60px] focus:ring-1 focus:ring-primary rounded" aria-label="Create new post"></textarea>
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="flex gap-3 text-primary">
                        <button class="p-2 rounded-full hover:bg-primary/10 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Add image">
                            <i class="fa-regular fa-image text-xl"></i>
                        </button>
                        <button class="p-2 rounded-full hover:bg-primary/10 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Add emoji">
                            <i class="fa-regular fa-face-smile text-xl"></i>
                        </button>
                        <button class="p-2 rounded-full hover:bg-primary/10 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Add location">
                            <i class="fa-solid fa-location-dot text-xl"></i>
                        </button>
                    </div>
                    <button class="bg-primary hover:bg-secondary text-white font-bold px-5 py-2 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-secondary" aria-label="Submit post">
                        Post
                    </button>
                </div>
            </div>
        </div>
    </div>
    

    @foreach([
        [
            'id' => 'post1',
            'user' => ['name' => 'Emma Wilson', 'handle' => '@emmaW', 'avatar' => 'https://source.unsplash.com/random/100x100?woman'],
            'time' => '3h',
            'content' => 'Just finished my morning hike! The view was absolutely breathtaking today. Nature always has a way of putting things into perspective.',
            'hashtags' => ['#MorningHike', '#NatureLover'],
            'image' => 'https://source.unsplash.com/random/600x400?hiking',
            'comments' => 42,
            'likes' => 287
        ],

    ] as $post)
        <article class="post-transition p-4 border-b border-dark-border" id="{{ $post['id'] }}">
            <div class="flex gap-4">
                <img data-src="{{ $post['user']['avatar'] }}" alt="{{ $post['user']['name'] }} profile" class="w-12 h-12 rounded-full object-cover">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span class="font-bold hover:underline cursor-pointer">{{ $post['user']['name'] }}</span>
                        <span class="text-gray-500">{{ $post['user']['handle'] }}</span>
                        <span class="text-gray-500">·</span>
                        <time class="text-gray-500 hover:underline cursor-pointer">{{ $post['time'] }}</time>
                        <div class="ml-auto relative">
                            <button class="text-gray-500 hover:text-primary p-1 rounded-full hover:bg-primary/10 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary post-menu-btn" aria-label="More options">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-dark-lighter rounded-xl shadow-lg z-10 hidden post-menu">
                                <!-- Menu options -->
                            </div>
                        </div>
                    </div>
                    <p class="mb-3 text-[15px] leading-normal">
                        {{ $post['content'] }}
                        @foreach($post['hashtags'] as $tag)
                            <a href="#" class="text-primary hover:underline cursor-pointer">{{ $tag }}</a>
                        @endforeach
                    </p>
                    <div class="rounded-2xl overflow-hidden mb-3 relative group cursor-pointer">
                        <img data-src="{{ $post['image'] }}" alt="Post image" class="w-full object-cover group-hover:brightness-90 transition-all duration-200">
                    </div>
                    <div class="flex justify-start gap-8">
                        <button class="post-action group comment-btn" data-post="{{ $post['id'] }}" aria-label="{{ $post['comments'] }} comments">
                            <div class="p-2 rounded-full group-hover:bg-primary/10 transition-colors duration-200">
                                <i class="fa-regular fa-comment text-lg"></i>
                            </div>
                            <span>{{ $post['comments'] }}</span>
                        </button>
                        <button class="post-action like group" aria-label="Like post ({{ $post['likes'] }} likes)">
                            <div class="p-2 rounded-full group-hover:bg-pink-500/10 transition-colors duration-200">
                                <i class="fa-regular fa-heart text-lg"></i>
                            </div>
                            <span>{{ $post['likes'] }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </article>
    @endforeach
@endsection

@section('right-sidebar')
    <aside class="hidden lg:block w-[350px] px-4 py-3 sticky top-0 h-screen overflow-y-auto custom-scrollbar" aria-label="Additional content">
        <!-- Right sidebar content -->
        <div class="mb-4">
            <div class="relative group">
                <input type="search" placeholder="Search" class="w-full bg-dark-lighter text-white rounded-full py-3 pl-12 pr-4 outline-none focus:bg-black focus:ring-1 focus:ring-primary transition-all duration-200" aria-label="Search platform">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-500 group-focus-within:text-primary transition-colors duration-200"></i>
            </div>
        </div>
        <!-- Trends and Who to follow sections -->
    </aside>
@endsection