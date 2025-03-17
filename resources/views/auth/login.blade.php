@extends('layouts.auth-layout')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Connexion</h2>

    @if(session('error'))
        <div class="bg-red-100 text-red-600 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block mb-1 text-gray-600">Email</label>
            <input type="email" name="email" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" required>
        </div>

        <div>
            <label class="block mb-1 text-gray-600">Mot de passe</label>
            <input type="password" name="password" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" required>
        </div>

        <div class="flex justify-between items-center">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="mr-2">
                <span class="text-sm text-gray-600">Se souvenir de moi</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Se connecter</button>
    </form>

    <p class="text-sm text-center mt-4">
        Pas encore de compte ?
        <a href="{{ route('register') }}" class="text-blue-600 hover:underline">S'inscrire</a>
    </p>
@endsection
