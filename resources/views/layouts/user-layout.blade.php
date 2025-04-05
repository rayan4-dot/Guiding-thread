<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A modern social media platform interface">
    <title>X-Style Social - @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js']) <!-- Fetch.js comes via app.js -->
    
    @production
    @else
    <script src="https://cdn.tailwindcss.com"></script>
    @endproduction
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Remove: <script src="{{ asset('js/fetch.js') }}" defer></script> -->
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#1d9bf0',
                        secondary: '#1a8cd8',
                        dark: '#000000',
                        'dark-lighter': '#16181c',
                        'dark-border': '#2f3336',
                        'dark-hover': 'rgba(255, 255, 255, 0.03)',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            background-color: #000000;
            color: #e7e9ea;
        }
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #16181c;
        }
        ::-webkit-scrollbar-thumb {
            background: #2f3336;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #3a3f45;
        }
        [x-cloak] {
            display: none !important;
        }
    </style>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          rel="stylesheet" 
          media="print" 
          onload="this.media='all'">
</head>
<body class="min-h-screen antialiased font-sans bg-black text-gray-100">
    <div class="flex max-w-7xl mx-auto" role="main">
        @include('admin.components.side-user')
        
        <main class="flex-1 max-w-[600px] border-r border-dark-border min-h-screen" aria-label="Main content">
            @yield('content')
        </main>
        
        <aside class="hidden lg:block w-[350px] px-4 py-3 sticky top-0 h-screen custom-scrollbar" aria-label="Additional content">
            @include('admin.components.right-sidebar')
        </aside>
    </div>
</body>
<!-- Remove: <script src="{{ asset('js/fetch.js') }}" defer></script> -->
</html>