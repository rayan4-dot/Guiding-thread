@extends('layouts.user-layout')

@section('title', 'Friends')

@section('content')
    <!-- Fixed Header -->
    <header class="sticky top-0 z-50 backdrop-blur-xl bg-black/80 border-b border-gray-800">
        <div class="max-w-screen-xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <a href="/profile" class="text-white hover:bg-gray-800 p-2 rounded-full transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h2 class="text-lg font-bold text-white">Friends</h2>
            </div>
            <div class="flex items-center space-x-2">
                <div class="relative">
                    <input type="text" id="searchFriends" placeholder="Search friends"
                           class="bg-gray-800 text-white rounded-full py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm w-40 md:w-60">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-screen-xl mx-auto">
        <!-- Tabs -->
        <section class="border-b border-gray-800 mb-4">
            <div class="flex px-4">
                <button id="tabAll" class="tab-button py-4 px-6 text-center text-white font-semibold border-b-2 border-blue-500 hover:bg-gray-800/50 transition-colors">
                    All Friends
                </button>
                <button id="tabRequests" class="tab-button py-4 px-6 text-center text-gray-500 font-semibold border-b-2 border-transparent hover:bg-gray-800/50 hover:text-gray-300 transition-colors">
                    Requests
                </button>
            </div>
        </section>

        <!-- Friends Section -->
        <div id="friendsContent">
            <!-- Friends Count -->
            <section class="px-4 mb-4">
                <p class="text-gray-400 text-sm">3 Friends</p>
            </section>

            <!-- Friends Grid -->
            <section class="px-4 mb-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <!-- Friend Card 1 -->
                    <div class="friend-card bg-gray-900 rounded-xl overflow-hidden border border-gray-800 hover:border-gray-700 transition-colors">
                        <div class="relative h-24 bg-gradient-to-r from-blue-900/60 to-purple-900/60">
                            <div class="absolute -bottom-8 left-4">
                                <img src="https://i.pravatar.cc/150?img=1" alt="John Doe" class="w-16 h-16 rounded-full border-4 border-black">
                            </div>
                        </div>
                        <div class="pt-10 px-4 pb-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <a href="/profile/johndoe" class="font-bold text-white hover:underline">John Doe</a>
                                    <p class="text-gray-500 text-sm">@johndoe</p>
                                </div>
                            </div>
                            <div class="flex gap-2 mt-4">
                                <button class="bg-gray-800 hover:bg-gray-700 text-white py-1.5 px-3 rounded-lg text-sm">Remove Friend</button>
                            </div>
                        </div>
                    </div>

                    <!-- Friend Card 2 -->
                    <div class="friend-card bg-gray-900 rounded-xl overflow-hidden border border-gray-800 hover:border-gray-700 transition-colors">
                        <div class="relative h-24 bg-gradient-to-r from-blue-900/60 to-purple-900/60">
                            <div class="absolute -bottom-8 left-4">
                                <img src="https://i.pravatar.cc/150?img=2" alt="Jane Smith" class="w-16 h-16 rounded-full border-4 border-black">
                            </div>
                        </div>
                        <div class="pt-10 px-4 pb-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <a href="/profile/janesmith" class="font-bold text-white hover:underline">Jane Smith</a>
                                    <p class="text-gray-500 text-sm">@janesmith</p>
                                </div>
                            </div>
                            <div class="flex gap-2 mt-4">
                                <button class="bg-gray-800 hover:bg-gray-700 text-white py-1.5 px-3 rounded-lg text-sm">Remove Friend</button>
                            </div>
                        </div>
                    </div>

                    <!-- Friend Card 3 -->
                    <div class="friend-card bg-gray-900 rounded-xl overflow-hidden border border-gray-800 hover:border-gray-700 transition-colors">
                        <div class="relative h-24 bg-gradient-to-r from-blue-900/60 to-purple-900/60">
                            <div class="absolute -bottom-8 left-4">
                                <img src="https://i.pravatar.cc/150?img=3" alt="Mike Johnson" class="w-16 h-16 rounded-full border-4 border-black">
                            </div>
                        </div>
                        <div class="pt-10 px-4 pb-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <a href="/profile/mikej" class="font-bold text-white hover:underline">Mike Johnson</a>
                                    <p class="text-gray-500 text-sm">@mikej</p>
                                </div>
                            </div>
                            <div class="flex gap-2 mt-4">
                                <button class="bg-gray-800 hover:bg-gray-700 text-white py-1.5 px-3 rounded-lg text-sm">Remove Friend</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Requests Section (Initially Hidden) -->
        <div id="requestsContent" style="display: none;">
            <!-- Requests Count -->
            <section class="px-4 mb-4">
                <p class="text-gray-400 text-sm">2 Requests</p>
            </section>

            <!-- Requests Grid -->
            <section class="px-4 mb-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <!-- Request Card 1 -->
                    <div class="friend-card bg-gray-900 rounded-xl overflow-hidden border border-gray-800 hover:border-gray-700 transition-colors">
                        <div class="relative h-24 bg-gradient-to-r from-blue-900/60 to-purple-900/60">
                            <div class="absolute -bottom-8 left-4">
                                <img src="https://i.pravatar.cc/150?img=4" alt="Sarah Connor" class="w-16 h-16 rounded-full border-4 border-black">
                            </div>
                        </div>
                        <div class="pt-10 px-4 pb-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <a href="/profile/sarahc" class="font-bold text-white hover:underline">Sarah Connor</a>
                                    <p class="text-gray-500 text-sm">@sarahc</p>
                                </div>
                            </div>
                            <div class="flex gap-2 mt-4">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white py-1.5 px-3 rounded-lg text-sm">Accept</button>
                                <button class="bg-red-600 hover:bg-red-700 text-white py-1.5 px-3 rounded-lg text-sm">Reject</button>
                            </div>
                        </div>
                    </div>

                    <!-- Request Card 2 -->
                    <div class="friend-card bg-gray-900 rounded-xl overflow-hidden border border-gray-800 hover:border-gray-700 transition-colors">
                        <div class="relative h-24 bg-gradient-to-r from-blue-900/60 to-purple-900/60">
                            <div class="absolute -bottom-8 left-4">
                                <img src="https://i.pravatar.cc/150?img=5" alt="Alex Turner" class="w-16 h-16 rounded-full border-4 border-black">
                            </div>
                        </div>
                        <div class="pt-10 px-4 pb-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <a href="/profile/alext" class="font-bold text-white hover:underline">Alex Turner</a>
                                    <p class="text-gray-500 text-sm">@alext</p>
                                </div>
                            </div>
                            <div class="flex gap-2 mt-4">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white py-1.5 px-3 rounded-lg text-sm">Accept</button>
                                <button class="bg-red-600 hover:bg-red-700 text-white py-1.5 px-3 rounded-lg text-sm">Reject</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabAll = document.getElementById('tabAll');
        const tabRequests = document.getElementById('tabRequests');
        const friendsContent = document.getElementById('friendsContent');
        const requestsContent = document.getElementById('requestsContent');

        tabAll.addEventListener('click', function () {
            friendsContent.style.display = 'block';
            requestsContent.style.display = 'none';

            tabAll.classList.add('text-white', 'border-blue-500');
            tabAll.classList.remove('text-gray-500', 'border-transparent');

            tabRequests.classList.add('text-gray-500', 'border-transparent');
            tabRequests.classList.remove('text-white', 'border-blue-500');
        });

        tabRequests.addEventListener('click', function () {
            requestsContent.style.display = 'block';
            friendsContent.style.display = 'none';

            tabRequests.classList.add('text-white', 'border-blue-500');
            tabRequests.classList.remove('text-gray-500', 'border-transparent');

            tabAll.classList.add('text-gray-500', 'border-transparent');
            tabAll.classList.remove('text-white', 'border-blue-500');
        });
    });
</script>
@endsection