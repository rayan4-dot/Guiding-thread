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

    <section class="divide-y divide-dark-border" x-data="{ mediaModalOpen: false, selectedMedia: '' }" id="posts-container">
        @forelse($posts as $post)
            <article class="p-4 border-b border-dark-border" id="post-{{ $post->id }}">
                <div class="flex gap-4">
                    <a href="/profile/{{ $post->user->username }}">
                        <img src="{{ $post->user->profile_picture ? Storage::url($post->user->profile_picture) : asset('images/default-profile.png') }}" alt="{{ $post->user->name }}" class="w-12 h-12 rounded-full object-cover">
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
                        @if($post->content)
                        <a href="{{ route('post.show', $post->id) }}">
    <p class="mb-3 text-[15px] leading-normal">{{ $post->content }}</p>
</a>                        @endif
                        @if($post->media_path)
                            <?php $mediaItems = is_string($post->media_path) ? json_decode($post->media_path, true) : $post->media_path; ?>
                            @if(is_array($mediaItems) && count($mediaItems) > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 rounded-2xl overflow-hidden mb-3">
                                    @foreach($mediaItems as $media)
                                        @if($media['type'] === 'image')
                                            <img src="{{ $media['path'] }}" alt="Post image" class="w-full h-auto max-h-96 object-contain rounded-lg hover:brightness-90 transition-all duration-200 cursor-pointer" @click="mediaModalOpen = true; selectedMedia = '{{ $media['path'] }}'">
                                        @elseif($media['type'] === 'video')
                                            <video controls class="w-full h-auto max-h-96 object-contain rounded-lg">
                                                <source src="{{ $media['path'] }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        @elseif($post->shared_link)
                            <a href="{{ $post->shared_link }}" target="_blank" class="text-primary hover:underline mb-3 block">{{ $post->shared_link }}</a>
                        @endif
                        <div class="flex justify-start gap-8">
                            <button class="flex items-center gap-2 hover:text-blue-500 transition-colors group" aria-label="Comments">
                                <div class="p-2 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                    <i class="fa-regular fa-comment"></i>
                                </div>
                                <span>0</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-green-500 transition-colors group" aria-label="Retweet">
                                <div class="p-2 rounded-full group-hover:bg-green-500/10 transition-colors">
                                    <i class="fa-solid fa-retweet"></i>
                                </div>
                                <span>0</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-red-500 transition-colors group" aria-label="Like">
                                <div class="p-2 rounded-full group-hover:bg-red-500/10 transition-colors">
                                    <i class="fa-regular fa-heart"></i>
                                </div>
                                <span>0</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-blue-500 transition-colors group" aria-label="Share">
                                <div class="p-2 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                    <i class="fa-solid fa-share"></i>
                                </div>
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
    <div class="fixed inset-0 z-50 overflow-auto bg-black/70" x-show="mediaModalOpen" x-cloak @keydown.escape="mediaModalOpen = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="relative flex items-center justify-center min-h-screen p-4" @click="mediaModalOpen = false">
            <div class="relative bg-black w-full max-w-2xl rounded-xl border border-gray-800 shadow-lg" @click.stop>
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-800">
                    <h3 class="text-xl font-bold text-white">Media</h3>
                    <button @click="mediaModalOpen = false" class="text-white hover:bg-gray-800 p-2 rounded-full transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-4 flex flex-col items-center">
                    <img :src="selectedMedia" alt="Selected Media" class="w-full h-auto max-h-[80vh] object-contain rounded-lg" x-show="selectedMedia">
                </div>
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

                const mediaHtml = Array.isArray(post.media_path) && post.media_path.length > 0 ? `
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 rounded-2xl overflow-hidden mb-3">
                        ${post.media_path.map(media => `
                            ${media.type === 'image' ? `
                                <img src="${media.path}" alt="Post image" class="w-full h-auto max-h-96 object-contain rounded-lg hover:brightness-90 transition-all duration-200 cursor-pointer" data-media="${media.path}">
                            ` : `
                                <video controls class="w-full h-auto max-h-96 object-contain rounded-lg">
                                    <source src="${media.path}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            `}
                        `).join('')}
                    </div>
                ` : post.shared_link ? `
                    <a href="${post.shared_link}" target="_blank" class="text-primary hover:underline mb-3 block">${post.shared_link}</a>
                ` : '';

                const postHtml = `
                    <article class="p-4 border-b border-dark-border" id="post-${post.id}">
                        <div class="flex gap-4">
                            <a href="/profile/${post.user.username}">
                                <img src="${post.user.profile_picture}" alt="${post.user.name}" class="w-12 h-12 rounded-full object-cover">
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
                                ${post.content ? `<p class="mb-3 text-[15px] leading-normal">${post.content}</p>` : ''}
                                ${mediaHtml}
                                <div class="flex justify-start gap-8">
                                    <button class="flex items-center gap-2 hover:text-blue-500 transition-colors group" aria-label="Comments">
                                        <div class="p-2 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                            <i class="fa-regular fa-comment"></i>
                                        </div>
                                        <span>0</span>
                                    </button>
                                    <button class="flex items-center gap-2 hover:text-green-500 transition-colors group" aria-label="Retweet">
                                        <div class="p-2 rounded-full group-hover:bg-green-500/10 transition-colors">
                                            <i class="fa-solid fa-retweet"></i>
                                        </div>
                                        <span>0</span>
                                    </button>
                                    <button class="flex items-center gap-2 hover:text-red-500 transition-colors group" aria-label="Like">
                                        <div class="p-2 rounded-full group-hover:bg-red-500/10 transition-colors">
                                            <i class="fa-regular fa-heart"></i>
                                        </div>
                                        <span>0</span>
                                    </button>
                                    <button class="flex items-center gap-2 hover:text-blue-500 transition-colors group" aria-label="Share">
                                        <div class="p-2 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                            <i class="fa-solid fa-share"></i>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                `;
                container.insertAdjacentHTML('afterbegin', postHtml);

                const newPost = document.getElementById(`post-${post.id}`);
                newPost.querySelectorAll('img[data-media]').forEach(img => {
                    img.addEventListener('click', () => {
                        const alpineData = container.__x.$data;
                        alpineData.mediaModalOpen = true;
                        alpineData.selectedMedia = img.dataset.media;
                    });
                });
            };
        });
    </script>
@endsection

@section('right-sidebar')
    <!-- Existing right sidebar content -->
@endsection