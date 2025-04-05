<div class="h-screen sticky top-0 p-4 space-y-6 w-80 hidden lg:block bg-black border-l border-dark-border z-10">
    <!-- Search -->
    <div class="relative group">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 group-focus-within:text-primary transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <input type="text" placeholder="Rechercher" class="pl-12 pr-4 py-3 bg-dark-lighter text-white rounded-full w-full focus:outline-none focus:ring-1 focus:ring-primary focus:bg-black transition-all duration-200" aria-label="Search">
    </div>
    
    <!-- Trending -->
    <div class="bg-dark-lighter rounded-2xl overflow-hidden">
        <h2 class="text-xl font-bold p-4 border-b border-dark-border">Tendances pour vous</h2>
        <div>
            <div class="cursor-pointer hover:bg-dark-hover p-4 transition-colors duration-200 border-b border-dark-border">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500">Technologie · Tendance</p>
                        <p class="font-bold text-[15px] mt-0.5">#React</p>
                        <p class="text-xs text-gray-500 mt-1">10.5K Tweets</p>
                    </div>
                    <button class="text-gray-500 hover:text-primary hover:bg-primary/10 rounded-full p-2 transition-colors duration-200" aria-label="More options">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="cursor-pointer hover:bg-dark-hover p-4 transition-colors duration-200 border-b border-dark-border">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500">Sport · Tendance</p>
                        <p class="font-bold text-[15px] mt-0.5">#PSG</p>
                        <p class="text-xs text-gray-500 mt-1">105K Tweets</p>
                    </div>
                    <button class="text-gray-500 hover:text-primary hover:bg-primary/10 rounded-full p-2 transition-colors duration-200" aria-label="More options">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="cursor-pointer hover:bg-dark-hover p-4 transition-colors duration-200 border-b border-dark-border">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500">Business · Tendance</p>
                        <p class="font-bold text-[15px] mt-0.5">#Bitcoin</p>
                        <p class="text-xs text-gray-500 mt-1">325K Tweets</p>
                    </div>
                    <button class="text-gray-500 hover:text-primary hover:bg-primary/10 rounded-full p-2 transition-colors duration-200" aria-label="More options">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="cursor-pointer hover:bg-dark-hover p-4 transition-colors duration-200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500">Divertissement · Tendance</p>
                        <p class="font-bold text-[15px] mt-0.5">#Netflix</p>
                        <p class="text-xs text-gray-500 mt-1">43K Tweets</p>
                    </div>
                    <button class="text-gray-500 hover:text-primary hover:bg-primary/10 rounded-full p-2 transition-colors duration-200" aria-label="More options">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <a href="#" class="block p-4 text-primary hover:bg-dark-hover transition-colors duration-200">
                Voir plus
            </a>
        </div>
    </div>
    
    <!-- Who to follow -->
    <div class="bg-dark-lighter rounded-2xl overflow-hidden">
        <h2 class="text-xl font-bold p-4 border-b border-dark-border">Qui suivre</h2>
        <div>
            <div class="p-4 hover:bg-dark-hover transition-colors duration-200 border-b border-dark-border">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/150?img=1" alt="Emma Smith" class="w-12 h-12 rounded-full border border-dark-border">
                        <div>
                            <p class="font-bold text-[15px] hover:underline">Emma Smith</p>
                            <p class="text-gray-500 text-sm">@emmasmith</p>
                        </div>
                    </div>
                    <button class="rounded-full bg-white text-black hover:bg-gray-200 text-sm font-bold px-4 py-1.5 transition-colors duration-200">
                        Suivre
                    </button>
                </div>
            </div>
            
            <div class="p-4 hover:bg-dark-hover transition-colors duration-200 border-b border-dark-border">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/150?img=2" alt="James Brown" class="w-12 h-12 rounded-full border border-dark-border">
                        <div>
                            <p class="font-bold text-[15px] hover:underline">James Brown</p>
                            <p class="text-gray-500 text-sm">@jamesbrown</p>
                        </div>
                    </div>
                    <button class="rounded-full bg-white text-black hover:bg-gray-200 text-sm font-bold px-4 py-1.5 transition-colors duration-200">
                        Suivre
                    </button>
                </div>
            </div>
            
            <div class="p-4 hover:bg-dark-hover transition-colors duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/150?img=3" alt="Olivia Johnson" class="w-12 h-12 rounded-full border border-dark-border">
                        <div>
                            <p class="font-bold text-[15px] hover:underline">Olivia Johnson</p>
                            <p class="text-gray-500 text-sm">@oliviaj</p>
                        </div>
                    </div>
                    <button class="rounded-full bg-white text-black hover:bg-gray-200 text-sm font-bold px-4 py-1.5 transition-colors duration-200">
                        Suivre
                    </button>
                </div>
            </div>
            
            <a href="#" class="block p-4 text-primary hover:bg-dark-hover transition-colors duration-200">
                Voir plus
            </a>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="text-xs text-gray-500 flex flex-wrap gap-x-2">
        <a href="#" class="hover:underline">Conditions d'utilisation</a>
        <a href="#" class="hover:underline">Politique de confidentialité</a>
        <a href="#" class="hover:underline">Accessibilité</a>
        <a href="#" class="hover:underline">Publicités</a>
        <span>© 2023 X-Social</span>
    </div>
</div>