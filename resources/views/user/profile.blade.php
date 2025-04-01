@extends('layouts.user-layout')

@section('title', 'Profile')

@section('content')
<!-- Fixed Header -->
<header class="sticky top-0 z-50 backdrop-blur-xl bg-black/80 border-b border-gray-800">
    <div class="max-w-screen-xl mx-auto px-4 py-3 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <a href="/home" class="text-white hover:bg-gray-800 p-2 rounded-full transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-lg font-bold text-white">John Doe</h2>
        </div>
        <button class="text-white hover:bg-gray-800 p-2 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-black" aria-label="Settings">
            <i class="fa-solid fa-gear"></i>
        </button>
    </div>
</header>

<!-- Main Content -->
<main class="max-w-screen-xl mx-auto" x-data="{ modalOpen: false }">
    <!-- Profile Header -->
    <section class="relative mb-6">
        <!-- Cover Photo -->
        <div class="h-48 w-full bg-gradient-to-r from-gray-900 to-gray-800 overflow-hidden">
            <img src="https://source.unsplash.com/random/1200x400?dark" alt="Cover Photo" class="w-full h-full object-cover opacity-80">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        </div>
        
        <!-- Profile Info -->
        <div class="relative px-4 pb-4">
            <!-- Profile Picture -->
            <div class="absolute -top-12 left-4 ring-4 ring-black rounded-full">
                <img src="https://i.pravatar.cc/150?img=4" alt="Profile Picture" class="w-24 h-24 rounded-full border-4 border-black">
            </div>
            
            <!-- Profile Details -->
            <div class="flex justify-between items-center pt-16">
                <div>
                    <h1 class="text-2xl font-bold text-white">John Doe</h1>
                    <p class="text-gray-400 text-sm">@johndoe</p>
                </div>
                <button @click="modalOpen = true" class="bg-white text-black font-bold px-4 py-2 rounded-full hover:bg-gray-200 transition-colors text-sm">
                    Edit profile
                </button>
            </div>
            
            <!-- Bio -->
            <div class="mt-3">
                <p class="text-gray-300 text-sm">Web developer, UX designer, and coffee enthusiast. Building awesome experiences for the web.</p>
            </div>
            
            <!-- Stats -->
            <div class="flex space-x-4 mt-3">
                <div class="flex items-center space-x-1">
                    <i class="fa-solid fa-location-dot text-gray-500"></i>
                    <span class="text-gray-400 text-sm">San Francisco, CA</span>
                </div>
                <div class="flex items-center space-x-1">
                    <i class="fa-solid fa-link text-gray-500"></i>
                    <a href="#" class="text-blue-400 text-sm hover:underline">johndoe.com</a>
                </div>
                <div class="flex items-center space-x-1">
                    <i class="fa-regular fa-calendar text-gray-500"></i>
                    <span class="text-gray-400 text-sm">Joined October 2020</span>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Friends Section -->
    <section class="px-4 mb-6">
        <h2 class="text-lg font-bold text-white mb-3">Friends</h2>
        <div class="grid grid-cols-6 gap-4 sm:grid-cols-8 md:grid-cols-10">
            @foreach([
                ['name' => 'Jane Smith', 'handle' => 'janesmith', 'avatar' => 'https://i.pravatar.cc/150?img=5'],
                ['name' => 'Alice Brown', 'handle' => 'alice', 'avatar' => 'https://i.pravatar.cc/150?img=6'],
                ['name' => 'Bob Wilson', 'handle' => 'bob', 'avatar' => 'https://i.pravatar.cc/150?img=7'],
                ['name' => 'Mike Johnson', 'handle' => 'mikej', 'avatar' => 'https://i.pravatar.cc/150?img=8'],
                ['name' => 'Sarah Lee', 'handle' => 'sarahlee', 'avatar' => 'https://i.pravatar.cc/150?img=9']
            ] as $friend)
                <a href="/profile/{{ $friend['handle'] }}" class="relative group flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full overflow-hidden ring-2 ring-gray-800 group-hover:ring-blue-500 transition-all">
                        <img src="{{ $friend['avatar'] }}" alt="{{ $friend['name'] }}" class="w-full h-full object-cover">
                    </div>
                    <span class="mt-1 text-xs text-gray-400 text-center truncate w-full">{{ explode(' ', $friend['name'])[0] }}</span>
                    <div class="absolute -bottom-16 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded-lg px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none shadow-lg border border-gray-800 w-max">
                        {{ $friend['name'] }}
                    </div>
                </a>
            @endforeach
            <a href="/friends" class="flex flex-col items-center justify-center w-12 h-12 rounded-full bg-gray-800 hover:bg-gray-700 transition-colors">
                <i class="fa-solid fa-ellipsis text-gray-400"></i>
                <span class="mt-1 text-xs text-gray-400">More</span>
            </a>
        </div>
    </section>

    <!-- Tabs -->
    <section class="border-b border-gray-800 mb-2">
        <div class="flex px-4">
            <button class="py-4 px-6 text-center text-white font-semibold border-b-2 border-blue-500 hover:bg-gray-800/50 transition-colors" aria-selected="true">
                Posts
            </button>
            <button class="py-4 px-6 text-center text-gray-500 font-semibold border-b-2 border-transparent hover:bg-gray-800/50 hover:text-gray-300 transition-colors">
                Media
            </button>
            <button class="py-4 px-6 text-center text-gray-500 font-semibold border-b-2 border-transparent hover:bg-gray-800/50 hover:text-gray-300 transition-colors">
                Likes
            </button>
        </div>
    </section>

    <!-- Posts Section -->
    <section class="divide-y divide-gray-800">
        @foreach([
            [
                'id' => 'post1',
                'user' => ['name' => 'John Doe', 'handle' => '@johndoe', 'avatar' => 'https://i.pravatar.cc/150?img=4'],
                'time' => '2h',
                'content' => 'Just finished a new web design project! Check it out. Really proud of how the UI turned out and the client was thrilled with the results.',
                'image' => 'https://source.unsplash.com/random/600x400?webdesign',
                'comments' => 12,
                'retweets' => 8,
                'likes' => 50
            ],
            [
                'id' => 'post2',
                'user' => ['name' => 'John Doe', 'handle' => '@johndoe', 'avatar' => 'https://i.pravatar.cc/150?img=4'],
                'time' => '1d',
                'content' => 'Exploring the new features of Laravel 9. Excited to implement them in my projects! The new syntax is much cleaner and more intuitive.',
                'image' => 'https://source.unsplash.com/random/600x400?coding',
                'comments' => 20,
                'retweets' => 15,
                'likes' => 85
            ],
            [
                'id' => 'post3',
                'user' => ['name' => 'John Doe', 'handle' => '@johndoe', 'avatar' => 'https://i.pravatar.cc/150?img=4'],
                'time' => '3d',
                'content' => 'Working on a new AI project that combines machine learning with accessible UI design. Stay tuned for updates!',
                'image' => null,
                'comments' => 8,
                'retweets' => 4,
                'likes' => 32
            ]
        ] as $post)
            <article class="px-4 py-4 hover:bg-gray-900/30 transition-colors" id="{{ $post['id'] }}">
                <div class="flex gap-3">
                    <a href="/profile/{{ $post['user']['handle'] }}" class="flex-shrink-0">
                        <img src="{{ $post['user']['avatar'] }}" alt="{{ $post['user']['name'] }} profile" class="w-12 h-12 rounded-full hover:opacity-90 transition-opacity">
                    </a>
                    <div class="flex-1">
                        <div class="flex items-center gap-1.5">
                            <a href="/profile/{{ $post['user']['handle'] }}" class="font-bold text-white hover:underline">{{ $post['user']['name'] }}</a>
                            <span class="text-gray-500">{{ $post['user']['handle'] }}</span>
                            <span class="text-gray-500">·</span>
                            <time class="text-gray-500 hover:underline">{{ $post['time'] }}</time>
                            <button class="ml-auto text-gray-500 hover:text-white transition-colors">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                        </div>
                        <p class="text-white text-base leading-relaxed mt-2">{{ $post['content'] }}</p>
                        @if($post['image'])
                        <div class="mt-3 rounded-xl overflow-hidden border border-gray-800">
                            <img src="{{ $post['image'] }}" alt="Post image" class="w-full object-cover hover:opacity-90 transition-opacity cursor-pointer">
                        </div>
                        @endif
                        <div class="flex justify-between mt-3 text-gray-500">
                            <button class="flex items-center gap-2 hover:text-blue-500 transition-colors group" aria-label="Comments">
                                <div class="p-2 rounded-full group-hover:bg-blue-500/10 transition-colors">
                                    <i class="fa-regular fa-comment"></i>
                                </div>
                                <span>{{ $post['comments'] }}</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-green-500 transition-colors group" aria-label="Retweet">
                                <div class="p-2 rounded-full group-hover:bg-green-500/10 transition-colors">
                                    <i class="fa-solid fa-retweet"></i>
                                </div>
                                <span>{{ $post['retweets'] }}</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-red-500 transition-colors group" aria-label="Like">
                                <div class="p-2 rounded-full group-hover:bg-red-500/10 transition-colors">
                                    <i class="fa-regular fa-heart"></i>
                                </div>
                                <span>{{ $post['likes'] }}</span>
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
        @endforeach
    </section>
    
    <!-- Load More -->
    <div class="flex justify-center py-6">
        <button class="bg-transparent border border-gray-800 text-blue-500 font-bold px-6 py-3 rounded-full hover:bg-gray-900 transition-colors text-sm">
            Load more
        </button>
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
                <button @click="modalOpen = false" class="bg-white text-black font-bold px-5 py-2 rounded-full hover:bg-gray-200 transition-colors text-sm">
                    Save
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-4">
                <form id="profileForm">
                    <!-- Cover and Profile Photo Section -->
                    <div class="relative mb-6">
                        <!-- Cover Photo -->
                        <div class="h-36 w-full bg-gradient-to-r from-gray-900 to-gray-800 rounded-lg overflow-hidden group relative">
                            <img id="coverPreview" src="https://source.unsplash.com/random/1200x400?dark" class="w-full h-full object-cover opacity-80">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <label for="coverPhoto" class="cursor-pointer bg-black/60 text-white p-2 rounded-full">
                                    <i class="fa-solid fa-camera"></i>
                                    <input type="file" id="coverPhoto" class="hidden" accept="image/*">
                                </label>
                            </div>
                        </div>
                        
                        <!-- Profile Picture -->
                        <div class="absolute left-4 bottom-0 transform translate-y-1/2 group">
                            <div class="relative w-20 h-20 rounded-full overflow-hidden ring-4 ring-black">
                                <img id="avatarPreview" src="https://i.pravatar.cc/150?img=4" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <label for="profilePhoto" class="cursor-pointer bg-black/60 text-white p-2 rounded-full">
                                        <i class="fa-solid fa-camera"></i>
                                        <input type="file" id="profilePhoto" class="hidden" accept="image/*">
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
                            <input type="text" value="John Doe" maxlength="50" 
                                class="w-full bg-transparent text-white border border-gray-700 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <!-- Username/Handle -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-400">Username</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">@</span>
                                <input type="text" value="johndoe" maxlength="15"
                                    class="w-full bg-transparent text-white border border-gray-700 rounded-md px-3 py-2 pl-7 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <!-- Bio -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-400">Bio</label>
                            <textarea rows="3" maxlength="160" 
                                class="w-full bg-transparent text-white border border-gray-700 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">Web developer, UX designer, and coffee enthusiast. Building awesome experiences for the web.</textarea>
                        </div>
                        
                        <!-- Location -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-400">Location</label>
                            <input type="text" value="San Francisco, CA" maxlength="30"
                                class="w-full bg-transparent text-white border border-gray-700 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <!-- Website -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-400">Website</label>
                            <input type="url" value="https://johndoe.com" 
                                class="w-full bg-transparent text-white border border-gray-700 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@section('right-sidebar')
    <!-- Right sidebar content remains unchanged -->
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview handlers
    const handleImagePreview = (inputId, previewId) => {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        
        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => preview.src = e.target.result;
                reader.readAsDataURL(this.files[0]);
            }
        });
    };

    handleImagePreview('profilePhoto', 'avatarPreview');
    handleImagePreview('coverPhoto', 'coverPreview');
});
</script>
@endsection