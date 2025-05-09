<div class="h-screen sticky top-0 w-80 hidden lg:block bg-black border-l border-dark-border z-10 p-0 overflow-hidden">
    <!-- Container for scrollable content -->
    <div class="flex flex-col h-full">
        <!-- Sticky Search Bar -->
        <div class="p-4 border-b border-dark-border bg-black sticky top-0 z-10">
            <form action="{{ route('search.index') }}" method="GET" class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 group-focus-within:text-primary transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="query" 
                    placeholder="Search posts, users, or #hashtags..." 
                    value="{{ request('query') }}"
                    class="pl-12 pr-4 py-3 bg-dark-lighter text-white rounded-full w-full focus:outline-none focus:ring-1 focus:ring-primary focus:bg-black transition-all duration-200" 
                    aria-label="Search"
                >
            </form>
        </div>

        <!-- Scrollable area -->
        <div class="overflow-y-auto px-4 py-6 space-y-6 flex-1">
            <!-- Trending -->
            <div class="bg-dark-lighter rounded-2xl overflow-hidden">
                <h2 class="text-xl font-bold p-4 border-b border-dark-border">Tendances pour vous</h2>
                <div>
                    @forelse ($trendingHashtags as $hashtag)
                        <div class="cursor-pointer hover:bg-dark-hover p-4 transition-colors duration-200 border-b border-dark-border">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs text-gray-500">Tendance · Hashtag</p>
                                    <p class="font-bold text-[15px] mt-0.5">#{{ $hashtag->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ number_format($hashtag->posts_count) }} Posts</p>
                                </div>
                                <button class="text-gray-500 hover:text-primary hover:bg-primary/10 rounded-full p-2 transition-colors duration-200" aria-label="More options">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-sm text-gray-400">
                            Aucune tendance trouvée.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Who to Connect With -->
            <div class="bg-dark-lighter rounded-2xl overflow-hidden">
                <h2 class="text-xl font-bold p-4 border-b border-dark-border">Qui suivre</h2>
                <div>
                    @forelse ($peopleToConnect as $user)
                        @if ($user->username)
                            <div class="p-4 hover:bg-dark-hover transition-colors duration-200 border-b border-dark-border">
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('public-profile.show', $user->username) }}" class="flex items-center space-x-3">
                                        <img src="{{ $user->profile_picture ? Storage::url($user->profile_picture) : 'https://via.placeholder.com/150' }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full border border-dark-border">
                                        <div>
                                            <p class="font-bold text-[15px] hover:underline">{{ $user->name }}</p>
                                            <p class="text-gray-500 text-sm">{{ '@' . $user->username }}</p>
                                        </div>
                                    </a>
                                    @auth
                                        @if(auth()->user()->hasPendingConnection($user))
                                            <button class="rounded-full bg-gray-500 text-white text-sm font-bold px-4 py-1.5 cursor-not-allowed" disabled title="Demande de connexion en attente">
                                                Pending
                                            </button>
                                        @else
                                            <form action="{{ route('connection.send', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="rounded-full bg-white text-black hover:bg-gray-200 text-sm font-bold px-4 py-1.5 transition-colors duration-200" title="Se connecter">
                                                     connect
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="rounded-full bg-white text-black hover:bg-gray-200 text-sm font-bold px-4 py-1.5 transition-colors duration-200" title="Connectez-vous pour vous connecter">
                                            connect
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="p-4 text-sm text-gray-400">
                            No connection suggestions found.
                        </div>
                    @endforelse
                    <a href="/explore" class="block p-4 text-primary hover:bg-dark-hover transition-colors duration-200">
                        See more
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-xs text-gray-500 flex flex-wrap gap-x-2">
                <a href="#" class="hover:underline">Conditions d'utilisation</a>
                <a href="#" class="hover:underline">Politique de confidentialité</a>
                <a href="#" class="hover:underline">Accessibilité</a>
                <a href="#" class="hover:underline">Publicités</a>
                <span>© 2025 X-Social</span>
            </div>
        </div> <!-- End of scrollable area -->
    </div> <!-- End flex container -->
</div>