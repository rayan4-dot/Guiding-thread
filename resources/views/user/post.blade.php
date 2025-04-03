@extends('layouts.user-layout')

@section('title', 'Post')

@section('content')
    <!-- Enhanced header with better accessibility and visual design -->
    <header class="sticky top-0 z-10 bg-black/90 backdrop-blur-md border-b border-dark-border shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('user.home') }}" 
               class="text-white hover:bg-dark-hover p-2 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary"
               aria-label="Back to home">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-xl font-bold">Post</h1>
            <button class="text-white hover:bg-dark-hover p-2 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary" 
                    aria-label="Settings">
                <i class="fa-solid fa-gear text-xl"></i>
            </button>
        </div>
    </header>

    <!-- Main content section with improved media handling -->
    <section class="max-w-3xl mx-auto" x-data="{ mediaModalOpen: false, selectedMedia: '', selectedMediaType: '' }">
        <!-- Post article with enhanced styling -->
        <article class="p-4 border-b border-dark-border">
            <div class="flex gap-4">
                <a href="/profile/{{ $post->user->username }}" class="flex-shrink-0">
                    <img src="{{ $post->user->profile_picture ? Storage::url($post->user->profile_picture) : asset('images/default-profile.png') }}" 
                         alt="{{ $post->user->name }}" 
                         class="w-12 h-12 rounded-full object-cover hover:opacity-90 transition-opacity">
                </a>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                        <a href="/profile/{{ $post->user->username }}" 
                           class="font-bold hover:underline cursor-pointer">{{ $post->user->name }}</a>
                        <span class="text-gray-500">{{ '@' . $post->user->username }}</span>
                    </div>
                    <time class="text-gray-500 block mb-3 text-sm" datetime="{{ $post->created_at->toISOString() }}">
                        {{ $post->created_at->format('g:i A · M d, Y') }} · {{ $post->created_at->diffForHumans() }}
                    </time>
                    
                    @if($post->content)
                        <p class="mb-4 text-[15px] leading-relaxed">{{ $post->content }}</p>
                    @endif
                    
                    @if($post->media_path)
                        <?php $mediaItems = is_string($post->media_path) ? json_decode($post->media_path, true) : $post->media_path; ?>
                        @if(is_array($mediaItems) && count($mediaItems) > 0)
                            <div class="grid {{ count($mediaItems) === 1 ? 'grid-cols-1' : (count($mediaItems) >= 2 ? 'grid-cols-2' : '') }} gap-2 rounded-xl overflow-hidden mb-4">
                                @foreach($mediaItems as $index => $media)
                                    <div class="{{ count($mediaItems) === 3 && $index === 0 ? 'col-span-2' : '' }} {{ count($mediaItems) >= 5 && $index >= 4 ? 'hidden sm:block' : '' }}">
                                        @if($media['type'] === 'image')
                                            <div class="relative group overflow-hidden rounded-xl">
                                                <img src="{{ $media['path'] }}" 
                                                     alt="Post image" 
                                                     class="w-full h-auto max-h-[500px] object-cover rounded-xl hover:scale-105 transition-all duration-300 cursor-pointer" 
                                                     @click="mediaModalOpen = true; selectedMedia = '{{ $media['path'] }}'; selectedMediaType = 'image'">
                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                    <button class="p-2 bg-black/50 rounded-full" @click="mediaModalOpen = true; selectedMedia = '{{ $media['path'] }}'; selectedMediaType = 'image'">
                                                        <i class="fa-solid fa-expand text-white"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @elseif($media['type'] === 'video')
                                            <div class="relative group rounded-xl overflow-hidden">
                                                <video controls class="w-full h-auto max-h-[500px] object-cover rounded-xl">
                                                    <source src="{{ $media['path'] }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                                <div class="absolute inset-0 bg-transparent cursor-pointer" 
                                                     @click="mediaModalOpen = true; selectedMedia = '{{ $media['path'] }}'; selectedMediaType = 'video'"></div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @elseif($post->shared_link)
                        <a href="{{ $post->shared_link }}" target="_blank" rel="noopener noreferrer" 
                           class="block p-4 border border-dark-border rounded-xl hover:bg-dark-hover/30 transition-all mb-4">
                            <div class="text-primary hover:underline line-clamp-1">{{ $post->shared_link }}</div>
                            <div class="text-gray-500 text-sm mt-1 line-clamp-1">{{ parse_url($post->shared_link, PHP_URL_HOST) }}</div>
                        </a>
                    @endif
                    
                    <!-- Post stats with timestamps -->
                    <div class="flex items-center gap-4 text-sm text-gray-500 py-3 border-t border-dark-border/50">
                        <span><strong class="text-white">0</strong> Retweets</span>
                        <span><strong class="text-white">0</strong> Quotes</span>
                        <span><strong class="text-white">0</strong> Likes</span>
                        <span><strong class="text-white">0</strong> Bookmarks</span>
                    </div>
                    
                    <!-- Enhanced interaction buttons -->
                    <div class="flex justify-between items-center py-2 border-y border-dark-border/50 mt-1">
                        <button class="flex items-center gap-1 text-gray-500 hover:text-blue-500 transition-colors group" 
                                aria-label="Comments">
                            <div class="p-2 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                <i class="fa-regular fa-comment"></i>
                            </div>
                        </button>
                        <button class="flex items-center gap-1 text-gray-500 hover:text-green-500 transition-colors group" 
                                aria-label="Retweet">
                            <div class="p-2 rounded-full group-hover:bg-green-500/10 transition-colors">
                                <i class="fa-solid fa-retweet"></i>
                            </div>
                        </button>
                        <button class="flex items-center gap-1 text-gray-500 hover:text-red-500 transition-colors group" 
                                aria-label="Like">
                            <div class="p-2 rounded-full group-hover:bg-red-500/10 transition-colors">
                                <i class="fa-regular fa-heart"></i>
                            </div>
                        </button>
                        <button class="flex items-center gap-1 text-gray-500 hover:text-blue-500 transition-colors group" 
                                aria-label="Bookmark">
                            <div class="p-2 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                <i class="fa-regular fa-bookmark"></i>
                            </div>
                        </button>
                        <button class="flex items-center gap-1 text-gray-500 hover:text-blue-500 transition-colors group" 
                                aria-label="Share">
                            <div class="p-2 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                <i class="fa-solid fa-share"></i>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </article>

        <!-- Comment form section -->
        <div class="p-4 border-b border-dark-border">
            <form class="flex gap-3">
                <div class="flex-shrink-0">
                    <img src="{{ asset('images/default-profile.png') }}" alt="Your profile" class="w-10 h-10 rounded-full object-cover">
                </div>
                <div class="flex-1">
                    <textarea 
                        class="w-full bg-transparent border-b border-dark-border/50 p-2 resize-none focus:outline-none focus:border-primary transition-colors" 
                        placeholder="Post your reply"
                        rows="2"></textarea>
                    <div class="flex justify-between items-center mt-3">
                        <div class="flex gap-2">
                            <button type="button" class="text-primary p-2 rounded-full hover:bg-primary/10 transition-colors" aria-label="Add image">
                                <i class="fa-regular fa-image"></i>
                            </button>
                            <button type="button" class="text-primary p-2 rounded-full hover:bg-primary/10 transition-colors" aria-label="Add emoji">
                                <i class="fa-regular fa-face-smile"></i>
                            </button>
                        </div>
                        <button type="submit" class="bg-primary hover:bg-primary/90 text-white px-4 py-1.5 rounded-full font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            Reply
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Comments Section with improved empty state -->
        <section class="p-4">
            <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                Comments
                <span class="text-sm font-normal text-gray-500">(0)</span>
            </h2>
            
            <div class="py-8 text-center">
                <div class="w-12 h-12 bg-dark-hover/50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-regular fa-comment-dots text-xl text-gray-400"></i>
                </div>
                <p class="text-gray-400 text-lg font-medium">No comments yet</p>
                <p class="text-gray-500 mt-2">Be the first to reply to this post</p>
            </div>
        </section>

        <!-- Enhanced media modal with better UX -->
        <div class="fixed inset-0 z-50 overflow-auto bg-black/90 backdrop-blur-sm" 
             x-show="mediaModalOpen" 
             x-cloak 
             @keydown.escape="mediaModalOpen = false"
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0">
            <div class="relative flex items-center justify-center min-h-screen p-4" @click="mediaModalOpen = false">
                <div class="absolute top-4 right-4 z-10">
                    <button @click="mediaModalOpen = false" class="text-white hover:bg-white/10 p-3 rounded-full transition-colors" aria-label="Close modal">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <div class="max-w-5xl w-full" @click.stop>
                    <template x-if="selectedMediaType === 'image'">
                        <img :src="selectedMedia" 
                             alt="Full size media" 
                             class="w-full h-auto max-h-[85vh] object-contain rounded-lg shadow-2xl"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100">
                    </template>
                    <template x-if="selectedMediaType === 'video'">
                        <div class="bg-black rounded-lg overflow-hidden shadow-2xl"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100">
                            <video controls autoplay class="w-full h-auto max-h-[85vh] object-contain">
                                <source :src="selectedMedia" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>
@endsection