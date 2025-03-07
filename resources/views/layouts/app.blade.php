<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Social Media Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet">
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#1e40af',
                        'primary-focus': '#1e3a8a',
                        'primary-content': '#ffffff',
                        'secondary': '#3b82f6',
                        'accent': '#0ea5e9',
                        'neutral': '#1f2937',
                        'base-100': '#0f172a',
                        'base-200': '#1e293b',
                        'base-300': '#334155',
                        'info': '#38bdf8',
                        'success': '#4ade80',
                        'warning': '#facc15',
                        'error': '#f87171',
                    }
                }
            },
            daisyui: {
                themes: [
                    {
                        dark: {
                            'primary': '#1e40af',
                            'primary-focus': '#1e3a8a',
                            'primary-content': '#ffffff',
                            'secondary': '#3b82f6',
                            'accent': '#0ea5e9',
                            'neutral': '#1f2937',
                            'base-100': '#0f172a',
                            'base-200': '#1e293b',
                            'base-300': '#334155',
                            'info': '#38bdf8',
                            'success': '#4ade80',
                            'warning': '#facc15',
                            'error': '#f87171',
                        },
                    },
                ],
            },
        }
    </script> --}}
</head>
<body class="bg-base-100 text-base-content">
    <div class="drawer lg:drawer-open">
        <input id="my-drawer" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col">
            @include('components.navbar')
            <main class="flex-1 overflow-y-auto p-4 lg:p-6 bg-base-100">
                @yield('content')
            </main>
        </div>
        @include('components.sidebar')
    </div>
</body>
</html>