<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Suspended</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-base-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="card bg-base-200 shadow-xl p-6 max-w-md w-full">
            <div class="card-body text-center">
                <i class="fas fa-ban text-error text-5xl mb-4"></i>
                <h1 class="text-2xl font-bold">Account Suspended</h1>
                <p class="text-sm opacity-70 mt-2">Your account has been suspended. Please contact support for assistance.</p>
                <div class="mt-6">
                    <a href="mailto:support@example.com" class="btn btn-primary">Contact Support</a>
                    <a href="{{ route('login') }}" class="btn btn-ghost ml-2">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>