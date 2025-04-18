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

    <section class="max-w-3xl mx-auto">
        <article class="p-4 border-b border-dark-border" id="post-{{ $post->id }}">
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
                        @if (Auth::check() && Auth::id() === $post->user_id)
                            <div class="relative">
                                <button onclick="document.getElementById('options-{{ $post->id }}').classList.toggle('hidden')"
                                        class="text-gray-500 hover:text-white p-2 rounded-full transition-colors focus:outline-none">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                                <div id="options-{{ $post->id }}"
                                     class="hidden absolute right-0 mt-2 w-48 bg-black border border-gray-800 rounded-md shadow-lg z-10">
                                    <a href="/post/{{ $post->id }}/edit" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white">
                                        <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Post
                                    </a>
                                    <button onclick="confirmDelete({{ $post->id }})"
                                            class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-800">
                                        <i class="fa-solid fa-trash-can mr-2"></i> Delete Post
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <time class="text-gray-500 text-sm block mb-2" datetime="{{ $post->created_at->toISOString() }}">
                        {{ $post->created_at->format('g:i A · M d, Y') }}
                    </time>
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
                                                 onclick="openMediaModal('{{ $media['path'] }}', 'image')">
                                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <button class="p-2 bg-black/50 rounded-full"
                                                        onclick="openMediaModal('{{ $media['path'] }}', 'image')">
                                                    <i class="fa-solid fa-expand text-white"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @elseif($media['type'] === 'video')
                                        <div class="relative group rounded-xl overflow-hidden {{ count($mediaItems) === 3 && $index === 0 ? 'col-span-2' : '' }} {{ count($mediaItems) >= 5 && $index === 0 ? 'col-span-2 row-span-2' : '' }}">
                                            <video
                                                class="w-full h-full object-cover rounded-xl cursor-pointer"
                                                poster="{{ asset('images/video-poster.jpg') }}"
                                                onclick="openMediaModal('{{ $media['path'] }}', 'video')">
                                                <source src="{{ $media['path'] }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                                                <button class="p-3 bg-black/60 rounded-full"
                                                        onclick="openMediaModal('{{ $media['path'] }}', 'video')">
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
                            <span><strong class="text-white">{{ $post->comments()->count() }}</strong> Comments</span>
                            <span><strong class="text-white like-summary-count">{{ $post->likes()->count() }}</strong> Likes</span>
                        </div>
                        <span class="text-gray-600">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-y border-dark-border">
                        <a href="#comments" class="flex items-center gap-1 text-gray-500 hover:text-blue-500 transition-colors group" aria-label="Comments">
                            <div class="p-2 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                <i class="fa-regular fa-comment"></i>
                            </div>
                            <span class="text-sm">Comment</span>
                        </a>
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

        <!-- Comment form -->
        <div class="p-4 border-b border-dark-border bg-black">
            @auth
                <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="flex gap-3">
                    @csrf
                    <div class="flex-shrink-0">
                        <img src="{{ Auth::user()->profile_picture ? Storage::url(Auth::user()->profile_picture) : asset('images/default-profile.png') }}"
                             alt="Your profile"
                             class="w-10 h-10 rounded-full object-cover">
                    </div>
                    <div class="flex-1">
                        <textarea
                            name="contenu"
                            class="w-full bg-transparent border-b border-dark-border/50 p-2 resize-none focus:outline-none focus:border-primary transition-colors min-h-[60px]"
                            placeholder="Post your reply"
                            rows="2"
                            required></textarea>
                        @error('contenu')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <div class="flex justify-end mt-3">
                            <button
                                type="submit"
                                class="bg-primary hover:bg-primary/90 text-white px-5 py-2 rounded-full font-medium transition-colors">
                                Reply
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <p class="text-gray-500 text-center">Please <a href="{{ route('login') }}" class="text-primary hover:underline">log in</a> to comment.</p>
            @endauth
        </div>

        <!-- Comments section -->
        <section id="comments" class="p-4 bg-black">
            <h2 class="text-lg font-bold mb-4">Comments ({{ $post->comments()->count() }})</h2>
            @forelse ($post->comments as $comment)
                <div class="flex gap-3 mb-4" id="comment-{{ $comment->id }}">
                    <a href="/profile/{{ $comment->user->username }}" class="flex-shrink-0">
                        <img src="{{ $comment->user->profile_picture ? Storage::url($comment->user->profile_picture) : asset('images/default-profile.png') }}"
                             alt="{{ $comment->user->name }}"
                             class="w-10 h-10 rounded-full object-cover hover:opacity-90 transition-opacity">
                    </a>
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <a href="/profile/{{ $comment->user->username }}" class="font-bold hover:underline">{{ $comment->user->name }}</a>
                            <span class="text-gray-500 text-sm">{{ '@' . $comment->user->username }}</span>
                            <span class="text-gray-500 text-sm">· {{ $comment->created_at->diffForHumans() }}</span>
                            @if (Auth::check() && Auth::id() === $comment->user_id)
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="ml-auto">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="text-red-500 hover:text-red-700 text-sm"
                                        onclick="return confirm('Are you sure you want to delete this comment?')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                        <p class="text-white text-sm leading-relaxed mt-1">{{ nl2br(e($comment->contenu)) }}</p>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center">
                    <div class="w-16 h-16 bg-dark-hover/50 rounded-full flex items-center justify-center mx-auto mb-5">
                        <i class="fa-regular fa-comment-dots text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-400 text-lg font-medium">No comments yet</p>
                    <p class="text-gray-500 mt-2">Be the first to share your thoughts on this post</p>
                </div>
            @endforelse
        </section>

        <!-- Delete Post Modal -->
        <div id="confirm-delete-modal"
             class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-black p-6 rounded-xl border border-gray-800 shadow-lg w-full max-w-md">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-red-500/20 p-2 rounded-full">
                        <i class="fa-solid fa-trash text-red-500"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">Delete Post?</h3>
                </div>
                <p class="text-gray-500 mb-6">This action cannot be undone. The post will be permanently removed.</p>
                <div class="flex justify-end gap-3">
                    <button onclick="document.getElementById('confirm-delete-modal').classList.add('hidden')"
                            class="px-4 py-2 border border-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors">
                        Cancel
                    </button>
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Media Modal -->
        <div class="media-modal fixed inset-0 z-50 overflow-auto bg-black/95 backdrop-blur-md hidden"
             id="mediaModal">
            <div class="relative flex items-center justify-center min-h-screen p-4">
                <button onclick="document.getElementById('mediaModal').classList.add('hidden')"
                        class="absolute top-4 right-4 text-white hover:bg-white/10 p-3 rounded-full transition-colors z-20"
                        aria-label="Close modal">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
                <div class="max-w-5xl w-full">
                    <img id="mediaModalImg" class="w-full h-auto max-h-[85vh] object-contain rounded-lg hidden" alt="Full size media">
                    <div id="mediaModalVideo" class="bg-black rounded-lg overflow-hidden hidden">
                        <video controls autoplay class="w-full h-auto max-h-[85vh] object-contain">
                            <source id="mediaModalVideoSource" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function confirmDelete(postId) {
                const modal = document.getElementById('confirm-delete-modal');
                modal.classList.remove('hidden');
            }

            function openMediaModal(src, type) {
                const modal = document.getElementById('mediaModal');
                const img = document.getElementById('mediaModalImg');
                const video = document.getElementById('mediaModalVideo');
                const videoSource = document.getElementById('mediaModalVideoSource');

                if (type === 'image') {
                    img.src = src;
                    img.classList.remove('hidden');
                    video.classList.add('hidden');
                } else {
                    videoSource.src = src;
                    video.classList.remove('hidden');
                    img.classList.add('hidden');
                    video.querySelector('video').load();
                }

                modal.classList.remove('hidden');
            }
        </script>
    </section>
@endsection