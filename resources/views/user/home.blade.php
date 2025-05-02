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

    <section class="divide-y divide-dark-border" id="posts-container">
        @forelse($posts as $post)
            <article class="p-4 border-b border-dark-border" id="post-{{ $post->id }}">
                <div class="flex gap-4">
                    <a href="/profile/{{ $post->user->username }}" class="flex-shrink-0">
                        <img src="{{ $post->user->profile_picture ? Storage::url($post->user->profile_picture) : asset('images/default-profile.png') }}" alt="{{ $post->user->name }}" class="w-12 h-12 rounded-full object-cover hover:opacity-90 transition-opacity">
                    </a>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <a href="/profile/{{ $post->user->username }}" class="font-bold hover:underline cursor-pointer">{{ $post->user->name }}</a>
                            <span class="text-gray-500">{{ '@' . $post->user->username }}</span>
                            <span class="text-gray-500">·</span>
                            <time class="text-gray-500 hover:underline cursor-pointer">{{ $post->created_at->diffForHumans() }}</time>
                            <div class="relative">
                                <button onclick="document.getElementById('options-{{ $post->id }}').classList.toggle('hidden')" class="p-2 rounded-full hover:bg-zinc-800 transition-colors">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                                <div id="options-{{ $post->id }}" class="hidden absolute right-0 mt-1 w-48 bg-zinc-900 border border-zinc-700 rounded-lg shadow-xl z-10">
                                    @if(Auth::check() && Auth::id() === $post->user_id)
                               
                                        <button onclick="deletePost({{ $post->id }}); document.getElementById('options-{{ $post->id }}').classList.add('hidden')" class="flex items-center gap-2 w-full text-left px-4 py-2.5 text-sm text-red-500 hover:bg-zinc-800 rounded-b-lg">
                                            <i class="fa-solid fa-trash-can w-5"></i>
                                            <span>Delete Post</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="block">
                            <?php 
                                $youtubePattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
                                $isYoutube = preg_match($youtubePattern, $post->content, $matches);
                                $videoId = $isYoutube ? $matches[1] : null;
                                $contentWithoutUrl = $videoId ? trim(preg_replace($youtubePattern, '', $post->content)) : $post->content;
                                $contentWithoutUrl = preg_replace('/\s+/', ' ', $contentWithoutUrl);
                                $contentWithoutUrl = trim($contentWithoutUrl);
                            ?>
                            @if($contentWithoutUrl)
                                <p class="mb-3 text-[15px] leading-relaxed">{{ nl2br(e($contentWithoutUrl)) }}</p>
                            @endif
                            @if($videoId && !$post->media_path && !$post->shared_link)
                                <div class="mb-3 flex justify-center">
                                    <iframe class="w-full max-w-2xl h-64 rounded-xl" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            @endif
                            @if($post->media_path)
                                <?php $mediaItems = is_string($post->media_path) ? json_decode($post->media_path, true) : $post->media_path; ?>
                                @if(is_array($mediaItems) && count($mediaItems) > 0)
                                    <div class="grid {{ count($mediaItems) === 1 ? 'grid-cols-1' : 'grid-cols-2' }} gap-2 rounded-xl overflow-hidden mb-3">
                                        @foreach($mediaItems as $media)
                                            @if($media['type'] === 'image')
                                                <img src="{{ $media['path'] }}" alt="Post image" class="w-full h-auto max-h-[500px] object-cover rounded-xl hover:brightness-90 transition-all duration-200 cursor-pointer" data-media="{{ $media['path'] }}" data-type="image">
                                            @elseif($media['type'] === 'video')
                                                <video controls class="w-full h-auto max-h-[500px] object-cover rounded-xl">
                                                    <source src="{{ $media['path'] }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            @elseif($post->shared_link)
                                <?php 
                                    $isYoutube = preg_match($youtubePattern, $post->shared_link, $matches);
                                    $videoId = $isYoutube ? $matches[1] : null;
                                ?>
                                @if($videoId)
                                    <div class="mb-3 flex justify-center">
                                        <iframe class="w-full max-w-2xl h-64 rounded-xl" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                @else
                                    <div class="p-3 border border-dark-border rounded-xl hover:bg-dark-hover/30 transition-all mb-3">
                                        <span class="text-primary hover:underline line-clamp-1">{{ $post->shared_link }}</span>
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
                        </div>
                        <div class="flex justify-start gap-8">
                            <a href="{{ route('post.show', $post->id) }}#comments">
    <button class="flex items-center gap-2 hover:text-blue-500 transition-colors group" aria-label="Comments">
        <div class="p-2 rounded-full group-hover:bg-blue-500/10 transition-colors">
            <i class="fa-regular fa-comment"></i>
        </div>
        <span>{{ $post->comments()->count() }}</span>
    </button>
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
        @empty
            <p class="p-4 text-gray-400 text-center">No posts yet.</p>
        @endforelse


              <!-- Pagination Links -->
              <div class="p-4">
            {{ $posts->links() }}
        </div>
    </section>
<!-- media modal-->

<div id="mediaModal" class="fixed inset-0 z-50 overflow-auto bg-black/90 backdrop-blur-sm hidden">
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="absolute top-4 right-4 z-10">
                <button id="closeMediaModal" class="text-white hover:bg-white/10 p-3 rounded-full transition-colors" aria-label="Close modal">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div class="max-w-5xl w-full">
                <img src="" alt="Selected Media" class="w-full h-auto max-h-[85vh] object-contain rounded-lg shadow-2xl hidden">
                <video controls class="w-full h-auto max-h-[85vh] object-contain rounded-lg shadow-2xl hidden">
                    <source src="" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>
@endsection
<style>
    #postModal, #postModalOverlay, #mediaModal {
    transition: opacity 0.3s ease-out, transform 0.3s ease-out;
}

.like-btn {
    position: absolute;
    gap: 0.5rem;
}

#postModal.hidden, #postModalOverlay.hidden, #mediaModal.hidden {
    opacity: 0;
    transform: scale(0.95);
}

#postModal:not(.hidden), #postModalOverlay:not(.hidden), #mediaModal:not(.hidden) {
    opacity: 1;
    transform: scale(1);
}
.view {
    width: 187px;
    position: relative;
    left: 81%;
    bottom: -36px;}
</style>


@section('right-sidebar')
    <!-- Right sidebar content unchanged -->
@endsection


