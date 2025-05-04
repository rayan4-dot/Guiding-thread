@extends('layouts.user-layout')

@section('title', 'Friends')

@section('content')
    <!-- Fixed Header -->
    <header class="sticky top-0 z-50 backdrop-blur-xl bg-black/80 border-b border-gray-800">
        <div class="max-w-screen-xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <a href="{{ route('user.profile') }}" class="text-white hover:bg-gray-800 p-2 rounded-full transition-colors">
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
        <!-- Navigation -->
        <section class="border-b border-gray-800 mb-4">
            <div class="flex px-4">
                <a href="{{ route('user.friends') }}"
                   class="py-4 px-6 text-center text-white font-semibold border-b-2 border-blue-500 hover:bg-gray-800/50 transition-colors">
                    Friends
                </a>
                <a href="{{ route('user.requests') }}"
                   class="py-4 px-6 text-center text-gray-500 font-semibold border-b-2 border-transparent hover:bg-gray-800/50 hover:text-gray-300 transition-colors">
                    Requests
                </a>
            </div>
        </section>

        <!-- Friends Count -->
        <section class="px-4 mb-4">
            <p class="text-gray-400 text-sm">{{ $friends->count() }} {{ Str::plural('Friend', $friends->count()) }}</p>
        </section>

        <!-- Friends Grid -->
        <section class="px-4 mb-8">
            @if($friends->isEmpty())
                <p class="text-gray-500 text-center">No friends yet.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($friends as $friend)
                        <div class="friend-card bg-gray-900 rounded-xl overflow-hidden border border-gray-800 hover:border-gray-700 transition-colors"
                             data-name="{{ $friend->name }}" data-username="{{ $friend->username }}">
                            <div class="relative h-24 bg-gradient-to-r from-blue-900/60 to-purple-900/60">
                                <div class="absolute -bottom-8 left-4">
                                    <img src="{{ $friend->profile_picture ? Storage::url($friend->profile_picture) : asset('images/default-profile.png') }}"
                                         alt="{{ $friend->name }}" class="w-16 h-16 rounded-full border-4 border-black">
                                </div>
                            </div>
                            <div class="pt-10 px-4 pb-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <a href="{{ route('public-profile.show', $friend->username) }}"
                                           class="font-bold text-white hover:underline">{{ $friend->name }}</a>
                                        <p class="text-gray-500 text-sm">{{ '@' . $friend->username }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-4">
                                    <form action="{{ route('connection.remove', $friend) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-gray-800 hover:bg-gray-700 text-white py-1.5 px-3 rounded-lg text-sm">
                                            Remove Connection
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </main>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="fixed bottom-4 right-4 bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
    @endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        try {
            const searchFriends = document.getElementById('searchFriends');
            console.log('Search input found:', !!searchFriends);

            if (searchFriends) {
                searchFriends.addEventListener('input', function () {
                    const query = this.value.toLowerCase();
                    console.log('Search query:', query);
                    filterFriends(query);
                });
            }

            function filterFriends(query) {
                const friendCards = document.querySelectorAll('.friend-card');
                console.log('Friend cards found:', friendCards.length);
                friendCards.forEach(card => {
                    const name = card.dataset.name ? card.dataset.name.toLowerCase() : '';
                    const username = card.dataset.username ? card.dataset.username.toLowerCase() : '';
                    card.style.display = (name.includes(query) || username.includes(query)) ? 'block' : 'none';
                });
            }
        } catch (error) {
            console.error('JavaScript error:', error);
        }
    });
</script>
@endsection