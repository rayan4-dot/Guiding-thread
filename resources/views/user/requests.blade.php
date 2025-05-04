@extends('layouts.user-layout')

@section('title', 'Friend Requests')

@section('content')
    <!-- Fixed Header -->
    <header class="sticky top-0 z-50 backdrop-blur-xl bg-black/80 border-b border-gray-800">
        <div class="max-w-screen-xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <a href="{{ route('user.friends') }}" class="text-white hover:bg-gray-800 p-2 rounded-full transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h2 class="text-lg font-bold text-white">Friend Requests</h2>
            </div>
            <div class="flex items-center space-x-2">
                <div class="relative">
                    <input type="text" id="searchRequests" placeholder="Search requests"
                           class="bg-gray-800 text-white rounded-full py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm w-40 md:w-60">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-screen-xl mx-auto">
        <section class="border-b border-gray-800 mb-4">
            <div class="flex px-4">
                <a href="{{ route('user.friends') }}"
                   class="py-4 px-6 text-center text-gray-500 font-semibold border-b-2 border-transparent hover:bg-gray-800/50 hover:text-gray-300 transition-colors">
                    Friends
                </a>
                <a href="{{ route('user.requests') }}"
                   class="py-4 px-6 text-center text-white font-semibold border-b-2 border-blue-500 hover:bg-gray-800/50 transition-colors">
                    Requests
                </a>
            </div>
        </section>
        <!-- Requests Count -->
        <section class="px-4 mb-4 mt-4">
            <p class="text-gray-400 text-sm">{{ $requests->count() }} {{ Str::plural('Request', $requests->count()) }}</p>
        </section>

        <!-- Requests Grid -->
        <section class="px-4 mb-8">
            @if($requests->isEmpty())
                <p class="text-gray-500 text-center">No pending requests.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($requests as $request)
                        <div class="friend-card bg-gray-900 rounded-xl overflow-hidden border border-gray-800 hover:border-gray-700 transition-colors"
                             data-name="{{ $request->name }}" data-username="{{ $request->username }}">
                            <div class="relative h-32 bg-gradient-to-r from-blue-900/60 to-purple-900/60">
                                <div class="absolute -bottom-10 left-4">
                                    <img src="{{ $request->profile_picture ? Storage::url($request->profile_picture) : asset('images/default-profile.png') }}"
                                         alt="{{ $request->name }}" class="w-20 h-20 rounded-full border-4 border-black">
                                </div>
                            </div>
                            <div class="pt-12 px-4 pb-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <a href="{{ route('public-profile.show', $request->username) }}"
                                           class="font-bold text-white text-lg hover:underline">{{ $request->name }}</a>
                                        <p class="text-gray-500 text-sm">{{ '@' . $request->username }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-3 mt-4">
                                    <form action="{{ route('friends.accept', $request) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full transition-colors"
                                                title="Accept Request">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('friends.reject', $request) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full transition-colors"
                                                title="Reject Request">
                                            <i class="fa-solid fa-times"></i>
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
            const searchRequests = document.getElementById('searchRequests');
            console.log('Search input found:', !!searchRequests);

            if (searchRequests) {
                searchRequests.addEventListener('input', function () {
                    const query = this.value.toLowerCase();
                    console.log('Search query:', query);
                    filterRequests(query);
                });
            }

            function filterRequests(query) {
                const requestCards = document.querySelectorAll('.friend-card');
                console.log('Request cards found:', requestCards.length);
                requestCards.forEach(card => {
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