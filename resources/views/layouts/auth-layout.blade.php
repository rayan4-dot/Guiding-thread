<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Auth' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-md">
    @yield('content')    </div>
</body>
</html>
