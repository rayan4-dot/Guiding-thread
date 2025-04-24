@extends('layouts.user-layout')

@section('title', 'Settings')

@section('content')
<!-- Fixed Header -->
<header class="sticky top-0 z-50 backdrop-blur-xl bg-black/80 border-b border-gray-800">
    <div class="max-w-screen-xl mx-auto px-4 py-3 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <a href="/profile" class="text-white hover:bg-gray-800 p-2 rounded-full transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-lg font-bold text-white">Settings</h2>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="max-w-screen-xl mx-auto">
    <!-- Profile Settings -->
    <section class="px-4 py-6">
        <h2 class="text-lg font-bold text-white mb-6">Profile Settings</h2>

        <!-- Email Section -->
        <div class="mb-8">
            <h3 class="text-md font-semibold text-gray-400 mb-2">Email</h3>
            <p class="text-gray-300 text-sm">{{ Auth::user()->email }}</p>
        </div>

        <!-- Update Password Section -->
        <div class="mb-8">
            <h3 class="text-md font-semibold text-gray-400 mb-4">Update Password</h3>
            <form action="{{ route('user.update-password') }}" method="POST" class="space-y-4">
    @csrf
    @if (session('success'))
        <div class="text-green-500 text-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="text-red-500 text-sm">{{ session('error') }}</div>
    @endif
    <div>
        <label for="current_password" class="block text-sm font-medium text-gray-400">Current Password</label>
        <input type="password" name="current_password" id="current_password" class="w-full bg-gray-900 text-white border border-gray-700 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        @error('current_password')
            <div class="text-red-500 text-sm">{{ $message }}</div>
        @enderror
    </div>
    <div>
        <label for="new_password" class="block text-sm font-medium text-gray-400">New Password (minimum 6 characters)</label>
        <input type="password" name="new_password" id="new_password" class="w-full bg-gray-900 text-white border border-gray-700 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        @error('new_password')
            <div class="text-red-500 text-sm">{{ $message }}</div>
        @enderror
    </div>
    <div>
        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-400">Confirm New Password</label>
        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="w-full bg-gray-900 text-white border border-gray-700 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        @error('new_password_confirmation')
            <div class="text-red-500 text-sm">{{ $message }}</div>
        @enderror
    </div>
    <div>
        <button type="submit" class="bg-blue-600 text-white font-bold px-4 py-2 rounded-full hover:bg-blue-700 transition-colors">Update Password</button>
    </div>
</form>
        </div>

<!-- Delete Account Section -->
<div>
    <h3 class="text-md font-semibold text-gray-400 mb-4">Delete Account</h3>
    <p class="text-gray-300 text-sm mb-4">Once you delete your account, there is no going back. Please be certain.</p>
    <form action="{{ route('user.delete-account') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
        @csrf
        @method('DELETE') 
        @if (session('error'))
            <div class="text-red-500 text-sm mb-4">{{ session('error') }}</div>
        @endif
        <div>
            <label for="password" class="block text-sm font-medium text-gray-400">Confirm Password</label>
            <input type="password" name="password" id="password" class="w-full bg-gray-900 text-white border border-gray-700 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            @error('password')
                <div class="text-red-500 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div class="mt-4">
            <button type="submit" class="bg-red-600 text-white font-bold px-4 py-2 rounded-full hover:bg-red-700 transition-colors">Delete Account</button>
        </div>
    </form>
</div>
    </section>
</main>
@endsection