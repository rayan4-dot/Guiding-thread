@extends('layouts.user-layout')

@section('title', 'Notifications')

@section('content')
    <header class="sticky top-0 z-10 bg-black/80 backdrop-blur-md border-b border-dark-border">
        <div class="px-4 py-3 flex justify-between items-center">
            <h2 class="text-xl font-bold">Notifications</h2>
            <button class="text-white hover:bg-dark-hover p-2 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Settings">
                <i class="fa-solid fa-gear text-xl"></i>
            </button>
        </div>
        <div class="flex">
            <button class="flex-1 text-center py-4 font-bold hover:bg-dark-hover transition-colors duration-200 relative focus:outline-none focus:ring-2 focus:ring-primary" aria-label="All">
                All
                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-16 h-1 bg-primary rounded-full"></div>
            </button>
            <button class="flex-1 text-center py-4 text-gray-500 hover:bg-dark-hover transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary" aria-label="Mentions">
                Mentions
            </button>
        </div>
    </header>
    
    <!-- Notifications Section -->
    <div class="p-4 space-y-6">
        <div class="bg-dark-lighter rounded-2xl overflow-hidden">
            <h2 class="text-xl font-bold p-4 border-b border-dark-border">Recent Notifications</h2>
            <div class="space-y-4 p-4">
                @foreach([
                    [
                        'user' => ['name' => 'Emma Smith', 'handle' => '@emmasmith', 'avatar' => 'https://i.pravatar.cc/150?img=1'],
                        'time' => '2h',
                        'content' => 'mentioned you in a post.',
                        'link' => '#'
                    ],
                    [
                        'user' => ['name' => 'James Brown', 'handle' => '@jamesbrown', 'avatar' => 'https://i.pravatar.cc/150?img=2'],
                        'time' => '4h',
                        'content' => 'liked your post.',
                        'link' => '#'
                    ],
                    [
                        'user' => ['name' => 'Olivia Johnson', 'handle' => '@oliviaj', 'avatar' => 'https://i.pravatar.cc/150?img=3'],
                        'time' => '1d',
                        'content' => 'followed you.',
                        'link' => '#'
                    ]
                ] as $notification)
                    <div class="flex items-start gap-4 cursor-pointer hover:bg-dark-hover p-4 transition-colors duration-200 border-b border-dark-border">
                        <img src="{{ $notification['user']['avatar'] }}" alt="{{ $notification['user']['name'] }}'s profile picture" class="w-12 h-12 rounded-full object-cover">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold hover:underline cursor-pointer">{{ $notification['user']['name'] }}</span>
                                <span class="text-gray-500">{{ $notification['user']['handle'] }}</span>
                                <span class="text-gray-500">·</span>
                                <time class="text-gray-500 hover:underline cursor-pointer">{{ $notification['time'] }}</time>
                            </div>
                            <p class="mb-1 text-[15px] leading-normal">
                                <a href="{{ $notification['link'] }}" class="text-primary hover:underline">{{ $notification['content'] }}</a>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('right-sidebar')
    <aside class="hidden lg:block w-[350px] px-4 py-3 sticky top-0 h-screen overflow-y-auto custom-scrollbar" aria-label="Additional content">
        <!-- Right sidebar content -->
        @include('admin.components.right-sidebar')
    </aside>
@endsection