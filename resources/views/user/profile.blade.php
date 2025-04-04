@extends('layouts.user-layout')

@section('title', 'Profile')

@section('content')
<!-- Display Success Message -->
@if (session('success'))
    <div class="max-w-screen-xl mx-auto px-4 py-3 bg-green-500 text-white rounded-md mb-4">
        {{ session('success') }}
    </div>
@endif

<!-- Display Error Message -->
@if (session('error'))
    <div class="max-w-screen-xl mx-auto px-4 py-3 bg-red-500 text-white rounded-md mb-4">
        {{ session('error') }}
    </div>
@endif

<!-- Fixed Header -->
<header class="sticky top-0 z-50 backdrop-blur-xl bg-black/80 border-b border-gray-800">
    <div class="max-w-screen-xl mx-auto px-4 py-3 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <a href="/home" class="text-white hover:bg-gray-800 p-2 rounded-full transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-lg font-bold text-white">{{ Auth::user()->name }}</h2>
        </div>
        <button class="text-white hover:bg-gray-800 p-2 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-black" aria-label="Settings">
            <i class="fa-solid fa-gear"></i>
        </button>
    </div>
</header>

<!-- Main Content -->
<main class="max-w-screen-xl mx-auto" x-data="{ modalOpen: false, pictureModalOpen: false, coverModalOpen: false, mediaModalOpen: false, selectedMedia: '', selectedMediaType: '', editModalOpen: false, editingPost: null }">
    <!-- Profile Header -->
    <section class="relative mb-6">
        <!-- Cover Photo -->
        <div class="h-48 w-full bg-gradient-to-r from-gray-900 to-gray-800 overflow-hidden sm:h-56 md:h-64">
            <button @click="coverModalOpen = true" class="w-full h-full">
                <img src="{{ Auth::user()->cover_photo ? Storage::url(Auth::user()->cover_photo) : 'https://source.unsplash.com/random/1200x400?dark' }}" alt="Cover Photo" class="w-full h-full object-cover opacity-80 hover:opacity-100 transition-opacity">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            </button>
        </div>
        
        <!-- Profile Info -->
        <div class="relative px-4 pb-4">
            <!-- Profile Picture -->
            <div class="absolute -top-16 left-4 ring-4 ring-black rounded-full sm:-top-20 md:-top-24">
                <button @click="pictureModalOpen = true" class="focus:outline-none">
                    <img src="{{ Auth::user()->profile_picture ? Storage::url(Auth::user()->profile_picture) : asset('images/default-profile.png') }}" alt="Profile Picture" class="w-24 h-24 rounded-full border-4 border-black hover:opacity-90 transition-opacity sm:w-28 sm:h-28 md:w-32 md:h-32">
                </button>
            </div>
            
            <!-- Profile Details -->
            <div class="flex justify-between items-center pt-16 sm:pt-20 md:pt-24">
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ Auth::user()->name }}</h1>
                    <p class="text-gray-400 text-sm">{{ '@' . Auth::user()->username }}</p>
                </div>
                <button @click="modalOpen = true" class="bg-white text-black font-bold px-4 py-2 rounded-full hover:bg-gray-200 transition-colors text-sm">
                    Edit profile
                </button>
            </div>
            
            <!-- Bio -->
            <div class="mt-3">
                <p class="text-gray-300 text-sm">{{ Auth::user()->bio ?? 'No bio defined' }}</p>
            </div>
            
            <!-- Stats -->
            <div class="flex space-x-4 mt-3">
                <div class="flex items-center space-x-1">
                    <i class="fa-regular fa-calendar text-gray-500"></i>
                    <span class="text-gray-400 text-sm">Joined {{ Auth::user()->created_at->format('F Y') }}</span>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Friends Section -->
    <section class="px-4 mb-6">
        <h2 class="text-lg font-bold text-white mb-3">Friends</h2>
        <div class="grid grid-cols-4 gap-3 sm:grid-cols-6 md:grid-cols-8">
            @forelse($friends as $friend)
                <a href="/profile/{{ $friend->username }}" class="relative group flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full overflow-hidden ring-2 ring-gray-800 group-hover:ring-blue-500 transition-all">
                        <img src="{{ $friend->profile_picture ? Storage::url($friend->profile_picture) : asset('images/default-profile.png') }}" alt="{{ $friend->name }}" class="w-full h-full object-cover">
                    </div>
                    <span class="mt-1 text-xs text-gray-400 text-center truncate w-full">{{ explode(' ', $friend->name)[0] }}</span>
                    <div class="absolute -bottom-12 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded-lg px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none shadow-lg border border-gray-800 w-max">
                        {{ $friend->name }}
                    </div>
                </a>
            @empty
                <p class="text-gray-400 text-sm col-span-full">No friends found.</p>
            @endforelse
            @if($friends->count() > 0)
                <a href="/friends" class="flex flex-col items-center justify-center w-12 h-12 rounded-full bg-gray-800 hover:bg-gray-700 transition-colors">
                    <i class="fa-solid fa-ellipsis text-gray-400"></i>
                    <span class="mt-1 text-xs text-gray-400">More</span>
                </a>
            @endif
        </div>
    </section>

    <!-- Tabs -->
    <section class="border-b border-gray-800 mb-2">
        <div class="flex px-4">
            <button class="py-4 px-6 text-center text-white font-semibold border-b-2 border-blue-500 hover:bg-gray-800/50 transition-colors" aria-selected="true">
                Posts
            </button>
        </div>
    </section>

    <!-- Posts Section -->
    <section class="divide-y divide-gray-800" id="posts-container">
        @forelse($posts as $post)
            <article class="p-4 hover:bg-gray-900/30 transition-colors" id="post-{{ $post->id }}" x-data="{ showOptions: false }">
                <div class="flex gap-3">
                    <a href="/profile/{{ $post->user->username }}" class="flex-shrink-0">
                        <img src="{{ $post->user->profile_picture ? Storage::url($post->user->profile_picture) : asset('images/default-profile.png') }}" alt="{{ $post->user->name }} profile" class="w-12 h-12 rounded-full hover:opacity-90 transition-opacity">
                    </a>
                    <div class="flex-1">
                        <div class="flex items-center gap-1.5">
                            <a href="/profile/{{ $post->user->username }}" class="font-bold text-white hover:underline">{{ $post->user->name }}</a>
                            <span class="text-gray-500">{{ '@' . $post->user->username }}</span>
                            <span class="text-gray-500">·</span>
                            <time class="text-gray-500 hover:underline">{{ $post->created_at->diffForHumans() }}</time>
                            <div class="relative ml-auto" @click.away="showOptions = false">
                                <button @click="showOptions = !showOptions" class="p-2 rounded-full hover:bg-zinc-800 transition-colors">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                                <div x-show="showOptions" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="absolute right-0 mt-1 w-48 bg-zinc-900 border border-zinc-700 rounded-lg shadow-xl z-10">
                                    @if(Auth::check() && Auth::id() === $post->user_id)
                                        <button @click="showOptions = false; $root.editModalOpen = true; $root.editingPost = { id: {{ $post->id }}, content: '{{ addslashes($post->content) }}', media: {{ $post->media_path ?? '[]' }}, shared_link: '{{ $post->shared_link }}' }" class="flex items-center gap-2 w-full text-left px-4 py-2.5 text-sm hover:bg-zinc-800 rounded-t-lg">
                                            <i class="fa-solid fa-pen-to-square w-5"></i>
                                            <span>Edit Post</span>
                                        </button>
                                        <button @click="showOptions = false; deletePost({{ $post->id }})" class="flex items-center gap-2 w-full text-left px-4 py-2.5 text-sm text-red-500 hover:bg-zinc-800 rounded-b-lg">
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
                                <p class="text-white text-base leading-relaxed mt-2">{{ nl2br(e($contentWithoutUrl)) }}</p>
                            @endif
                            @if($videoId && !$post->media_path && !$post->shared_link)
                                <div class="mt-3 flex justify-center">
                                    <iframe class="w-full max-w-2xl h-64 rounded-xl" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            @endif
                            @if($post->media_path)
                                <?php $mediaItems = is_string($post->media_path) ? json_decode($post->media_path, true) : $post->media_path; ?>
                                @if(is_array($mediaItems) && count($mediaItems) > 0)
                                    <div class="mt-3 grid {{ count($mediaItems) === 1 ? 'grid-cols-1' : 'grid-cols-2' }} gap-2 rounded-xl overflow-hidden border border-gray-800">
                                        @foreach($mediaItems as $media)
                                            @if($media['type'] === 'image')
                                                <img src="{{ $media['path'] }}" alt="Post image" class="w-full h-auto max-h-[500px] object-cover hover:opacity-90 transition-opacity cursor-pointer" @click.stop="mediaModalOpen = true; selectedMedia = '{{ $media['path'] }}'; selectedMediaType = 'image'">
                                            @elseif($media['type'] === 'video')
                                                <video controls class="w-full h-auto max-h-[500px] object-cover">
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
                                    <div class="mt-3 flex justify-center">
                                        <iframe class="w-full max-w-2xl h-64 rounded-xl" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                @else
                                    <div class="mt-3 p-3 border border-gray-800 rounded-xl hover:bg-gray-900/30">
                                        <span class="text-blue-500 hover:underline line-clamp-1">{{ $post->shared_link }}</span>
                                    </div>
                                @endif
                            @endif
                            <!-- Add the "View Post" link here -->
                            <a href="{{ route('post.show', $post->id) }}" class="block text-blue-500 hover:underline text-sm mt-2">View Post</a>
                        </div>
                        <div class="flex justify-start gap-8 mt-3 text-gray-500">
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
            <div class="px-4 py-6 text-center text-gray-400">
                <p class="text-lg font-medium">No posts yet</p>
                <p class="mt-2">Share your thoughts with the world!</p>
            </div>
        @endforelse
    </section>

    <!-- Pagination -->
    @if($posts->hasPages())
        <div class="flex justify-center py-6">
            {{ $posts->links('vendor.pagination.tailwind') }}
        </div>
    @endif

    <!-- Edit Post Modal -->
    <div class="fixed inset-0 z-50 overflow-auto bg-black/70 backdrop-blur-sm flex items-center justify-center" 
         x-show="editModalOpen" 
         x-cloak 
         @keydown.escape="editModalOpen = false" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0">
        <div class="relative bg-black w-full max-w-lg rounded-xl border border-gray-800 shadow-lg p-4" @click.away="editModalOpen = false">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-white">Edit Post</h3>
                <button @click="editModalOpen = false" class="text-white hover:bg-gray-800 p-2 rounded-full transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form x-data="{ content: editingPost?.content || '', sharedLink: editingPost?.shared_link || '', media: editingPost?.media || [], newMedia: [], removedMedia: [] }" 
                  @submit.prevent="updatePost(editingPost?.id)">
                <div class="mb-4">
                    <textarea x-model="content" name="content" rows="4" class="w-full bg-transparent text-white border border-gray-700 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" placeholder="What's on your mind?"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Shared Link</label>
                    <input x-model="sharedLink" type="url" name="shared_link" class="w-full bg-transparent text-white border border-gray-700 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="https://example.com">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Media</label>
                    <div class="grid grid-cols-3 gap-2 mb-2">
                        <template x-for="(item, index) in media" :key="item.path">
                            <div class="relative">
                                <img :src="item.path" class="w-full h-24 object-cover rounded-md" alt="Media">
                                <button type="button" @click="media.splice(index, 1); removedMedia.push(item.path)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                    <input type="file" name="media[]" multiple accept="image/*,video/*" @change="newMedia = Array.from($event.target.files)" class="w-full text-gray-400">
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 rounded-full hover:bg-blue-600 transition-colors">Update Post</button>
            </form>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="fixed inset-0 z-50 overflow-auto flex items-center justify-center bg-black/70" 
         x-show="modalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="relative bg-black w-full max-w-lg rounded-xl border border-gray-800 shadow-lg" @click.away="modalOpen = false">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-800">
                <div class="flex items-center space-x-4">
                    <button @click="modalOpen = false" class="text-white hover:bg-gray-800 p-2 rounded-full transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <h3 class="text-xl font-bold text-white">Edit profile</h3>
                </div>
                <button type="submit" form="profileForm" class="bg-white text-black font-bold px-5 py-2 rounded-full hover:bg-gray-200 transition-colors text-sm">
                    Save
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-4">
                <form id="profileForm" action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <!-- Cover Photo Section -->
                    <div class="relative mb-6">
                        <h4 class="text-sm font-medium text-gray-400 mb-2">Cover Photo</h4>
                        <div class="group">
                            <div class="relative w-full h-32 rounded-lg overflow-hidden border border-gray-800">
                                <img id="coverPreview" src="{{ Auth::user()->cover_photo ? Storage::url(Auth::user()->cover_photo) : 'https://source.unsplash.com/random/1200x400?dark' }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <label for="coverPhoto" class="cursor-pointer bg-black/60 text-white p-2 rounded-full">
                                        <i class="fa-solid fa-camera"></i>
                                        <input type="file" id="coverPhoto" name="cover_photo" class="hidden" accept="image/*">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Photo Section -->
                    <div class="relative mb-6">
                        <h4 class="text-sm font-medium text-gray-400 mb-2">Profile Picture</h4>
                        <div class="group">
                            <div class="relative w-20 h-20 rounded-full overflow-hidden ring-4 ring-black">
                                <img id="avatarPreview" src="{{ Auth::user()->profile_picture ? Storage::url(Auth::user()->profile_picture) : asset('images/default-profile.png') }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <label for="profilePhoto" class="cursor-pointer bg-black/60 text-white p-2 rounded-full">
                                        <i class="fa-solid fa-camera"></i>
                                        <input type="file" id="profilePhoto" name="profile_picture" class="hidden" accept="image/*">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Fields -->
                    <div class="space-y-4 mt-6">
                        <!-- Name -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-400">Name</label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" maxlength="50" 
                                class="w-full bg-transparent text-white border border-gray-700 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Username (Editable) -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-400">Username</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">@</span>
                                <input type="text" name="username" value="{{ old('username', Auth::user()->username) }}" maxlength="50"
                                    class="w-full bg-transparent text-white border border-gray-700 rounded-md px-3 py-2 pl-7 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            @error('username')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Bio -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-400">Bio</label>
                            <textarea name="bio" rows="3" maxlength="160" 
                                class="w-full bg-transparent text-white border border-gray-700 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('bio', Auth::user()->bio) ?? '' }}</textarea>
                            @error('bio')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Profile Picture Modal -->
    <div class="fixed inset-0 z-50 overflow-auto flex items-center justify-center bg-black/70" 
         x-show="pictureModalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="relative bg-black w-full max-w-md rounded-xl border border-gray-800 shadow-lg" @click.away="pictureModalOpen = false">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-800">
                <h3 class="text-xl font-bold text-white">Profile Picture</h3>
                <button @click="pictureModalOpen = false" class="text-white hover:bg-gray-800 p-2 rounded-full transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-4 flex flex-col items-center">
                <img src="{{ Auth::user()->profile_picture ? Storage::url(Auth::user()->profile_picture) : asset('images/default-profile.png') }}" alt="Profile Picture" class="w-64 h-64 object-cover rounded-lg">
                @if (Auth::user()->profile_picture)
                    <form action="{{ route('user.profile.remove-picture') }}" method="POST" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" onclick="return confirm('Are you sure you want to remove your profile picture?')">
                            <i class="fa-solid fa-trash"></i> Remove Picture
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Cover Photo Modal -->
    <div class="fixed inset-0 z-50 overflow-auto flex items-center justify-center bg-black/70" 
         x-show="coverModalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="relative bg-black w-full max-w-3xl rounded-xl border border-gray-800 shadow-lg" @click.away="coverModalOpen = false">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-800">
                <h3 class="text-xl font-bold text-white">Cover Photo</h3>
                <button @click="coverModalOpen = false" class="text-white hover:bg-gray-800 p-2 rounded-full transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-4 flex flex-col items-center">
                <img src="{{ Auth::user()->cover_photo ? Storage::url(Auth::user()->cover_photo) : 'https://source.unsplash.com/random/1200x400?dark' }}" alt="Cover Photo" class="w-full h-96 object-cover rounded-lg">
                @if (Auth::user()->cover_photo)
                    <form action="{{ route('user.profile.remove-cover') }}" method="POST" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" onclick="return confirm('Are you sure you want to remove your cover photo?')">
                            <i class="fa-solid fa-trash"></i> Remove Cover Photo
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

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
   
</script>
</main>

@endsection

@section('right-sidebar')
    <!-- Right sidebar content remains unchanged -->
@endsection