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

    <section class="divide-y divide-dark-border" x-data="{ mediaModalOpen: false, selectedMedia: '', selectedMediaType: '' }" id="posts-container">
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
                            <button class="ml-auto text-gray-500 hover:text-primary p-1 rounded-full hover:bg-primary/10 transition-all duration-200">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                        </div>
                        <div class="block">
                            <?php 
                        $youtubePattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
                        $isYoutube = preg_match($youtubePattern, $post->content, $matches);
                        $videoId = $isYoutube ? $matches[1] : null;
                        $contentWithoutUrl = $videoId ? trim(preg_replace($youtubePattern, '', $post->content)) : $post->content;
                        // Remove extra newlines and whitespace
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
                                                <img src="{{ $media['path'] }}" alt="Post image" class="w-full h-auto max-h-[500px] object-cover rounded-xl hover:brightness-90 transition-all duration-200 cursor-pointer" @click.stop="mediaModalOpen = true; selectedMedia = '{{ $media['path'] }}'; selectedMediaType = 'image'">
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
                            <a href="{{ route('post.show', $post->id) }}" class="block text-primary hover:underline text-sm mt-2">View Post</a>
                        </div>
                        <div class="flex justify-start gap-8">
                            <button class="flex items-center gap-2 hover:text-blue-500 transition-colors group" aria-label="Comments">
                                <div class="p-2 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                    <i class="fa-regular fa-comment"></i>
                                </div>
                                <span>0</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-red-500 transition-colors group" aria-label="Like">
                                <div class="p-2 rounded-full group-hover:bg-red-500/10 transition-colors">
                                    <i class="fa-regular fa-heart"></i>
                                </div>
                                <span>0</span>
                            </button>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <p class="p-4 text-gray-400 text-center">No posts yet.</p>
        @endforelse
    </section>

    <!-- Media Modal -->
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
                    <img :src="selectedMedia" alt="Selected Media" class="w-full h-auto max-h-[85vh] object-contain rounded-lg shadow-2xl">
                </template>
                <template x-if="selectedMediaType === 'video'">
                    <video controls class="w-full h-auto max-h-[85vh] object-contain rounded-lg shadow-2xl">
                        <source :src="selectedMedia" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </template>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof Alpine === 'undefined') {
                console.error('Alpine.js is not loaded!');
                return;
            }

            window.appendNewPost = (post) => {
                console.log('Appending post:', post);
                const container = document.getElementById('posts-container');
                if (!container) {
                    console.error('Posts container not found!');
                    return;
                }

                const youtubePattern = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/embed\/)([^"&?\/\s]{11})/i;
                const isYoutubeContent = youtubePattern.test(post.content);
                const videoIdContent = isYoutubeContent ? post.content.match(youtubePattern)[1] : null;
                const isYoutubeShared = post.shared_link && youtubePattern.test(post.shared_link);
                const videoIdShared = isYoutubeShared ? post.shared_link.match(youtubePattern)[1] : null;
                const contentWithoutUrl = videoIdContent ? post.content.replace(youtubePattern, '').trim() : post.content;

                console.log("Content Video ID: ", videoIdContent, "Shared Link Video ID: ", videoIdShared);

                const mediaHtml = Array.isArray(post.media_path) && post.media_path.length > 0 ? `
                    <div class="grid ${post.media_path.length === 1 ? 'grid-cols-1' : 'grid-cols-2'} gap-2 rounded-xl overflow-hidden mb-3">
                        ${post.media_path.map(media => `
                            ${media.type === 'image' ? `
                                <img src="${media.path}" alt="Post image" class="w-full h-auto max-h-[500px] object-cover rounded-xl hover:brightness-90 transition-all duration-200 cursor-pointer" data-media="${media.path}" data-type="image">
                            ` : `
                                <video controls class="w-full h-auto max-h-[500px] object-cover rounded-xl">
                                    <source src="${media.path}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            `}
                        `).join('')}
                    </div>
                ` : videoIdContent && !post.media_path && !post.shared_link ? `
                    <div class="mb-3 flex justify-center">
                        <iframe class="w-full max-w-2xl h-64 rounded-xl" src="https://www.youtube.com/embed/${videoIdContent}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                ` : post.shared_link ? `
                    ${videoIdShared ? `
                        <div class="mb-3 flex justify-center">
                            <iframe class="w-full max-w-2xl h-64 rounded-xl" src="https://www.youtube.com/embed/${videoIdShared}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    ` : `
                        <div class="p-3 border border-dark-border rounded-xl hover:bg-dark-hover/30 transition-all mb-3">
                            <span class="text-primary hover:underline line-clamp-1">${post.shared_link}</span>
                        </div>
                    `}
                ` : '';

                const postHtml = `
                    <article class="p-4 border-b border-dark-border" id="post-${post.id}">
                        <div class="flex gap-4">
                            <a href="/profile/${post.user.username}" class="flex-shrink-0">
                                <img src="${post.user.profile_picture}" alt="${post.user.name}" class="w-12 h-12 rounded-full object-cover hover:opacity-90 transition-opacity">
                            </a>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <a href="/profile/${post.user.username}" class="font-bold hover:underline cursor-pointer">${post.user.name}</a>
                                    <span class="text-gray-500">@${post.user.username}</span>
                                    <span class="text-gray-500">·</span>
                                    <time class="text-gray-500 hover:underline cursor-pointer">${post.created_at}</time>
                                    <button class="ml-auto text-gray-500 hover:text-primary p-1 rounded-full hover:bg-primary/10 transition-all duration-200">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                </div>
                                <div class="block">
                                    ${contentWithoutUrl ? `<p class="mb-3 text-[15px] leading-relaxed">${contentWithoutUrl.replace(/\n/g, '<br>')}</p>` : ''}
                                    ${mediaHtml}
                                    <a href="/post/${post.id}" class="block text-primary hover:underline text-sm mt-2">View Post</a>
                                </div>
                                <div class="flex justify-start gap-8">
                                    <button class="flex items-center gap-2 hover:text-blue-500 transition-colors group" aria-label="Comments">
                                        <div class="p-2 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                            <i class="fa-regular fa-comment"></i>
                                        </div>
                                        <span>0</span>
                                    </button>
                                    <button class="flex items-center gap-2 hover:text-red-500 transition-colors group" aria-label="Like">
                                        <div class="p-2 rounded-full group-hover:bg-red-500/10 transition-colors">
                                            <i class="fa-regular fa-heart"></i>
                                        </div>
                                        <span>0</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                `;
                container.insertAdjacentHTML('afterbegin', postHtml);

                const newPost = document.getElementById(`post-${post.id}`);
                newPost.querySelectorAll('img[data-media]').forEach(img => {
                    img.addEventListener('click', (e) => {
                        e.preventDefault();
                        const alpineData = container.__x.$data;
                        alpineData.mediaModalOpen = true;
                        alpineData.selectedMedia = img.dataset.media;
                        alpineData.selectedMediaType = img.dataset.type;
                    });
                });
            };
        });
    </script>
@endsection

@section('right-sidebar')
    <!-- Existing right sidebar content -->
@endsection