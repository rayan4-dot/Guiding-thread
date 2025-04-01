<aside class="w-20 md:w-64 h-screen sticky top-0 flex flex-col p-4 space-y-4 bg-black text-white border-r border-dark-border">
  <!-- Logo -->
  <div class="p-2 mb-6">
    <a href="index.html" class="flex items-center justify-center md:justify-start">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 text-primary">
        <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path>
      </svg>
      <span class="ml-3 text-xl font-bold hidden md:inline">X-Social</span>
    </a>
  </div>
  
  <!-- Navigation -->
  <nav class="flex flex-col space-y-1">
    <a href="{{ route('user.home') }}" class="{{ request()->routeIs('user.home') ? 'bg-dark-lighter text-primary' : 'text-gray-300 hover:bg-dark-hover hover:text-white' }} flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 group">
      <div class="flex items-center justify-center w-10 h-10 rounded-full group-hover:bg-opacity-10 group-hover:bg-white transition-all duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
      </div>
      <span class="text-lg font-medium hidden md:inline">Accueil</span>
    </a>
    
    <a href="{{ route('user.explore') }}" class="{{ request()->routeIs('user.explore') ? 'bg-dark-lighter text-primary' : 'text-gray-300 hover:bg-dark-hover hover:text-white' }} flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 group">
      <div class="flex items-center justify-center w-10 h-10 rounded-full group-hover:bg-opacity-10 group-hover:bg-white transition-all duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
        </svg>
      </div>
      <span class="text-lg font-medium hidden md:inline">Explorer</span>
    </a>
    
    <a href="{{ route('user.notifications') }}" class="{{ request()->routeIs('user.notifications') ? 'bg-dark-lighter text-primary' : 'text-gray-300 hover:bg-dark-hover hover:text-white' }} flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 group">
      <div class="flex items-center justify-center w-10 h-10 rounded-full group-hover:bg-opacity-10 group-hover:bg-white transition-all duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
      </div>
      <span class="text-lg font-medium hidden md:inline">Notifications</span>
    </a>
    
    <a href="{{ route('user.profile') }}" class="{{ request()->routeIs('user.profile') ? 'bg-dark-lighter text-primary' : 'text-gray-300 hover:bg-dark-hover hover:text-white' }} flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 group">
      <div class="flex items-center justify-center w-10 h-10 rounded-full group-hover:bg-opacity-10 group-hover:bg-white transition-all duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
      </div>
      <span class="text-lg font-medium hidden md:inline">Profile</span>
    </a>
  </nav>
  
  <!-- Post Button -->
  <div class="mt-6">
    <button class="w-full bg-primary hover:bg-secondary text-white font-bold py-3 px-4 rounded-full transition-colors duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-primary/20">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
      </svg>
      <span class="hidden md:inline text-base">Post</span>
    </button>
  </div>
  
  <!-- Profile -->
  <div class="mt-auto relative">
    <div class="flex items-center space-x-3 p-3 rounded-xl hover:bg-dark-hover cursor-pointer transition-all duration-200" onclick="toggleDropdown()">
      <img src="https://i.pravatar.cc/100" alt="Avatar" class="w-10 h-10 rounded-full border-2 border-dark-border">
      <div class="hidden md:block overflow-hidden">
        <p class="font-bold text-sm truncate">John Doe</p>
        <p class="text-gray-400 text-sm truncate">@johndoe</p>
      </div>
      <div class="hidden md:block ml-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </div>
    </div>
    <!-- Dropdown -->
    <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-dark-lighter rounded-lg shadow-lg z-10">
      <a href="{{ route('user.settings') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-dark-hover hover:text-white rounded-t-lg">Settings</a>
      <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-dark-hover hover:text-white rounded-b-lg"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
      </form>
    </div>
  </div>

  <script>
    function toggleDropdown() {
      const dropdown = document.getElementById('dropdownMenu');
      dropdown.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
      const dropdown = document.getElementById('dropdownMenu');
      const profile = dropdown?.previousElementSibling;
      if (!dropdown.contains(event.target) && !profile.contains(event.target)) {
        dropdown.classList.add('hidden');
      }
    });
  </script>
</aside>