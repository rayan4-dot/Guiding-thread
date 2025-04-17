@extends('layouts.user-layout')

@section('title', 'Post')

@section('content')
    <header class="sticky top-0 z-20 bg-black/95 backdrop-blur-lg border-b border-dark-border shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="javascript:history.back()" 
               class="text-white hover:bg-dark-hover p-2 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary"
               aria-label="Back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-xl font-bold">Post</h1>
            <div class="w-8"></div>
        </div>
    </header>

    <section class="max-w-3xl mx-auto" x-data="{
        mediaModalOpen: false,
        selectedMedia: '',
        selectedMediaType: '',
        optionsOpen: false,
        confirmDeleteOpen: false,
        postToDelete: null,
        deletePost: null,
        replyText: '',
        canSubmit() { return this.replyText.trim().length > 0; },
        init() {
            setTimeout(() => {
                this.deletePost = window.deletePost;
                console.log('Alpine initialized, deletePost set:', this.deletePost);
            }, 100);
        }
    }">
    <article class="p-4 border-b border-dark-border" id="post-{{ $post->id }}" x-data="{ isOwner: {{ json_encode(Auth::check() && Auth::id() === $post->user_id) }} }">
    <div class="flex gap-3">
        <a href="/profile/{{ $post->user->username }}" class="flex-shrink-0 self-start">
            <img src="{{ $post->user->profile_picture ? Storage::url($post->user->profile_picture) : asset('images/default-profile.png') }}"
                 alt="{{ $post->user->name }}"
                 class="w-12 h-12 rounded-full object-cover hover:opacity-90 transition-opacity">
        </a>
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between mb-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="/profile/{{ $post->user->username }}"
                       class="font-bold hover:underline cursor-pointer">{{ $post->user->name }}</a>
                    <span class="text-gray-500 text-sm">{{ '@' . $post->user->username }}</span>
                </div>
                <div x-show="isOwner" class="relative">
                    <button @click.prevent="optionsOpen = !optionsOpen"
                            class="text-gray-500 hover:text-white p-2 rounded-full transition-colors focus:outline-none">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>
                    <div x-show="optionsOpen"
                         x-cloak
                         @click.away="optionsOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-black border border-gray-800 rounded-md shadow-lg z-10">
                        <a href="/post/{{ $post->id }}/edit" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white">
                            <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Post
                        </a>
                        <button @click.prevent="postToDelete = {{ $post->id }}; confirmDeleteOpen = true; optionsOpen = false"
                                class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-800">
                            <i class="fa-solid fa-trash-can mr-2"></i> Delete Post
                        </button>
                    </div>
                </div>
            </div>
            <time class="text-gray-500 text-sm block mb-2" datetime="{{ $post->created_at->toISOString() }}">
                {{ $post->created_at->format('g:i A · M d, Y') }}
            </time>
            <!-- Content rendering logic remains unchanged -->
            <div class="mb-3 text-white">
                <?php
                    $youtubePattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
                    $isYoutube = preg_match($youtubePattern, $post->content, $matches);
                    $videoId = $isYoutube ? $matches[1] : null;
                    $contentWithoutUrl = $videoId ? trim(preg_replace($youtubePattern, '', $post->content)) : $post->content;
                    $contentWithoutUrl = trim($contentWithoutUrl);
                    $paragraphs = preg_split('/\n\s*\n/', $contentWithoutUrl);
                ?>
                @if($contentWithoutUrl)
                    <div class="space-y-2 text-[15px] leading-relaxed whitespace-pre-wrap break-words">
                        @foreach($paragraphs as $paragraph)
                            <?php
                                $paragraph = trim($paragraph);
                                $paragraph = preg_replace('/"([^"]+)"/', '<span class="italic text-gray-300">"$1"</span>', $paragraph);
                                $paragraph = preg_replace('/—([^—]+)—/', '—<span class="font-semibold">$1</span>—', $paragraph);
                            ?>
                            <p>{!! nl2br(e($paragraph)) !!}</p>
                        @endforeach
                    </div>
                @endif
            </div>
            @if($videoId && !$post->media_path && !$post->shared_link)
                <div class="mb-3 rounded-xl overflow-hidden">
                    <div class="aspect-w-16 aspect-h-9">
                        <iframe class="w-full h-full"
                                src="https://www.youtube.com/embed/{{ $videoId }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    </div>
                </div>
            @endif
            @if($post->media_path)
                <?php $mediaItems = is_string($post->media_path) ? json_decode($post->media_path, true) : $post->media_path; ?>
                @if(is_array($mediaItems) && count($mediaItems) > 0)
                    <div class="grid {{ count($mediaItems) === 1 ? 'grid-cols-1' : (count($mediaItems) >= 4 ? 'grid-cols-2' : 'grid-cols-' . count($mediaItems)) }} gap-2 rounded-xl overflow-hidden mb-3">
                        @foreach($mediaItems as $index => $media)
                            @if($media['type'] === 'image')
                                <div class="relative group overflow-hidden {{ count($mediaItems) === 3 && $index === 0 ? 'col-span-2' : '' }} {{ count($mediaItems) >= 5 && $index === 0 ? 'col-span-2 row-span-2' : '' }} rounded-xl">
                                    <img src="{{ $media['path'] }}"
                                         alt="Post image"
                                         class="w-full h-full object-cover rounded-xl hover:brightness-90 transition-all duration-300 cursor-pointer"
                                         @click="mediaModalOpen = true; selectedMedia = '{{ $media['path'] }}'; selectedMediaType = 'image'">
                                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <button class="p-2 bg-black/50 rounded-full"
                                                @click="mediaModalOpen = true; selectedMedia = '{{ $media['path'] }}'; selectedMediaType = 'image'">
                                            <i class="fa-solid fa-expand text-white"></i>
                                        </button>
                                    </div>
                                </div>
                            @elseif($media['type'] === 'video')
                                <div class="relative group rounded-xl overflow-hidden {{ count($mediaItems) === 3 && $index === 0 ? 'col-span-2' : '' }} {{ count($mediaItems) >= 5 && $index === 0 ? 'col-span-2 row-span-2' : '' }}">
                                    <video
                                        class="w-full h-full object-cover rounded-xl cursor-pointer"
                                        poster="{{ asset('images/video-poster.jpg') }}"
                                        @click="mediaModalOpen = true; selectedMedia = '{{ $media['path'] }}'; selectedMediaType = 'video'">
                                        <source src="{{ $media['path'] }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                                        <button class="p-3 bg-black/60 rounded-full"
                                                @click.stop="mediaModalOpen = true; selectedMedia = '{{ $media['path'] }}'; selectedMediaType = 'video'">
                                            <i class="fa-solid fa-play text-white text-xl"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            @elseif($post->shared_link && !$videoId)
                <a href="{{ $post->shared_link }}" target="_blank" rel="noopener noreferrer"
                   class="block p-4 border border-dark-border rounded-xl hover:bg-dark-hover/30 transition-all mb-3">
                    <div class="flex items-center text-primary mb-1">
                        <i class="fa-solid fa-link mr-2"></i>
                        <span class="hover:underline line-clamp-1 overflow-hidden text-ellipsis">{{ $post->shared_link }}</span>
                    </div>
                    <div class="text-gray-500 text-sm line-clamp-1">{{ parse_url($post->shared_link, PHP_URL_HOST) }}</div>
                </a>
            @endif
            <div class="flex items-center justify-between text-sm text-gray-500 py-2 border-t border-dark-border">
                <div class="flex gap-4">
                    <span><strong class="text-white">{{ $post->likes()->count() }}</strong> Likes</span>
                </div>
                <span class="text-gray-600">{{ $post->created_at->diffForHumans() }}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-y border-dark-border">
                <button class="flex items-center gap-1 text-gray-500 hover:text-blue-500 transition-colors group"
                        aria-label="Comments">
                    <div class="p-2 rounded-full group-hover:bg-blue-500/10 transition-colors">
                        <i class="fa-regular fa-comment"></i>
                    </div>
                    <span class="text-sm">Comment</span>
                </button>
                <form action="{{ route('posts.like', $post) }}" method="POST" class="like-form">
                    @csrf
                    <button 
                        class="like-btn flex items-center gap-1 text-gray-500 hover:text-red-500 transition-colors group" 
                        aria-label="Like" 
                        data-post-id="{{ $post->id }}"
                        data-liked="{{ $post->isLikedBy(auth()->user()) ? 'true' : 'false' }}"
                    >
                        <div class="p-2 rounded-full group-hover:bg-red-500/10 transition-colors">
                            <i class="fa{{ $post->isLikedBy(auth()->user()) ? 's' : 'r' }} fa-heart {{ $post->isLikedBy(auth()->user()) ? 'text-red-500' : '' }}"></i>
                        </div>
                        <span class="text-sm like-count">{{ $post->likes()->count() }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</article>

        <!-- Reply form and comments section unchanged -->
        <div class="p-4 border-b border-dark-border bg-black">
            <form class="flex gap-3" @submit.prevent="console.log('Reply submitted:', replyText)">
                <div class="flex-shrink-0">
                    <img src="{{ Auth::check() ? (Auth::user()->profile_picture ? Storage::url(Auth::user()->profile_picture) : asset('images/default-profile.png')) : asset('images/default-profile.png') }}"
                         alt="Your profile"
                         class="w-10 h-10 rounded-full object-cover">
                </div>
                <div class="flex-1">
                    <textarea
                        x-model="replyText"
                        class="w-full bg-transparent border-b border-dark-border/50 p-2 resize-none focus:outline-none focus:border-primary transition-colors min-h-[60px]"
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
                            <button type="button" class="text-primary p-2 rounded-full hover:bg-primary/10 transition-colors" aria-label="Add GIF">
                                <i class="fa-solid fa-film"></i>
                            </button>
                        </div>
                        <button
                            type="submit"
                            class="bg-primary hover:bg-primary/90 text-white px-5 py-2 rounded-full font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="!canSubmit()">
                            Reply
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <section class="p-4 bg-black">
            <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                Comments
                <span class="text-sm font-normal text-gray-500">(0)</span>
            </h2>
            <div class="py-12 text-center">
                <div class="w-16 h-16 bg-dark-hover/50 rounded-full flex items-center justify-center mx-auto mb-5">
                    <i class="fa-regular fa-comment-dots text-2xl text-gray-400"></i>
                </div>
                <p class="text-gray-400 text-lg font-medium">No comments yet</p>
                <p class="text-gray-500 mt-2">Be the first to share your thoughts on this post</p>
            </div>
        </section>

        <!-- Confirmation Modal -->
        <div x-show="confirmDeleteOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-black p-6 rounded-xl border border-gray-800 shadow-lg w-full max-w-md"
                 @click.away="confirmDeleteOpen = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-red-500/20 p-2 rounded-full">
                        <i class="fa-solid fa-trash text-red-500"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">Delete Post?</h3>
                </div>
                <p class="text-gray-500 mb-6">This action cannot be undone. The post will be permanently removed.</p>
                <div class="flex justify-end gap-3">
                    <button @click="confirmDeleteOpen = false"
                            class="px-4 py-2 border border-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors">
                        Cancel
                    </button>
                    <button @click="deletePost(postToDelete); confirmDeleteOpen = false"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- Media Modal unchanged -->
        <div class="fixed inset-0 z-50 overflow-auto bg-black/95 backdrop-blur-md"
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
                <button @click="mediaModalOpen = false"
                        class="absolute top-4 right-4 text-white hover:bg-white/10 p-3 rounded-full transition-colors z-20"
                        aria-label="Close modal">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
                <div class="max-w-5xl w-full" @click.stop>
                    <template x-if="selectedMediaType === 'image'">
                        <img :src="selectedMedia"
                             alt="Full size media"
                             class="w-full h-auto max-h-[85vh] object-contain rounded-lg"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100">
                    </template>
                    <template x-if="selectedMediaType === 'video'">
                        <div class="bg-black rounded-lg overflow-hidden"
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
                <button class="absolute left-4 text-white hover:bg-white/10 p-3 rounded-full transition-colors" aria-label="Previous media">
                    <i class="fa-solid fa-chevron-left text-xl"></i>
                </button>
                <button class="absolute right-4 text-white hover:bg-white/10 p-3 rounded-full transition-colors" aria-label="Next media">
                    <i class="fa-solid fa-chevron-right text-xl"></i>
                </button>
            </div>
        </div>
    </section>
@endsection

